<?php
if (isset($_GET['code'])) {
    $tab = addslashes($_GET['code']);
} else {
    $tab = '';
}
if (isset($_GET['page']) && $_GET['page'] > 0) {
    $page = $_GET['page'];
} else {
    $page = 1;
}
$categories = array('Everything' => '', "Movies" => 'movie', "TV Series" => 'tv', "Books" => 'book');
?>
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
<h1>Latest Contents</h1>
<ul class="nav nav-tabs">
    <?php
    foreach ($categories as $key => $value) {
        echo "<li " . (($tab == $value) ? ("class='active'") : ('')) . "><a href='" . SITE_URL . "/latest" . (($value != '') ? ("/$value") : ('')) . "'>$key</a></li>";
    }
    ?>
    <?php if (!in_array($tab, $categories)) { ?>
        <li class="active"><a href="<?php echo SITE_URL . "/latest/$tab"; ?>"><?php echo $tab; ?></a></li>
    <?php } ?>
</ul>
<table class="table table-hover">
    <tr><th>File name</th><th>Tags</th><th>Shared by</th><th style='text-align: center;'>Recommendations</th></tr>
    <?php
    $query = "from dchub_content where deleted=0";
    if ($tab != "")
        $query .= " and tag like '%$tab%'";
    $query .= " order by timestamp desc";
    $res = DB::findAllWithCount("select *", $query, $page, 25);
    $data = $res['data'];
    foreach ($data as $row) {
        $query = "select nick1 from dchub_users where id = $row[uid]";
        $user = DB::findOneFromQuery($query);
        $splittag = explode(',', $row['tag']);
        echo "<tr><td>" . (($row['magnetlink'] != "") ? ("<a href='$row[magnetlink]'>" . stripslashes($row['title']) . "</a>") : (stripslashes($row['title']))) . "</td><td>";
        foreach ($splittag as $tag)
            echo "<a href='" . SITE_URL . "/latest/".strtolower($tag)."'>$tag</a> ";
        $query = "select count(cid) as recommendations from dchub_recommend where cid = $row[cid]";
        $rec = DB::findOneFromQuery($query);
        if (isset($_SESSION['loggedin'])) {
            $query = "select uid from dchub_recommend where cid = $row[cid] and uid = " . $_SESSION['user']['id'];
            $response = DB::findAllFromQuery($query);
            if ($response) {
                $btn = "<a href='#' class='btn discourage' id='$row[cid]'>Discourage</a>";
            } else {
                $btn = "<a href='#' class='btn recommend' id='$row[cid]'>Recommend</a>";
            }
        } else {
            $btn = "<a href='" . SITE_URL . "' class='btn'>Login to Recommend</a>";
        }
        echo "</td><td><a href='" . SITE_URL . "/users/$user[nick1]'>$user[nick1]</a></td><td style='text-align:center;'><span id='$row[cid]_count'>$rec[recommendations]</span> recommendation(s)<br/> 
                  $btn</td></tr>";
    }
    ?>
</table>
<?php
pagination($res['noofpages'], SITE_URL . "/latest" . (($tab != '') ? ("/" . $tab) : ('')), $page, 10);
?>