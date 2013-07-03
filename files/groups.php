<?php
if (isset($_GET['code'])) {
    if (isset($_GET['page']) && $_GET['page'] > 0) {
        $page = $_GET['page'];
    } else {
        $page = 1;
    }
    $_GET['code'] = addslashes($_GET['code']);
    $group = DB::findOneFromQuery("select * from dchub_groups where name='$_GET[code]'");
    if ($group) {
        if (isset($_SESSION['loggedin']))
            echo "<form class='pull-right' style='margin-top: 10px;'  action='" . SITE_URL . "/process.php' method='post'>
                <input type='hidden' value='$_GET[code]' name='group'/>
                <input type='submit' name='grpToggle' class='btn btn-danger' " . ((in_array($_GET['code'], $_SESSION['user']['groups'])) ? ("value='Leave'") : ("value='Join'")) . "/>
            </form>";
        echo "<h1>$_GET[code]</h1>";
        $modrators = explode(',', $group['moderators']);
        echo "<div class='palette palette-firm desc'>$group[description]</div>";
        if (isset($_SESSION['loggedin']) && (in_array($_SESSION['user']['nick'], $modrators) || $_SESSION['user']['accesslevel'] >= 4)) {
            $query = "select * from dchub_post where gid=$group[id] and deleted = 0 and approvedby = 0";
            $res = DB::findAllFromQuery($query);
            if ($res) {
                echo "<div style='background: #f2f2f2; padding: 5px; margin: 10px 0;'><h4>Posts awaiting approval</h4>";
                foreach ($res as $row) {
                    $row['post'] = htmlspecialchars(preg_replace('/\n/', '<br/>', $row['post']));
                    echo "<div style='border-bottom: 1px solid #ddd;'><b><a href='" . SITE_URL . "/users/$row[postby]'>$row[postby]</a></b><br/></div>$row[post]<br/>
                        <form class='form-inline pull-left' style='margin:5px;' action='" . SITE_URL . "/process.php' method='post'>
                <input type='hidden' value='$row[id]' name='id'>
                <input type='submit' value='Approve' name='approve' class='btn btn-danger'/>
                </form>
                <form class='form-inline' style='margin:5px;' action='" . SITE_URL . "/process.php' method='post'>
                <input type='hidden' value='$row[id]' name='id'>
                <input type='submit' value='Decline' name='decline' class='btn btn-danger'/>
                </form>";
                }
                echo "</div>";
            }
        }
        if (isset($_SESSION['loggedin'])) {
            echo "<h4>Post</h4>
            <form action='" . SITE_URL . "/process.php' method='post'>
                <input type='hidden' value='$group[id]' name='data[gid]'/>
                    <textarea name='data[post]'></textarea><br/>
                    <input type='submit' class='btn' name='post'/>
              </form>";
        } else {
            echo "<a href='" . SITE_URL . "' class='btn btn-danger btn-block btn-large'>Login to Post</a><br/><br/>";
        }
        $body = "from dchub_post where gid=$group[id] and deleted = 0 and approvedby != 0 order by timestamp desc";
        $res = DB::findAllWithCount("select *", $body, $page, 10);
        $data = $res['data'];
        foreach ($data as $row) {
            $row['post'] = htmlspecialchars(preg_replace('/\n/', '<br/>', $row['post']));
            echo "<div style='border-bottom: 1px solid #ddd;'><b><a href='" . SITE_URL . "/users/$row[postby]'>$row[postby]</a></b><br/><span class='postdate'>" . date('M d, h:i a', $row['timestamp']) . "</span></div>$row[post]<br/>";
            if (isset($_SESSION['loggedin']) && $_SESSION['user']['accesslevel'] >= 6) {
                $app = DB::findOneFromQuery("select nick1 from dchub_users where id=$row[approvedby]");
                echo "Approved by : <a href='" . SITE_URL . "/users/$app[nick1]'>$app[nick1]</a><br/>";
            }
            echo "<br/>";
        }
        pagination($res['noofpages'], SITE_URL . "/groups/$_GET[code]", $page, 10);
    } else {
        echo "<br/><br/><br/><h1>Group not found</h1>The group you are searching for doesn't exsits.<br/><br/><br/>";
    }
} else {
    $query = "select * from dchub_groups where deleted = 0";
    $res = DB::findAllFromQuery($query);
    echo "<h1>Groups</h1><table class='table table-hover'><tr><th>Name</th><th>Description</th></tr>";
    foreach ($res as $row) {
        echo "<tr><td><a href='" . SITE_URL . "/groups/$row[name]'>$row[name]</a></td><td>$row[description]</td></tr>";
    }
    echo "</table>";
}
?>