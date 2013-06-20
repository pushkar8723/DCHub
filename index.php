<?php include 'config.php'; ?>
<!DOCTYPE html>
<html>
    <head>
        <?php head(); ?>
    </head>
    <body>
        <?php navbar();?>
        <div class="container" style="text-align: justify;">
            <?php
            if (isset($_SESSION['msg'])) {
                echo '<div class="alert alert-block"><button type="button" class="close" data-dismiss="alert">&times;</button>' . $_SESSION['msg'] . '</div>';
                unset($_SESSION['msg']);
            }
            if ((!isset($_GET['tab']) || $_GET['tab'] == '') && !isset($_SESSION['loggedin'])) {
                ?>
                <div class="row" style='padding-top: 10px;'>
                    <div class="span8">
                        <div class="todo mrm">
                            <div class="todo-search">
                                <span style="font-size: 16px;">What's New on DC</span>
                            </div>
                            <ul style="height: 300px; overflow-y: auto;">
                                <?php
                                $query = "SELECT * FROM dchub_content where deleted = 0 order by timestamp desc LIMIT 0 , 10";
                                $result = DB::findAllFromQuery($query);
                                foreach ($result as $row) {
                                    ?>
                                    <li>
                                        <div class="todo-icon fui-man-24"></div>
                                        <div class="todo-content">
                                            <h4 class="todo-name">
                                                <strong><?php echo "<a href='".(($row['magnetlink'] != '')?($row['magnetlink']):("#"))."'>".stripslashes($row['title'])."</a>" ?></strong>
                                            </h4>
                                            by <strong>
                                                <?php
                                                $query = "SELECT nick1 FROM dchub_users where id = " . $row['uid'];
                                                $user = DB::findOneFromQuery($query);
                                                echo $user['nick1'];
                                                ?>
                                            </strong>
                                        </div>
                                    </li>
                                <?php } ?>
                            </ul>
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
                <div class="row">
                    <div class="span3">
                        <div class="palette palette-firm">
                            <strong>Groups</strong>
                        </div>
                        <div class="palette palette-night" style="height: 100px;">
                            Users of DC Hub are divided in various branches and years.
                            Any user can convey message to a particular group after approval from group moderator. 
                        </div>
                    </div>
                    <div class="span3">
                        <div class="palette palette-firm">
                            <strong>Request Page</strong>
                        </div>
                        <div class="palette palette-night" style="height: 100px;">
                            On request page anyone can make a request for some content.
                            Remaining users can volunteer to download and share it.

                        </div>
                    </div>
                    <div class="span3">
                        <div class="palette palette-firm">
                            <strong>HOT Page</strong>
                        </div>
                        <div class="palette palette-night" style="height: 100px;">
                            Like button has been added to latest content. 
                            Contents with largest no of like in past 24 hr makes it to HOT page.
                        </div>
                    </div>
                    <div class="span3">
                        <div class="palette palette-firm">
                            <strong>Hall of Fame</strong>
                        </div>
                        <div class="palette palette-night" style="height: 100px;">
                            Users who contribute to make DC a better place will end up here.
                            And there names will remain here till eternity!!!
                        </div>
                    </div>
                </div>
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