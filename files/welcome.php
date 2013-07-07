<?php
if (isset($_SESSION['loggedin'])) {
    ?>
    <center>
        <h1>Welcome to DC Hub</h1>
        <h3>BIT's Official Direct Connect Hub</h3>
    </center>
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
