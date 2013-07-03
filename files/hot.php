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
<h1>HOT Page</h1>
<table class='table table-hover'>
    <tr><th>Name</th><th>Tags</th><th>Shared By</th><th style='text-align: center;'>Recommendations</th></tr>
<?php
$time = time() - 60*60*24*7;
$query = "select * from dchub_hot where time > $time order by votes desc limit 0, 24";
$res = DB::findAllFromQuery($query);
foreach ($res as $row) {
    $splittag = explode(',', $row['tag']);
    $taglink = "";
    foreach ($splittag as $tag)
            $taglink .= "<a href='" . SITE_URL . "/latest/$tag'>$tag</a> ";
    $query = "select nick1 from dchub_users where id = $row[uid]";
    $user = DB::findOneFromQuery($query);    
   if($row['type'] == 'lc'){
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
   } else {
       $btn = '';
   }
    echo "<tr><td>" . (($row['magnetlink'] != "") ? ("<a href='$row[magnetlink]'>" . stripslashes($row['name']) . "</a>") : (stripslashes($row['name']))) . "</td><td>$taglink</td><td><a href='" . SITE_URL . "/users/$user[nick1]'>$user[nick1]</a></td><td style='text-align:center;'><span id='$row[cid]_count'>$row[votes]</span> Recommendation(s)<br/>$btn</td></tr>";
}
?>
</table>