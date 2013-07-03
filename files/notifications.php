<script type='text/javascript'>
    $(document).ready(function(){
        $('#notnav').removeClass('active');
    });
</script>
<?php if (!isset($_SESSION['loggedin'])) redirectTo(SITE_URL); 
$usrgrp = "'" . implode("','", $_SESSION['user']['groups']) . "'";
$query = "select * from dchub_post where deleted=0 and approvedby!=0 and id > '" . $_SESSION['user']['lastnotificationid'] . "' and gid in 
(select id from dchub_groups where name in ($usrgrp))    
order by timestamp desc";
$res = DB::findAllFromQuery($query);
if ($res) {
    echo "<h1>Notifications</h1>";
    $_SESSION['user']['notificationid'] = $res[0]['id'];
    foreach ($res as $row) {
        $group = DB::findOneFromQuery("select name from dchub_groups where id = $row[gid]");
        $row['post'] = preg_replace('/\n/', '<br/>', htmlspecialchars(stripslashes($row['post'])));
        echo "<div style='border-bottom: 1px solid #ddd;'><b><a href='" . SITE_URL . "/users/$row[postby]'>$row[postby]</a> <i class='icon-chevron-right'></i> <a href='" . SITE_URL . "/groups/$group[name]'>$group[name]</a></b><br/><span class='postdate'>" . date('M d, h:i a', $row['timestamp']) . "</span></div>$row[post]<br/>";
        if (isset($_SESSION['loggedin']) && $_SESSION['user']['accesslevel'] >= 6) {
            $app = DB::findOneFromQuery("select nick1 from dchub_users where id=$row[approvedby]");
            echo "Approved by : <a href='" . SITE_URL . "/users/$app[nick1]'>$app[nick1]</a><br/>";
        }
        echo "<br/>";
    }
} else {
    echo "<br/><br/><br/><h1>Check back later.</h1>There are no unseen notifications.<br/><br/><br/>";
}
?>