<?php
include 'config.php';
$_SESSION['url'] = $_SERVER['REQUEST_URI'];
?>
<!DOCTYPE html>
<html>
    <head>
        <?php head(); ?>
    </head>
    <body>
        <?php navbar(); ?>
        <div class="container" style="text-align: justify;">
            <?php
            if (isset($_SESSION['msg'])) {
                echo '<div id="sesmsg" class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">&times;</button>' . $_SESSION['msg'] . '</div>';
                unset($_SESSION['msg']);
            }
            if ((!isset($_GET['tab']) || $_GET['tab'] == '') && !isset($_SESSION['loggedin'])) {
                ?>
                <div class="row" style='padding-top: 10px;'>
                    <div class="span8" >
                        <div class="palette palette-firm">
                            <h3>What's new on DC?</h3>
                        </div>
                        <div class="palette palette-night" style='max-height: 280px; overflow-y: auto; padding: 0;'>
                            <?php
                            $query = "SELECT * FROM dchub_content where deleted = 0 order by timestamp desc LIMIT 0 , 10";
                            $result = DB::findAllFromQuery($query);
                            foreach ($result as $row) {
                                $row['title'] = stripcslashes($row['title']);
                                $user = DB::findOneFromQuery("select nick1  from dchub_users where id = $row[uid]");
                                echo "<div class='newondc'><span class='head'>".(($row['magnetlink'] != "") ? ("<a href='$row[magnetlink]'>$row[title]</a>") : ($row['title'])) . "</span><br/>by <a href='" . SITE_URL . "/users/$user[nick1]'>$user[nick1]</a></div>";
                            }
                            ?>
                        </div>
                    </div>
                    <div class="span4">
                        <div class="palette palette-firm">
                            <h3>Sign In</h3>
                        </div>
                        <div class="palette palette-night">
                            <form action="<?php echo SITE_URL; ?>/process.php" method="post">
                                <label for="username">Username:</label>
                                <input id='username' type="text" name="data[username]" style="width:95%"/><br/><br/>
                                <label for="password">Password:</label>
                                <input id="password" type="password" name="data[password]" style="width:95%"/><br/>
                                <input class="btn btn-danger" type="submit" value="Sign In" name ="login"/> <br/><br/>
                                <a href="#" style="color: #fff">Forgot Password?</a>
                            </form>
                        </div>
                    </div>
                </div>
                <hr>
                <h3>New Features</h3>
                <h5>Fill this area</h5>
                <?php
            } else if (isset($_SESSION['loggedin']) && (!isset($_GET['tab']) || $_GET['tab'] == '')) {
                require_once 'files/home.php';
            } else if (file_exists('files/' . $_GET['tab'] . '.php')) {
                require_once 'files/' . $_GET['tab'] . '.php';
            } else {
                echo "<br/><br/><br/><h1>Page not found</h1>The page you are searching for doesn't exsits.<br/><br/><br/>";
            }
            footer();
            ?>
        </div>
    </body>
</html>