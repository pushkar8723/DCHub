<?php
if (true or isset($_SESSION['loggedin'])) {
    ?>
    <center>
        <h1 style='margin: 30px; color:rgb(26, 188, 156);'>You have successfully Registered!</h1>
        <h4>You can now log onto the Hub right away with <u>0GB share</u> and <u>without authenticating yourself</u>.</h4>
        <br/>
    </center>
    <br/>
    Just Press <b>Ctrl+Q</b> on your DC Software and Enter the IP <b>172.16.32.222</b>.<br/>
   <br/> However your IP and chat facilities will be restricted until you: <br /><br />
    1) Authenticate yourself. To do that, visit <a href='<?php echo SITE_URL; ?>/account'><?php echo SITE_URL; ?>/account</a><br/>
    2) AND have a minimum share of 20GB.
<br /><br />
    <b>New to DC? (aka If you're a <u>First Year</u>):</b>
    Follow our step-by-step setup guide : <a href='<?php echo SITE_URL; ?>/info'>Hub Info</a><br/>
    <br/>
    Having difficulties connecting to DC Hub? You may find a Solution <a href='<?php echo SITE_URL; ?>/frequent'>here</a>.<br/>
    <br/>
     Still cannot figure out what is wrong? <a href='<? php echo SITE_URL; ?>/complaints'>Contact an Admin.</a><br/>
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
