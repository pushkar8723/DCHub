<?php
require_once 'config.php';
if (isset($_POST['register'])) {
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
    if ($_POST['data']['password1'] != $_POST['data']['repassword1']) {
        $_SESSION['msg'] .= "Password mismatch<br/>";
        $error = 1;
    }
    if ($_POST['data']['password1'] != $_POST['data']['repassword1']) {
        $_SESSION['msg'] .= "Password mismatch for second nick<br/>";
        $error = 1;
    }
    $res = DB::findOneFromQuery("select count(email) as count from dchub_users where nick1 = '".$_POST['data']['nick1']."'");
    if ($res['count'] > 0) {
        $_SESSION['msg'] .= "Nick already registered!<br/>";
        $error = 1;
    }
    $res = DB::findOneFromQuery("select count(email) count from dchub_users where nick2 like '".$_POST['data']['nick2']."'");
    if ($_POST['data']['nick2'] != '' && $res['count'] > 0) {
        $_SESSION['msg'] .= "Second Nick is  already registered!<br/>";
        $error = 1;
    }
    $res = DB::findOneFromQuery("select count(email) as count from dchub_users where hostel = '".$_POST['data']['hostel']."' and room = '".$_POST['data']['room']."'");
    if ($res['count'] > 0) {
        $_SESSION['msg'] .= "Someone already registered from this Room. Contact Admins if you haven't registered or if you have forgoten the credentials.<br/>";
        $error = 1;
    }
    if ($_POST['data']['cyberpass'] != "") {
        // Test for cyberoam password
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
        unset($_POST['data']['repassword1']);
        unset($_POST['data']['repassword2']);
        unset($_POST['data']['cyberpass']);
        $_POST['data']['ipaddress'] = $_SERVER['REMOTE_ADDR'];
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
            redirectAfter(SITE_URL . "/home");
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
    $query = "select * from dchub_users where (nick1 = '". $_POST['data']['username'] . "' and password1 = '" . $_POST['data']['password'] . "') OR (nick2 = '" .$_POST['data']['username'] . "' and password2 = '" . $_POST['data']['password'] . "')";
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
    } else {
        $_SESSION['msg'] = 'Incorrect Username/Password';
    }
    redirectTo(SITE_URL);
} else if (isset ($_POST['share']) && isset ($_SESSION['loggedin'])){
    $_POST['data'] = secure($_POST['data']);
    if (check(array($_POST['data']['title'], $_POST['data']['tag']))){
        $_POST['data']['uid'] = $_SESSION['user']['id'];
        $_POST['data']['timestamp'] = time();
        DB::insert('dchub_content', $_POST['data']);
        redirectTo(SITE_URL);
    } else {
        $_SESSION['msg'] = "Some values missing<br/>";
        redirectTo(SITE_URL);
    }
}
?>
