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
    $res = DB::findOneFromQuery("select count(email) as count from dchub_users where deleted = 0 and nick1 = '" . $_POST['data']['nick1'] . "' or nick2 = '" . $_POST['data']['nick1'] . "'");
    if ($res['count'] > 0) {
        $_SESSION['msg'] .= "Nick already registered!<br/>";
        $error = 1;
    }
    $res = DB::findOneFromQuery("select count(email) count from dchub_users where deleted = 0 and nick1 = '" . $_POST['data']['nick2'] . "' or nick2 = '" . $_POST['data']['nick2'] . "'");
    if ($_POST['data']['nick2'] != '' && $res['count'] > 0) {
        $_SESSION['msg'] .= "Second Nick is  already registered!<br/>";
        $error = 1;
    }
    $res = DB::findOneFromQuery("select count(email) as count from dchub_users where roll_course = '" . $_POST['data']['roll_course'] . "' and roll_number = '" . $_POST['data']['roll_number'] . "' and roll_year = '" . $_POST['data']['roll_year'] . "' and branch = '" . $_POST['data']['branch'] . "'");
    if ($res['count'] > 0) {
        $_SESSION['msg'] .= "Roll number in your branch already registered. contact the Admins if you haven't registered.<br/>";
        $error = 1;
    }
    if (($_POST['roll_year'] < 1000 && $_POST['roll_year'] > 2000) || ($_POST['roll_year'] < 10000 && $_POST['roll_year'] > 20000)) {
        $_SESSION['msg'] .= "Invalid Roll no.<br/>";
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
        $res = DB::findAllFromQuery("Select * from dchub_groups");
        foreach ($res as $row) {
            $identifiertoid[$row['identifier']] = $row['id'];
        }
        if (in_array($_POST['data']['roll_year'], array_keys($defaultGroup))) {
            $pieces = array(
                $identifiertoid['Everybody'],
                $identifiertoid[$defaultGroup[$_POST['data']['roll_year']]],
                $identifiertoid[$_POST['data']['branch']],
                $identifiertoid[$_POST['data']['branch'] . "-" . $defaultGroup[$_POST['data']['roll_year']]],
                $identifiertoid["H-" . $_POST['data']['hostel']]
            );
        } else {
            $pieces = array(
                $identifiertoid['Everybody'],
                $identifiertoid[$_POST['data']['branch']],
                $identifiertoid["H-" . $_POST['data']['hostel']]
            );
        }
        $_POST['data']['groups'] = implode(',', $pieces);
        unset($_POST['data']['repassword_']);
        unset($_POST['data']['others']);
        $maxmsg = DB::findOneFromQuery("select max(id) as id from dchub_message");
        $maxnot = DB::findOneFromQuery("select max(id) as id from dchub_post");
        $_POST['data']['lastmsgid'] = $maxmsg['id'];
        $_POST['data']['lastnotificationid'] = $maxnot['id'];
        $res = DB::insert("dchub_users", $_POST['data']);
        $ver = array('nick' => $_POST['data']['nick1'], 'reg_op' => 'HubBot', 'pwd_change' => 0, 'class' => '0', 'pwd_crypt' => 0, 'login_pwd' => $_POST['data']['password_']);
        $res1 = DB::insert("reglist", $ver);
        if ($_POST['data']['nick2'] != '') {
            $ver = array('nick' => $_POST['data']['nick2'], 'reg_op' => 'HubBot', 'pwd_change' => 0, 'class' => '0', 'pwd_crypt' => 0, 'login_pwd' => $_POST['data']['password_']);
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
            redirectAfter(SITE_URL . "/welcome");
        } else {
            $_SESSION['data'] = $_POST['data'];
            $_SESSION['msg'] .= "Sorry there was an error! contact the Admins";
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
        $_SESSION['msg'] = 'Yet to implement';
        redirectTo(SITE_URL . "/groups/$_POST[group]");
//        $_POST = secure($_POST);
//        if (($key = array_search($_POST['group'], $_SESSION['user']['groups'])) !== false) {
//            unset($_SESSION['user']['groups'][$key]);
//        } else {
//            array_push($_SESSION['user']['groups'], $_POST['group']);
//        }
//        $query = "update dchub_users set groups='" . implode(',', $_SESSION['user']['groups']) . "' where id = " . $_SESSION['user']['id'];
//        DB::query($query);
//        redirectTo(SITE_URL . "/groups/$_POST[group]");
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
        $res = DB::findOneFromQuery("select count(id) as voted from dchub_recommend where cid = $_POST[cid] and uid=" . $_SESSION['user']['id'] . " and type='lc'");
        if ($res['voted'] == 0) {
            $res = DB::query("insert into dchub_recommend (cid, uid, type) values($_POST[cid], " . $_SESSION['user']['id'] . ", 'lc')");
            echo $res ? "1" : "0";
        } else {
            echo "Discourage";
        }

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
        if (!in_array($_SESSION['user']['nick'], $vol)) {
            array_push($vol, $_SESSION['user']['nick']);
            $vol = implode(',', $vol);
            $res = DB::query("update dchub_request set volunteer = '$vol' where id=$_POST[cid]");
            echo $res ? "1" : "0";
        } else {
            echo "Chicken Out";
        }

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
    } else if (isset($_POST['approvefriend']) && $_SESSION['user']['accesslevel'] > 0) {
        $_POST = secure($_POST);
        $query = "update dchub_users set class=1 where nick1 = '$_POST[nick]' and (friend ='" . $_SESSION['user']['nick'] . "'" . ((isset($_SESSION['user']['nick2'])) ? (" or friend='" . $_SESSION['user']['nick2'] . "')") : (")"));
        DB::query($query);
        $friend = DB::findOneFromQuery("select nick1, nick2 from dchub_users where nick1 = '$_POST[nick]'");
        DB::update('reglist', array('class' => 1), "nick='" . $friend['nick1'] . "'");
        if ($friend['nick2'] != "") {
            DB::update('reglist', array('class' => 1), "nick='" . $friend['nick2'] . "'");
        }
        redirectTo(SITE_URL . "/friends");

        // deny a friend
    } else if (isset($_POST['denyfriend']) && $_SESSION['user']['accesslevel'] > 0) {
        $_POST = secure($_POST);
        $query = "update dchub_users set friend='' where nick1 = '$_POST[nick]' and (friend ='" . $_SESSION['user']['nick'] . "'" . ((isset($_SESSION['user']['nick2'])) ? (" or friend='" . $_SESSION['user']['nick2'] . "')") : (")"));
        DB::query($query);
        redirectTo(SITE_URL . "/friends");

        // offline msg to someone
    } else if (isset($_POST['messagepost'])) {
        if ($_SESSION['user']['accesslevel'] > 0 || in_array(strtolower($_POST['data']['to']), $admins)) {
            $_POST['data'] = secure($_POST['data']);
            $_POST['data']['fromid'] = $_SESSION['user']['id'];
            $user = DB::findOneFromQuery("select id from dchub_users where nick1 = '" . $_POST['data']['to'] . "' or nick2 ='" . $_POST['data']['to'] . "'");
            unset($_POST['data']['to']);
            $_POST['data']['toid'] = $user['id'];
            DB::insert('dchub_message', $_POST['data']);
            $_SESSION['msg'] = "Message sent successfully";
        } else {
            $_SESSION['msg'] = "You can send message to admins only.<br/>You need to be authenticated to message others.";
        }
        redirectTo("http://" . $_SERVER['HTTP_HOST'] . $_SESSION['url']);

        // authtication via cyberoam password
    } else if (isset($_POST['cyberauth'])) {
        $roll = explode('/', $_SESSION['user']['roll']);
        if (strpos($_POST['cyberid'], substr($roll[1], -3)) && strpos($_POST['cyberid'], $roll[2])) {
            $url = 'https://172.16.1.1:8090/login.xml';
            $data = array('mode' => '191', 'username' => $_POST['cyberid'], 'password' => $_POST['cyberpass'], 'a' => (string) (time() * 1000));
            $options = array(
                'http' => array(
                    'header' => "Content-type: application/x-www-form-urlencoded\r\n",
                    'method' => 'POST',
                    'content' => http_build_query($data),
                ),
            );
            $context = stream_context_create($options);
            $result = file_get_contents($url, false, $context);
            $xml = simplexml_load_string($result) or $_SESSION['msg'] = 'Error! contact the Admins';
            $opt = array("You have successfully logged in", "You are not allowed to login at this time", "Your data transfer has been exceeded, Please contact the administrator", "You have reached Maximum Login Limit.");
            if (isset($xml->message) && in_array($xml->message, $opt)) {
                $_SESSION['msg'] = 'Authentication Successful!';
                $_SESSION['user']['accesslevel'] = 1;
                DB::update('dchub_users', array('class' => 1, 'friend' => "HubBot:" . addslashes($_POST['cyberid'])), "id = " . $_SESSION['user']['id']);
                DB::update('reglist', array('class' => 1), "nick='" . $_SESSION['user']['nick'] . "'");
                if (isset($_SESSION['user']['nick2'])) {
                    DB::update('reglist', array('class' => 1), "nick='" . $_SESSION['user']['nick2'] . "'");
                }
            } else {
                $_SESSION['msg'] = 'Authentication Failed! If you are sure about your password contact the Admins.';
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
            $_SESSION['msg'] = "Your Cyberoam id is not similar to your roll no. <br/> To authenticate by this method your roll no and cyberoam id should be similar.";
        }
        redirectTo("http://" . $_SERVER['HTTP_HOST'] . $_SESSION['url']);

        // ask friend to authenticate
    } else if (isset($_POST['friendauth'])) {
        $_POST['friend'] = addslashes($_POST['friend']);
        $res = DB::findOneFromQuery("Select * from dchub_users where nick1 = '$_POST[friend]' or nick2 = '$_POST[friend]'");
        if ($res) {
            DB::update('dchub_users', array('friend' => $_POST['friend']), "id = " . $_SESSION['user']['id']);
            $_SESSION['msg'] = 'Authentication request sent. Your class will be updated as soon as your friend authenticates you.';
        } else {
            $_SESSION['msg'] = "Friend not found. Are you sure that the nick is correct?";
        }
        redirectTo("http://" . $_SERVER['HTTP_HOST'] . $_SESSION['url']);

        // password change
    } else if (isset($_POST['changepasswd'])) {
        $_POST['data'] = secure($_POST['data']);
        $res = DB::findOneFromQuery("select * from dchub_users where id =" . $_SESSION['user']['id'] . " and password_ = '" . $_POST['data']['oldpassword'] . "'");
        if ($res) {
            if ($_POST['data']['newpassword'] == $_POST['data']['repassword']) {
                $update['password_'] = $_POST['data']['newpassword'];
                DB::update('dchub_users', $update, "id = " . $_SESSION['user']['id']);
                DB::update('reglist', array('login_pwd' => $update['password_']), "nick = '" . $_SESSION['user']['nick'] . "'" . ((isset($_SESSION['user']['nick2'])) ? (" or nick = '" . $_SESSION['user']['nick2'] . "'") : ("")));
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
        if ($_POST['data']['deleted'] == 1) {
            $row = DB::findOneFromQuery("select nick1, nick2 from dchub_users where id = " . $_POST['data']['id']);
            DB::query("delete from reglist where nick = '" . $row['nick1'] . "'");
            if ($row['nick2'] != '') {
                DB::query("delete from reglist where nick = '" . $row['nick2'] . "'");
            }
        }
        if ($_POST['data']['nick1'] != "") {
            $row = DB::findOneFromQuery("select nick1, nick2 from dchub_users where id = " . $_POST['data']['id']);
            DB::query("update reglist set nick='" . $_POST['data']['nick1'] . "' where nick = '" . $row['nick1'] . "'");
            if ($row['nick2'] != '') {
                DB::query("update reglist set nick='" . $_POST['data']['nick2'] . "' where nick = '" . $row['nick2'] . "'");
            }
        }
        if ($_POST['data']['password_'] != "") {
            $row = DB::findOneFromQuery("select nick1, nick2 from dchub_users where id = " . $_POST['data']['id']);
            DB::query("update reglist set login_pwd='" . $_POST['data']['password_'] . "' where nick = '" . $row['nick1'] . "'");
            if ($row['nick2'] != '') {
                DB::query("update reglist set login_pwd='" . $_POST['data']['password_'] . "' where nick = '" . $row['nick2'] . "'");
            }
        }
        if ($_POST['data']['class'] != 10) {
            $nicks = DB::findOneFromQuery("select nick1, nick2 from dchub_users where id = " . $_POST['data']['id']);
            DB::update('reglist', array('class' => $classmap[$_POST['data']['class']], 'login_pwd' => $_POST['data']['password_'], 'nick' => $_POST['data']['nick1']), "nick = '$nicks[nick1]'");
            DB::update('reglist', array('class' => $classmap[$_POST['data']['class']], 'login_pwd' => $_POST['data']['password_'], 'nick' => $_POST['data']['nick2']), "nick = '$nicks[nick2]'");
        } else {
            $nicks = DB::findOneFromQuery("select nick1, nick2 from dchub_users where id = " . $_POST['data']['id']);
            DB::update('reglist', array('class' => $classmap[$_POST['data']['class']], 'login_pwd' => $_POST['data']['password_'], 'nick' => $_POST['data']['nick1']), "nick = '$nicks[nick1]'");
            DB::update('reglist', array('class' => $classmap[9], 'login_pwd' => $_POST['data']['password_'], 'nick' => $_POST['data']['nick2']), "nick = '$nicks[nick2]'");
        }
        DB::update('dchub_users', $_POST['data'], 'id = ' . $_POST['data']['id']);
        $_SESSION['msg'] = "Account Updated";
        redirectTo("http://" . $_SERVER['HTTP_HOST'] . $_SESSION['url']);

        // add second nick
    } else if (isset($_POST['addnick']) && !isset($_SESSION['user']['nick2'])) {
        if (in_array(strtolower($_POST['data']['nick2']), $restrictednicks)) {
            $_SESSION['msg'] = 'Nick not allowed';
        } else {
            $_POST['data'] = secure($_POST['data']);
            DB::update('dchub_users', $_POST['data'], 'id = ' . $_SESSION['user']['id']);
            $passwd = DB::findOneFromQuery("select password_, class from dchub_users where id = " . $_SESSION['user']['id']);
            $ver = array('nick' => $_POST['data']['nick2'], 'reg_op' => 'HubBot', 'pwd_change' => 0, 'class' => $classmap[$passwd['class']], 'pwd_crypt' => 0, 'login_pwd' => $passwd['password_']);
            $res1 = DB::insert("reglist", $ver);
            $_SESSION['user']['nick2'] = $_POST['data']['nick2'];
            $_SESSION['msg'] = 'Nick Added';
        }
        redirectTo(SITE_URL . "/account");

        // recommend a content on recommend page
    } else if (isset($_POST['rec_recommend'])) {
        $_POST = secure($_POST);
        $res = DB::findOneFromQuery("select count(id) as voted from dchub_recommend where cid = $_POST[cid] and uid=" . $_SESSION['user']['id'] . " and type='lc'");
        if ($res['voted'] == 0) {
            $res = DB::query("insert into dchub_recommend (cid, uid, type) values($_POST[cid], " . $_SESSION['user']['id'] . ", 'rc')");
            echo $res ? "1" : "0";
        } else {
            echo "Discourage";
        }


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
        } else if ($_POST['data']['select'] == 'everybody') {
            $data['post'] = $_POST['data']['msg'];
            $data['postby'] = $_SESSION['user']['nick'];
            $gid = DB::findOneFromQuery("select * from dchub_groups where name='everybody'");
            $data['gid'] = $gid['id'];
            DB::insert('dchub_post', $data);
            $_SESSION['msg'] = "Message will be broadcasted after approval from Admin.";
        } else {
            $res = DB::findAllFromQuery("select * from dchub_groups");
            foreach ($res as $row) {
                $identifiertoid[$row['identifier']] = $row['id'];
            }
            $res = DB::findAllFromQuery("select * from dchub_branch");
            foreach ($res as $row) {
                $branchtoid[$row['branch']] = $row['id'];
            }
            $data['post'] = $_POST['data']['msg'];
            $data['postby'] = $_SESSION['user']['nick'];
            $togid = array();
            $grname = array();
            if (isset($_POST['branch']) && isset($_POST['batch'])) {
                foreach ($_POST['branch'] as $brval) {
                    foreach ($_POST['batch'] as $btval) {
                        array_push($togid, $identifiertoid[$branchtoid[$brval] . '-' . $btval]);
                        array_push($grname, $brval . '-' . $btval);
                    }
                }
            } else if (isset($_POST['branch'])) {
                foreach ($_POST['branch'] as $brval) {
                    array_push($togid, $identifiertoid[$branchtoid[$brval]]);
                    array_push($grname, $brval);
                }
            } else if (isset($_POST['batch'])) {
                foreach ($_POST['batch'] as $btval) {
                    array_push($togid, $identifiertoid[$btval]);
                    array_push($grname, $btval);
                }
            }
            if (isset($_POST['hostel'])) {
                foreach ($_POST['hostel'] as $htval) {
                    array_push($togid, $identifiertoid[$htval]);
                    array_push($grname, $htval);
                }
            }
            foreach ($togid as $val) {
                $data['gid'] = $val;
                DB::insert('dchub_post', $data);
            }
            $_SESSION['msg'] = "Message will be delivered to people in " . implode(', ', $grname) . " after approval from Admin.";
        }
        redirectTo(SITE_URL . "/messages");

        // post a content on recommendation page
    } else if (isset($_POST['recommendcontent']) && $_SESSION['user']['accesslevel'] > 0) {
        $_POST['data'] = secure($_POST['data']);
        if (check(array($_POST['data']['title']))) {
            $_POST['data']['uid'] = $_SESSION['user']['id'];
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
            redirectTo(SITE_URL . "/recommend");
        } else {
            $_SESSION['msg'] = "Some values missing<br/>";
            redirectTo(SITE_URL . "/recommend");
        }

        // update recommended content
    } else if (isset($_POST['updaterec']) && $_SESSION['user']['accesslevel'] >= 2) {
        $_POST['data'] = secure($_POST['data']);
        $cid = $_POST['data']['cid'];
        unset($_POST['data']['cid']);
        $rec = DB::update('dchub_rc', $_POST['data'], "cid = $cid");
        echo ($rec) ? ('1') : ('0');

        // delete recommended content
    } else if (isset($_POST['deleterec']) && $_SESSION['user']['accesslevel'] >= 2) {
        $cid = addslashes($_POST['cid']);
        $rec = DB::delete('dchub_rc', "cid = $cid");
        echo ($rec) ? ('1') : ('0');

        // update request
    } else if (isset($_POST['updatereq']) && $_SESSION['user']['accesslevel'] >= 2) {
        $_POST['data'] = secure($_POST['data']);
        $cid = $_POST['data']['id'];
        unset($_POST['data']['cid']);
        $rec = DB::update('dchub_request', $_POST['data'], "id = $cid");
        echo ($rec) ? ('1') : ('0');

        // delete request
    } else if (isset($_POST['deletereq']) && $_SESSION['user']['accesslevel'] >= 2) {
        $cid = addslashes($_POST['id']);
        $rec = DB::delete('dchub_request', "id = $cid");
        echo ($rec) ? ('1') : ('0');

        // update latest content
    } else if (isset($_POST['updatelat']) && $_SESSION['user']['accesslevel'] >= 3) {
        $_POST['data'] = secure($_POST['data']);
        $cid = $_POST['data']['cid'];
        unset($_POST['data']['cid']);
        $rec = DB::update('dchub_content', $_POST['data'], "cid = $cid");
        echo ($rec) ? ('1') : ('0');

        // delete latest content
    } else if (isset($_POST['deletelat']) && $_SESSION['user']['accesslevel'] >= 3) {
        $cid = addslashes($_POST['cid']);
        $rec = DB::delete('dchub_content', "cid = $cid");
        echo ($rec) ? ('1') : ('0');

        // set latest content to featured
    } else if (isset($_POST['featurelat']) && $_SESSION['user']['accesslevel'] >= 3) {
        $cid = addslashes($_POST['cid']);
        $p = DB::findOneFromQuery("select priority from dchub_content where cid = '$cid'");
        if ($p['priority'] == '0') {
            $maxp = DB::findOneFromQuery("select max(priority) as max from dchub_content");
            $priority = $maxp['max'] + 1;
            $rec = DB::update('dchub_content', array('priority' => $priority), "cid = $cid");
            $ret = '1';
        } else {
            $rec = DB::update('dchub_content', array('priority' => 0), "cid = $cid");
            $ret = '2';
        }
        echo ($rec) ? ($ret) : ('0');

        // Update motd
    } else if (isset($_POST['motdupdate']) && $_SESSION['user']['accesslevel'] >= 9) {
        $_POST['motdcontent'] = addslashes($_POST['motdcontent']);
        file_put_contents($motdfile, $_POST['motdcontent']);
        redirectTo(SITE_URL . "/motd");
    } else if (isset($_POST['som'])) {
        $_POST = secure($_POST);
        $nickuser = $_SESSION['user']['nick'] . ((isset($_SESSION['user']['nick2'])) ? ("','" . $_SESSION['user']['nick2']) : (""));
        $nickuserfriend = $_POST['code'];
        $body = "from msgarchive where (fromnick in ('$nickuser') and tonick = '$nickuserfriend') or (fromnick = '$nickuserfriend' and tonick in ('$nickuser')) order by createdOn desc";
        $res = DB::findAllWithCount("select *", $body, $_POST['page'], 5);
        $i = count($res['data']);
        if ($res['noofpages'] != $_POST['page']) {
            echo "<div id='msgloader'><center><a href='#' onclick=\"som( '$_POST[code]'," . ($_POST['page'] + 1) . ")\" >Show older messages</a></center></div>";
        }
        for (; $i > 0; $i--) {
            $row = $res['data'][$i - 1];
            $row['msg'] = preg_replace('/\n/', '<br/>', htmlspecialchars(stripslashes($row['msg'])));
            if ($row['fromnick'] == $nickuserfriend)
                echo "<b><a href='" . SITE_URL . "/users/$nickuserfriend'>$nickuserfriend</a></b><div class='pull-right'>$row[createdOn]</div><br/>$row[msg]<hr/>";
            else
                echo "<b>Me</b><div class='pull-right'>$row[createdOn]</div><br/>$row[msg]<hr/>";
        }
    } else if (isset($_POST['complaints'])) {
        if ($_POST['msg'] != "") {
            $_POST = secure($_POST);
            DB::insert('dchub_message', array('toid' => 1, 'fromid' => $_SESSION['user']['id'], 'msg' => $_POST['msg']));
            DB::insert('dchub_message', array('toid' => 2, 'fromid' => $_SESSION['user']['id'], 'msg' => $_POST['msg']));
            DB::insert('dchub_message', array('toid' => 3, 'fromid' => $_SESSION['user']['id'], 'msg' => $_POST['msg']));
            $_SESSION['msg'] = "We will reply as soon as we can.";
        } else {
            $_SESSION['msg'] = "Message cannot be empty.";
        }
        redirectTo(SITE_URL . "/complaints");
    } else if (isset($_POST['tagupdate'])) {
        $_POST['data'] = secure($_POST['data']);
        $res = DB::update('dchub_content', $_POST['data'], "cid = $_POST[cid] and uid = " . $_SESSION['user']['id']);
        echo ($res) ? ('1') : ('0');
    } else if (isset($_POST['adminsearch']) && $_SESSION['user']['accesslevel'] == 10) {
        $_POST['data'] = secure($_POST['data']);
        $condition = array();
        if ($_POST['data']['nick'] != "")
            array_push($condition, "nick1 like '%" . $_POST['data']['nick'] . "%' or nick2 like '%" . $_POST['data']['nick'] . "%'");
        if ($_POST['data']['fullname'] != "")
            array_push($condition, "fullname like '%" . $_POST['data']['fullname'] . "%'");
        if ($_POST['data']['ip'] != "")
            array_push($condition, "ipaddress like '%" . $_POST['data']['ip'] . "%'");
        if ($_POST['data']['roll'] != "")
            array_push($condition, " concat(roll_course,roll_number,roll_year) like '%" . $_POST['data']['roll'] . "%'");
        $query = "select id, fullname, nick1, nick2, roll_course, roll_number, roll_year from dchub_users where ";
        $query .= implode(' or ', $condition);
        $res = DB::findAllFromQuery($query);
        if ($res) {
            echo "<div class='row'>";
            foreach ($res as $row) {
                if ($row['fullname'] == "") {
                    $row['fullname'] = $row['nick1'];
                }
                echo "<div class='span4'><div class='accesslevel'><h4><a href='#' onclick=\"adminselect('$row[id]')\"'>$row[fullname]</a></h4><hr/><b>Nicks : </b>$row[nick1] ][ $row[nick2]<br/><b>Roll No. : </b>$row[roll_course]/$row[roll_number]/$row[roll_year]</div></div>";
            }
            echo "</div>";
        } else {
            echo "<center><h1>No record found :(</h1></center>";
        }
    } else if (isset($_POST['adminselect']) && $_SESSION['user']['accesslevel'] == 10) {
        $_POST['id'] = addslashes($_POST['id']);
        $query = "select ipaddress, id, class, nick1, nick2, groups, password_, fullname, roll_course, roll_number, roll_year, hostel, room, branch, phone, friend, deleted  from dchub_users where id = " . $_POST['id'];
        $user = DB::findOneFromQuery($query);
        $stat = DB::findOneFromQuery("select logtype from dchub_log where (nick = '$user[nick1]' or nick = '$user[nick2]') and (logtype = 'Login' or logtype='Logout') order by createdOn desc");
        if($stat && $stat['logtype'] == 'Login'){
            echo "<h3>$user[fullname] ($user[nick1]) - Online</h3>";
        } else {
            echo "<h3>$user[fullname] ($user[nick1]) - Offline</h3>";
        }
        ?>
        <script type="text/javascript">
            $(document).ready(function() {
                $('#myTab a').click(function(e) {
                    e.preventDefault();
                    $(this).tab('show');
                });
            });
            function somadmin(friend, id, page) {
                if (page !== 1) {
                    $('#msgloader').html("Loading...");
                    $.post("<?php echo SITE_URL; ?>/process.php", {
                        "somadmin": "",
                        "id": id,
                        "page": page,
                        "code": friend
                    }, function(data) {
                        $('#msgloader').replaceWith(data);
                    });
                } else {
                    $('#initialLoader').html("Loading...");
                    $.post("<?php echo SITE_URL; ?>/process.php", {
                        "somadmin": "",
                        "id": id,
                        "page": page,
                        "code": friend
                    }, function(data) {
                        $('#initialLoader').html(data);
                    });
                }
            }
        </script>
        <ul class="nav nav-tabs" id="myTab">
            <li class="active"><a href="#data">Data</a></li>
            <li><a href="#chat">Chat History</a></li>
            <li><a href="#search">Search History</a></li>
        </ul>

        <div class="tab-content">
            <div class="tab-pane active" id="data">
                <?php
                $fields = array();
                foreach ($user as $key => $value) {
                    if ($key == 'id') {
                        $fields['data[id]'] = array($key, 'hidden', $value);
                    } else if ($key == 'class') {
                        $cstr = array();
                        foreach ($class as $ckey => $cvalue) {
                            array_push($cstr, "$ckey:$cvalue ($ckey)");
                        }
                        $cstr = implode(',', $cstr);
                        $fields['data[' . $key . "]"] = array($key, 'select', $cstr, $value);
                    } else if ($key == 'roll_course') {
                        $fields['data[' . $key . "]"] = array($key, 'select', "BE:BE,ME:ME,MEEE:MEEE,MESE:MESE,MESER:MESER,MCA:MCA,MBA:MBA,MBI:MBI,BPH:BPH,BPH:BPH,BT:BT,MT/CS:MT/CS,MT/IS:MT/IS,MT/RS:MT/RS,MSC:MSC,BARCH:BARCH,BHMCT:BHMCT,BMI:BMI,MUP:MUP,IMH:IMH,PHD:PHD,EMP:EMP", $value);
                    } else if ($key == 'roll_year') {
                        $fields['data[' . $key . "]"] = array($key, 'select', "2013:2013,2012:2012,2011:2011,2010:2010,2009:2009,2008:2008,2007:2007,2006:2006,2005:2005", $value);
                    } else if ($key == 'branch') {
                        $query = "select * from dchub_branch order by branch";
                        $res = DB::findAllFromQuery($query);
                        $str = array();
                        foreach ($res as $row) {
                            array_push($str, "$row[id]:$row[branch]");
                        }
                        $str = implode(',', $str);
                        $fields['data[' . $key . "]"] = array($key, 'select', $str, $value);
                    } else {
                        $fields['data[' . $key . ']'] = array($key, 'text', $value);
                    }
                }
                createForm('adminupdate', $fields, 'Update Account');
                ?>
            </div>
            <div class="tab-pane" id="chat">
                <div id = 'offmsg'>
                    <div style = "width: 30%; height: 450px; float: left; overflow-y: auto; background: #f5f5f5; border-right: 1px solid #eee;">
                        <ul class = "nav nav-list">
                            <?php
                            $query = "select tonick, fromnick from msgarchive where (fromnick ='$user[nick1]'" . (($user['nick2'] != "") ? (" or fromnick ='$user[nick2]'") : ("")) . " ) or (tonick ='$user[nick1]'" . (($user['nick2'] != "") ? (" or tonick ='$user[nick2]'") : ("")) . " )";
                            $res = DB::findAllFromQuery($query);
                            $nickarray = array();
                            foreach ($res as $row) {
                                array_push($nickarray, $row['tonick']);
                                array_push($nickarray, $row['fromnick']);
                            }
                            $nickarray = array_unique($nickarray);
                            sort($nickarray);
                            foreach ($nickarray as $row) {
                                echo "<li " . ((isset($_GET['code']) && $_GET['code'] == $row) ? ("class='active'") : ("")) . "><a href='#' onclick=\"somadmin('$row', $user[id], 1)\">$row</a></li>";
                            }
                            ?>
                        </ul>
                    </div>
                    <div id ='initialLoader' style="width: 69%; height: 450px; margin-left: 30%;  overflow-y: auto; padding-left: 5px;">
                        <div style='text-align:center;margin-top: 175px;'><h3>Select a user to show messages.</h3></div>
                    </div>
                </div>
            </div>
            <div class="tab-pane" id="search">
                <?php
//                $query = "select nick, group_concat(concat(sr,' (',cnt,')') separator ', ') res from 
//                (SELECT nick,TRIM(replace(SUBSTRING_INDEX(message, '?', -1),'$',' ')) sr, count(*) cnt
//                FROM dchub_log
//                WHERE logtype = 'Search' and message not like '%TTH:%'
//                group by nick,sr
//                having sr not like ''
//                order by timedate desc) s where (nick = '$user[nick1]' or nick='$user[nick2]')";
//                $res = DB::findOneFromQuery($query);
//                echo "<b>Search Count :</b><br/>".$res['res']."<hr/><b>Last few searches : </br></b>";
                $query = "select * from dchub_log where (nick = '$user[nick1]' or nick='$user[nick2]') and logtype='Search' and message not like '%TTH%' order by createdOn desc limit 0, 50";
                $res = DB::findAllFromQuery($query);
                echo "<table class='table table-hover'>";
                foreach ($res as $row) {
                    $row['message'] = preg_replace('/\$/', ' ', substr($row['message'], strrpos($row['message'], '?') + 1));
                    echo "<tr><td><div class='pull-right'>$row[createdOn]</div>$row[message]</td></tr>";
                }
                echo "</table>";
                ?>
            </div>
        </div>
        <?php
    } else if (isset($_POST['somadmin']) && $_SESSION['user']['accesslevel'] == 10) {
        $_POST = secure($_POST);
        $query = "select ipaddress, id, class, nick1, nick2, groups, password_, fullname, roll_course, roll_number, roll_year, hostel, room, branch, phone, friend, deleted  from dchub_users where id = " . $_POST['id'];
        $user = DB::findOneFromQuery($query);
        $nickuser = $user['nick1'] . (($user['nick2'] != "") ? ("','" . $user['nick2']) : (""));
        $nickuserfriend = $_POST['code'];
        $body = "from msgarchive where (fromnick in ('$nickuser') and tonick = '$nickuserfriend') or (fromnick = '$nickuserfriend' and tonick in ('$nickuser')) order by createdOn desc";
        $res = DB::findAllWithCount("select *", $body, $_POST['page'], 5);
        $i = count($res['data']);
        if ($res['noofpages'] != $_POST['page']) {
            echo "<div id='msgloader'><center><a href='#' onclick=\"somadmin( '$_POST[code]'," . $user['id'] . "," . ($_POST['page'] + 1) . ")\" >Show older messages</a></center></div>";
        }
        for (; $i > 0; $i--) {
            $row = $res['data'][$i - 1];
            $row['msg'] = preg_replace('/\n/', '<br/>', htmlspecialchars(stripslashes($row['msg'])));
            if ($row['fromnick'] == $nickuserfriend)
                echo "<b><a href='" . SITE_URL . "/users/$nickuserfriend'>$nickuserfriend</a></b><div class='pull-right'>$row[createdOn]</div><br/>$row[msg]<hr/>";
            else
                echo "<b><a href='" . SITE_URL . "/users/$user[nick1]'>$user[nick1]</a></b><div class='pull-right'>$row[createdOn]</div><br/>$row[msg]<hr/>";
        }
    } else if (isset($_POST['courseware']) && $_SESSION['user']['accesslevel'] >= 9) {
        if ($_FILES['fileadd']['size'] == 0) {
            $_SESSION['msg'] = "Please select a file";
        } else if ($_FILES["fileadd"]["error"] > 0) {
            $_SESSION['msg'] = "File Error : " . $_FILES["fileadd"]["error"];
        } else {
            if (file_exists("/srv/http/dchub/course/" . $_FILES["fileadd"]["name"])) {
                $_SESSION['msg'] = $_FILES["fileadd"]["name"] . " already exists. ";
            } else {
                $_SESSION['msg'] = "Uploaded.";
                move_uploaded_file($_FILES["fileadd"]["tmp_name"], "/srv/http/dchub/course/" . $_FILES["fileadd"]["name"]);
//                echo "Stored in: " . "upload/" . $_FILES["file"]["name"];
            }
        }
//        print_r($_FILES);
        redirectTo(SITE_URL . "/courseware");
    }
}
?>