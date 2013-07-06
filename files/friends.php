<?php
if (!isset($_SESSION['loggedin'])) {
    echo "<br/><br/><br/><h1>Not Logged in :(</h1>You need to login to access this page.<br/><br/><br/>";
    return;
}
if ($_SESSION['user']['accesslevel'] == 0) {
    echo "<br/><br/><br/><h1>Permission Denied :(</h1>You have to get authenticated first to authenticate your firend.<br/><br/><br/>";
} else {
    ?>
    <h1>Friend List</h1>
    <div class='auth'>
        <h4>Authentication Request</h4><hr/>
        <?php
        $query = "select * from dchub_users where deleted=0 and class=0 and (friend = '" . $_SESSION['user']['nick'] . "'" . ((isset($_SESSION['user']['nick2']) ? (" or friend = '" . $_SESSION['user']['nick2'] . "')") : (")")));
        $res = DB::findAllFromQuery($query);
        foreach ($res as $row) {
            echo "<h5>$row[fullname]</h5><b>Nick :</b> <a href='" . SITE_URL . "/users/$row[nick1]'>$row[nick1]</a><br/>
        <b>Room No :</b> $row[room], <b>Hostel :</b> $row[hostel]<br/><b>IP Address :</b> $row[ipaddress]<br/>
            <form class='form-inline pull-left' style='margin: 5px;' action='" . SITE_URL . "/process.php' method='post'>
                <input type='hidden' name='nick' value='$row[nick1]'/>
                <input class='btn' type='submit' name='approvefriend' value='Approve' />
            </form>
            <form class='form-inline' style='margin: 5px;' action='" . SITE_URL . "/process.php' method='post'>
                <input type='hidden' name='nick' value='$row[nick1]'/>
                <input class='btn btn-danger' type='submit' name='denyfriend' value='Deny' />
            </form>";
        }
        ?>
    </div><br/>
    <h4>Approved Friends</h4><hr/>
    <div class="row">
        <?php
        $query = "select * from dchub_users where deleted=0 and class=1 and (friend = '" . $_SESSION['user']['nick'] . "'" . ((isset($_SESSION['user']['nick2']) ? (" or friend = '" . $_SESSION['user']['nick2'] . "')") : (")")));
        $res = DB::findAllFromQuery($query);
        foreach ($res as $row) {
            echo "<div class='span4'><h5>$row[fullname]</h5><b>Nick :</b> <a href='" . SITE_URL . "/users/$row[nick1]'>$row[nick1]</a><br/>
        <b>Room No :</b> $row[room], <b>Hostel :</b> $row[hostel]<br/><b>IP Address :</b> $row[ipaddress]</div>";
        }
        ?>
    </div>
    <?php
}
?>