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
                    <li><a href="#">Latest Contents</a></li>
                    <li>
                        <a href="#">
                            Pages
                        </a>
                        <ul>
                            <li><a href="hot">HOT Page</a></li>
                            <li><a href="request">Request Page</a></li>
                            <li><a href="hof">Hall of Fame</a></li>
                        </ul>
                    </li>
                    <li><a href="#">FAQ</a></li>
                </ul>
                <?php
                if (isset($_SESSION['loggedin'])) {
                    $query = "select * from dchub_users where authenticated = 0 and (friend = '" . $_SESSION['user']['nick'] . "' " . ((isset($_SESSION['user']['nick2'])) ? ("OR friend = '" . $_SESSION['user']['nick2'] . "'") : ('')) . ")";
                    $res = DB::findAllFromQuery($query);
                    ?>
                    <ul class="nav pull-right">
                        <li <?php if (count($res) > 0) echo "class='active'"; ?>><a href="#"><span class="fui-man-24"></span><?php if (count($res) > 0) echo '<span class="navbar-unread">' . count($res) . '</span>'; ?></a></li>
                        <li><a href="#"><span class="fui-bubble-24"></span></a></li>
                        <li><a href="#"><span class="fui-menu-24"></span></a></li>
                        <li>
                            <a href="#">Account</a>
                            <ul style='left: -120px;'>
                                <li><a href="<?php echo SITE_URL ?>/#">Account Settings</a></li>
                                <li><a href="<?php echo SITE_URL ?>/process.php?logout">Logout</a></li>
                            </ul>
                        </li>
                    </ul>
                <?php } else { ?>
                    <a class="btn btn-large btn-danger pull-right" href="register">Create Account</a>
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
        Administrators : DeathEater · Red_Devil · sdh
    </div>
    <div class="pull-right">
        <a href="#">About</a> · <a href="terms">Terms and Conditions</a>
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