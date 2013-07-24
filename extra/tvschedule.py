from bs4 import BeautifulSoup
import os.path
import os
import sys
import urllib
import urllib2
import cookielib
import re
import time
import MySQLdb

class Downloader:

    def __init__(self,writetopath=False):
        try:
            sys.path.append("/etc/verlihub/scripts")
            from config import email, password, savepath
        except ImportError as er:
            raise Exception("Error: %s. You should provide config.py with email, password" % er.message)
        if email == '' or password == '':
            raise Exception("Please edit config.py with your email, password")
        auth = {"login_name":email,"login_pass":password}
        self.url = "http://www.tvrage.com/mytvrage.php?page=myschedule"
        self.loginurl = "http://www.tvrage.com/login.php"
        auth["curr_page"]=""
        auth["submit"]="Log In"
        self.auth = auth
        self.savepath = savepath
        self.loggedin = 0
        self.writetopath = writetopath
        self.cookiefilepath = os.path.join(os.getcwd(), "cookie")
        self.cookie = cookielib.LWPCookieJar()
        opener = urllib2.build_opener(urllib2.HTTPCookieProcessor(self.cookie))
        urllib2.install_opener(opener)
        self.headers = {'User-Agent': 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:17.0) Gecko/20100101 Firefox/17.0'}
        
    def escape(self, s):
        return s.replace("'","\\'").replace('"','\\"').encode('ascii','replace')
    
    def getDataInFormat(self, alldata):
        st = ""
        for data in alldata:
            for date in data:
                st += date+"\n-------------------------\n"
                for time in data[date]:
                    for show in data[date][time]:
                        st += "    "+show[0]+" : "+show[1]+"\n"
                st += "\n"
        return st

    def getPageHandle(self):
        #return open("a.html","r")
        self.cookie.clear()
        self.headers['Referer']=self.loginurl
        try:
            req = urllib2.Request(self.loginurl, urllib.urlencode(self.auth).encode('utf-8'), self.headers)
            handle = urllib2.urlopen(req)
        except IOError as e:
            raise Exception("Could not submit login form: "+e.reason)
        self.cookie.save(self.cookiefilepath)
        try:
            req = urllib2.Request(self.url, None, self.headers)
            handle = urllib2.urlopen(req)
        except IOError as e:
            raise Exception("Could not download files list: "+e.reason)
        return handle
    
    def downloadContents(self):
        alldata = []
        handle = self.getPageHandle()
        html = BeautifulSoup(handle.read())
        rows=html.find("table","b").find_all("tr")
        datepos = []
        for pos, row in enumerate(rows):
            if 'colorheader' in str(row):
                datepos.append(pos)
        datepos.append(len(rows))
        for index in range(len(datepos)-1):
            dategroup = rows[datepos[index]:datepos[index+1]]
            data = {}
            date = self.escape(str(rows[datepos[index]].find("b").contents[0]))
            data[date] = {}
            timepos = []
            for pos,row in enumerate(dategroup):
                if 'b53' in str(row):
                    timepos.append(pos)
            timepos.append(datepos[index+1])
            for i in range(len(timepos)-1):
                time = self.escape(str(dategroup[timepos[i]].find("b").contents[0]))
                data[date][time] = []
                shows = dategroup[timepos[i]+1:timepos[i+1]]
                for show in shows:
                    columns = show.find_all("td")
                    name = self.escape(str(columns[1].find("a").contents[0]))
                    episode = self.escape(columns[2].find("span").get_text(" ",strip=True))
                    data[date][time].append((name,episode))
            alldata.append(data)
        db=MySQLdb.connect(host="localhost",user="verlihub",passwd="tr3a5ur3",db="verlihub")
        c=db.cursor(MySQLdb.cursors.DictCursor)
        c.execute("TRUNCATE TABLE dchub_tvschedule")
        for data in alldata:
            for date in data:
                for time in data[date]:
                    for show in data[date][time]:
                        d={"date":date,"showname":show[0],"showtitle":show[1],"time":time}
                        st=[]
                        for i in d:
                            st.append(i+"='"+d[i]+"'")
                        c.execute("insert into dchub_tvschedule set %s" % ",".join(st))
        db.commit()
        if self.writetopath:
          fl=open(self.savepath,"w")
          data = self.getDataInFormat(alldata[0:6])
          print(data)
          fl.write(data)
          fl.close()

def main():
    c = Downloader(True)
    c.downloadContents()

if __name__ == "__main__":
    main()

