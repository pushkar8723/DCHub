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
    <script src="<?php echo JS_URL; ?>/bootstrap.js"></script>
    <script src="<?php echo JS_URL; ?>/jquery-ui-1.10.0.custom.min.js"></script>
    <?php
    $list = array('register', 'admin', 'adminpanel');
    if (!isset($_GET['tab']) || !in_array($_GET['tab'], $list)) {
        ?>
        <script src="<?php echo JS_URL; ?>/jquery.dropkick-1.0.0.js"></script>
        <script src="<?php echo JS_URL; ?>/application.js"></script>
    <?php } ?>
    <script src="<?php echo JS_URL; ?>/custom_checkbox_and_radio.js"></script>
    <script src="<?php echo JS_URL; ?>/custom_radio.js"></script>
    <script src="<?php echo JS_URL; ?>/jquery.tagsinput.js"></script>
    <script src="<?php echo JS_URL; ?>/bootstrap-tooltip.js"></script>
    <script src="<?php echo JS_URL; ?>/jquery.placeholder.js"></script>

    <?php
    if (isset($_SESSION['loginerr'])) {
        echo "<script type='text/javascript'>
                $(document).ready(function(){
                    $('#signinbox').modal('show');
                });
              </script>";
    }
}

function navbar() {
    ?>
    <div id="signinbox" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            <h3 id="myModalLabel">Sign In</h3>
        </div>
        <div class="modal-body">
            <?php
            if (isset($_SESSION['loginerr'])) {
                echo "<div class='alert alert-danger' style='text-align:center;'>$_SESSION[loginerr]</div>";
                unset($_SESSION['loginerr']);
            }
            echo "<div style='padding-left: 50px;'>";
            $fields = array(
                'data[username]' => array('Username', 'text'),
                'data[password]' => array('Password', 'password')
            );
            createForm('login', $fields, 'Sign In');
            echo "</div>";
            ?>

        </div>
        <div class="modal-footer">
            <center><a href = "#">Forgot Password?</a></center>
        </div>
    </div>

    <div class="navbar navbar-fixed-top navbar-inverse">
        <div class="navbar-inner">
            <div class="container">
                <ul class="nav">
                    <li><a href="<?php echo SITE_URL; ?>">DC Hub</a></li>
                    <li><a href="<?php echo SITE_URL; ?>/latest">Latest Contents</a></li>
                    <li>
                        <a href="#">
                            Pages
                        </a>
                        <ul>
                            <li><a href="<?php echo SITE_URL; ?>/hot">HOT Page</a></li>
                            <li><a href="<?php echo SITE_URL; ?>/request">Request Page</a></li>
                            <li><a href="<?php echo SITE_URL; ?>/recommend">Recommendation Page</a></li>
                            <li><a href="<?php echo SITE_URL; ?>/courseware">Courseware</a></li>
                            <!--<li><a href="<?php echo SITE_URL; ?>/hof">Hall of Fame</a></li>-->
                        </ul>
                    </li>
                    <li>
                        <a href="#">
                            Help
                        </a>
                        <ul>
                            <li><a href="<?php echo SITE_URL; ?>/info">Hub Info</a></li>
                            <li><a href="<?php echo SITE_URL; ?>/bitinfo">BIT Info</a></li>
                            <li><a href="<?php echo SITE_URL; ?>/complaints">Complaints</a></li>
                            <li><a href="<?php echo SITE_URL; ?>/accesslevel">Access Level</a></li>
                        </ul>
                    </li>
                    <?php if (isset($_SESSION['loggedin']) && $_SESSION['user']['accesslevel'] >= 2) { ?>
                        <li><a href="#">Manage</a>
                            <ul>
                                <li><a href="<?php echo SITE_URL; ?>/recmanage">Recommendation Page</a></li>
                                <li><a href="<?php echo SITE_URL; ?>/reqmanage">Request Page</a></li>
                                <?php
                                if ($_SESSION['user']['accesslevel'] >= 3) {
                                    echo "<li><a href='" . SITE_URL . "/latmanage'>Latest Content</a></li>";
                                }
                                if ($_SESSION['user']['accesslevel'] >= 4) {
                                    echo "<li><a href='" . SITE_URL . "/msgmanage'>Message Approval</a></li>";
                                }
                                ?>
                            </ul>
                        </li>
                    <?php } ?>
                    <?php if (isset($_SESSION['loggedin']) && $_SESSION['user']['accesslevel'] >= 9) { ?>
                        <li><a href="#">Admin</a>
                            <ul>
                                <li><a href="<?php echo SITE_URL; ?>/groups">Groups</a></li>
                                <?php
                                if ($_SESSION['user']['accesslevel'] == 9)
                                    echo "<li><a href='" . SITE_URL . "/admin'>Administration</a></li>";
                                else
                                    echo "<li><a href='" . SITE_URL . "/adminpanel'>Administration</a></li>";
                                ?>
                                <li><a href="<?php echo SITE_URL; ?>/motd">MotD</a></li>
                            </ul>
                        </li>
                    <?php } ?>
                </ul>
                <?php
                if (isset($_SESSION['loggedin'])) {
                    $query = "select * from dchub_users where deleted=0 and class = 0 and (friend = '" . $_SESSION['user']['nick'] . "' " . ((isset($_SESSION['user']['nick2'])) ? ("OR friend = '" . $_SESSION['user']['nick2'] . "'") : ('')) . ")";
                    $res = DB::findAllFromQuery($query);
                    $query = "select distinct(fromid) as fromid from dchub_message where deleted=0 and id > '" . $_SESSION['user']['msgid'] . "' and toid = " . $_SESSION['user']['id'] . "
            union
            select distinct(toid) as fromid from dchub_message where deleted=0 and id > '" . $_SESSION['user']['msgid'] . "' and fromid = " . $_SESSION['user']['id'];
                    $resmsg = DB::findAllFromQuery($query);
                    $usrgrp = implode(",", $_SESSION['user']['groups']);
                    $query = "select id from dchub_post where deleted=0 and approvedby!=0 and id > '" . $_SESSION['user']['notificationid'] . "' and gid in ($usrgrp)    
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
                                <li><a href="<?php echo SITE_URL ?>/msgarchive">Message Archive</a></li>
                                <li><a href="<?php echo SITE_URL ?>/notifications/archive">Notification Archive</a></li>
                                <li><a href="<?php echo SITE_URL ?>/account">Account Settings</a></li>
                                <li><a href="<?php echo SITE_URL ?>/process.php?logout">Logout</a></li>
                            </ul>
                        </li>
                    </ul>
                <?php } else { ?>
                    <a style='margin-top: 3px;' class="btn btn-large btn-danger pull-right" href="<?php echo SITE_URL; ?>/register">Register</a>
                    <a style='margin-top: 3px; margin-right: 5px;'  role="button" data-toggle="modal" class="btn btn-large btn-danger pull-right" href="#signinbox">Sign In</a>
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
        <a href="<?php echo SITE_URL; ?>/about">About</a> · <a href="<?php echo SITE_URL; ?>/privacy">Privacy Policy</a> · <a href="<?php echo SITE_URL; ?>/terms">Terms and Conditions</a>
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

function contentshow($data, $highlight = '', $sharedby = true, $edit = FALSE) {
    ?>
    <script type="text/javascript">
        $(document).ready(function() {
            $('.tagupdate').click(function(event) {
                id = event.target.id;
                id = id.replace('tags_', '');
                $(this).html('Processing...');
                $.post("<?php echo SITE_URL; ?>/process.php", {
                    "tagupdate": "",
                    "cid": id,
                    "data[tag]": $('#tag_' + id).val()
                }, function(result) {
                    if (result === '1') {
                        $('#tags_' + id).html('Update');
                    }
                    else {
                        $('#tags_' + id).html(result);
                    }
                });
            });
        });
    </script>
    <?php
    echo "<table class='table table-hover'>
                    <tr><th>File Name</th><th>Tags</th>" . (($sharedby) ? ("<th>Shared By</th>") : ("")) . "<th style='width:170px; text-align:center;'>Recommendations</th></tr>";
    foreach ($data as $row) {
        // highlight searched terms
        if ($highlight != '') {
            $str = preg_replace('/' . str_replace(' ', '|', trim($highlight)) . '/i', '<b>$0</b>', stripslashes($row['title']));
        } else {
            $str = stripslashes($row['title']);
        }
        // who shared the content
        if ($sharedby) {
            $query = "select nick1 from dchub_users where id = $row[uid]";
            $user = DB::findOneFromQuery($query);
        }
        // Tags Manipulation
        $tagstr = '';
        $splittag = explode(',', stripslashes($row['tag']));
        if ($edit) {
            if (isset($_SESSION['loggedin']) && $row['uid'] == $_SESSION['user']['id']) {
                $tagstr .= "<input name='data[tag]' id='tag_$row[cid]' name='data[tag]' class='tagsinput' value='$row[tag]'/>
                    <a href='#' id='tags_$row[cid]' class='btn tagupdate'>Update</a><br/><br/>";
            } else {
                foreach ($splittag as $tag) {
                    $tagstr .= "<a href='" . SITE_URL . "/latest/$tag'>$tag</a> ";
                }
            }
        } else {
            foreach ($splittag as $tag) {
                $tagstr .= "<a href='" . SITE_URL . "/latest/$tag'>$tag</a> ";
            }
        }
        // recommend button
        $query = "select count(cid) as recommendations from dchub_recommend where cid = $row[cid] and type='lc'";
        $rec = DB::findOneFromQuery($query);
        if (isset($_SESSION['loggedin'])) {
            $query = "select uid from dchub_recommend where type='lc' and cid = $row[cid] and uid = " . $_SESSION['user']['id'];
            $response = DB::findAllFromQuery($query);
            if ($response) {
                $btn = "<a href='#' class='btn discourage' id='$row[cid]'>Discourage</a>";
            } else {
                $btn = "<a href='#' class='btn recommend' id='$row[cid]'>Recommend</a>";
            }
        } else {
            $btn = "<a href='#' onclick=\"$('#signinbox').modal('show');\" class='btn'>Login to Recommend</a>";
        }

        //printing
        echo "<tr><td>" . (($row['magnetlink'] != "") ? ("<a href='$row[magnetlink]'>" . $str . "</a>") : ($str)) . "</td>
            <td>$tagstr</td>" . (($sharedby) ? ("<td><a href='" . SITE_URL . "/users/$user[nick1]'>$user[nick1]</a></td>") : ("")) . "
                <td style='text-align:center;'><span id='$row[cid]_count'>$rec[recommendations]</span> recommendation(s)<br/>$btn</td></tr>";
    }
    echo "</table>";
}

function createForm($name, $fields, $submitname) {
    echo "<form class='form-horizontal' method='post' action='" . SITE_URL . "/process.php'>";
    foreach ($fields as $key => $value) {
        if ($value[1] == 'hidden') {
            echo "<input type='$value[1]' name='$key' id='$key' " . ((isset($value[2])) ? ("value='$value[2]'") : ("")) . "/>";
        } else {
            echo "<div class='control-group'>
            <div class='control-label'><label for='$key'>$value[0]</label></div>
            <div class='controls'>";
            if ($value[1] != "select") {
                echo "<input type='$value[1]' name='$key' id='$key' " . ((isset($value[2])) ? ("value='$value[2]'") : ("")) . "/>";
            } else {
                echo "<select name='$key' id='$key'>";
                foreach (explode(',', $value[2]) as $opt) {
                    $attr = explode(':', $opt);
                    echo "<option value='$attr[0]' " . ((isset($value[3]) && $value[3] == $attr[0]) ? ("selected") : ("")) . ">$attr[1]</option>";
                }
                echo "</select>";
            }
            echo "</div>
        </div>";
        }
    }
    echo "<div class='control-group'><div class='control-label'></div><div class='controls'><input class='btn' type='submit' name='$name' value='$submitname'></div></div>
        </form>";
}
?>
