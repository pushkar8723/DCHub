<?php

if (isset($_SESSION['loggedin']) && isset($_GET['code'])) {
    if (isset($_GET['page']) && $_GET['page'] > 0) {
        $page = $_GET['page'];
    } else {
        $page = 1;
    }
    echo "<h1>Notification Archive</h1>";
    $body = "from dchub_post where deleted=0 and approvedby!=0 and id <= '" . $_SESSION['user']['lastnotificationid'] . "' and gid in 
    (" . implode(',', $_SESSION['user']['groups']) . ")    
    order by timestamp desc";
    $res = DB::findAllWithCount("select *", $body, $page, 20);
    foreach ($res['data'] as $row) {
        echo "<div class='accesslevel'>";
        $group = DB::findOneFromQuery("select name from dchub_groups where id = $row[gid]");
        $row['post'] = preg_replace('/\n/', '<br/>', htmlspecialchars(stripslashes($row['post'])));
        echo "<div style='border-bottom: 1px solid #ddd;'><span class='pull-right postdate'>" . date('M d, h:i a', $row['timestamp']) . "</span><h4><a href='" . SITE_URL . "/users/$row[postby]'>$row[postby]</a> <i class='icon-chevron-right'></i> $group[name]</h4></div>$row[post]<br/>";
        if (isset($_SESSION['loggedin']) && $_SESSION['user']['accesslevel'] >= 9) {
            $app = DB::findOneFromQuery("select nick1 from dchub_users where id=$row[approvedby]");
            echo "Approved by : <a href='" . SITE_URL . "/users/$app[nick1]'>$app[nick1]</a><br/>";
        }
        echo "<br/>";
        echo '</div>';
    }
    pagination($res['noofpages'], SITE_URL."/notifications/archive", $page, 10);
} else {
    ?>
    <script type='text/javascript'>
        $(document).ready(function() {
            $('#notnav').removeClass('active');
        });
    </script>
    <?php

    if (!isset($_SESSION['loggedin'])) {
        echo "<br/><br/><br/><h1>Not Logged in :(</h1>You need to login access this page.<br/><br/><br/>";
        return;
    }
    $usrgrp = "'" . implode("','", $_SESSION['user']['groups']) . "'";
    $query = "select * from dchub_post where deleted=0 and approvedby!=0 and id > '" . $_SESSION['user']['lastnotificationid'] . "' and gid in 
(" . implode(',', $_SESSION['user']['groups']) . ")    
order by timestamp desc";
    $res = DB::findAllFromQuery($query);
    if ($res) {
        echo "<h1>Notifications</h1>";
        $_SESSION['user']['notificationid'] = $res[0]['id'];
        DB::update('dchub_users', array('lastnotificationid' => $res[0]['id']), 'id = ' . $_SESSION['user']['id']);
        foreach ($res as $row) {
            echo "<div class='accesslevel'>";
            $group = DB::findOneFromQuery("select name from dchub_groups where id = $row[gid]");
            $row['post'] = preg_replace('/\n/', '<br/>', htmlspecialchars(stripslashes($row['post'])));
            echo "<div style='border-bottom: 1px solid #ddd;'><span class='pull-right postdate'>" . date('M d, h:i a', $row['timestamp']) . "</span><h4><a href='" . SITE_URL . "/users/$row[postby]'>$row[postby]</a> <i class='icon-chevron-right'></i> $group[name]</h4></div>$row[post]<br/>";
            if (isset($_SESSION['loggedin']) && $_SESSION['user']['accesslevel'] >= 9) {
                $app = DB::findOneFromQuery("select nick1 from dchub_users where id=$row[approvedby]");
                echo "Approved by : <a href='" . SITE_URL . "/users/$app[nick1]'>$app[nick1]</a><br/>";
            }
            echo "<br/>";
            echo '</div>';
        }
    } else {
        echo "<br/><br/><br/><h1>Check back later.</h1>There are no unseen notifications.<br/><br/><br/>";
    }
}
?>