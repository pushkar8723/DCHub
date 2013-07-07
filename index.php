<?php
include 'config.php';
$_SESSION['url'] = $_SERVER['REQUEST_URI'];
if(isset($_SESSION['loggedin']) && $_SESSION['user']['accesslevel'] == 0){
    $update = DB::findOneFromQuery("select class, friend from dchub_users where id=".$_SESSION['user']['id']);
    $_SESSION['user']['accesslevel'] = $update['class'];
}
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
                <div class="palette palette-firm" style="margin-top: 10px;">
                    <h3>What's new on DC?</h3>
                </div>
                <div class="palette palette-night" style='overflow-y: auto; padding: 0;'>
                    <?php
                    $query = "SELECT * FROM dchub_content where deleted = 0 order by timestamp desc LIMIT 0 , 10";
                    $result = DB::findAllFromQuery($query);
					if($result){
						foreach ($result as $row) {
							$row['title'] = stripcslashes($row['title']);
							$user = DB::findOneFromQuery("select nick1  from dchub_users where id = $row[uid]");
							echo "<div class='newondc'><span class='head'>" . (($row['magnetlink'] != "") ? ("<a href='$row[magnetlink]'>$row[title]</a>") : ($row['title'])) . "</span><br/>by <a href='" . SITE_URL . "/users/$user[nick1]'>$user[nick1]</a></div>";
						}
					} else {
						echo "<br/><br/><br/><center><h1>No Shares Till now :(</h1></center><br/><br/><br/>";
					}
                    ?>
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