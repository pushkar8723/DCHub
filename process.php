<?php

require_once 'config.php';
// Registration Code
if (isset($_POST['register'])) {
    $_POST['data']['ipaddress'] = $_SERVER['REMOTE_ADDR'];
    $_POST['data'] = secure($_POST['data']);
    $error = 0;
    if ($_POST['data']['fullname'] == "") {
        $_SESSION['msg'] .= "Name required<br/>";
        $error = 1;
    }
    if ($_POST['data']['roll_number'] == "") {
        $_SESSION['msg'] .= "Roll Number required<br/>";
        $error = 1;
    }
    if ($_POST['data']['branch'] == "") {
        $_SESSION['msg'] .= "Branch required<br/>";
        $error = 1;
    }
    if ($_POST['data']['room'] == "") {
        $_SESSION['msg'] .= "Room required<br/>";
        $error = 1;
    }
    if ($_POST['data']['email'] == "") {
        $_SESSION['msg'] .= "Email required<br/>";
        $error = 1;
    }
    if ($_POST['data']['nick1'] == "") {
        $_SESSION['msg'] .= "Nick required<br/>";
        $error = 1;
    }
    if ($_POST['data']['password_'] == "") {
        $_SESSION['msg'] .= "Password required<br/>";
        $error = 1;
    }
    if ($_POST['data']['nick1'] == $_POST['data']['nick2']) {
        $_SESSION['msg'] .= "Nick must be different<br/>";
        $error = 1;
    }
    if (in_array(strtolower($_POST['data']['nick1']), $restrictednicks)) {
        $_SESSION['msg'] .= "Public Nick not allowed<br/>";
        $error = 1;
    }
    if (in_array(strtolower($_POST['data']['nick2']), $restrictednicks)) {
        $_SESSION['msg'] .= "Secret Nick not allowed<br/>";
        $error = 1;
    }
    if ($_POST['data']['branch'] == "others" && $_POST['data']['others'] != "") {
        DB::query("Insert into dchub_branch (branch) values ('" . $_POST['data']['others'] . "')");
        $id = DB::lastInsertId();
        $_POST['data']['branch'] = $id;
    } else if ($_POST['data']['branch'] == "others") {
        $_SESSION['msg'] .= "Branch cannot be empty<br/>";
        $error = 1;
    }
    if ($_POST['data']['password_'] != $_POST['data']['repassword_']) {
        $_SESSION['msg'] .= "Password mismatch<br/>";
        $error = 1;
    }
    $res = DB::findOneFromQuery("select count(email) as count from dchub_users where nick1 = '" . $_POST['data']['nick1'] . "' or nick2 = '" . $_POST['data']['nick1'] . "'");
    if ($res['count'] > 0) {
        $_SESSION['msg'] .= "Nick already registered!<br/>";
        $error = 1;
    }
    $res = DB::findOneFromQuery("select count(email) count from dchub_users where nick1 = '" . $_POST['data']['nick2'] . "' or nick2 = '" . $_POST['data']['nick2'] . "'");
    if ($_POST['data']['nick2'] != '' && $res['count'] > 0) {
        $_SESSION['msg'] .= "Second Nick is  already registered!<br/>";
        $error = 1;
    }
    $res = DB::findOneFromQuery("select count(email) as count from dchub_users where roll_course = '" . $_POST['data']['roll_course'] . "' and roll_number = '" . $_POST['data']['roll_number'] . "' and roll_year = '" . $_POST['data']['roll_year'] . "'");
    if ($res['count'] > 0) {
        $_SESSION['msg'] .= "Roll number is already registered. Contact Admins if you haven't registered.<br/>";
        $error = 1;
    }
    if ($error == 1) {
        $_SESSION['data'] = $_POST['data'];
        redirectTo(SITE_URL . "/register");
        exit;
    } else {
        $save = $_SESSION;
        session_destroy();
        session_set_cookie_params(0, substr(SITE_URL, strlen("http://" . $_SERVER['HTTP_HOST'])));
        session_regenerate_id(true);
        session_start();
        $_SESSION = $save;
        $_SESSION['user']['nick'] = $_POST['data']['nick1'];
        if ($_POST['data']['nick2'] != '') {
            $_SESSION['user']['nick2'] = $_POST['data']['nick2'];
        }
        $_SESSION['user']['email'] = $_POST['data']['email'];
        $res = DB::findAllFromQuery("select * from dchub_branch");
        $grps = array();
        foreach ($res as $row) {
            $grps[$row['id']] = $row['branch'];
        }
        if(in_array($_POST['data']['roll_year'], array('2010','2011','2012','2013'))){
            $_POST['data']['groups'] = 'Everybody,'.$grps[$_POST['data']['branch']] . "," . $grps[$_POST['data']['branch']] . '-' . $defaultGroup[$_POST['data']['roll_year']] . ',' . $defaultGroup[$_POST['data']['roll_year']] . ',H-' . $_POST['data']['hostel'];
        } else {
            $_POST['data']['groups'] = 'Everybody,'.$grps[$_POST['data']['branch']] . "," .',H-' . $_POST['data']['hostel'];
        }
        unset($_POST['data']['repassword_']);
        unset($_POST['data']['others']);
        $maxmsg = DB::findOneFromQuery("select max(id) as id from dchub_message");
        $maxnot = DB::findOneFromQuery("select max(id) as id from dchub_post");
        $_POST['data']['lastmsgid'] = $maxmsg['id'];
        $_POST['data']['lastnotificationid'] = $maxnot['id'];
        $res = DB::insert("dchub_users", $_POST['data']);
        $ver = array('nick' => $_POST['data']['nick1'], 'class' => '0', 'pwd_crypt' => 0, 'login_pwd' => $_POST['data']['password_']);
        $res1 = DB::insert("reglist", $ver);
        if ($_POST['data']['nick2'] != '') {
            $ver = array('nick' => $_POST['data']['nick2'], 'class' => '0', 'pwd_crypt' => 0, 'login_pwd' => $_POST['data']['password_']);
            $res1 = DB::insert("reglist", $ver);
        }
        if ($res) {
            $query = "select * from dchub_users where nick1 = '" . $_SESSION['user']['nick'] . "'";
            $res2 = DB::findOneFromQuery($query);
        }
        if ($res2) {
            $branch = DB::findOneFromQuery("select branch from dchub_branch where id = $res2[branch]");
            $_SESSION['loggedin'] = "true";
            $_SESSION['user']['id'] = $res2['id'];
            $_SESSION['user']['accesslevel'] = $res2['class'];
            $_SESSION['user']['name'] = $res2['fullname'];
            $_SESSION['user']['branch'] = $branch['branch'];
            $_SESSION['user']['hostel'] = $res2['hostel'];
            $_SESSION['user']['room'] = $res2['room'];
            $_SESSION['user']['phone'] = $res2['phone'];
            $_SESSION['user']['roll'] = $res2['roll_course'] . "/" . $res2['roll_number'] . "/" . $res2['roll_year'];
            $_SESSION['user']['ip'] = $res2['ipaddress'];
            $_SESSION['user']['groups'] = explode(',', $res2['groups']);
            $_SESSION['user']['lastmsgid'] = $maxmsg['id'];
            $_SESSION['user']['msgid'] = $maxmsg['id'];
            $_SESSION['user']['lastnotificationid'] = $maxnot['id'];
            $_SESSION['user']['notificationid'] = $maxnot['id'];
            $_SESSION['msg'] = 'Registration Successful';
            redirectAfter(SITE_URL);
        } else {
            $_SESSION['data'] = $_POST['data'];
            $_SESSION['msg'] .= "Sorry there was an error! Contact Admin";
            redirectTo(SITE_URL . '/register');
        }
    }
    
    // Logout
} else if (isset($_GET['logout'])) {
    session_destroy();
    redirectTo(SITE_URL);
    
    //Login 
} else if (isset($_POST['login'])) {
    $_POST['data'] = secure($_POST['data']);
    $query = "select * from dchub_users where (nick1 = '" . $_POST['data']['username'] . "' OR nick2 = '" . $_POST['data']['username'] . "') and password_ = '" . $_POST['data']['password'] . "'";
    $user = DB::findOneFromQuery($query);
    if ($user) {
        $_SESSION['user']['nick'] = $user['nick1'];
        if ($user['nick2'] != '') {
            $_SESSION['user']['nick2'] = $user['nick2'];
        }
        $branch = DB::findOneFromQuery("select branch from dchub_branch where id = $user[branch]");
        $_SESSION['user']['email'] = $user['email'];
        $_SESSION['loggedin'] = "true";
        $_SESSION['user']['id'] = $user['id'];
        $_SESSION['user']['accesslevel'] = $user['class'];
        $_SESSION['user']['name'] = $user['fullname'];
        $_SESSION['user']['branch'] = $branch['branch'];
        $_SESSION['user']['hostel'] = $user['hostel'];
        $_SESSION['user']['room'] = $user['room'];
        $_SESSION['user']['phone'] = $user['phone'];
        $_SESSION['user']['roll'] = $user['roll_course'] . "/" . $user['roll_number'] . "/" . $user['roll_year'];
        $_SESSION['user']['ip'] = $user['ipaddress'];
        $_SESSION['user']['groups'] = explode(',', $user['groups']);
        $_SESSION['user']['lastmsgid'] = $user['lastmsgid'];
        $_SESSION['user']['msgid'] = $user['lastmsgid'];
        $_SESSION['user']['lastnotificationid'] = $user['lastnotificationid'];
        $_SESSION['user']['notificationid'] = $user['lastnotificationid'];
        $_SESSION['msg'] = 'Successfully Loggedin';
        redirectTo(SITE_URL);
    } else {
        $_SESSION['loginerr'] = 'Incorrect Username or Password';
        redirectTo("http://" . $_SERVER['HTTP_HOST'] . $_SESSION['url']);
    }
    
    // Import from last year's nick
} else if (isset($_POST['ajaxFetch'])) {
    $res = DB::findOneFromQuery("select fullname, roll_course, roll_number, roll_year, email, phone from dchub_importedusers where nickname = '$_POST[nick]' and password_ = '$_POST[password]'");
    if ($res) {
        $str = "{'data' :[{'fullname' : '$res[fullname]','roll_course' : '$res[roll_course]','roll_number' : '$res[roll_number]','roll_year' : '$res[roll_year]','email' : '$res[email]','phone' : '$res[phone]'}]}";
        echo $str;
    } else {
        echo "Incorrect nick / password";
    }
} else if (isset($_SESSION['loggedin'])) { // Request available for only logged in users

    //share new content on latest content page
    if (isset($_POST['share'])) {
        $_POST['data'] = secure($_POST['data']);
        if (check(array($_POST['data']['title']))) {
            $_POST['data']['uid'] = $_SESSION['user']['id'];
            $mUri = new MagnetUri($_POST['data']['title']);
            if ($mUri->valid) {
                $_POST['data']['magnetlink'] = $_POST['data']['title'];
                $_POST['data']['title'] = $mUri->dn;
            }
            foreach ($categories as $value) {
                if (isset($_POST['data'][$value])) {
                    if ($_POST['data']['tag'] != "") {
                        $_POST['data']['tag'] .= ",$value";
                    } else {
                        $_POST['data']['tag'] .= "$value";
                    }
                    unset($_POST['data'][$value]);
                }
            }
            //$_SESSION['msg'] = print_r($_POST['data'], true);
            $_POST['data']['timestamp'] = time();
            DB::insert('dchub_content', $_POST['data']);
            redirectTo(SITE_URL);
        } else {
            $_SESSION['msg'] = "Some values missing<br/>";
            redirectTo(SITE_URL);
        }
        
        // Join or leave a group 
    } else if (isset($_POST['grpToggle'])) {
        $_POST = secure($_POST);
        if (($key = array_search($_POST['group'], $_SESSION['user']['groups'])) !== false) {
            unset($_SESSION['user']['groups'][$key]);
        } else {
            array_push($_SESSION['user']['groups'], $_POST['group']);
        }
        $query = "update dchub_users set groups='" . implode(',', $_SESSION['user']['groups']) . "' where id = " . $_SESSION['user']['id'];
        DB::query($query);
        redirectTo(SITE_URL . "/groups/$_POST[group]");
        
        // approve a post on groups
    } else if (isset($_POST['approve'])) {
        $_POST = secure($_POST);
        $query = "select moderators, name from dchub_groups where id=(select gid from dchub_post where id=$_POST[id])";
        $res = DB::findOneFromQuery($query);
        $mod = explode(',', $res['moderators']);
        if ($_SESSION['user']['accesslevel'] >= 4 || in_array($_SESSION['user']['nick'], $mod)) {
            DB::query("update dchub_post set approvedby = " . $_SESSION['user']['id'] . ", timestamp=" . time() . " where id = $_POST[id]");
            redirectTo("http://" . $_SERVER['HTTP_HOST'] . $_SESSION['url']);
        } else {
            $_SESSION['msg'] = "Access Denied: Not enough previleges.";
            redirectTo($_SERVER['HTTP_HOST'] . $_SESSION['url']);
        }
        
        // decline a post on groups
    } else if (isset($_POST['decline'])) {
        $_POST = secure($_POST);
        $query = "select moderators, name from dchub_groups where id=(select gid from dchub_post where id=$_POST[id])";
        $res = DB::findOneFromQuery($query);
        $mod = explode(',', $res['moderators']);
        if ($_SESSION['user']['accesslevel'] >= 4 || in_array($_SESSION['user']['nick'], $mod)) {
            DB::query("update dchub_post set approvedby = " . $_SESSION['user']['id'] . ", timestamp=" . time() . ", deleted=1 where id = $_POST[id]");
            redirectTo("http://" . $_SERVER['HTTP_HOST'] . $_SESSION['url']);
        } else {
            $_SESSION['msg'] = "Access Denied: Not enough previleges.";
            redirectTo($_SERVER['HTTP_HOST'] . $_SESSION['url']);
        }
        
        // user post something on group page
    } else if (isset($_POST['post'])) {
        if ($_SESSION['user']['accesslevel'] > 0) {
            $_POST['data'] = secure($_POST['data']);
            $_POST['data']['postby'] = $_SESSION['user']['nick'];
            DB::insert('dchub_post', $_POST['data']);
            $_SESSION['msg'] = "Post submited for approval";
        } else {
            $_SESSION['msg'] = "You need to be authenticated to use this feature.";
        }
        redirectTo("http://" . $_SERVER['HTTP_HOST'] . $_SESSION['url']);
        
        // recommend a content on latest content page
    } else if (isset($_POST['recommend'])) {
        $_POST = secure($_POST);
        $res = DB::query("insert into dchub_recommend (cid, uid, type) values($_POST[cid], " . $_SESSION['user']['id'] . ", 'lc')");
        echo $res ? "1" : "0";
        
        // discourage a content on latest content page
    } else if (isset($_POST['discourage'])) {
        $_POST = secure($_POST);
        $res = DB::query("delete from dchub_recommend where type = 'lc' and cid = $_POST[cid] and uid = " . $_SESSION['user']['id']);
        echo $res ? "1" : "0";
        
        // volunteer on request page
    } else if (isset($_POST['volunteer'])) {
        $_POST = secure($_POST);
        $vol = DB::findOneFromQuery("select volunteer from dchub_request where id = $_POST[cid]");
        $vol = explode(',', $vol['volunteer']);
        array_push($vol, $_SESSION['user']['nick']);
        $vol = implode(',', $vol);
        $res = DB::query("update dchub_request set volunteer = '$vol' where id=$_POST[cid]");
        echo $res ? "1" : "0";
        
        // chicken out on request page
    } else if (isset($_POST['chickenout'])) {
        $_POST = secure($_POST);
        $vol = DB::findOneFromQuery("select volunteer from dchub_request where id = $_POST[cid]");
        $vol = explode(',', $vol['volunteer']);
        $key = array_search($_SESSION['user']['nick'], $vol);
        unset($vol[$key]);
        $vol = implode(',', $vol);
        $res = DB::query("update dchub_request set volunteer = '$vol' where id=$_POST[cid]");
        echo $res ? "1" : "0";
        
        // request something on request page
    } else if (isset($_POST['request']) && $_POST['data']['request_file'] != '') {
        if ($_SESSION['user']['accesslevel'] > 0) {
            $_POST['data'] = secure($_POST['data']);
            $_POST['data']['uid'] = $_SESSION['user']['id'];
            $res = DB::insert("dchub_request", $_POST['data']);
        } else {
            $_SESSION['msg'] = "You need to be authenticated to use this feature.";
        }
        redirectTo(SITE_URL . "/request");
        
        // approve / authenticate a friend
    } else if (isset($_POST['approvefriend']) && $_SESSION['user']['accesslevl'] > 0) {
        $_POST = secure($_POST);
        $query = "update dchub_users set authenticated=1, class=2 where nick1 = '$_POST[nick]' and (friend ='" . $_SESSION['user']['nick'] . "'" . ((isset($_SESSION['user']['nick2'])) ? (" or friend='" . $_SESSION['user']['nick2'] . "')") : (")"));
        DB::query($query);
        $friend = DB::findOneFromQuery("select nick1, nick2 from dchub_users where nick = '$_POST[nick]'");
        DB::update('reglist', array('class' => 1, "nick='" . $friend['nick1'] . "'"));
        if ($friend['nick2'] != "") {
            DB::update('reglist', array('class' => 1, "nick='" . $friend['nick2'] . "'"));
        }
        redirectTo(SITE_URL . "/friends");
        
        // deny a friend
    } else if (isset($_POST['denyfriend']) && $_SESSION['user']['accesslevl'] > 0) {
        $_POST = secure($_POST);
        $query = "update dchub_users set friend='' where nick1 = '$_POST[nick]' and (friend ='" . $_SESSION['user']['nick'] . "'" . ((isset($_SESSION['user']['nick2'])) ? (" or friend='" . $_SESSION['user']['nick2'] . "')") : (")"));
        DB::query($query);
        redirectTo(SITE_URL . "/friends");
        
        // offline msg to someone
    } else if (isset($_POST['messagepost'])) {
        if ($_SESSION['user']['accesslevel'] > 0) {
            $_POST['data'] = secure($_POST['data']);
            $_POST['data']['fromid'] = $_SESSION['user']['id'];
            $user = DB::findOneFromQuery("select id from dchub_users where nick1 = '" . $_POST['data']['to'] . "' or nick2 ='" . $_POST['data']['to'] . "'");
            unset($_POST['data']['to']);
            $_POST['data']['toid'] = $user['id'];
            DB::insert('dchub_message', $_POST['data']);
            $_SESSION['msg'] = "Message sent successfully";
        } else {
            $_SESSION['msg'] = "You need to be authenticated to use this feature.";
        }
        redirectTo("http://" . $_SERVER['HTTP_HOST'] . $_SESSION['url']);
        
        // authtication via cyberoam password
    } else if (isset($_POST['cyberauth'])) {
        $url = 'https://172.16.1.1:8090/login.xml';
        $roll = implode('', explode('/', $_SESSION['user']['roll']));
        $data = array('mode' => '191', 'username' => $roll, 'password' => $_POST['cyberpass'], 'a' => (string) (time() * 1000));
        $options = array(
            'http' => array(
                'header' => "Content-type: application/x-www-form-urlencoded\r\n",
                'method' => 'POST',
                'content' => http_build_query($data),
            ),
        );
        $context = stream_context_create($options);
        $result = file_get_contents($url, false, $context);
        $xml = simplexml_load_string($result) or $_SESSION['msg'] = 'Error! Contact Admin';
        $opt = array("You have successfully logged in", "You are not allowed to login at this time", "You have reached Maximum Login Limit.");
        if (isset($xml->message) && in_array($xml->message, $opt)) {
            $_SESSION['msg'] = 'Authentication Successfull' . $xml->message;
            $_SESSION['user']['accesslevel'] = 1;
            DB::update('dchub_users', array('class' => 1, 'authenticated' => 1, 'friend' => 'HubBot'), "id = " . $_SESSION['user']['id']);
            DB::update('reglist', array('class' => 1, "nick='" . $_SESSION['user']['nick'] . "'"));
            if (isset($_SESSION['user']['nick2'])) {
                DB::update('reglist', array('class' => 1, "nick='" . $_SESSION['user']['nick2'] . "'"));
            }
            $url = 'https://172.16.1.1:8090/login.xml';
            $roll = implode('', explode('/', $_SESSION['user']['roll']));
            $data = array('mode' => '193', 'username' => $roll, 'a' => (string) (time() * 1000));
            $options = array(
                'http' => array(
                    'header' => "Content-type: application/x-www-form-urlencoded\r\n",
                    'method' => 'POST',
                    'content' => http_build_query($data),
                ),
            );
            $context = stream_context_create($options);
            $result = file_get_contents($url, false, $context);
        } else {
            $_SESSION['msg'] = 'Authentication Failed! If you are sure about your password contact admin.';
        }
        redirectTo("http://" . $_SERVER['HTTP_HOST'] . $_SESSION['url']);
        
        // ask friend to authenticate
    } else if (isset($_POST['friendauth'])) {
        $_POST['friend'] = addslashes($_POST['friend']);
        DB::update('dchub_users', array('friend' => $_POST['friend']), "id = " . $_SESSION['user']['id']);
        $_SESSION['msg'] = 'Authentication request sent. Your class will be updated as soon as your friend authenticates you.';
        redirectTo("http://" . $_SERVER['HTTP_HOST'] . $_SESSION['url']);
        
        // password change
    } else if (isset($_POST['changepasswd'])) {
        $_POST['data'] = secure($_POST['data']);
        $res = DB::findOneFromQuery("select * from dchub_users where id =" . $_SESSION['user']['id'] . " and password_ = '" . $_POST['data']['oldpassword'] . "'");
        if ($res) {
            if ($_POST['data']['newpassword'] == $_POST['data']['repassword']) {
                $update['password_'] = $_POST['data']['newpassword'];
                DB::update('dchub_users', $update, "id = " . $_SESSION['user']['id']);
                $_SESSION['msg'] = "Password Changed";
            } else {
                $_SESSION['msg'] = "Password don't match";
            }
        } else {
            $_SESSION['msg'] = "Incorrect Password";
        }
        redirectTo(SITE_URL . "/account");
        
        // update details
    } else if (isset($_POST['updatedetails'])) {
        $_POST['data'] = secure($_POST['data']);
        $_SESSION['user']['phone'] = $_POST['data']['phone'];
        $_SESSION['user']['email'] = $_POST['data']['email'];
        DB::update('dchub_users', $_POST['data'], "id = " . $_SESSION['user']['id']);
        $_SESSION['msg'] = "Account Updated";
        redirectTo(SITE_URL . "/account");
        
        // admin updates an account
    } else if (isset($_POST['adminupdate']) && $_SESSION['user']['accesslevel'] >= 9) {
        $_POST['data'] = secure($_POST['data']);
        DB::update('dchub_users', $_POST['data'], 'id = ' . $_POST['data']['id']);
        $_SESSION['msg'] = "Account Updated";
        redirectTo("http://" . $_SERVER['HTTP_HOST'] . $_SESSION['url']);
    } else if (isset($_POST['addnick']) && !isset($_SESSION['user']['nick2'])) {
        if (in_array(strtolower($_POST['data']['nick2']), $restrictednicks)) {
            $_SESSION['msg'] = 'Nick not allowed';
        } else {
            $_POST['data'] = secure($_POST['data']);
            DB::update('dchub_users', $_POST['data'], 'id = ' . $_SESSION['user']['id']);
            $_SESSION['user']['nick2'] = $_POST['data']['nick2'];
            $_SESSION['msg'] = 'Nick Added';
        }
        redirectTo(SITE_URL . "/account");
        
        // recommend a content on recommend page
    } else if (isset($_POST['rec_recommend'])) {
        $_POST = secure($_POST);
        $res = DB::query("insert into dchub_recommend (cid, uid, type) values($_POST[cid], " . $_SESSION['user']['id'] . ", 'rc')");
        echo $res ? "1" : "0";
        
        // discourage a content on content page
    } else if (isset($_POST['rec_discourage'])) {
        $_POST = secure($_POST);
        $res = DB::query("delete from dchub_recommend where type='rc' and cid = $_POST[cid] and uid = " . $_SESSION['user']['id']);
        echo $res ? "1" : "0";
        
        // message of one or many
    } else if (isset($_POST['composemsg']) && $_SESSION['user']['accesslevel'] > 0) {
        $_POST['data'] = secure($_POST['data']);
        if ($_POST['data']['select'] == 'one') {
            $tonick = $_POST['data']['to'];
            $to = DB::findOneFromQuery("select id from dchub_users where nick1 = '$tonick' or nick2 = '$tonick'");
            $data['toid'] = $to['id'];
            $data['msg'] = $_POST['data']['msg'];
            $data['fromid'] = $_SESSION['user']['id'];
            DB::insert('dchub_message', $data);
            $_SESSION['msg'] = "Message queued for delivery to $tonick";
        } else if($_POST['data']['select'] == 'everybody'){
            $data['post'] = $_POST['data']['msg'];
            $data['postby'] = $_SESSION['user']['nick'];
            $gid = DB::findOneFromQuery("select * from dchub_groups where name='everybody'");
            $data['gid'] =$gid['id'];
            DB::insert('dchub_post', $data);
            $_SESSION['msg'] = "Message will be broadcasted after approval from admin.";
        }else {
            $res = DB::findAllFromQuery("select * from dchub_groups");
            $groups = array();
            foreach ($res as $row) {
                $groups[$row['name']] = $row['id'];
            }
            $data['post'] = $_POST['data']['msg'];
            $data['postby'] = $_SESSION['user']['nick'];
            $togid = array();
            $grname = array();
            if (isset($_POST['branch']) && isset($_POST['batch'])) {
                foreach ($_POST['branch'] as $brval) {
                    foreach ($_POST['batch'] as $btval) {
                        array_push($togid, $groups[$brval . '-' . $btval]);
                        array_push($grname, $brval . '-' . $btval);
                    }
                }
            } else if (isset($_POST['branch'])) {
                foreach ($_POST['branch'] as $brval) {
                    array_push($togid, $groups[$brval]);
                    array_push($grname, $brval);
                }
            } else if (isset($_POST['batch'])) {
                foreach ($_POST['batch'] as $btval) {
                    array_push($togid, $groups[$btval]);
                    array_push($grname, $btval);
                }
            }
            if (isset($_POST['hostel'])) {
                foreach ($_POST['hostel'] as $htval) {
                    array_push($togid, $groups[$htval]);
                    array_push($grname, $htval);
                }
            }
            foreach ($togid as $val) {
                $data['gid'] = $val;
                DB::insert('dchub_post', $data);
            }
            $_SESSION['msg'] = "Message will be delivered to people in " . implode(', ', $grname) . " after approval from admin.";
        }
        redirectTo(SITE_URL . "/messages");
        
        // post a content on recommendation page
    } else if (isset($_POST['recommendcontent'])) {
        $_POST['data'] = secure($_POST['data']);
        if (check(array($_POST['data']['title']))) {
            $_POST['data']['uid'] = $_SESSION['user']['id'];
            $_POST['data']['magnetlink'] = $_POST['data']['title'];
            foreach ($categories as $value) {
                if (isset($_POST['data'][$value])) {
                    if ($_POST['data']['tag'] != "") {
                        $_POST['data']['tag'] .= ",$value";
                    } else {
                        $_POST['data']['tag'] .= "$value";
                    }
                    unset($_POST['data'][$value]);
                }
            }
            //$_SESSION['msg'] = print_r($_POST['data'], true);
            $_POST['data']['timestamp'] = time();
            DB::insert('dchub_rc', $_POST['data']);
            redirectTo(SITE_URL."/recommend");
        } else {
            $_SESSION['msg'] = "Some values missing<br/>";
            redirectTo(SITE_URL."/recommend");
        }
        
        // update recommended content
    } else if (isset ($_POST['updaterec']) && $_SESSION['user']['accesslevel'] >= 2) {
        $_POST['data'] = secure($_POST['data']);
        $cid = $_POST['data']['cid'];
        unset($_POST['data']['cid']);
        $rec = DB::update('dchub_rc', $_POST['data'], "cid = $cid");
        echo ($rec)?('1'):('0');
        
        // delete recommended content
    } else if (isset ($_POST['deleterec']) && $_SESSION['user']['accesslevel'] >= 2) {
        $cid = addslashes($_POST['cid']);
        $rec = DB::delete('dchub_rc', "cid = $cid");
        echo ($rec)?('1'):('0');
        
        // update request
    } else if (isset ($_POST['updatereq']) && $_SESSION['user']['accesslevel'] >= 2) {
        $_POST['data'] = secure($_POST['data']);
        $cid = $_POST['data']['id'];
        unset($_POST['data']['cid']);
        $rec = DB::update('dchub_request', $_POST['data'], "id = $cid");
        echo ($rec)?('1'):('0');
        
        // delete request
    } else if (isset ($_POST['deletereq']) && $_SESSION['user']['accesslevel'] >= 2) {
        $cid = addslashes($_POST['id']);
        $rec = DB::delete('dchub_request', "id = $cid");
        echo ($rec)?('1'):('0');
        
        // update latest content
    } else if (isset ($_POST['updatelat']) && $_SESSION['user']['accesslevel'] >= 3) {
        $_POST['data'] = secure($_POST['data']);
        $cid = $_POST['data']['cid'];
        unset($_POST['data']['cid']);
        $rec = DB::update('dchub_content', $_POST['data'], "cid = $cid");
        echo ($rec)?('1'):('0');
        
        // delete latest content
    } else if (isset ($_POST['deletelat']) && $_SESSION['user']['accesslevel'] >= 3) {
        $cid = addslashes($_POST['cid']);
        $rec = DB::delete('dchub_content', "cid = $cid");
        echo ($rec)?('1'):('0');
        
        // set latest content to featured
    } else if (isset ($_POST['featurelat']) && $_SESSION['user']['accesslevel'] >= 3) {
        $cid = addslashes($_POST['cid']);
        $p = DB::findOneFromQuery("select priority from dchub_content where cid = '$cid'");
        if($p['priority'] == '0'){
            $maxp = DB::findOneFromQuery("select max(priority) as max from dchub_content");
            $priority = $maxp['max'] + 1;
            $rec = DB::update('dchub_content', array('priority' => $priority) ,"cid = $cid");
            $ret = '1';
        } else {
            $rec = DB::update('dchub_content', array('priority' => 0) ,"cid = $cid");
            $ret = '2';
        }
        echo ($rec)?($ret):('0');
        
        // Update motd
    } else if(isset ($_POST['motdupdate']) && $_SESSION['user']['accesslevel'] >= 9){
        $_POST['motdcontent'] = addslashes($_POST['motdcontent']);
        file_put_contents($motdfile, $_POST['motdcontent']);
        redirectTo(SITE_URL."/motd");
    }
}
?>