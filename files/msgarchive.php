<?php
if (isset($_SESSION['loggedin'])) {
    if (isset($_GET['page']) && $_GET['page'] > 0) {
        $page = $_GET['page'];
    } else {
        $page = 1;
    }
    $_GET = secure($_GET);
    $query = "select * from (SELECT distinct(fromnick) as nick FROM `msgarchive` where (tonick ='".$_SESSION['user']['nick']."'".((isset($_SESSION['user']['nick2']))?(" or tonick ='".$_SESSION['user']['nick2']."'"):(""))." )
                union
              SELECT distinct(tonick) as nick FROM `msgarchive` where (fromnick ='".$_SESSION['user']['nick']."'".((isset($_SESSION['user']['nick2']))?(" or fromnick ='".$_SESSION['user']['nick2']."'"):(""))." ))t order by nick";
    ?>
    <script type="text/javascript">
        function som(page) {
            $.post("<?php echo SITE_URL; ?>/process.php", {
                "som":"",
                "page": page,
                "code": '<?php echo $_GET['code']; ?>'
            }, function(data) {
                $('#msgloader').replaceWith(data);
            });
        }
    </script>
    <h1>Message Archive</h1>
    <div id = 'offmsg'>
        <div style = "width: 30%; height: 450px; float: left; overflow-y: auto; background: #f5f5f5; border-right: 1px solid #eee;">
            <ul class = "nav nav-list">
                <?php
                $res = DB::findAllFromQuery($query);
                foreach ($res as $row) {
                    echo "<li " . ((isset($_GET['code']) && $_GET['code'] == "$row[nick]") ? ("class='active'") : ("")) . "><a href='" . SITE_URL . "/msgarchive/".  urlencode(urlencode($row['nick']))."'>$row[nick]</a></li>";
                }
                ?>
            </ul>
        </div>
        <div style="width: 69%; height: 450px; margin-left: 30%;  overflow-y: auto; padding-left: 5px;">
            <?php
            if (isset($_GET['code'])) {
                $nickuser = $_SESSION['user']['nick'] . ((isset($_SESSION['user']['nick2'])) ? ("','" . $_SESSION['user']['nick2']) : (""));
                $nickuserfriend = $_GET['code'];
                $body = "from msgarchive where (fromnick in ('$nickuser') and tonick = '$nickuserfriend') or (fromnick = '$nickuserfriend' and tonick in ('$nickuser')) order by createdOn desc";
                $res = DB::findAllWithCount("select *", $body, $page, 5);
                $i = count($res['data']);
                if ($res['noofpages'] > 1) {
                    echo "<div id='msgloader'><center><a href='#' onclick='som(2)' >Show older messages</a></center></div>";
                }
                for (; $i > 0; $i--) {
                    $row = $res['data'][$i - 1];
                    $row['msg'] = preg_replace('/\n/', '<br/>', htmlspecialchars(stripslashes($row['msg'])));
                    if ($row['fromnick'] == $nickuserfriend)
                        echo "<b><a href='" . SITE_URL . "/users/$nickuserfriend'>$nickuserfriend</a></b><div class='pull-right'>$row[createdOn]</div><br/>$row[msg]<hr/>";
                    else
                        echo "<b>Me</b><div class='pull-right'>$row[createdOn]</div><br/>$row[msg]<hr/>";
                }
            } else {
                echo "<div style='text-align:center;margin-top: 175px;'><h3>Select a user to show messages.</h3></div>";
            }
            ?>
        </div>
    </div>
    <?php
} else {
    echo "<br/><br/><br/><h1>Not Logged in :(</h1>You need to be logged in to access this page.<br/><br/><br/>";
}
?>
