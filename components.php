<?php

function head() { ?>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <title>DC Hub</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="css/bootstrap.css" rel="stylesheet">
    <link href="css/flat-ui.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
    <script type="text/javascript" src="js/jquery.js"></script>
    <script type="text/javascript" src="js/bootstrap.js"></script>
    <script type="text/javascript" src="js/plugin.js"></script>
    <script type="text/javascript" src="js/scripts.js"></script>
    <script src="js/jquery.tagsinput.js"></script>
    <script src="js/jquery.placeholder.js"></script>
    <script src="js/application.js"></script>
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
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            Pages
                            <b class="caret"></b>
                        </a>
                        <ul class="dropdown-menu">
                            <li><a href="hot">HOT Page</a></li>
                            <li><a href="request">Request Page</a></li>
                            <li><a href="hof">Hall of Fame</a></li>
                        </ul>
                    </li>
                    <li><a href="#">FAQ</a></li>
                </ul>
                <a class="btn btn-large btn-danger pull-right" href="register">Create Account</a>
            </div>
        </div>
    </div>
<?php }

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
<?php }
?>