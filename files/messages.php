
<script type='text/javascript'>
    $(document).ready(function() {
        $('#msgnav').removeClass('active');
        $('#optionsRadios1').change(function() {
            $('#sendtoone').show();
            $('#sendtomany').hide();
        });
        $('#optionsRadios2').change(function() {
            $('#sendtoone').hide();
            $('#sendtomany').show();
        });
        $('#optionsRadios3').change(function() {
            $('#sendtoone').hide();
            $('#sendtomany').hide();
        });
        $('#composemsg').click(function() {
            if ($('#composemsg').html() == 'Compose Message')
                $('#composemsg').html('Discart Message');
            else
                $('#composemsg').html('Compose Message');
            $('#cmpmsg').slideToggle();
            $('#offmsg').slideToggle();
        });
    });
</script>
<?php
if (!isset($_SESSION['loggedin'])) {
    echo "<br/><br/><br/><h1>Not Logged in :(</h1>You need to login to access this page.<br/><br/><br/>";
    return;
}
$_GET = secure($_GET);
?>
<h1>Offline Messages</h1>
<?php
if ($_SESSION['user']['accesslevel'] > 0) {
    $branch = DB::findAllFromQuery("select * from dchub_branch");
    ?>
    <a href='#' id='composemsg' class='btn btn-large btn-danger'>Compose Message</a><br/><br/>
    <div id='cmpmsg' style='display: none;'>
        <form class='form-horizontal' method='post' action='<?php echo SITE_URL; ?>/process.php'>
            <div class='control-group'>
                <div class='control-label'><label>Message:</label></div>
                <div class='controls'>
                    <textarea id='msg' name='data[msg]'></textarea>
                </div>
            </div>

            <div class='control-group'>
                <div class='control-label'><label>To:</label></div>
                <div class='controls'>
                    <label class='radio'>
                        <input type="radio" name="data[select]" id="optionsRadios1" value="one" checked="">
                        Send to One
                    </label>
                    <label class='radio'>
                        <input type="radio" name="data[select]" id="optionsRadios2" value="many">
                        Send to Many
                    </label>
                    <label class='radio'>
                        <input type="radio" name="data[select]" id="optionsRadios3" value="everybody">
                        Send to Everybody
                    </label>
                    <div id='sendtoone'>
                        <input type='text' name='data[to]' placeholder='Enter Nick here'/>
                    </div>
                    <div id ='sendtomany' class='row-fluid'  style='display: none;'>
                        <div class='span4'>
                            <h5>Branch</h5>
                            <?php
                            foreach ($branch as $value) {
                                echo "<label class='checkbox' for='$value[branch]'>
                                            <input type='checkbox' value='$value[branch]' id='$value[branch]' name='branch[$value[branch]]'>
                                            $value[branch]
                                          </label>";
                            }
                            ?>
                        </div>
                        <div class='span4'>
                            <h5>Year</h5>
                            <label class='checkbox' for='2k10'>
                                <input type='checkbox' value='2k10' id='2k10' name='batch[2k10]'>
                                2k10
                            </label>
                            <label class='checkbox' for='2k11'>
                                <input type='checkbox' value='2k11' id='2k11' name='batch[2k11]'>
                                2k11
                            </label>
                            <label class='checkbox' for='2k12'>
                                <input type='checkbox' value='2k12' id='2k12' name='batch[2k12]'>
                                2k12
                            </label>
                            <label class='checkbox' for='2k13'>
                                <input type='checkbox' value='2k13' id='2k13' name='batch[2k13]'>
                                2k13
                            </label>
                        </div>
                        <div class='span4'>
                            <h5>Hostel</h5>
                            <?php
                            for ($i = 1; $i <= 13; $i++) {
                                echo "<label class='checkbox' for='H-$i'>
                                    <input type='checkbox' value='H-$i' id='H-$i' name='hostel[H-$i]'>
                                    H-$i
                                </label>";
                            }
                            ?>
                            <label class='checkbox' for='H-RS'>
                                <input type='checkbox' value='H-RS' id='H-RS' name='hostel[H-RS]'>
                                H-RS
                            </label>
                        </div>
                    </div>

                </div>
            </div>



            <div class='control-group'>
                <div class='control-label'></div>
                <div class='controls'><input type='submit' name='composemsg' value='Send' class='btn btn-large'/></div>
            </div>
        </form>
    </div>
    <?php
}
?>
<div id='offmsg'>
    <div style="width: 30%; height: 450px; float: left; overflow-y: auto; background: #f5f5f5; border-right: 1px solid #eee;">
        <ul class="nav nav-list">
            <?php
            $query = "select distinct(fromid) as fromid from dchub_message where id > '" . $_SESSION['user']['lastmsgid'] . "' and toid = " . $_SESSION['user']['id'] . "
            union
            select distinct(toid) as fromid from dchub_message where id > '" . $_SESSION['user']['lastmsgid'] . "' and fromid = " . $_SESSION['user']['id'];
            $res = DB::findAllFromQuery($query);
            foreach ($res as $row) {
                $user = DB::findOneFromQuery("select nick1 from dchub_users where id = $row[fromid]");
                echo "<li " . ((isset($_GET['code']) && $_GET['code'] == "$user[nick1]") ? ("class='active'") : ("")) . "><a href='" . SITE_URL . "/messages/$user[nick1]'>$user[nick1]</a></li>";
            }
            $query = "select max(id) as maxid from dchub_message where fromid = " . $_SESSION['user']['id'] . " or toid = " . $_SESSION['user']['id'];
            $id = DB::findOneFromQuery($query);
            $_SESSION['user']['msgid'] = $id['maxid'];
            DB::update('dchub_users', array('lastmsgid' => $id['maxid']), 'id = ' . $_SESSION['user']['id']);
            ?>
        </ul>
    </div>
    <div style="width: 69%; height: 450px; margin-left: 30%;  overflow-y: auto; padding-left: 5px;">
        <?php
        if (isset($_GET['code'])) {
            $fromid = DB::findOneFromQuery("select id from dchub_users where nick1 = '$_GET[code]'");
            $toid = $_SESSION['user']['id'];
            $query = "select * from dchub_message where id > '" . $_SESSION['user']['lastmsgid'] . "' and (fromid = $fromid[id] and toid = $toid) or (fromid = $toid and toid = $fromid[id])";
            $res = DB::findAllFromQuery($query);
            foreach ($res as $row) {
                $row['msg'] = preg_replace('/\n/', '<br/>', htmlspecialchars(stripslashes($row['msg'])));
                if ($row['fromid'] == $fromid['id'])
                    echo "<b><a href='" . SITE_URL . "/users/$_GET[code]'>$_GET[code]</a></b><div class='pull-right'>$row[createdOn]</div><br/>$row[msg]<hr/>";
                else
                    echo "<b>Me</b><div class='pull-right'>$row[createdOn]</div><br/>$row[msg]<hr/>";
            }
            ?>
            <form action="<?php echo SITE_URL; ?>/process.php" method="post">
                <input type="hidden" name="data[to]" value="<?php echo $_GET['code']; ?>" />
                <textarea name='data[msg]'></textarea><br/>
                <input type='submit' class='btn' value='Reply' name='messagepost' />
            </form>
            <?php
        } else {
            echo "<div style='text-align:center;margin-top: 175px;'><h3>Select a user to show messages.</h3></div>";
        }
        ?>
    </div>
</div>
