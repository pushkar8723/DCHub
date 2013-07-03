<?php
if (isset($_SESSION['loggedin'])) {
    if (isset($_GET['page']) && $_GET['page'] > 0) {
        $page = $_GET['page'];
    } else {
        $page = 1;
    }
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
    <div class='row'>
        <div class='span4'>
            <div class='palette palette-firm-dark' style='box-shadow: 0 0 10px #aaa;'>
                <center><h4>User Class : <?php echo $class[$_SESSION['user']['accesslevel'] - 1]; ?></h4></center>
            </div>
            <div class='palette palette-firm' style='border-radius: 0 0 10px 10px; box-shadow: 0 0 10px #aaa;'>
                <h5>Account Details</h5>
                <hr/>
                <table>
                    <tr><td class="bold" width='60px'>IP</td><td> <?php echo $_SESSION['user']['ip']; ?></td></tr>
                    <tr><td class="bold">Nick1</td><td> <?php echo $_SESSION['user']['nick']; ?></td></tr>
                    <?php if (isset($_SESSION['user']['nick2'])) { ?>
                        <tr><td class="bold">Nick2</td><td> <?php
                                echo $_SESSION['user']['nick2'] . "<br/>";
                            }
                            ?>
                    <tr><td class="bold">Name</td><td> <?php echo $_SESSION['user']['name']; ?></td></tr>
                    <tr><td class="bold">Roll</td><td> <?php echo $_SESSION['user']['roll']; ?></td></tr>
                    <tr><td class="bold">Email</td><td> <?php echo $_SESSION['user']['email']; ?></td></tr>
                    <tr><td class="bold">Branch</td><td> <?php echo $_SESSION['user']['branch']; ?></td></tr>
                    <tr><td class="bold">Hostel</td><td> <?php echo $_SESSION['user']['hostel']; ?></td></tr>
                    <tr><td class="bold">Room</td><td> <?php echo $_SESSION['user']['room']; ?></td></tr>
                </table><br/>
                <h5>My Groups</h5>
                <hr/>
                <ul class='nav nav-list grp'>
                    <?php
                    foreach ($_SESSION['user']['groups'] as $row) {
                        echo "<li><a href='" . SITE_URL . "/groups/$row'>$row</a></li>";
                    }
                    ?>
                    <li><a href="<?php echo SITE_URL; ?>/groups">See All</a></li>
                </ul>
            </div>
        </div>
        <div class='span8'>
            <h3>Share</h3>
            <form class='form-horizontal' action="<?php echo SITE_URL; ?>/process.php" method="post">
                <div class='control-group'>
                    <div class='control-label'><label for='filename'>File Name(required)</label></div>
                    <div class='controls'><input type='text' style='width:97%;' name='data[title]' id='filename' /></div>
                </div>
                <div class='control-group'>
                    <div class='control-label'><label for='magnet'>Magnet Link</label></div>
                    <div class='controls'><input type='text' style='width:97%;' name='data[magnetlink]' id='magnet' /></div>
                </div>
                <div class='control-group'>
                    <div class='control-label'><label for='tagsinput'>Tags (required)</label></div>
                    <div class='controls'><input name="data[tag]" style='width: 100%;' id="tagsinput" class="tagsinput" /></div>
                </div>
                <div class='control-group'>
                    <div class='control-label'></div>
                    <div class='controls'><input type="submit" value="Share" name="share" class="btn"/></div>
                </div>
            </form>
            <h3>Shared Contents</h3>
            <?php
            $body = "from dchub_content where deleted = 0 and uid = " . $_SESSION['user']['id'] . " order by timestamp desc";
            $res = DB::findAllWithCount("select *", $body, $page, 10);
            $data = $res['data'];
            echo "<table class='table'>
                    <tr><th>File Name</th><th>Tags</th><th style='text-align: center;'>Recommendations</th></tr>";
            foreach ($data as $row) {
                $query = "select count(cid) as recommendations from dchub_recommend where cid = $row[cid]";
                $rec = DB::findOneFromQuery($query);
                $query = "select uid from dchub_recommend where cid = $row[cid] and uid = " . $_SESSION['user']['id'];
                $response = DB::findAllFromQuery($query);
                if ($response) {
                    $btn = "<a href='#' class='btn discourage' id='$row[cid]'>Discourage</a>";
                } else {
                    $btn = "<a href='#' class='btn recommend' id='$row[cid]'>Recommend</a>";
                }
                $splittag = explode(',', $row['tag']);
                echo "<tr><td>" . (($row['magnetlink'] != "") ? ("<a href='$row[magnetlink]'>" . stripslashes($row['title']) . "</a>") : (stripslashes($row['title']))) . "</td><td>";
                foreach ($splittag as $tag)
                    echo "<a href='" . SITE_URL . "/latest/$tag'>$tag</a> ";
                echo "</td><td style='text-align:center;'><span id='$row[cid]_count'>$rec[recommendations]</span> recommendation(s)<br/> 
                  $btn</td></tr>";
            }
            echo "</table>";
            pagination($res['noofpages'], SITE_URL . "/", $page, 10);
            ?>
        </div>
    </div>
    <?php
} else {
    redirectTo(SITE_URL);
}
?>