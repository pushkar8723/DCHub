<?php
if (isset($_SESSION['loggedin'])) {
    if (isset($_GET['page']) && $_GET['page'] > 0) {
        $page = $_GET['page'];
    } else {
        $page = 1;
    }
    ?>
    <script type='text/javascript'>
        $(document).ready(function() {
            $('.recommend, .discourage').click(function(event) {
                $('#' + event.target.id).html("Processing...");
                if ($('#' + event.target.id).attr('class') == "btn discourage") {
                    $.post("<?php echo SITE_URL; ?>/process.php", {
                        "discourage": '',
                        "cid": event.target.id
                    }, function(result) {
                        if (result == '1') {
                            $('#' + event.target.id).removeClass('discourage').addClass('recommend').html('Recommend');
                            $('#' + event.target.id + "_count").html(parseInt($('#' + event.target.id + "_count").html()) - 1);
                        }
                        else {
                            $('#' + event.target.id).html(result);
                        }
                    });
                }
                else {
                    $.post("<?php echo SITE_URL; ?>/process.php", {
                        "recommend": '',
                        "cid": event.target.id
                    }, function(result) {
                        if (result == '1') {
                            $('#' + event.target.id).removeClass('recommend').addClass('discourage').html('Discourage');
                            $('#' + event.target.id + "_count").html(parseInt($('#' + event.target.id + "_count").html()) + 1);

                        } else {
                            $('#' + event.target.id).html(result);
                        }
                    });
                }
            });
        });
    </script>
    <div class='row'>
        <div class='span4'>
            <div class='palette palette-firm-dark' style='box-shadow: 0 0 10px #aaa;'>
                <center><h4>User Class : <?php echo $class[$_SESSION['user']['accesslevel']]; ?></h4></center>
            </div>
            <div class='palette palette-firm' style='border-radius: 0 0 10px 10px; box-shadow: 0 0 10px #aaa;'>
                <h5>Account Details</h5>
                <hr/>
                <table>
                    <tr><td class="bold" width='60px'>IP</td><td> <?php echo $_SESSION['user']['ip']; ?></td></tr>
                    <tr><td class="bold">Nick1</td><td> <?php echo $_SESSION['user']['nick']; ?></td></tr>
                    <?php if (isset($_SESSION['user']['nick2'])) { ?>
                        <tr><td class="bold">Nick2</td><td> <?php
                                echo $_SESSION['user']['nick2'] . "<br/>";
                            }
                            ?>
                    <tr><td class="bold">Name</td><td> <?php echo $_SESSION['user']['name']; ?></td></tr>
                    <tr><td class="bold">Roll</td><td> <?php echo $_SESSION['user']['roll']; ?></td></tr>
                    <tr><td class="bold">Email</td><td> <?php echo $_SESSION['user']['email']; ?></td></tr>
                    <tr><td class="bold">Branch</td><td> <?php echo $_SESSION['user']['branch']; ?></td></tr>
                    <tr><td class="bold">Hostel</td><td> <?php echo $_SESSION['user']['hostel']; ?></td></tr>
                    <tr><td class="bold">Room</td><td> <?php echo $_SESSION['user']['room']; ?></td></tr>
                </table><br/>
                <?php if($_SESSION['user']['accesslevel'] >= 6) { ?>
                <h5>My Groups</h5>
                <hr/>
                <ul class='nav nav-list grp'>
                    <?php
                    foreach ($_SESSION['user']['groups'] as $row) {
                        echo "<li><a href='" . SITE_URL . "/groups/$row'>$row</a></li>";
                    }
                    ?>
                    <li><a href="<?php echo SITE_URL; ?>/groups">See All</a></li>
                </ul>
                <?php } ?>
            </div>
        </div>
        <div class='span8'>
            <?php
                if($_SESSION['user']['accesslevel'] == 0){
                    echo "<div class='alert' style='text-align: center; margin-top: 10px;'>You are not an authenticated user. IP and chat facilities are restricted.<br/> <a href='".SITE_URL."/account'>Click Here</a> to authenticate yourself.</div>";
                }
            ?>
            <h3>Share</h3>
            <form class='form-horizontal' action="<?php echo SITE_URL; ?>/process.php" method="post">
                <div class='control-group'>
                    <div class='control-label'><label for='filename'>File Name / Magnet Link</label></div>
                    <div class='controls'><input type='text' style='width:97%;' name='data[title]' id='filename' /></div>
                </div>
                <div class='control-group'>
                    <div class='control-label'><label for='tagsinput'>Tags</label></div>
                    <div class='controls'>
                        <input name="data[tag]" style='width: 100%;' id="tagsinput" class="tagsinput"/><br/>
                        <?php
                        foreach ($categories as $key => $value) {
                            if ($value != '') {
                                ?>
                                <label class="checkbox" for="<?php echo $value; ?>">
                                    <input type="checkbox" name='data[<?php echo $value; ?>]' value="<?php echo $value; ?>" id="<?php echo $value; ?>">
                                    <?php echo $key; ?>
                                </label>
                                <?php
                            }
                        }
                        ?>
                    </div>
                </div>
                <div class='control-group'>
                    <div class='control-label'></div>
                    <div class='controls'><input type="submit" value="Share" name="share" class="btn"/></div>
                </div>
            </form>
            <h3>Shared Contents</h3>
            <?php
            $body = "from dchub_content where deleted = 0 and uid = " . $_SESSION['user']['id'] . " order by timestamp desc";
            $res = DB::findAllWithCount("select *", $body, $page, 10);
            $data = $res['data'];
            contentshow($data,'',FALSE);
            pagination($res['noofpages'], SITE_URL . "/", $page, 10);
            ?>
        </div>
    </div>
    <?php
} else {
    redirectTo(SITE_URL);
}
?>