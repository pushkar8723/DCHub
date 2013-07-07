<?php
if (isset($_GET['code'])) {
    $tab = addslashes($_GET['code']);
} else {
    $tab = '';
}
?>
<script type='text/javascript'>
    function replaceURLWithHTMLLinks(text) {
        var exp = /(\b(magnet):?[-A-Z0-9+&@#\/%?=~_|!:,.;]*[-A-Z0-9+&@#\/%=~_|])/ig;
        text = text.replace(exp, "<a href='$1'>$1</a>");
        var exp = /(\b(https?|ftp|file):\/\/[-A-Z0-9+&@#\/%?=~_|!:,.;]*[-A-Z0-9+&@#\/%=~_|])/ig;
        return text.replace(exp, "<a target='_blank' href='$1'>$1</a>");
    }
    $(document).ready(function() {
        $('.rec_recommend, .rec_discourage').click(function(event) {
            $('#' + event.target.id).html("Processing...");
            if ($('#' + event.target.id).attr('class') == "btn rec_discourage") {
                $.post("<?php echo SITE_URL; ?>/process.php", {
                    "rec_discourage": '',
                    "cid": event.target.id
                }, function(result) {
                    if (result == '1') {
                        $('#' + event.target.id).removeClass('rec_discourage').addClass('rec_recommend').html('Recommend');
                        $('#' + event.target.id + "_count").html(parseInt($('#' + event.target.id + "_count").html()) - 1);
                    }
                    else {
                        $('#' + event.target.id).html(result);
                    }
                });
            }
            else {
                $.post("<?php echo SITE_URL; ?>/process.php", {
                    "rec_recommend": '',
                    "cid": event.target.id
                }, function(result) {
                    if (result == '1') {
                        $('#' + event.target.id).removeClass('rec_recommend').addClass('rec_discourage').html('Discourage');
                        $('#' + event.target.id + "_count").html(parseInt($('#' + event.target.id + "_count").html()) + 1);

                    } else {
                        $('#' + event.target.id).html(result);
                    }
                });
            }
        });
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
        $('.des').each(function() {
            $(this).html(replaceURLWithHTMLLinks($(this).html()));
        });
    });
</script>
<h1>HOT Page</h1>
<ul class="nav nav-tabs">
    <?php
    foreach ($categories as $key => $value) {
        echo "<li " . (($tab == $value) ? ("class='active'") : ('')) . "><a href='" . SITE_URL . "/hot" . (($value != '') ? ("/$value") : ('')) . "'>$key</a></li>";
    }
    ?>
    <?php if (!in_array($tab, $categories)) { ?>
        <li class="active"><a href="<?php echo SITE_URL . "/hot/$tab"; ?>"><?php echo $tab; ?></a></li>
    <?php } ?>
</ul>
<table class='table table-hover'>
    <tr><th>Name</th><th>Tags</th><th>Shared By / Recommended By</th><th style='width: 170px; text-align: center;'>Recommendations</th></tr>
            <?php
            $time = time() - 60 * 60 * 24 * 7;
            $query = "select * from dchub_hot where time > $time";
            if ($tab != "")
                $query .= " and tag like '%$tab%'";
            $query .= " order by votes desc limit 0, 24";
            $res = DB::findAllFromQuery($query);
            foreach ($res as $row) {
                $splittag = explode(',', $row['tag']);
                $taglink = "";
                foreach ($splittag as $tag)
                    $taglink .= "<a href='" . SITE_URL . "/hot/$tag'>$tag</a> ";
                $query = "select nick1 from dchub_users where id = $row[uid]";
                $user = DB::findOneFromQuery($query);
                if ($row['type'] == 'lc') {
                    if (isset($_SESSION['loggedin'])) {
                        $query = "select uid from dchub_recommend where type='$row[type]' and cid = $row[cid] and uid = " . $_SESSION['user']['id'];
                        $response = DB::findAllFromQuery($query);
                        if ($response) {
                            $btn = "<a href='#' class='btn discourage' id='$row[cid]'>Discourage</a>";
                        } else {
                            $btn = "<a href='#' class='btn recommend' id='$row[cid]'>Recommend</a>";
                        }
                    } else {
                        $btn = "<a href='#' onclick=\"$('#signinbox').modal('show');\" class='btn'>Login to Recommend</a>";
                    }
                    $str = (($row['magnetlink'] != "") ? ("<a href='$row[magnetlink]'>" . stripslashes($row['name']) . "</a>") : (stripslashes($row['name'])));
                } else {
                    if (isset($_SESSION['loggedin'])) {
                        $query = "select uid from dchub_recommend where type='rc' and cid = $row[cid] and uid = " . $_SESSION['user']['id'];
                        $response = DB::findAllFromQuery($query);
                        if ($response) {
                            $btn = "<a href='#' class='btn rec_discourage' id='$row[cid]'>Discourage</a>";
                        } else {
                            $btn = "<a href='#' class='btn rec_recommend' id='$row[cid]'>Recommend</a>";
                        }
                    } else {
                        $btn = "<a href='#' onclick=\"$('#signinbox').modal('show');\" class='btn'>Login to Recommend</a>";
                    }
                    $str = "<div class='des'>" . preg_replace('/\n/', '<br/>', htmlspecialchars(stripslashes($row['name']))) . "</div>";
                }
                echo "<tr><td>" . $str . "</td><td>$taglink</td><td><a href='" . SITE_URL . "/users/$user[nick1]'>$user[nick1]</a></td><td style='text-align:center;'><span id='$row[cid]_count'>$row[votes]</span> Recommendation(s)<br/>$btn</td></tr>";
            }
            ?>
</table>