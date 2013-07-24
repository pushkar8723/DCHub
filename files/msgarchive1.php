<?php
if (isset($_SESSION['loggedin'])) {
    if (isset($_GET['page']) && $_GET['page'] > 0) {
        $page = $_GET['page'];
    } else {
        $page = 1;
    }
    $_GET = secure($_GET);
    $query = "select tonick, fromnick from msgarchive where (fromnick ='".$_SESSION['user']['nick']."'".((isset($_SESSION['user']['nick2']))?(" or fromnick ='".$_SESSION['user']['nick2']."'"):(""))." ) or (tonick ='".$_SESSION['user']['nick']."'".((isset($_SESSION['user']['nick2']))?(" or tonick ='".$_SESSION['user']['nick2']."'"):(""))." )";
    ?>
    <script type="text/javascript">
        function som(friend, page) {
            if(page != 1){
                $('#msgloader').html("Loading...");
                $.post("<?php echo SITE_URL; ?>/process.php", {
                    "som":"",
                    "page": page,
                    "code": friend
                }, function(data) {
                    $('#msgloader').replaceWith(data);
                });
            } else {
                $('#initialLoader').html("Loading...");
                $.post("<?php echo SITE_URL; ?>/process.php", {
                    "som":"",
                    "page": page,
                    "code": friend
                }, function(data) {
                    $('#initialLoader').html(data);
                });
            }
        }
    </script>
    <h1>Message Archive</h1>
    <div id = 'offmsg'>
        <div style = "width: 30%; height: 450px; float: left; overflow-y: auto; background: #f5f5f5; border-right: 1px solid #eee;">
            <ul class = "nav nav-list">
                <?php
                $res = DB::findAllFromQuery($query);
                $nickarray = array();
                foreach ($res as $row) {
                    array_push($nickarray, $row['tonick']);
                    array_push($nickarray, $row['fromnick']);
                }
                $nickarray = array_unique($nickarray);
                sort($nickarray);
                foreach($nickarray as $row){
                    echo "<li ". ((isset($_GET['code']) && $_GET['code'] == $row) ? ("class='active'") : (""))."><a href='#' onclick=\"som('$row', 1)\">$row</a></li>";
                }
                ?>
            </ul>
        </div>
        <div id ='initialLoader' style="width: 69%; height: 450px; margin-left: 30%;  overflow-y: auto; padding-left: 5px;">
            <div style='text-align:center;margin-top: 175px;'><h3>Select a user to show messages.</h3></div>
        </div>
    </div>
    <?php
} else {
    echo "<br/><br/><br/><h1>Not Logged in :(</h1>You need to be logged in to access this page.<br/><br/><br/>";
}
?>
