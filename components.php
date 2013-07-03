<?php

function head() { ?>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <title>DC Hub</title>
    <link href="<?php echo CSS_URL; ?>/bootstrap.css" rel="stylesheet">
    <link href="<?php echo CSS_URL; ?>/flat-ui.css" rel="stylesheet">
    <link href="<?php echo CSS_URL; ?>/style.css" rel="stylesheet">
    <script src="<?php echo JS_URL; ?>/jquery-1.8.2.min.js"></script>
    <script src="<?php echo JS_URL; ?>/scripts.js"></script>
    <script src="<?php echo JS_URL; ?>/bootstrap.js"></script>
    <script src="<?php echo JS_URL; ?>/jquery-ui-1.10.0.custom.min.js"></script>
    <script src="<?php echo JS_URL; ?>/jquery.dropkick-1.0.0.js"></script>
    <script src="<?php echo JS_URL; ?>/custom_checkbox_and_radio.js"></script>
    <script src="<?php echo JS_URL; ?>/custom_radio.js"></script>
    <script src="<?php echo JS_URL; ?>/jquery.tagsinput.js"></script>
    <script src="<?php echo JS_URL; ?>/bootstrap-tooltip.js"></script>
    <script src="<?php echo JS_URL; ?>/jquery.placeholder.js"></script>
    <script src="<?php echo JS_URL; ?>/application.js"></script>
    <?php
}

function navbar() {
    ?>
    <div class="navbar navbar-fixed-top navbar-inverse">
        <div class="navbar-inner">
            <div class="container">
                <b class="brand">DC Hub</b>
                <ul class="nav">
                    <li><a href="<?php echo SITE_URL; ?>">Home</a></li>
                    <li><a href="<?php echo SITE_URL; ?>/latest">Latest Contents</a></li>
                    <li><a href="<?php echo SITE_URL; ?>/groups">Groups</a></li>
                    <li>
                        <a href="#">
                            Pages
                        </a>
                        <ul>
                            <li><a href="<?php echo SITE_URL; ?>/hot">HOT Page</a></li>
                            <li><a href="<?php echo SITE_URL; ?>/request">Request Page</a></li>
                            <li><a href="<?php echo SITE_URL; ?>/hof">Hall of Fame</a></li>
                        </ul>
                    </li>
                    <li><a href="#">FAQ</a></li>
                </ul>
                <?php
                if (isset($_SESSION['loggedin'])) {
                    $query = "select * from dchub_users where authenticated = 0 and (friend = '" . $_SESSION['user']['nick'] . "' " . ((isset($_SESSION['user']['nick2'])) ? ("OR friend = '" . $_SESSION['user']['nick2'] . "'") : ('')) . ")";
                    $res = DB::findAllFromQuery($query);
                    $query = "select distinct(fromid) as fromid from dchub_message where id > '" . $_SESSION['user']['msgid'] . "' and toid = " . $_SESSION['user']['id'] . "
            union
            select distinct(toid) as fromid from dchub_message where id > '" . $_SESSION['user']['msgid'] . "' and fromid = " . $_SESSION['user']['id'];
                    $resmsg = DB::findAllFromQuery($query);
                    $usrgrp = "'".implode("','", $_SESSION['user']['groups'])."'";
                    $query = "select id from dchub_post where deleted=0 and approvedby!=0 and id > '" . $_SESSION['user']['notificationid'] . "' and gid in 
(select id from dchub_groups where name in ($usrgrp))    
order by timestamp desc";
                    $resnot = DB::findAllFromQuery($query);
                    ?>
                    <ul class="nav pull-right">
                        <li id='friendnav' <?php if (count($res) > 0) echo "class='active'"; ?>><a href="<?php echo SITE_URL; ?>/friends"><span class="fui-man-24"></span><?php if (count($res) > 0) echo '<span class="navbar-unread">' . count($res) . '</span>'; ?></a></li>
                        <li id='msgnav' <?php if (count($resmsg) > 0) echo "class='active'"; ?>><a href="<?php echo SITE_URL; ?>/messages"><span class="fui-bubble-24"></span><?php if (count($resmsg) > 0) echo '<span class="navbar-unread">' . count($resmsg) . '</span>'; ?></a></li>
                        <li id='notnav' <?php if (count($resnot) > 0) echo "class='active'"; ?>><a href="<?php echo SITE_URL; ?>/notifications"><span class="fui-menu-24"></span><?php if (count($resnot) > 0) echo '<span class="navbar-unread">' . count($resnot) . '</span>'; ?></a></li>
                        <li>
                            <a href="#">Account</a>
                            <ul style='left: -120px;'>
                                <li><a href="<?php echo SITE_URL ?>/#">Account Settings</a></li>
                                <li><a href="<?php echo SITE_URL ?>/process.php?logout">Logout</a></li>
                            </ul>
                        </li>
                    </ul>
                <?php } else { ?>
                    <a class="btn btn-large btn-danger pull-right" href="<?php echo SITE_URL; ?>/register">Create Account</a>
                <?php } ?>
            </div>
        </div>
    </div>
    <?php
}

function footer() {
    ?>
    <hr>
    <div class="pull-left">
        Administrators : <a href="<?php echo SITE_URL; ?>/users/DeathEater">DeathEater</a> · <a href="<?php echo SITE_URL; ?>/users/Red_Devil">Red_Devil</a> · <a href="<?php echo SITE_URL; ?>/users/sdh">sdh</a>
    </div>
    <div class="pull-right">
        <a href="#">About</a> · <a href="<?php echo SITE_URL; ?>/privacy">Privacy Policy</a> · <a href="<?php echo SITE_URL; ?>/terms">Terms and Conditions</a>
    </div>
    <br/><br/>    
    <?php
}

function pagination($noofpages, $url, $page, $maxcontent) {
    if ($noofpages > 1) {
        if ($page - ($maxcontent / 2) > 0)
            $start = $page - 5;
        else
            $start = 1;
        if ($noofpages >= $start + $maxcontent)
            $end = $start + $maxcontent;
        else
            $end = $noofpages;
        ?>
        <div class ="pagination pagination-centered">
            <ul>        
                <?php if ($page > 1) { ?>
                    <li class="previous"><a href="<?php echo $url . "&page=" . ($page - 1); ?>"><img src="<?php echo IMAGE_URL; ?>/pager/previous.png" /></a></li>
                    <?php
                }
                for ($i = $start; $i <= $end; $i++) {
                    ?>
                    <li <?php echo ($i == $page) ? ("class='disabled'") : (''); ?>><a href="<?php echo ($i != $page) ? ($url . "&page=" . $i) : ("#"); ?>"><?php echo $i; ?></a></li>
                    <?php
                }
                if ($page < $noofpages) {
                    ?>
                    <li class="next"><a href="<?php echo $url . "&page=" . ($page + 1); ?>"><img src="<?php echo IMAGE_URL; ?>/pager/next.png" /></a></li>
                        <?php } ?>
            </ul>
        </div>
        <?php
    }
}

function secure($var) {
    foreach ($var as $key => $value)
        $var[$key] = addslashes($value);
    return $var;
}

function check($var) {
    $flag = True;
    foreach ($var as $value) {
        if ($value == "")
            $flag = FALSE;
    }
    return $flag;
}
?>