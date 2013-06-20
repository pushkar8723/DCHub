<?php
if(isset($_SESSION['loggedin'])) {
$class = array('Novice', 'Experienced', 'Famous', 'Moderator', 'Pseudo-admin', 'GD Admin', 'Operator', 'Master', 'Cheef', 'Admin');
if(isset($_GET['page']) && $_GET['page'] > 0){
    $page = $_GET['page'];
} else {
    $page = 1;
}
?>
<div class='row'>
    <div class='span4'>
        <div class='palette palette-firm-dark' style='border-radius: 0 0 10px 10px; box-shadow: 0 0 10px #aaa;'>
            <center><h4>User Class : <?php echo $class[$_SESSION['user']['accesslevel'] - 1]; ?></h4></center>
            <br/>
            <h5>Account Details</h5>
            <hr/>
            <table>
                <tr><th width='60px'>IP</th><td> <?php echo $_SESSION['user']['ip']; ?></td></tr>
                <tr><th>Nick1</th><td> <?php echo $_SESSION['user']['nick']; ?></td></tr>
                <?php if (isset($_SESSION['user']['nick2'])) { ?>
                    <tr><th>Nick2</th><td> <?php echo $_SESSION['user']['nick2'] . "<br/>";
            }
                ?>
                <tr><th>Name</th><td> <?php echo $_SESSION['user']['name']; ?></td></tr>
                <tr><th>Roll</th><td> <?php echo $_SESSION['user']['roll']; ?></td></tr>
                <tr><th>Email</th><td> <?php echo $_SESSION['user']['email']; ?></td></tr>
                <tr><th>Branch</th><td> <?php echo $_SESSION['user']['branch']; ?></td></tr>
                <tr><th>Hostel</th><td> <?php echo $_SESSION['user']['hostel']; ?></td></tr>
                <tr><th>Room</th><td> <?php echo $_SESSION['user']['room']; ?></td></tr>
            </table>
        </div>
    </div>
    <div class='span8'>
        <h3>Share</h3>
        <form class='form-horizontal' action="<?php echo SITE_URL; ?>/process.php" method="post">
            <div class='control-group'>
                <div class='control-label'><label for='filename'>File Name(required)</label></div>
                <div class='controls'><input type='text' style='width:100%;' name='data[title]' id='filename' /></div>
            </div>
            <div class='control-group'>
                <div class='control-label'><label for='magnet'>Magnet Link</label></div>
                <div class='controls'><input type='text' style='width:100%;' name='data[magnetlink]' id='magnet' /></div>
            </div>
            <div class='control-group'>
                <div class='control-label'><label for='tagsinput'>Tags (required)</label></div>
                <div class='controls'><input name="data[tag]" style='width: 100%;' id="tagsinput" class="tagsinput" /></div>
            </div>
            <div class='control-group'>
                <div class='control-label'></div>
                <div class='controls'><input type="submit" value="Share" name="share" class="btn"/></div>
            </div>
        </form>
        <h3>Shared Contents</h3>
        <?php 
            $body = "from dchub_content where deleted = 0 and uid = ".$_SESSION['user']['id']." order by timestamp desc";
            $res = DB::findAllWithCount("select *", $body, $page, 10);
            $data = $res['data'];
            echo "<table class='table table-striped'>
                    <tr><th>File Name</th><th>Tags</th></tr>";
            foreach ($data as $row){
                echo "<tr><td><a href='".(($row['magnetlink'] !="")?("$row[magnetlink]"):("#"))."'>".stripslashes($row['title'])."</a></td><td>$row[tag]</td></tr>";
            }
            echo "</table>";
            pagination($res['noofpages'], SITE_URL."/", $page, 10);
        ?>
    </div>
</div>
<?php } else {
     redirectTo(SITE_URL);
}
?>