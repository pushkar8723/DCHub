<?php
if (!isset($_SESSION['loggedin']) || $_SESSION['user']['accesslevel'] < 9) {
    echo "<br/><br/><br/><h1>Permission Denied :(</h1>You don't have enough previledges.<br/><br/><br/>";
    return;
}
?>
<h1>MotD</h1>
<form method='post' action='<?php echo SITE_URL; ?>/process.php'>
    <textarea name='motdcontent' style='width: 600px; height: 400px;'><?php echo stripslashes(file_get_contents($motdfile)); ?></textarea><br/>
    <input type='submit' class='btn btn-large' value='Update motd' name='motdupdate'/>
</form>