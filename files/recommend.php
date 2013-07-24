<?php
if (isset($_GET['page']) && $_GET['page'] > 0) {
    $page = $_GET['page'];
} else {
    $page = 1;
}
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
        $('.des').each(function() {
            $(this).html(replaceURLWithHTMLLinks($(this).html()));
        });
    });
</script>
<h1>Recommendation Page</h1>
<div class='alert'>
    Here you can 
    <b>recommend</b> a good movie that you watched and want others to see. 
    <br/>If everyone contributes we'll have a collection of 
    recommendations of different items like <b>TV Shows, Books, Movies</b> 
    etc. Do Contribute!
</div>
<ul class="nav nav-tabs">
    <?php
    foreach ($categories as $key => $value) {
        echo "<li " . (($tab == $value) ? ("class='active'") : ('')) . "><a href='" . SITE_URL . "/recommend" . (($value != '') ? ("/$value") : ('')) . "'>$key</a></li>";
    }
    ?>
    <?php if (!in_array($tab, $categories)) { ?>
        <li class="active"><a href="<?php echo SITE_URL . "/recommend/$tab"; ?>"><?php echo $tab; ?></a></li>
    <?php } ?>
</ul>
<div class="row">
    <div class="span7" style='min-height: 450px;'>
        <?php
        $query = "from dchub_rc where deleted=0";
        if ($tab != "")
            $query .= " and tag like '%$tab%'";
        $query .= " order by timestamp desc";
        $res = DB::findAllWithCount("select *", $query, $page, 25);
        $data = $res['data'];
        echo "<table class='table table-hover'>
                    <tr><th>Site Link</th><th>Tags</th><th style='min-width: 140px;'>Recommend By</th></tr>";
        foreach ($data as $row) {
            // who shared the content
            $query = "select nick1 from dchub_users where id = $row[uid]";
            $user = DB::findOneFromQuery($query);
            // Tags Manipulation
            $splittag = explode(',', $row['tag']);
            $tagstr = '';
            foreach ($splittag as $tag)
                $tagstr .= "<a href='" . SITE_URL . "/recommend/$tag'>$tag</a> ";
            // recommend button
            $query = "select count(cid) as recommendations from dchub_recommend where cid = $row[cid] and type='rc'";
            $rec = DB::findOneFromQuery($query);
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
            $str = preg_replace('/\n/', '<br/>', htmlspecialchars(stripslashes($row['title'])));
            //printing
            echo "<tr><td class='wrapword'><div class='des'>$str</div></td>
            <td>$tagstr</td><td class='wrapword'><a href='" . SITE_URL . "/users/$user[nick1]'>$user[nick1]</a><br/
                <span id='$row[cid]_count'>$rec[recommendations]</span> recommendation(s)<br/>$btn</td>
                </tr>";
        }
        echo "</table>";
        pagination($res['noofpages'], SITE_URL . "/recommend", $page, 10);
        ?>


    </div>
    <div class="span5" style='min-height: 450px;'>
        <div style="position: fixed; width: 360px;">
            <?php if (isset($_SESSION['loggedin']) && $_SESSION['user']['accesslevel'] > 0) { ?>
                <form class='form-horizontal' action="<?php echo SITE_URL; ?>/process.php" method="post">
                    <label for='filename'>Description</label>
                    <textarea name='data[title]' style='width: 100%;' id='filename'></textarea>
                    <label for='tagsinput'>Tags</label>
                    <input name="data[tag]" id="tagsinput" class="tagsinput"/><br/>
                    <?php
                    foreach ($categories as $key => $value) {
                        if ($value != '') {
                            ?>
                            <label style="display: inline-block; width: 70px;" class="checkbox" for="<?php echo $value; ?>">
                                <input type="checkbox" name='data[<?php echo $value; ?>]' value="<?php echo $value; ?>" id="<?php echo $value; ?>">
                                <?php echo $key; ?>
                            </label>
                            <?php
                        }
                    }
                    ?>
                    <br/><input type="submit" value="Recommend" name="recommendcontent" class="btn"/>
                </form>
                <?php
            } else if (!isset($_SESSION['loggedin'])) {
                echo "<center><h3>Login to recommend</h3></center><br/>";
            } else {
                echo "<center><h3>Only authenticated user can recommend.</h3></center><br/>";
            }
            ?>
        </div>
    </div>
</div>

