<?php if(!isset($_SESSION['loggedin'])) redirectTo(SITE_URL); ?>
<h1>Friend List</h1>
<div style='background: #f2f2f2; padding: 5px; margin: 10px 0;'>
<h4>Authentication Request</h4><hr/>
<?php
$query = "select * from dchub_users where deleted=0 and authenticated=0 and (friend = '".$_SESSION['user']['nick']."'".((isset($_SESSION['user']['nick2'])?(" or friend = '".$_SESSION['user']['nick2']."')"):(")")));
$res = DB::findAllFromQuery($query);
foreach ($res as $row){
    echo "<h5>$row[fullname]</h5><b>Primary Nick :</b> <a href='".SITE_URL."/users/$row[nick1]'>$row[nick1]</a><br/>".(($row['nick2'] != "")?("<b>Secondary Nick : </b>$row[nick2]<br/>"):(""))."
        <b>Room No :</b> $row[room], <b>Hostel :</b> $row[hostel]<br/><b>IP Address :</b> $row[ipaddress]<br/>
            <form class='form-inline pull-left' style='margin: 5px;' action='".SITE_URL."/process.php' method='post'>
                <input type='hidden' name='nick' value='$row[nick1]'/>
                <input class='btn' type='submit' name='approvefriend' value='Approve' />
            </form>
            <form class='form-inline' style='margin: 5px;' action='".SITE_URL."/process.php' method='post'>
                <input type='hidden' name='nick' value='$row[nick1]'/>
                <input class='btn btn-danger' type='submit' name='denyfriend' value='Deny' />
            </form>";
}
?>
</div>
<h4>Approved Friends</h4><hr/>
<div class="row">
    <?php
$query = "select * from dchub_users where deleted=0 and authenticated=1 and (friend = '".$_SESSION['user']['nick']."'".((isset($_SESSION['user']['nick2'])?(" or friend = '".$_SESSION['user']['nick2']."')"):(")")));
$res = DB::findAllFromQuery($query);
foreach ($res as $row){
    echo "<div class='span4'><h5>$row[fullname]</h5><b>Primary Nick :</b> <a href='".SITE_URL."/users/$row[nick1]'>$row[nick1]</a><br/>".(($row['nick2'] != "")?("<b>Secondary Nick : </b>$row[nick2]<br/>"):(""))."
        <b>Room No :</b> $row[room], <b>Hostel :</b> $row[hostel]<br/><b>IP Address :</b> $row[ipaddress]</div>";
}
?>
</div>