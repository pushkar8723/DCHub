#!/usr/bin/python2

import vh
import math
import re
import datetime,time
import urllib
import os, os.path
import sys
import traceback

sys.path.append("/etc/verlihub/scripts")
from config import *

datafiles = basepath + "datafiles/"
logpath = basepath + "logs/"

config = {"hubbot": "HubBot",
          "offlinebot": "DCOfflineMessages",
          "notifybot": "DCNotifications",
          "stalker": "DCStalker",
          "admins": ['Red_Devil','DeathEater','sdh'],
          "blockcommand": 0,
          "allowcommand": 1,
          "authclass":3,
          "displayipauth":8,
          "authlimit":True,"iplimit":True,"sharelimit":True,
          "logs": True,
          "sharesize":20,
          "mainchat": 10,
          "ecount":70,
          "clock":0,"authremind":60 * 15,"saveuserlist":60*60,"messages":60*1+30,"notifications":60*1+15,"displayips":60*1,"clockcountreset": 60*60*24,"error":0,
          "generalpath":logpath,
          "userlistpath": logpath + "UserLists/",
          "pmpath": logpath + "PM/",
          "mainchatpath": logpath + "MainChat/",
          "notice": datafiles + "notice",
          "tvschedule": datafiles + "tvschedule",
          "latest": datafiles + "latest",
          "lastmsg": datafiles + "lastmsg",
          "regurl": rooturl + "register",
          "faqurl": rooturl + "info",
          "freq": rooturl + "frequent",
          "hoturl": rooturl + "hot",
          "requesturl": rooturl + "request",
          "authurl": rooturl + "account",
          "latesturl": rooturl + "latest",
          "tables": {"users":"dchub_users","dcusers":"reglist","branches":"dchub_branch","log":"dchub_log","content":"dchub_content","tvschedule":"dchub_tvschedule","messages":"dchub_message","posts":"dchub_post","request":"dchub_request","hot":"dchub_hot","groups":"dchub_groups"},
          "classmap":{0:0,1:1,2:1,3:1,4:1,8:1,9:3,10:10},
          "levelcommands":{0:{"help":{"+help <command>":"Shows help for a particular command"},
                              "share":{"+share <content> <tags>":"Lets you share new content by title and comma separated tags without space.",
                                       "+share <magnet-link> <tags>":"Lets you share new content using magnet links. The title is extracted from the magnet link filename. Tags should be comma separated without spaces"},
                              "latest":{"+latest":"Displays the latest shared content"},
                              "notice":{"+notice":"Displays the notices"},
                              "myinfo":{"+myinfo":"Shows you information about yourself"},
                              "password":{"+password <old password> <new password>":"Allows you to update your password"},
                              "hubinfo":{"+hubinfo":"Gives you the link for the DC Hub Info",},
                              "hot":{"+hot":"See recommended contents"}
                              },
                          1:{ "schedule":{"+schedule":"Shows the TV Schedule","+schedule <show name>":"Shows schedule for the given show. You can type in partial name (eg: how met, big bang)"},
                              "offline":{"+offline <nick> <message>":"Allows you to send an offline message to the specified user"},
                              "request":{"+request <content>":"Allows you to post on the Request Page, from where others can download the content requested by you"},
                              "notify":{"+notify <message>":"Allows you to broadcast message to all users, subject to Admin approval"},
                              "authenticate":{"+authenticate":"Shows you pending authentication requests",
                                              "+authenticate <nick>":"Allows you to authenticate a friend who has sent you a request"},
                              "me":{"+me <message>":"Lets you 'flash' a message on the Main Chat"}
                             },
                          2:{},
                          3:{ "unshare":{"+unshare <id>":"Allows you to delete content from Latest Content page."},
                              "clear":{"+clear":"Allows you to clear the mainchat"}
                              },
                          4:{ "send":{"+send":"Approves and Broadcasts a message that was posted on Notifications page using +notify"},
                              "view":{"+view":"Displays pending Broadcast messages"}
                            },
                          8:{ "info":{"+info <nick>":"Shows details of the specified user"},
                              "infoip":{"+infoip <ip-address>":"Shows the registered users from the given address"},
                              "authlist":{"+authlist <nick>":"Lists the users authenticated by the specified user"}
                             },
                          9:{"multicast":{"!multicast <starting-ip> <ending-ip> <message>":""},
                             "regnew":{"!regnew <nickname> <password> [<access-level> <ip>]":""},
                             "regpasswd":{"!regpasswd <nickname>":"See current password",
                                          "!regpasswd <nickname> <new-password>":"Set new password"},
                             "regdelete":{"!regdelete <nickname>":""},
                             "regclass":{"!regclass <nickname> <access-level>":""}
                             },
                          10:{"generatelatest":{"+generatelatest":"Generates latest content file"},
                              "generateschedule":{"+generateschedule":"Generates schedule file"},
                              "sendoffline":{"+sendoffline":"Send"},
                              "sendmsg":{"+sendmsg (mc|pm to) from msg":"Send"}
                              }
                          }
          }

#get table columns
for table in config['tables']:
    (result, sqldata) = vh.SQL("SELECT group_concat(column_name separator ',') FROM information_schema.columns WHERE table_schema = DATABASE() AND table_name='%s'" % config['tables'][table])
    config[table + "_fields"] = sqldata[0][0].split(',')

#save help information for easy access
helpdata = {}
for uclass in config['classmap']:
    for command in config['levelcommands'][uclass]:
        helpdata[command] = config['levelcommands'][uclass][command]
config['help'] = helpdata

#store all commands with level details
commandslevel={}
for level in config['levelcommands']:
    for command in config['levelcommands'][level]:
        commandslevel[command]=level
config['commandswithlevel']=commandslevel

mainchatlog = []

# Helper functions

def escape(s):
    if type(s)==type(list()):
        for i,v in enumerate(s):
            s[i] = str(v).replace("'","\\'").replace('"','\\"')
    else:
        s=str(s).replace("'","\\'").replace('"','\\"')
    return s

def dc_escape(s):
    return s.replace("&","&amp;").replace("|","&#124;").replace("$","&#36;")

def ip2int(x):
        y = 0
        for i in x.split("."):
                y = y*256 + int(i)
        return y
        
def int2ip(x):
        y = ""
        for i in range(4):
                y=str(x%256)+"."+y
                x/=256
        return y[:-1]

def sendMainChatMsgToNick(msg,nick,fromNick=None):
    msg = vh.encode(msg)
    if fromNick != None:
        vh.SendDataToUser("<%s> %s|" % (fromNick,msg),nick)
    else:
        vh.usermc(msg,nick)

def sendPMToNick(msg,nick,fromNick=None):
    msg = vh.encode(msg)
    if fromNick != None:
        data = "$To: %s From: %s $<%s> %s|" % (nick, fromNick, fromNick, msg)
        vh.SendDataToUser(data,nick)
    else:
        vh.pm(msg,nick)

def sendMainChatMsgToAll(msg, fromNick=None):
    msg = vh.encode(msg)
    if fromNick != None:
        vh.SendDataToAll("<%s> %s|" % (fromNick,msg),0,10)
    else:
        vh.SendDataToAll("%s|" % msg,0,10)

def sendDebugMessage(msg):
    if debug:
        sendPMToNick(msg,"sdh","DCDebug");

def sendSQLDebugMessage(msg):
    if sqldebug:
        sendMainChatMsgToNick(msg,"sdh","DCQueries");

#Database interactions

def selectOneFromTable(query,values,table=None, escp = True):
    sql = query % tuple(escape(values) if escp else values)
    sendSQLDebugMessage(sql)
    (result, sqldata) = vh.SQL(sql)
    if table == None:
        return (result, sqldata)
    data = dict()
    if result!=0 and len(sqldata)!=0:
        fields = config[table+"_fields"]
        data = dict(zip(fields, sqldata[0]))
    return (result, data)

def selectAllFromTable(query,values,table=None, escp = True):
    sql = query % tuple(escape(values) if escp else values)
    sendSQLDebugMessage(sql)
    (result, sqldata) = vh.SQL(sql)
    if table == None:
        return (result, sqldata)
    alldata = []
    if result!=0 and len(sqldata)!=0:
        fields = config[table+"_fields"]
        data=dict()
        for d in sqldata:
            data = dict(zip(fields, d))
            alldata.append(data)
    return (result, alldata)

def insertIntoTable(table, data):
    data['createdOn'] = getDateTimeInFormat("%Y-%m-%d %H:%M:%S");
    data['updatedOn'] = getDateTimeInFormat("%Y-%m-%d %H:%M:%S");
    vals = []
    for d in data:
        vals.append("%s='%s'" % (d, escape(data[d])))
    sql = "INSERT into %s SET %s" % (table, ",".join(vals))
    sendSQLDebugMessage(sql)
    (res, data) = vh.SQL(sql)
    return res

def updateTable(table, data, where, values = []):
    data['updatedOn'] = getDateTimeInFormat("%Y-%m-%d %H:%M:%S");
    vals = []
    for d in data:
        vals.append("%s='%s'" % (d, escape(data[d])))
    sql = "UPDATE %s SET %s where %s" % (table, ",".join(vals), where % tuple(escape(values)))
    sendSQLDebugMessage(sql)
    (res, data) = vh.SQL(sql)
    return res
    
def updatePassword(nick1, nick2, password):
    result1 = updateTable(config["tables"]["users"],{"password_":password}, "(nick1='%s' or nick2='%s')", [nick1, nick1])
    result2 = updateTable(config["tables"]["dcusers"],{"login_pwd":password}, "(nick='%s')", [nick1])
    result3 = updateTable(config["tables"]["dcusers"],{"login_pwd":password}, "(nick='%s')", [nick2])
    if result1==0 or result2==0 or result3==0:
        return 1
    return 0

def changeUserClass(nick1, nick2, newclass):
    updateTable(config['tables']['users'], {"class":newclass}, "deleted=0 and (nick1='%s' or nick2='%s')", [nick1,nick1])
    updateTable(config['tables']['dcusers'], {"class":config['classmap'][newclass]}, "nick='%s'", [nick1])
    updateTable(config['tables']['dcusers'], {"class":config['classmap'][newclass]}, "nick='%s'", [nick2])

def getUserDetailfromTable(nick):
    (result, sqldata) = selectOneFromTable("SELECT u.*,b.branch as branchname FROM %s u left join %s b on u.branch=b.id  WHERE (nick1='%s' or nick2='%s') AND deleted=0;",[config["tables"]["users"],config["tables"]["branches"], nick, nick])
    data = dict()
    if result!=0 and len(sqldata)!=0:
        fields = config["users_fields"]
        fields.append("branchname")
        data = dict(zip(fields, sqldata[0]))
    return (result, data)

def getDateTimeInFormat(dtformat):
    now = datetime.datetime.now()
    return now.strftime(dtformat)

def log(mtype, message, mfrom, mto = "", flag = 0):
    global config
    if config["logs"]==False: return
    data = {"logtype":mtype,"nick":mfrom,"nick_to":mto,"message":message,"flag":flag}
    res = insertIntoTable(config["tables"]["log"], data)
    if res == 0:
        logToFile("<%s> %s : %s\n%s\n" % (mtype, mfrom, mto, message), "generalpath", "general.log")

def logToFile(data, ltype, filename):
    global config
    if config["logs"]==False: return
    path = config[ltype]
    try: os.makedir(path)
    except: pass
    try: print >>open(path+filename,"a"), getDateTimeInFormat("[%Y-%m-%d %H:%M:%S] ")+data
    except: pass

def handleError(e, nick, st):
    sendMainChatMsgToNick("%s. Please try again (%s)" % (st, getDateTimeInFormat("%Y-%m-%d %H:%M:%S")), nick)
    exc_type, exc_value, exc_traceback = sys.exc_info()
    lines = traceback.format_exception(exc_type, exc_value, exc_traceback)
    logToFile("<%s> %s: %s\n%s" % (nick, st, e, "".join(line for line in lines)),"generalpath","errors.log")
    #log("Exception", "%s: %s" % (st, e), nick)

def getFileContents(path):
    fl = open(path,"r")
    data = fl.read()
    fl.close()
    return data

def getUserShareinGB(nick):
    (nick, desc, tag, speed, mail, size) = vh.GetMyINFO(nick)
    if size and len(size)>0:
        try:
            share = int(size) / (1024.0 * 1024 * 1024)
            return math.ceil(share*100)/100
        except Exception as e:
            sendMainChatMsgToNick("Error: Your share could not be determined properly",nick)
    return -1

def checkStatus(nick, userdata):
    ipnotmatching = False
    notauthenticated = False
    notshared = False
    if config["authlimit"] and int(userdata["class"])==0:
        notauthenticated = True
    if config["sharelimit"] and int(userdata["class"]) < config["authclass"] and getUserShareinGB(nick) < config["sharesize"] and userdata['shareLimitRemoved']=="0":
        notshared = True
    if config["iplimit"] and userdata["ipaddress"]!=vh.GetUserIP(nick) and userdata['IPLimitRemoved']=="0":
        ipnotmatching = True
    return (notauthenticated, notshared, ipnotmatching)

def notice(nick, flag=0):
    data = getFileContents(config["notice"])
    if len(data)!=0:
        send = "\nMessages of the day:\n%s\n\n%s\n\n%s\n" % ("="*config['ecount'] ,getFileContents(config['notice']), "="*config['ecount'])
        sendPMToNick(send,nick) if flag==0 else sendMainChatMsgToNick(send,nick)

def mainChat(nick):
    global mainchatlog
    if len(mainchatlog)!=0:
        sendMainChatMsgToNick("\nLast few posts on the Main Chat: \n\n%s\n" % ("\n".join(mainchatlog)),nick)

def getHotContent():
    st = ""
    (result,sqldata) = selectAllFromTable("SELECT * FROM %s WHERE deleted=0 ORDER BY votes desc, time DESC LIMIT 0,10;", [config["tables"]["hot"]], "hot")
    if len(sqldata)==0:
        st = ""
    else:
        out = []
        for data in sqldata:
            out.append("   %s" % data["magnetlink"] if len(data["magnetlink"])>0 and data["magnetlink"]!="NULL" else data["name"])
        st = "The current HOT content are:\n\n%s\n\nSee more and recommend content yourself at %s\n" % ("\n".join(out), config['hoturl'])
    return st

def getLatestContent():
    global config
    data = ""
    (result,sqldata) = selectAllFromTable("SELECT cid,title,magnetlink,(SELECT nick1 FROM %s WHERE id=%s.uid) as nickname FROM %s WHERE deleted=0 ORDER BY priority desc, timestamp DESC LIMIT 0,10;", [config["tables"]["users"],config["tables"]["content"],config["tables"]["content"]])
    if len(sqldata)==0:
        data = "No items have been shared till now."
    else:
        out = []
        for row in sqldata:
            item = {"id":row[0],"title":row[1],"magnetlink":row[2],"nickname":row[3]}
            out.append("%s by %s  [id=%s]" % (item["magnetlink"] if len(item["magnetlink"])>0 and item["magnetlink"]!="NULL" else item["title"], item["nickname"], item['id']))
        data = "\nLatest Content: %s\n%s\n%s\n%s\n" % (config["latesturl"], "="*config['ecount'] ,"\n".join(out), "="*config['ecount'])
    return data
        
def generateLatest(nick):
    return
    global config
    f = open(config["latest"],"w")
    f.write(getLatestContent())
    f.close()

def sendOfflineMessages():
    try:
        out = []
        for nick in vh.GetNickList():
            out.append("'%s'" % escape(nick))
        nicks = ",".join(out)
        (r, d) = selectAllFromTable("select dut.nick1 nto,duf.nick1 nfrom,msg,dm.createdOn,dm.id from %s dm,%s dut,%s duf where dm.deleted=0 and dut.deleted=0 and duf.deleted=0 and dm.fromid=duf.id and dm.toid=dut.id and dm.id > dut.lastmsgid and (dut.nick1 in (%s) or dut.nick2  in (%s)) order by nto,nfrom,createdOn", [config["tables"]["messages"],config["tables"]["users"],config["tables"]["users"],nicks,nicks], None, False)
        if r==0 or len(d)==0: return
        data = dict()
        msgids = []
        for row in d:
            if row[0] in data:
                data[row[0]].append([row[1],row[2],row[3]])
            else:
                data[row[0]]=[[row[1],row[2],row[3]]]
            msgids.append(int(row[4]))
        for nick in data:
            #out = "You have received the following offline messages:\n%s\n" % ("="*config['ecount'],)
            for msg in data[nick]:
                sendPMToNick(msg[1],nick,msg[0])
                #out += "\n[%s] <%s> %s\n\n%s\n" % (msg[2], msg[0], msg[1], "="*config['ecount'])
            #out += "\nTo send offline messages, see the '+help offline' command on main chat"
            #sendPMToNick(out,nick,config['offlinebot'])
        sendDebugMessage("Offline messages sent to %d users" % len(data))
        out = []
        for nick in data.keys():
            out.append("'%s'" % escape(nick))
        nicks = ",".join(out)
        updateTable(config["tables"]["users"],{"lastmsgid":max(msgids)},"(nick1 in (%s) or nick2  in (%s))" % (nicks, nicks))
    except Exception, e:
        handleError(e,"sdh","Error sending offline messages")

def sendNotifications():
    try:
        out = []
        for nick in vh.GetNickList():
            out.append("'%s'" % escape(nick))
        nicks = ",".join(out)
        (r, d) = selectAllFromTable("select nick1 nto,dp.postby nfrom,post,dp.createdOn,dg.name,dp.id from %s dp,%s du,%s dg where dg.id=dp.gid and du.deleted=0 and dp.deleted=0 and (du.nick1 in (%s) or du.nick2  in (%s)) and dp.id > du.lastnotificationid and dp.approvedby!=0 and (du.groups like concat(dp.gid) or du.groups like concat('%%,',dp.gid,',%%') or du.groups like concat(dp.gid,',%%') or du.groups like concat('%%,',dp.gid)) order by nto,nfrom,createdOn", [config["tables"]["posts"],config["tables"]["users"],config["tables"]["groups"],nicks,nicks], None, False)
        if r==0 or len(d)==0: return
        data = dict()
        msgids = []
        for row in d:
            if row[0] in data:
                data[row[0]].append([row[1],row[2],row[3],row[4]])
            else:
                data[row[0]]=[[row[1],row[2],row[3],row[4]]]
            msgids.append(int(row[5]))
        for nick in data:
            out = "You have received the following broadcasts:\n%s\n" % ("="*config['ecount'],)
            for msg in data[nick]:
                out += "%s to %s:\n\n%s\n\n%s\n" % (msg[0], msg[3], msg[1], "="*config['ecount'])
            out += "\nTo send broadcasts, see the '+help notify' command on main chat"
            sendPMToNick(out,nick,config['notifybot'])
        sendDebugMessage("Notifications sent to %d users" % len(data))
        out = []
        for nick in data.keys():
            out.append("'%s'" % escape(nick))
        nicks = ",".join(out)
        updateTable(config["tables"]["users"],{"lastnotificationid":max(msgids)},"(nick1 in (%s) or nick2  in (%s))" % (nicks, nicks))
    except Exception, e:
        handleError(e,"sdh","Error sending notifications")

def showUserInfo(usernick, nick, flag = 0):
    (result,userdata) = getUserDetailfromTable(usernick)
    if result==0 or len(userdata)==0:
        sendMainChatMsgToNick("Error: Nickname '%s' not found in Database." % usernick,nick)
        return config["blockcommand"]
    nicknames = "%s ][ %s" % (userdata['nick1'], userdata['nick2']) if flag==0 else usernick
    st = "Details for '%s':\n\nNicknames: %s\nAccess Level: %s\nIP Address: %s\nFull Name: %s\nGender: %s\nBranch: %s\nRoll Number: %s/%s/%s\nAddress : Hostel %s Room %s\nPhone Number: %s\nEmail Address: %s\nFriend: %s\nLast Login: %s\n" % (usernick, nicknames, userdata['class'], userdata['ipaddress'], userdata['fullname'], userdata['gender'], userdata['branchname'], userdata['roll_course'], userdata['roll_number'], userdata['roll_year'], userdata['hostel'], userdata['room'], userdata['phone'], userdata['email'], userdata['friend'], userdata['lastLogin'])
    return st

def getAuthenticateRequestsData(nick):
    (result,sqldata) = selectAllFromTable("SELECT * FROM %s WHERE friend='%s' AND class=0 AND deleted=0;", [config["tables"]["users"],nick], "users")
    if result==0 or len(sqldata)==0:
        return ""
    else:
        out = "The following users have specified your name as a friend for authentication:\n"
        for userdata in sqldata:
            out += "    Nick: %s, Name: %s, Roll: %s/%s/%s, Branch: %s\n" % (userdata['nick1'], userdata['fullname'], userdata['roll_course'], userdata['roll_number'], userdata['roll_year'], userdata['branch'])
        out += "Please authenticate these accounts by using +authenticate <nick> or decline authentication by using +authenticate decline <nick> or visit %s" % config['authurl']
        return out

def sendIPDetails(unick = "all"):
    data = "$UserIP "
    for nick in vh.GetNickList():
        ip = vh.GetUserIP(nick)
        if ip!="": data+=nick+" "+ip+"$$"
    data = data[:-2]+"|"
    if len(data)>7:
        if unick == "all":
            (result,sqldata) = selectAllFromTable("SELECT nick1 FROM %s WHERE class>='%d' AND deleted=0 UNION SELECT nick2 FROM %s WHERE class>='%d' AND deleted=0", [config["tables"]["users"],config["displayipauth"],config["tables"]["users"],config["displayipauth"]], None, False)
            for row in sqldata: vh.SendDataToUser(data,row[0])
        else:
            vh.SendDataToUser(data,unick)

# Actual DC Hub Events handling starts here

def OnUserLogin (nick):
    global config
    try:
        (result, userdata) = getUserDetailfromTable(nick)
        if result==0 or len(userdata)==0:
            notice(nick)
            sendMainChatMsgToNick("Error: Nickname '%s' not found in Database. Please register yourself at %s" % (nick, config["regurl"]),nick)
            vh.CloseConnection(nick)
            return config["blockcommand"]
        
        share = getUserShareinGB(nick)
        ip = vh.GetUserIP(nick)
        sendMainChatMsgToNick("Welcome to DC Hub, BIT Mesra ][ Nick: %s ][ Share: %s GB ][ IP: %s ][ Level: %s" % (nick, share, ip, userdata['class']), nick)
        
        (notauthenticated, notshared, ipnotmatching) = checkStatus(nick, userdata)
        if notauthenticated or notshared:
            if ipnotmatching:
                sendMainChatMsgToNick("Error: This (%s) is not the IP Address that you registered from. Please use your original IP Address (%s).\nThis restriction is in place because %s.\nTo remove this restriction: \n    1) Authenticate you account at %s\n    2) Share %dGB.\n" % (ip, userdata["ipaddress"], "you have not autheticated your account" if notauthenticated else "your share is less than %dGB" % config["sharesize"], config["authurl"], config["sharesize"]), nick)
                vh.CloseConnection(nick)
                log("Login","%s,%sGB" % (ip, share),nick,"",1)
                return config["blockcommand"]
            sendPMToNick("WARNING: %s. You cannot talk in Main Chat or send Private Messages.\nTo remove this restriction: \n    1) Authenticate you account at %s\n    2) Share %dGB.\n" % ("You have not authenticated your account" if notauthenticated else "You have shared less than %dGB" % config["sharesize"], config["authurl"], config["sharesize"]),nick)
        
        log("Login","%s,%sGB" % (ip, share),nick)
        updateTable(config['tables']['users'],{"lastShared":getUserShareinGB(nick),"lastLogin":getDateTimeInFormat("%Y-%m-%d %H:%M:%S")},"id='%s'",[userdata['id']])
        if int(userdata['class'])>=config['displayipauth']:
            sendIPDetails(nick)
        notice(nick, 1)
        data = getLatestContent()
        sendMainChatMsgToNick(data,nick)
        mainChat(nick)
        sendMainChatMsgToNick(getFileContents(config['lastmsg']),nick)
        (nick, desc, tag, speed, mail, size) = vh.GetMyINFO(nick)
        if "M:P" in tag:
            sendMainChatMsgToNick("You have connected in passive mode. Your search and downloads will be very slow. See how to connect in active mode at %s#step5" % config['faqurl'],nick)
        if (userdata['gender']=="F" or userdata['hostel']=="9") and userdata['roll_course']=="BE" and userdata['roll_year']=="2010":
            for nk in ["sdh"]:
                sendPMToNick("%s\nName: %s\nBranch: %s\nRoll: %s/%s/%s\nHostel %s Room %s\nIP: %s" % (nick,userdata['fullname'],userdata['branchname'],userdata['roll_course'],userdata['roll_number'],userdata['roll_year'],userdata['hostel'],userdata['room'],ip),nk,config['stalker'])
        return config["allowcommand"]
    except Exception, e:
        handleError(e,nick,"Error on user login")
        if nick in config['admins']: return config["allowcommand"]
    return config["blockcommand"]

def OnTimer():
    global config,debug
    try:
        if config["clock"]%config["authremind"]==0:
            for nick in vh.GetNickList():
                data = getAuthenticateRequestsData(nick)
                if data!="":
                    sendPMToNick(data,nick)
        if config["clock"]%config["messages"]==0:
            sendOfflineMessages()
        if config["clock"]%config["notifications"]==0:
            sendNotifications()
        if config["clock"]%config["saveuserlist"]==0:
            logToFile(str(vh.GetNickList()),"userlistpath","UserList-%s.log" % getDateTimeInFormat("%Y-%m-%d"))
        if config["clock"]%config["displayips"]==0:
            sendIPDetails()
    except Exception, e:
        if config['error']==0:
            handleError(e,"sdh","Error in timer")
            config['error']=1
    if config['clock']==config['clockcountreset']:
        config["clock"]=0
    else:
        config["clock"]+=1

def OnParsedMsgMyINFO(nick, desc, tag, speed, mail, size):
    return config["allowcommand"]
    (result, userdata) = getUserDetailfromTable(nick)
    if result==0 or len(userdata)==0: pass
    else: return ("%s [class=%s]" % (desc,userdata['class']), None, None, None, None)

def OnUserCommand(nick,command):
    global config
    try:
        _command = "".join(command)
        command=_command[1:].split()
        
        (result, userdata) = getUserDetailfromTable(nick)
        if result==0 or len(userdata)==0:
            sendMainChatMsgToNick("Error: Nickname not found in Database. Please register yourself at %s" % config["regurl"],nick)
            vh.CloseConnection(nick)
            return config["blockcommand"]    
        userclass = int(userdata["class"])
        
        sendMainChatMsgToNick("Your command: %s" % _command, nick)
        
        if command[0] not in config['commandswithlevel']:
            sendMainChatMsgToNick("Error: No such command. Type +help to see all commands available to you", nick)
            log("UserCommand","%s" % _command,nick,"",1)
            return config["blockcommand"]
        if userclass < config['commandswithlevel'][command[0]]:
            sendMainChatMsgToNick("Error: You need an access level of atleast %s to use this command. Type +help to see all commands available to you" % config['commandswithlevel'][command[0]], nick)
            log("UserCommand","%s" % _command,nick,"",1)
            return config["blockcommand"]
        log("UserCommand","%s" % _command,nick)
        
        if command[0]=="help":
            if len(command)==2:
                if command[1] not in config['commandswithlevel']:
                    sendMainChatMsgToNick("Error: No such command. Type +help to see all commands available to you", nick)
                    return config["blockcommand"]
                st="Help for '%s':\n\n" % command[1]
                for data in config['help'][command[1]]:
                    st += "%s: %s\n" % (data,config['help'][command[1]][data])
                sendMainChatMsgToNick(st, nick)
            else:
                st="List of commands available to you:\n"
                classes = config['classmap'].keys()
                classes.sort()
                i=0
                while i < len(classes) and classes[i]<=userclass:
                    st += "\nLevel %s:\n" % classes[i]
                    commands = config['levelcommands'][classes[i]].keys()
                    commands.sort()
                    if len(commands)==0:
                        st += "Nothing here\n"
                    else:
                        for command in commands:
                            dt = config['levelcommands'][classes[i]][command]
                            for data in dt:
                                st += "%s: %s\n" % (data,dt[data])
                    i += 1
                sendPMToNick(st, nick)
                sendMainChatMsgToNick("Results sent as PM", nick)
        
        elif command[0]=="share":
            if len(command)<2:
                sendMainChatMsgToNick("Error: Incorrect format due to missing data. Type '+help %s' to see the correct format" % command[0],nick)
                return config["blockcommand"]
            title = []; magnet = [];
            phase = 0
            for word in command[1:]:
                if word=="&T": phase = 0
                elif word=="&M": phase = 1
                elif phase==0:
                    if re.match("magnet\:\?xt=urn\:tree\:tiger\:([A-Za-z0-9]{39})\&xl=[0-9]+\&dn=.+",word)==None and re.match("magnet\:\?xt=urn\:bitprint\:[A-Za-z0-9]{32}\.[A-Za-z0-9]{39}\&xt=urn\:md5\:[A-Za-z0-9]{32}\&xl=[0-9]+\&dn=.+",word)==None:
                        title.append(word)
                    else:
                        magnet.append(word)
                        title.append(urllib.unquote(word[word.rindex("&dn=")+4:].replace("+"," ")))
                elif phase==1: magnet.append(word)
            title = " ".join(title); magnet = " ".join(magnet);
            if re.match("magnet\:\?xt=urn\:tree\:tiger\:([A-Za-z0-9]{39})\&xl=[0-9]+\&dn=.+",magnet)==None and re.match("magnet\:\?xt=urn\:bitprint\:[A-Za-z0-9]{32}\.[A-Za-z0-9]{39}\&xt=urn\:md5\:[A-Za-z0-9]{32}\&xl=[0-9]+\&dn=.+",magnet)==None: magnet = ""
            result = insertIntoTable(config["tables"]["content"],{"uid":userdata["id"],"timestamp":int(time.time()),"title":title,"magnetlink":magnet})
            if result==0:
                sendMainChatMsgToNick("Error: Could not add new share",nick)
            else:
                sendMainChatMsgToAll(nick+" has shared : "+(title if magnet=="" else magnet[:magnet.rindex("&")]+"&dn="+urllib.quote_plus(title)))

        elif command[0]=="latest":
            data = getLatestContent()
            sendMainChatMsgToNick(data,nick)
        
        elif command[0]=="notice":
            notice(nick)
            sendMainChatMsgToNick("Results sent as PM", nick)
        
        elif command[0]=="myinfo":
            d = showUserInfo(nick, nick, 1)
            if d == config['blockcommand']: return config['blockcommand']
            else: sendMainChatMsgToNick(d,nick)
        
        elif command[0]=="password":
            if len(command)!=3:
                sendMainChatMsgToNick("Error: Incorrect Format. Type '+help %s' to see the correct format" % command[0],nick)
                return config["blockcommand"]
            if command[1]==command[2]:
                sendMainChatMsgToNick("Error: You have entered the same old and new passwords",nick)
                return config["blockcommand"]
            if command[1]!=userdata['password_']:
                sendMainChatMsgToNick("Error: You have entered incorrect old password",nick)
                return config["blockcommand"]
            if updatePassword(userdata['nick1'],userdata['nick2'], command[2])!=0:
                sendMainChatMsgToNick("Error: Could not update password",nick)
            else:
                sendMainChatMsgToNick("Success: Your password has been changed to '%s'" % command[2],nick)
        
        elif command[0]=="hubinfo":
            sendMainChatMsgToNick("Information related to the DC Hub can be found at: %s" % config['faqurl'],nick)
        
        elif command[0]=="schedule":
            tosend = "No data retrieved."
            if len(command)>1:
                (result, sqldata) = selectAllFromTable("select date from %s order by id desc limit 1", [config["tables"]["tvschedule"]], None, False)
                if result==0 or len(sqldata)==0:
                    sendMainChatMsgToNick("We faced some error.",nick)
                    return config["blockcommand"]
                lastdate = sqldata[0][0]
                st = ""
                terms = []
                for i in command[1:]:
                    terms.append(" showname like '%%%s%%' " % escape(i))
                (result, sqldata) = selectAllFromTable("select * from %s  where %s", [config["tables"]["tvschedule"], "and".join(terms)], "tvschedule", False)
                if result==0 or len(sqldata)==0:
                    st += "Found Nothing :("
                else:
                    for userdata in sqldata:
                        st += "%-30s %s - %s\n" % (userdata['date'] + " :", userdata['showname'], userdata['showtitle'])
                tosend = "TV Schedule for '%s' till %s\n\n%s\n%s\n%s\n" % (" ".join(command[1:]), lastdate,"="*config['ecount'],st,"="*config['ecount'])
            else:
                tosend = "TV Schedule for the next week:\n%s\n%s\n%s\n" % ("="*config['ecount'],getFileContents(config['tvschedule']),"="*config['ecount'])
                '''(result,sqldata) = selectAllFromTable("SELECT * from %s order by id limit 100", [config["tables"]["tvschedule"]], "tvschedule")
                if len(sqldata)==0:
                    tosend = "No schedule data found."
                else:
                    out = []
                    alldata = []
                    for row in sqldata:
                        if [row['date']] in [i.keys() for i in alldata]:
                            pos = [i.keys() for i in alldata].index([row['date']])
                            if row['time'] in alldata[pos]:
                                alldata[pos][row['time']].append([row['showname'],row['showtitle']])
                            else:
                                alldata[pos][row['time']] = [[row['showname'],row['showtitle']]]
                        else:
                            alldata.append({row['date']:{row['time']:[[row['showname'],row['showtitle']]]}})
                    sendMainChatMsgToNick("%s %s" % (type(alldata),type(alldata[0])),"sdh")
                    from tvschedule import Downloader
                    c=Downloader()
                    d = c.getDataInFormat(alldata)
                    tosend = "TV Schedule for the next week:\n%s\n%s\n%s\n" % ("="*config['ecount'],d,"="*config['ecount'])'''
            sendPMToNick(tosend, nick)
            sendMainChatMsgToNick("Results sent as PM", nick)
        
        elif command[0]=="offline":
            if len(command)<3:
                sendMainChatMsgToNick("Error: Incorrect Format. Type '+help %s' to see the correct format" % command[0],nick)
                return config["blockcommand"]
            nickname = command[1]
            message = " ".join(command[2:])
            if nickname==nick:
                sendMainChatMsgToNick("Why would you want to send a message to yourself?",nick)
                return config["blockcommand"]
            (result, data) = getUserDetailfromTable(nickname)
            if result==0 or len(data)==0:
                sendMainChatMsgToNick("Error: Nickname not found in Database.",nick)
                return config["blockcommand"]
            if nickname in vh.GetNickList():
                sendMainChatMsgToNick("Warning: User '%s' is online now. You should send a PM directly as this message will be delivered after a delay" % nickname, nick)
            result = insertIntoTable(config["tables"]["messages"],{"toid":data['id'],"fromid":userdata['id'],"msg":message})
            if result==0:
                sendMainChatMsgToNick("Error: There was some error",nick)
                return config["blockcommand"]
            sendMainChatMsgToNick("Success: Offline message to '%s' will be eventually delivered." % nickname, nick)
        
        elif command[0]=="hot":
            data = getHotContent()
            if data=="":
                sendMainChatMsgToNick("Sorry, no HOT content retrieved",nick)
            else:
                sendPMToNick(data,nick)
                sendMainChatMsgToNick("Results sent as PM", nick)
                
        elif command[0]=="request":
            if len(command)<2:
                sendMainChatMsgToNick("Error: Incorrect Format. Type '+help %s' to see the correct format" % command[0],nick)
                return config["blockcommand"]
            request = " ".join(command[1:])
            result = insertIntoTable(config["tables"]["request"],{"uid":userdata['id'],"request_file":request})
            if result==0:
                sendMainChatMsgToNick("Error: There was some error",nick)
                return config["blockcommand"]
            sendMainChatMsgToNick("Success. Your request was successfully posted at %s" % config['requesturl'], nick)
	            
        elif command[0]=="notify":
            if len(command)<2:
                sendMainChatMsgToNick("Error: Incorrect Format. Type '+help %s' to see the correct format" % command[0],nick)
                return config["blockcommand"]
            message = " ".join(command[1:])
            result = insertIntoTable(config["tables"]["posts"],{"gid":1,"postby":nick,"post":message})
            if result==0:
                sendMainChatMsgToNick("Error: There was some error",nick)
                return config["blockcommand"]
            sendMainChatMsgToNick("Success: This message will be eventually delivered after Admin approval.", nick)
        
        elif command[0]=="authenticate":
            if len(command) not in [1,2,3] and (len(command)==3 and command[1]!="decline"):
                sendMainChatMsgToNick("Error: Incorrect Format. Type '+help %s' to see the correct format" % command[0],nick)
                return config["blockcommand"]
            if len(command)==1:
                data = getAuthenticateRequestsData(nick)
                if data == "":
                    sendMainChatMsgToNick("No authentication requests for you",nick)
                else:
                    sendPMToNick(data,nick)
                    sendMainChatMsgToNick("Results sent as PM", nick)
            elif len(command)==2:
                nickname = command[1]
                if nickname == nick:
                    sendMainChatMsgToNick("Error: You cannot authenticate yourself",nick)
                    return config["blockcommand"]
                (result, data) = getUserDetailfromTable(nickname)
                if result==0 or len(data)==0:
                    sendMainChatMsgToNick("Error: Nickname not found in Database.",nick)
                    return config["blockcommand"]
                if data['friend']!=nick:
                    sendMainChatMsgToNick("Error: %s has not indicated your nick for authentication" % nickname,nick)
                    return config["blockcommand"]
                if int(data['class'])!=0:
                    sendMainChatMsgToNick("Error: %s has already been authenticated" % nickname,nick)
                    return config["blockcommand"]                
                changeUserClass(data['nick1'],data['nick2'], 1)
                if result==0:
                    sendMainChatMsgToNick("Error: There was some error",nick)
                    return config["blockcommand"]
                sendMainChatMsgToNick("Cool! You have successfully authenticated '%s'" % nickname, nick)
            elif len(command)==3:
                nickname = command[2]
                if nickname == nick:
                    sendMainChatMsgToNick("Error: You cannot authenticate yourself",nick)
                    return config["blockcommand"]
                (result, data) = getUserDetailfromTable(nickname)
                if result==0 or len(data)==0:
                    sendMainChatMsgToNick("Error: Nickname not found in Database.",nick)
                    return config["blockcommand"]
                if data['friend']!=nick:
                    sendMainChatMsgToNick("Error: %s has not indicated your nick for authentication" % nickname,nick)
                    return config["blockcommand"]
                result = updateTable(config["tables"]["users"],{"friend":""},"(nick1='%s' or nick2='%s')",[nickname, nickname])
                if result==0:
                    sendMainChatMsgToNick("Error: There was some error",nick)
                    return config["blockcommand"]
                sendMainChatMsgToNick("You have declined authenticating '%s'" % nickname, nick)
        
        elif command[0]=="me":
            if len(command)<2:
                sendMainChatMsgToNick("Error: Incorrect format due to missing message. Type '+help %s' to see the correct format" % command[0],nick)
                return config["blockcommand"]
            sendMainChatMsgToAll("[%s] %s" % (nick," ".join(command[1:])))
        
        elif command[0]=="unshare":
            if len(command)!=2:
                sendMainChatMsgToNick("Error: Incorrect format due to missing id. Type '+help %s' to see the correct format" % command[0],nick)
                return config["blockcommand"]
            cid = command[1]
            (r,d) = selectAllFromTable("select cid from %s where cid = '%s' and deleted=0 ORDER BY priority desc, timestamp DESC LIMIT 0,10", [config["tables"]["content"], cid])
            if r==0 or len(d)==0:
                sendMainChatMsgToNick("Error: Content ID not found in top 10 list",nick)
                return config["blockcommand"]
            result = updateTable(config["tables"]["content"],{"deleted":1},"cid='%s'",[cid])
            if result==0:
                sendMainChatMsgToNick("Error: Content was not deleted",nick)
                return config["blockcommand"]
            data = getLatestContent()
            sendMainChatMsgToNick(data,nick)
            sendMainChatMsgToNick("Success: Content has been removed.",nick)
        
        elif command[0]=="clear":
            sendMainChatMsgToAll("\n"*50, nick)
            data = getLatestContent()
            sendMainChatMsgToAll(data, config['hubbot'])
        
        elif command[0]=="info":
            if len(command)<2:
                sendMainChatMsgToNick("Error: Incorrect format due to missing nickname. Type '+help %s' to see the correct format" % command[0],nick)
                return config["blockcommand"]
            d = showUserInfo(command[1], nick)
            if d == config['blockcommand']: return config['blockcommand']
            else: sendMainChatMsgToNick(d,nick)
            if nick in config['admins'] and len(command)>2:
                sendMainChatMsgToNick("Retrieving search...",nick)
                (r,s) = selectAllFromTable("SELECT TRIM(replace(SUBSTRING_INDEX(message, '?', -1),'$',' ')) sr, count(*) cnt FROM %s WHERE nick='%s' and logtype = 'Search' and message not like '%%TTH:%%' group by nick,sr having sr not like '' order by datetime desc",[config['tables']['log'],command[1]])
                if r==0 or len(s)==0: dt = "No data"
                else:
                    dt = ""
                    for row in s:
                        dt+= "%s (%s), " % (row[0],row[1])
                sendMainChatMsgToNick("%s\n" % dt,nick)
                sendMainChatMsgToNick("Retrieving commands...",nick)
                (r,s) = selectAllFromTable("SELECT case when message like '!bc%%' or message like '!broadcast%%' or message like '+offline%%' or message like '+notify%%' then SUBSTRING_INDEX(message, ' ', 1) else message end sr, count(*) cnt FROM %s WHERE nick='%s' and (logtype = 'UserCommand' or logtype = 'OperatorCommand') group by nick,sr having sr not like '' order by sr",[config['tables']['log'],command[1]])
                if r==0 or len(s)==0: dt = "No data"
                else:
                    dt = ""
                    for row in s:
                        dt+= "%s (%s), " % (row[0],row[1])
                sendMainChatMsgToNick("%s\n" % dt,nick)

        elif command[0]=="infoip":
            if len(command)!=2:
                sendMainChatMsgToNick("Error: Incorrect format due to missing IP address. Type '+help %s' to see the correct format" % command[0],nick)
                return config["blockcommand"]
            if re.match("[0-9]+\.[0-9]+\.[0-9]+\.[0-9]",command[1])==None:
                sendMainChatMsgToNick("Error: Incorrect IP Format",nick)
                return config["blockcommand"]
            (result, sqldata) = selectAllFromTable("SELECT nick1,nick2,fullname FROM %s WHERE deleted=0 AND ipaddress='%s';", [config["tables"]["users"],command[1]])
            if result==0:
                sendMainChatMsgToNick("Error: Data not retrieved successfuly",nick)
                return config["blockcommand"]
            if len(sqldata)==0:
                sendMainChatMsgToNick("Could not find any records for IP %s." % command[1],nick)
            else:
                out = "Records for IP %s:\n" % command[1]
                for entry in sqldata:
                    out += "Name: %s, Nick1: %s, Nick2: %s\n" % (entry[2],entry[0],entry[1])
                sendMainChatMsgToNick(out,nick)
       
        elif command[0]=="authlist":
            if len(command)!=2:
                sendMainChatMsgToNick("Error: Incorrect format due to missing nickname. Type '+help %s' to see the correct format" % command[0],nick)
                return config["blockcommand"]
            (result, sqldata) = getUserDetailfromTable(command[1])
            if result==0:
                sendMainChatMsgToNick("Error: Data not retrieved successfuly (1)",nick)
                return config["blockcommand"]
            if len(sqldata)==0:
                sendMainChatMsgToNick("Error: The nick '%s' was not found" % command[1],nick)
            else:
                (result, sqldata) = selectAllFromTable("SELECT nick1,nick2,fullname FROM %s WHERE friend='%s' AND deleted=0", [config["tables"]["users"],command[1]])
                if result==0:
                    sendMainChatMsgToNick("Error: Data not retrieved successfuly (2)",nick)
                    return config["blockcommand"]
                if len(sqldata)==0:
                    sendMainChatMsgToNick("'%s' has not authenticated any user" % command[1],nick)
                else:
                    out = "List of users authenticated by %s:\n" % command[1]
                    for entry in sqldata:
                        out += "Name: %s, Nick1: %s, Nick2: %s\n" % (entry[2],entry[0],entry[1])
                    sendMainChatMsgToNick(out,nick)
        
        elif command[0]=="send":
            #TODO
            sendMainChatMsgToNick("Command not yet implemented",nick)
        
        elif command[0]=="view":
            #TODO
            sendMainChatMsgToNick("Command not yet implemented",nick)
        
        elif command[0]=="sendoffline":
            sendOfflineMessages()
        
        elif command[0]=="generatelatest":
            sendMainChatMsgToNick("Not needed",nick)
            #generateLatest(nick)
        
        elif command[0]=="generateschedule":
            from tvschedule import Downloader
            try:
                c = Downloader(True)
                c.downloadContents()
                sendMainChatMsgToNick("Latest schedule has been downloaded",nick)
            except Exception,e:
                handleError(e,nick,"Error downloading schedule")
        
        elif command[0]=="sendmsg":
            if len(command)<4:
                sendMainChatMsgToNick("Error: Incorrect format due to missing data. Type '+help %s' to see the correct format" % command[0],nick)
                return config["blockcommand"]
            if command[1]=="mc":
                fromnick = command[2]
                if fromnick == "sdh":
                    sendMainChatMsgToNick("Are you sure you want to do that? [If yes GG GM]",nick)
                    return config["blockcommand"]
                data = " ".join(command[3:])
                sendMainChatMsgToAll(data,fromnick)
                OnParsedMsgChat(fromnick,data)
            elif command[1]=="pm":
                tonick = command[2]
                fromnick = command[3]
                if fromnick == "sdh":
                    sendMainChatMsgToNick("Are you sure you want to do that? [If yes GG GM]",nick)
                    return config["blockcommand"]
                data = " ".join(command[4:])
                sendPMToNick(data,tonick,fromnick)
                OnParsedMsgPM(fromnick, data, tonick)
            else:
                sendMainChatMsgToNick("Error: Wrong format",nick)
                return config["blockcommand"]

        else:
            sendMainChatMsgToNick("Command not found", nick)
        
        return config["blockcommand"]
    except Exception, e:
        handleError(e,nick,"Error parsing user command")
    return config["blockcommand"]
    
def OnOperatorCommand(nick,command):
    global config
    try:
        _command = "".join(command)
        command=_command[1:].split()
        
        (result, userdata) = getUserDetailfromTable(nick)
        if result==0 or len(sqldata)==0:
            sendMainChatMsgToNick("Error: Nickname not found in Database. Please register yourself at %s" % config["regurl"],nick)
            vh.CloseConnection(nick)
            return config["blockcommand"]    
        userclass = int(userdata["class"])
        
        sendMainChatMsgToNick("Your command: %s" % _command, nick)
        
        if command[0] not in config['commandswithlevel']:
            log("OperatorCommand","%s" % _command,nick,"",2)
            return config["allowcommand"]
        if userclass < config['commandswithlevel'][command[0]]:
            sendMainChatMsgToNick("Error: You need an access level of atleast %s to use this command. Type +help to see all commands available to you" % config['commandswithlevel'][command[0]], nick)
            log("OperatorCommand","%s" % _command,nick,"",1)
            return config["blockcommand"]
        log("OperatorCommand","%s" % _command,nick)
        
        if command[0]=="multicast":
            if len(command)!=4:
                sendMainChatMsgToNick("Error: Incorrect format due to missing data. Type '+help %s' to see the correct format" % command[0],nick)
                return config["blockcommand"]
            if re.match("[0-9]+\.[0-9]+\.[0-9]+\.[0-9]",command[1])==None or re.match("[0-9]+\.[0-9]+\.[0-9]+\.[0-9]",command[2])==None:
                sendMainChatMsgToNick("Error : Incorrect IP Format. Type '+help %s' to see the correct format" % command[0],nick)
                return config["blockcommand"]
            start = ip2int(command[1]); end = ip2int(command[2]);
            for user in vh.GetNickList():
                ip = vh.GetUserIP(user)
                if re.match("[0-9]+\.[0-9]+\.[0-9]+\.[0-9]",ip)==None: continue
                ip = ip2int(ip)
                if start<=ip and ip<=end:
                    sendPMToNick(_command[len(command[0])+len(command[1])+len(command[2])+4:],user)
            sendMainChatMsgToNick("Successfully sent specified message to all users in given IP Range.",nick)
        
        if command[0]=="regnew":
            if len(command)!=3 and len(command)!=5:
                sendMainChatMsgToNick("Error: Incorrect format due to missing data. Type '+help %s' to see the correct format" % command[0],nick)
                return config["blockcommand"]
            if len(command)==5:
                if int(command[3]) not in config["classmap"]:
                    sendMainChatMsgToNick("Error: Invalid Access Level",nick)
                    return config["blockcommand"]
                if re.match("[0-9]+\.[0-9]+\.[0-9]+\.[0-9]",command[4])==None:
                    sendMainChatMsgToNick("Error: Incorrect IP Address",nick)
                    return config["blockcommand"]
                accesslevel = command[3]
                ip = command[4]
            else:
                accesslevel = 3
                ip = "127.0.0.1"
            nickname = command[1]
            password = command[2]
            (r,d) = getUserDetailfromTable(nickname)
            if len(d)>0:
                sendMainChatMsgToNick("Error: This nickname has already been taken",nick)
                return config["blockcommand"]
            r1 = insertIntoTable(config["tables"]["users"],{"nick1":nickname,"password_":password,"ipaddress":ip,"class":accesslevel})
            r2 = insertIntoTable(config["tables"]["dcusers"], {"nick":nickname,"class":config["classmap"][accesslevel],"reg_date":int(time.time()),"reg_op":nick,"pwd_change":0,"pwd_crypt":0,"login_pwd":password,"login_ip":ip})
            if r1==0 or r2==0:
                sendMainChatMsgToNick("Error: Could not insert data",nick)
            else:
                sendMainChatMsgToNick("User '%s' created successfully. Please ensure that you fill up his/her details via the Web Interface ASAP." % command[1],nick)
        
        if command[0]=="regpasswd":
            if len(command)!=2 and len(command)!=3:
                sendMainChatMsgToNick("Error: Incorrect format due to missing data. Type '+help %s' to see the correct format" % command[0],nick)
                return config["blockcommand"]
            nickname = command[1]
            (r,d) = getUserDetailfromTable(nickname)
            if r==0 or len(d)==0:
                sendMainChatMsgToNick("Error: The nick '%s' was not found" % nickname,nick)
                return config["blockcommand"]
            if len(command)==2:
                sendMainChatMsgToNick("%s's password = %s" % (nickname, d['password_']),nick)
            else:
                if updatePassword(d['nick1'],d['nick2'], command[2])!=0:
                    sendMainChatMsgToNick("Error: Could not set %s's password to '%s'" % (nickname, command[2]),nick)
                else:
                    sendMainChatMsgToNick("Success: %s's password has been changed to '%s'" % (nickname, command[2]),nick)
        
        if command[0]=="regdelete":
            if len(command)!=2:
                sendMainChatMsgToNick("Error: Incorrect format due to missing nick. Type '+help %s' to see the correct format" % command[0],nick)
                return config["blockcommand"]
            nickname = command[1]
            (r,d) = getUserDetailfromTable(nickname)
            if r==0 or len(d)==0:
                sendMainChatMsgToNick("Error: The nick '%s' was not found" % nickname,nick)
                return config["blockcommand"]
            updateTable(config["tables"]["users"],{"deleted":1},"(nick1='%s' or nick2='%s')",[nickname, nickname])
            sendMainChatMsgToNick("The account for user '%s' deleted successfully." % nickname,nick)
        
        if command[0]=="regclass":
            if len(command)!=3:
                sendMainChatMsgToNick("Error: Incorrect format due to missing data. Type '+help %s' to see the correct format" % command[0],nick)
                return config["blockcommand"]
            nickname = command[1]
            classlevel = int(command[2])
            if classlevel not in config["classmap"]:
                sendMainChatMsgToNick("Error: Invalid access level",nick)
                return config["blockcommand"]
            (r,d) = getUserDetailfromTable(nickname)
            if r==0 or len(d)==0:
                sendMainChatMsgToNick("Error: The nick '%s' was not found" % nickname,nick)
                return config["blockcommand"]
            changeUserClass(d['nick1'],d['nick2'],classlevel)
            sendMainChatMsgToNick("Success: The access level of '%s' has been changed to %s." % (nickname, classlevel),nick)
            
        return config['blockcommand']
    except Exception, e:
        handleError(e,nick,"Error parsing operator command")
    return config["blockcommand"]

def OnParsedMsgChat(nick,data):
    global config, mainchatlog
    try:
        (result, userdata) = getUserDetailfromTable(nick)
        if result==0 or len(userdata)==0:
            sendMainChatMsgToNick("Error: Nickname not found in Database. Please register yourself at %s" % config["regurl"],nick)
            log("MC", data, nick, "", 1)
            vh.CloseConnection(nick)
            return config["blockcommand"]    
        
        (notauthenticated, notshared, ipnotmatching) = checkStatus(nick, userdata)
        if notauthenticated or notshared:
            sendMainChatMsgToNick("Error: Your message to the main chat was blocked because %s.\nTo remove this restriction: \n    1) Authenticate you account at %s\n    2) Share %dGB.\n" % ("you have not autheticated your account" if notauthenticated else "your share is less than %dGB" % config["sharesize"], config["authurl"], config["sharesize"]), nick)
            log("MC", data, nick, "", 1)
            return config["blockcommand"]
        
        logToFile("<%s> %s" % (nick, data), "mainchatpath", "%s.log" % getDateTimeInFormat("%Y-%m-%d"))
        now = datetime.datetime.now()
        mainchatlog.append("[%s] <%s> %s" % (getDateTimeInFormat("%Y-%m-%d %H:%M:%S"),nick,data));
        mainchatlog = mainchatlog[-config["mainchat"]:]
        return config["allowcommand"]
    except Exception, e:
        handleError(e,nick,"Error parsing message to chat")
    return config["blockcommand"]
        
def OnParsedMsgPM(nick,data,receiver):
    global config
    try:
        (result, userdata) = getUserDetailfromTable(nick)
        if result==0 or len(userdata)==0:
            sendMainChatMsgToNick("Error: Nickname not found in Database. Please register yourself at %s" % config["regurl"],nick)
            log("PM", data, nick, receiver, 1)
            vh.CloseConnection(nick)
            return config["blockcommand"]    

        (notauthenticated, notshared, ipnotmatching) = checkStatus(nick, userdata)
        if (notauthenticated or notshared) and receiver not in config['admins']:
            sendPMToNick("Error: Your message to %s was blocked because %s.\nTo remove this restriction: \n    1) Authenticate you account at %s\n    2) Share %dGB.\n" % (receiver, "you have not autheticated your account" if notauthenticated else "your share is less than %dGB" % config["sharesize"], config["authurl"], config["sharesize"]), nick)
            log("PM", data, nick, receiver, 1)
            return config["blockcommand"]
        
        log("PM", data, nick, receiver)
        return config["allowcommand"]
    except Exception, e:
        handleError(e,nick,"Error parsing message to chat")
    return config["blockcommand"]

def OnParsedMsgSearch (nick,data):
    global config
    try:
        log("Search", data, nick)
        (nick, desc, tag, speed, mail, size) = vh.GetMyINFO(nick)
        if "M:P" in tag:
            sendPMToNick("You have connected in passive mode. Your search and downloads will be very slow. See how to connect in active mode at %s#step5" % config['faqurl'],nick,"DCPassiveWarning")
        return config["allowcommand"]
    except Exception, e:
        handleError(e,nick,"Error parsing search")
    return config["allowcommand"]

def OnUserLogout(nick):
    global config
    try:
        log("Logout", "%s,%sGB" % (vh.GetUserIP(nick), getUserShareinGB(nick)), nick)
        return config["allowcommand"]
    except Exception, e:
        handleError(e,nick,"Error parsing logout")
    return config["allowcommand"]

'''
!pyreload /etc/verlihub/scripts/verlihub.py
'''
