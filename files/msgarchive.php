<h1>Message Archive</h1>
<?php
if (isset($_SESSION['loggedin'])) {
    if (isset($_GET['page']) && $_GET['page'] > 0) {
        $page = $_GET['page'];
    } else {
        $page = 1;
    }
    $_GET = secure($_GET);
    $query = "select distinct(fromnick) as fromnick from msgarchive where tonick in ('" . $_SESSION['user']['nick'] . "' " . ((isset($_SESSION['user']['nick2'])) ? (" or tonick = '" . $_SESSION['user']['nick2'] . "'") : ("")) . ")
            union
            select distinct(tonick) as fromnick from msgarchive where fromnick in ('" . $_SESSION['user']['nick'] . "'" . ((isset($_SESSION['user']['nick2'])) ? (" or fromnick = '" . $_SESSION['user']['nick2']. "'") : ("")).")";
    ?>
    <div id = 'offmsg'>
        <div style = "width: 30%; height: 450px; float: left; overflow-y: auto; background: #f5f5f5; border-right: 1px solid #eee;">
            <ul class = "nav nav-list">
                <?php
                $res = DB::findAllFromQuery($query);
                foreach ($res as $row) {
                    echo "<li " . ((isset($_GET['code']) && $_GET['code'] == "$row[fromnick]") ? ("class='active'") : ("")) . "><a href='" . SITE_URL . "/msgarchive/$row[fromnick]'>$row[fromnick]</a></li>";
                }
                ?>
            </ul>
        </div>
        <div style="width: 69%; height: 450px; margin-left: 30%;  overflow-y: auto; padding-left: 5px;">
            <?php
            if (isset($_GET['code'])) {
                $nickuser = $_SESSION['user']['nick'] . ((isset($_SESSION['user']['nick2'])) ? ("','" . $_SESSION['user']['nick2']) : (""));
                $nickuserfriend = $_GET['code'];
                $query = "select * from msgarchive where (fromnick in ('$nickuser') and tonick = '$nickuserfriend') or (fromnick = '$nickuserfriend' and tonick in ('$nickuser')) order by createdOn";
                $res = DB::findAllFromQuery($query);
                foreach ($res as $row) {
                    $row['msg'] = preg_replace('/\n/', '<br/>', htmlspecialchars(stripslashes($row['msg'])));
                    if ($row['fromnick'] == $nickuserfriend)
                        echo "<b><a href='" . SITE_URL . "/users/$nickuserfriend'>$nickuserfriend</a></b><div class='pull-right'>$row[createdOn]</div><br/>$row[msg]<hr/>";
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
    <?php
}
?>