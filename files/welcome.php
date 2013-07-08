<?php
if (isset($_SESSION['loggedin'])) {
    ?>
    <center>
        <h1 style='margin: 30px; color:rgb(26, 188, 156);'>You have successfully Registered!</h1>
        <h4>You can now log onto the Hub right away with 0 GB share and without authentication.</h4>
        However your IP and chat facilities will be restricted until you authenticate yourself.
        <br/>
    </center>
    <br/>
    To Authenticate yourself visit : <a href='<?php echo SITE_URL; ?>/account'><?php echo SITE_URL; ?>/account</a><br/>
    <b>New to DC?</b>
    Follow our step-by-step setup guide : <a href='<?php echo SITE_URL; ?>/info'>Hub Info</a><br/>
    <br/>
    Having difficulties connecting to DC HUB. You may find your solution <a href='<?php echo SITE_URL; ?>/complaints'>here</a>.<br/>
    <br/>
    <b>Enjoy the new features introduced this year :</b><br/>
    <ol>
        <li><a href='<?php echo SITE_URL; ?>/recommend'>Recommendation Page</a></li>
        <li><a href='<?php echo SITE_URL; ?>/request'>Request Page</a></li>
        <li><a href='<?php echo SITE_URL; ?>/message'>Offline Messaging</a></li>
        <li><a href='<?php echo SITE_URL; ?>/hot'>HOT Page</a></li>
    </ol>
<?php } else {
    ?>
    <br/><br/><br/><br/><br/>
    <center>
        <h1>Welcome to DC Hub</h1>
        <h3>BIT's Official Direct Connect Hub</h3>
        <a href='<?php echo SITE_URL; ?>/register'>Register Here</a> and start Downloading.
    </center>
    <br/><br/><br/><br/><br/>
    <?php
}
?>
