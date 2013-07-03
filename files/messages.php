<script type='text/javascript'>
    $(document).ready(function() {
        $('#msgnav').removeClass('active');
    });
</script>
<?php
if (!isset($_SESSION['loggedin']))
    redirectTo(SITE_URL);
$_GET = secure($_GET);
?>
<h1>Offline Messages</h1>
<div style="width: 30%; height: 450px; float: left; overflow-y: auto; background: #f5f5f5; border-right: 1px solid #eee;">
    <ul class="nav nav-list">
        <?php
        $query = "select distinct(fromid) as fromid from dchub_message where id > '" . $_SESSION['user']['lastmsgid'] . "' and toid = " . $_SESSION['user']['id'] . "
            union
            select distinct(toid) as fromid from dchub_message where id > '" . $_SESSION['user']['lastmsgid'] . "' and fromid = " . $_SESSION['user']['id'];
        $res = DB::findAllFromQuery($query);
        foreach ($res as $row) {
            $user = DB::findOneFromQuery("select nick1 from dchub_users where id = $row[fromid]");
            echo "<li " . ((isset($_GET['code']) && $_GET['code'] == "$user[nick1]") ? ("class='active'") : ("")) . "><a href='" . SITE_URL . "/messages/$user[nick1]'>$user[nick1]</a></li>";
        }
        $query = "select max(id) as maxid from dchub_message where fromid = " . $_SESSION['user']['id'] . " or toid = " . $_SESSION['user']['id'];
        $id = DB::findOneFromQuery($query);
        $_SESSION['user']['msgid'] = $id['maxid'];
        ?>
    </ul>
</div>
<div style="width: 69%; height: 450px; margin-left: 30%;  overflow-y: auto; padding-left: 5px;">
    <?php
    if (isset($_GET['code'])) {
        $fromid = DB::findOneFromQuery("select id from dchub_users where nick1 = '$_GET[code]'");
        $toid = $_SESSION['user']['id'];
        $query = "select * from dchub_message where id > '" . $_SESSION['user']['lastmsgid'] . "' and (fromid = $fromid[id] and toid = $toid) or (fromid = $toid and toid = $fromid[id])";
        $res = DB::findAllFromQuery($query);
        foreach ($res as $row) {
            $row['msg'] = preg_replace('/\n/', '<br/>', htmlspecialchars(stripslashes($row['msg'])));
            if ($row['fromid'] == $fromid['id'])
                echo "<b><a href='" . SITE_URL . "/users/$_GET[code]'>$_GET[code]</a></b><div class='pull-right'>$row[createdOn]</div><br/>$row[msg]<hr/>";
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