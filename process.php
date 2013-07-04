<?php

require_once 'config.php';
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
    if ($_POST['data']['password1'] == "") {
        $_SESSION['msg'] .= "Password required<br/>";
        $error = 1;
    }
    if ($_POST['data']['nick1'] == $_POST['data']['nick2']) {
        $_SESSION['msg'] .= "Nick must be different<br/>";
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
    if ($_POST['data']['password1'] != $_POST['data']['repassword1']) {
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
    $res = DB::findOneFromQuery("select count(email) as count from dchub_users where ipaddress = '" . $_POST['data']['ipaddress'] . "'");
    if ($res['count'] > 0) {
        $_SESSION['msg'] .= "Someone already registered from this IP. Contact Admins if you haven't registered or if you have forgoten the credentials.<br/>";
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
        $_POST['data']['groups'] = $defaultGroup[$_POST['data']['roll_year']];
        unset($_POST['data']['repassword1']);
        unset($_POST['data']['others']);
        $maxmsg = DB::findOneFromQuery("select max(id) as id from dchub_message");
        $maxnot = DB::findOneFromQuery("select max(id) as id from dchub_post");
        $_POST['data']['lastmsgid'] = $maxmsg['id'];
        $_POST['data']['lastnotificationid'] = $maxnot['id'];
        $res = DB::insert("dchub_users", $_POST['data']);
        if ($res) {
            $query = "select * from dchub_users where nick1 = '" . $_SESSION['user']['nick'] . "'";
            $res2 = DB::findOneFromQuery($query);
        }
        if ($res2) {
            $_SESSION['loggedin'] = "true";
            $_SESSION['user']['id'] = $res2['id'];
            $_SESSION['user']['accesslevel'] = $res2['class'];
            $_SESSION['user']['name'] = $res2['fullname'];
            $_SESSION['user']['branch'] = $res2['branch'];
            $_SESSION['user']['hostel'] = $res2['hostel'];
            $_SESSION['user']['room'] = $res2['room'];
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
} else if (isset($_GET['logout'])) {
    session_destroy();
    redirectTo(SITE_URL);
} else if (isset($_POST['login'])) {
    $_POST['data'] = secure($_POST['data']);
    $query = "select * from dchub_users where (nick1 = '" . $_POST['data']['username'] . "' and password1 = '" . $_POST['data']['password'] . "') OR (nick2 = '" . $_POST['data']['username'] . "' and password2 = '" . $_POST['data']['password'] . "')";
    $user = DB::findOneFromQuery($query);
    if ($user) {
        $_SESSION['user']['nick'] = $user['nick1'];
        if ($user['nick2'] != '') {
            $_SESSION['user']['nick2'] = $user['nick2'];
        }
        $_SESSION['user']['email'] = $user['email'];
        $_SESSION['loggedin'] = "true";
        $_SESSION['user']['id'] = $user['id'];
        $_SESSION['user']['accesslevel'] = $user['class'];
        $_SESSION['user']['name'] = $user['fullname'];
        $_SESSION['user']['branch'] = $user['branch'];
        $_SESSION['user']['hostel'] = $user['hostel'];
        $_SESSION['user']['room'] = $user['room'];
        $_SESSION['user']['roll'] = $user['roll_course'] . "/" . $user['roll_number'] . "/" . $user['roll_year'];
        $_SESSION['user']['ip'] = $user['ipaddress'];
        $_SESSION['user']['groups'] = explode(',', $user['groups']);
        $_SESSION['user']['lastmsgid'] = $user['lastmsgid'];
        $_SESSION['user']['msgid'] = $user['lastmsgid'];
        $_SESSION['user']['lastnotificationid'] = $user['lastnotificationid'];
        $_SESSION['user']['notificationid'] = $user['lastnotificationid'];
        $_SESSION['msg'] = 'Successfully Loggedin';
    } else {
        $_SESSION['msg'] = 'Incorrect Username/Password';
    }
    redirectTo(SITE_URL);
} else if (isset($_POST['share']) && isset($_SESSION['loggedin'])) {
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
        DB::insert('dchub_content', $_POST['data']);
        redirectTo(SITE_URL);
    } else {
        $_SESSION['msg'] = "Some values missing<br/>";
        redirectTo(SITE_URL);
    }
} else if (isset($_SESSION['loggedin']) && isset($_POST['grpToggle'])) {
    $_POST = secure($_POST);
    if (($key = array_search($_POST['group'], $_SESSION['user']['groups'])) !== false) {
        unset($_SESSION['user']['groups'][$key]);
    } else {
        array_push($_SESSION['user']['groups'], $_POST['group']);
    }
    $query = "update dchub_users set groups='" . implode(',', $_SESSION['user']['groups']) . "' where id = " . $_SESSION['user']['id'];
    DB::query($query);
    redirectTo(SITE_URL . "/groups/$_POST[group]");
} else if (isset($_POST['approve']) && isset($_SESSION['loggedin'])) {
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
} else if (isset($_POST['decline']) && isset($_SESSION['loggedin'])) {
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
} else if (isset($_SESSION['loggedin']) && isset($_POST['post'])) {
    $_POST['data'] = secure($_POST['data']);
    $_POST['data']['postby'] = $_SESSION['user']['nick'];
    DB::insert('dchub_post', $_POST['data']);
    $_SESSION['msg'] = "Post submited for approval";
    redirectTo("http://" . $_SERVER['HTTP_HOST'] . $_SESSION['url']);
} else if (isset($_POST['recommend']) && isset($_SESSION['loggedin'])) {
    $_POST = secure($_POST);
    $res = DB::query("insert into dchub_recommend (cid, uid, type) values($_POST[cid], " . $_SESSION['user']['id'] . ", 'lc')");
    echo $res ? "1" : "0";
} else if (isset($_POST['discourage']) && isset($_SESSION['loggedin'])) {
    $_POST = secure($_POST);
    $res = DB::query("delete from dchub_recommend where cid = $_POST[cid] and uid = " . $_SESSION['user']['id']);
    echo $res ? "1" : "0";
} else if (isset($_POST['volunteer']) && isset($_SESSION['loggedin'])) {
    $_POST = secure($_POST);
    $vol = DB::findOneFromQuery("select volunteer from dchub_request where id = $_POST[cid]");
    $vol = explode(',', $vol['volunteer']);
    array_push($vol, $_SESSION['user']['nick']);
    $vol = implode(',', $vol);
    $res = DB::query("update dchub_request set volunteer = '$vol' where id=$_POST[cid]");
    echo $res ? "1" : "0";
} else if (isset($_POST['chickenout']) && isset($_SESSION['loggedin'])) {
    $_POST = secure($_POST);
    $vol = DB::findOneFromQuery("select volunteer from dchub_request where id = $_POST[cid]");
    $vol = explode(',', $vol['volunteer']);
    $key = array_search($_SESSION['user']['nick'], $vol);
    unset($vol[$key]);
    $vol = implode(',', $vol);
    $res = DB::query("update dchub_request set volunteer = '$vol' where id=$_POST[cid]");
    echo $res ? "1" : "0";
} else if (isset($_SESSION['loggedin']) && isset($_POST['request'])) {
    $_POST['data'] = secure($_POST['data']);
    $_POST['data']['uid'] = $_SESSION['user']['id'];
    $res = DB::insert("dchub_request", $_POST['data']);
    redirectTo(SITE_URL . "/request");
} else if (isset($_SESSION['loggedin']) && isset($_POST['approvefriend']) && $_SESSION['user']['accesslevl'] > 0) {
    $_POST = secure($_POST);
    $query = "update dchub_users set authenticated=1, class=2 where nick1 = '$_POST[nick]' and (friend ='" . $_SESSION['user']['nick'] . "'" . ((isset($_SESSION['user']['nick2'])) ? (" or friend='" . $_SESSION['user']['nick2'] . "')") : (")"));
    DB::query($query);
    redirectTo(SITE_URL . "/friends");
} else if (isset($_SESSION['loggedin']) && isset($_POST['denyfriend']) && $_SESSION['user']['accesslevl'] > 0) {
    $_POST = secure($_POST);
    $query = "update dchub_users set friend='' where nick1 = '$_POST[nick]' and (friend ='" . $_SESSION['user']['nick'] . "'" . ((isset($_SESSION['user']['nick2'])) ? (" or friend='" . $_SESSION['user']['nick2'] . "')") : (")"));
    DB::query($query);
    redirectTo(SITE_URL . "/friends");
} else if (isset($_SESSION['loggedin']) && isset($_POST['messagepost'])) {
    $_POST['data'] = secure($_POST['data']);
    $_POST['data']['fromid'] = $_SESSION['user']['id'];
    $user = DB::findOneFromQuery("select id from dchub_users where nick1 = '" . $_POST['data']['to'] . "' or nick2 ='" . $_POST['data']['to'] . "'");
    unset($_POST['data']['to']);
    $_POST['data']['toid'] = $user['id'];
    DB::insert('dchub_message', $_POST['data']);
    $_SESSION['msg'] = "Message sent successfully";
    redirectTo("http://" . $_SERVER['HTTP_HOST'] . $_SESSION['url']);
} else if (isset($_POST['ajaxFetch'])) {
    $res = DB::findOneFromQuery("select fullname, roll_course, roll_number, roll_year, email, phone from dchub_importedusers where nickname = '$_POST[nick]' and password_ = '$_POST[password]'");
    if ($res) {
        $str = "{'data' :[{'fullname' : '$res[fullname]','roll_course' : '$res[roll_course]','roll_number' : '$res[roll_number]','roll_year' : '$res[roll_year]','email' : '$res[email]','phone' : '$res[phone]'}]}";
        echo $str;
    } else {
        echo "Incorrect nick / password";
    }
} else if (isset($_SESSION['loggedin']) && isset($_POST['cyberauth'])) {
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
    if ($xml->message != "The system could not log you on. Make sure your password is correct") {
        $_SESSION['msg'] = 'Authentication Successfull';
        $_SESSION['user']['accesslevel'] = 1;
        DB::update('dchub_users', array('class' => 1, 'authenticated' => 1), "id = " . $_SESSION['user']['id']);
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
} else if (isset($_SESSION['loggedin']) && isset($_POST['friendauth'])) {
    $_POST['friend'] = addslashes($_POST['friend']);
    DB::update('dchub_users', array('friend' => $_POST['friend']), "id = " . $_SESSION['user']['id']);
    $_SESSION['msg'] = 'Authentication request sent. Your class will be updated as soon as your friend authenticates you.';
    redirectTo("http://" . $_SERVER['HTTP_HOST'] . $_SESSION['url']);
}
?>