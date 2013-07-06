<?php
if (!isset($_SESSION['loggedin'])) {
    echo "<br/><br/><br/><h1>Not Logged in :(</h1>You need to login to access this page.<br/><br/><br/>";
    return;
}
if ($_SESSION['user']['accesslevel'] < 4) {
    echo "<br/><br/><br/><h1>Permission Denied :(</h1>You don't have enough previleges.<br/><br/><br/>";
    return;
}
$query = "select * from dchub_post where deleted = 0 and approvedby = 0";
?>
<h1>Manage Mass Message</h1>
<?php
$res = DB::findAllFromQuery($query);
if ($res) {
    foreach ($res as $row) {
        $group = DB::findOneFromQuery("select name from dchub_groups where id = $row[gid]");
        echo "<div class='accesslevel'>";
        $row['post'] = preg_replace('/\n/', '<br/>', htmlspecialchars($row['post']));
        echo "<div><h4><a href='" . SITE_URL . "/users/$row[postby]'>$row[postby]</a> <i class='icon-chevron-right'></i> $group[name]</h4></div><div class='post'>$row[post]</div><br/>
                        <form class='form-inline pull-left' style='margin:5px;' action='" . SITE_URL . "/process.php' method='post'>
                <input type='hidden' value='$row[id]' name='id'>
                <input type='submit' value='Approve' name='approve' class='btn btn-danger'/>
                </form>
                <form class='form-inline' style='margin:5px;' action='" . SITE_URL . "/process.php' method='post'>
                <input type='hidden' value='$row[id]' name='id'>
                <input type='submit' value='Decline' name='decline' class='btn btn-danger'/>
                </form>";
        echo "</div>";
    }
}
?>