<script type='text/javascript'>
    $(document).ready(function() {
        $('.recommend, .discourage').click(function(event) {
            $('#' + event.target.id).html("Processing...");
            if ($('#' + event.target.id).attr('class') == "btn discourage") {
                $.post("<?php echo SITE_URL; ?>/process.php", {
                    "discourage": '',
                    "cid": event.target.id
                }, function(result) {
                    if (result == '1') {
                        $('#' + event.target.id).removeClass('discourage').addClass('recommend').html('Recommend');
                        $('#' + event.target.id + "_count").html(parseInt($('#' + event.target.id + "_count").html()) - 1);
                    }
                    else {
                        $('#' + event.target.id).html(result);
                    }
                });
            }
            else {
                $.post("<?php echo SITE_URL; ?>/process.php", {
                    "recommend": '',
                    "cid": event.target.id
                }, function(result) {
                    if (result == '1') {
                        $('#' + event.target.id).removeClass('recommend').addClass('discourage').html('Discourage');
                        $('#' + event.target.id + "_count").html(parseInt($('#' + event.target.id + "_count").html()) + 1);

                    } else {
                        $('#' + event.target.id).html(result);
                    }
                });
            }
        });
    });
</script>
<?php
$_GET = secure($_GET);
if (isset($_GET['code'])) {
    if (isset($_GET['page']) && $_GET['page'] > 0) {
        $page = $_GET['page'];
    } else {
        $page = 1;
    }
    $query = "select * from dchub_users where deleted=0 and (nick1 = '$_GET[code]' OR nick2 = '$_GET[code]')";
    $user = DB::findOneFromQuery($query);
    if ($user) {
        echo "<center><h1>User : $_GET[code]</h1><h4>User Class : " . $class[$user['class']] . "</h4></center>";
        if (isset($_SESSION['loggedin'])) {
            ?>
            <h3>Leave a Message</h3>
            <form action="<?php echo SITE_URL; ?>/process.php" method="post">
                <input type="hidden" name="data[to]" value="<?php echo $_GET['code']; ?>" />
                <textarea name='data[msg]'></textarea><br/>
                <input type='submit' class='btn' value='Post' name='messagepost' />
            </form>
            <?php
        }
        if (isset($_SESSION['loggedin']) && $_SESSION['user']['accesslevel'] >= 9) {
            echo "<table class='table table-hover'>";
            foreach ($user as $key => $value) {
                echo "<tr><th>$key</th><td>$value</td></tr>";
            }
            echo "</table>";
        }
        echo "<h3>Shared Content</h3>";
        $body = "from dchub_content where deleted = 0 and uid = $user[id] order by timestamp desc";
        $res = DB::findAllWithCount("select *", $body, $page, 25);
        $data = $res['data'];
        contentshow($data, FALSE);
        pagination($res['noofpages'], SITE_URL . "/users/$_GET[code]", $page, 10);
    } else {
        echo "<br/><br/><br/><h1>User not found</h1>The user you are searching for doesn't exsits.<br/><br/><br/>";
    }
} else {
    echo "<br/><br/><br/><h1>Page not found</h1>The page you are searching for doesn't exsits.<br/><br/><br/>";
}
?>