<script type='text/javascript'>
    function replaceURLWithHTMLLinks(text) {
        var exp = /(\b(magnet):?[-A-Z0-9+&@#\/%?=~_|!:,.;]*[-A-Z0-9+&@#\/%=~_|])/ig;
        text = text.replace(exp, "<a href='$1'>$1</a>");
        var exp = /(\b(https?|ftp|file):\/\/[-A-Z0-9+&@#(\/%?=~_|!:,.;]*[-A-Z0-9+&@#)\/%=~_|])/ig;
        return text.replace(exp, "<a target='_blank' href='$1'>$1</a>");
    }
    $(document).ready(function() {
        $('form').submit(function(event) {
            if (event.target.id == "searchform") {
                $(location).attr('href', '<?php echo SITE_URL . "/request" . ((isset($_GET['code'])) ? ("/$_GET[code]") : ("")) . ((isset($_GET['page'])) ? ("&page=$_GET[page]") : ("")); ?>&q=' + $('#search').val());
                return false;
            } else {
                return true;
            }
        });
<?php
if (isset($_SESSION['loggedin'])) {
    ?>
            $('.delete').click(function(event) {
                var tr = $(this).closest('div.accesslevel');
                var id = event.target.id;
                id = id.replace('_delete', '');
                $('#' + id + '_delete').html('Processing');
                $.post("<?php echo SITE_URL; ?>/process.php", {
                    "deletereq": '',
                    "id": id
                }, function(result) {
                    if (result === '1') {
                        $('#' + id + '_detete').html('Deleted');
                        tr.remove();
                    }
                    else {
                        $('#' + id + '_delete').html(result);
                    }
                });
            });
            $('.comment').keypress(function(e) {
                if (e.which == 13) {
                    id = e.target.id;
                    id = id.replace('comment_', '');
                    $.post("<?php echo SITE_URL; ?>/process.php", {
                        "commentwa": '',
                        "data[rid]": id,
                        "data[comment]": $('#' + e.target.id).val()
                    }, function(result) {
                        if (result == '1') {
                            $("#commentbox_" + id).append("<div class='comment'><b><a href='<?php echo SITE_URL; ?>/users/<?php echo $_SESSION['user']['nick']; ?>'><?php echo $_SESSION['user']['nick']; ?></a> : </b><p class='pcomment'>" + replaceURLWithHTMLLinks($('#' + e.target.id).val()) + "</p></div>");
                            $('#' + e.target.id).val("");
                        } else {
                            alert(result);
                        }
                    });
                }
            });
            $('.volunteer, .chickenout').click(function(event) {
                $('#' + event.target.id).html("Processing...");
                if ($('#' + event.target.id).attr('class') == "volunteer") {
                    $.post("<?php echo SITE_URL; ?>/process.php", {
                        "volunteer": '',
                        "cid": event.target.id
                    }, function(result) {
                        if (result == '1') {
                            $('#' + event.target.id).removeClass('volunteer').addClass('chickenout').html('Chicken Out');
                            if ($('#' + event.target.id + "_volcount").html() == "No one")
                                $('#' + event.target.id + "_volcount").html("<?php if (isset($_SESSION['loggedin'])) echo "<a href=\\\"" . SITE_URL . "/users/" . $_SESSION['user']['nick'] . "\\\">" . $_SESSION['user']['nick'] . "</a>"; ?>");
                            else
                                $('#' + event.target.id + "_volcount").html($('#' + event.target.id + "_volcount").html() + " <?php if (isset($_SESSION['loggedin'])) echo "<a href='" . SITE_URL . "/users/" . $_SESSION['user']['nick'] . "'>" . $_SESSION['user']['nick'] . "</a>"; ?>");
                        }
                        else {
                            $('#' + event.target.id).html(result);
                        }
                    });
                }
                else {
                    $.post("<?php echo SITE_URL; ?>/process.php", {
                        "chickenout": '',
                        "cid": event.target.id
                    }, function(result) {
                        if (result == '1') {
                            $('#' + event.target.id).removeClass('chickenout').addClass('volunteer').html('Volunteer');
                            var str;
                            str = $('#' + event.target.id + "_volcount").html();
                            str = str.replace("<?php if (isset($_SESSION['loggedin'])) echo "<a href=\\\"" . SITE_URL . "/users/" . $_SESSION['user']['nick'] . "\\\">" . $_SESSION['user']['nick'] . "</a>"; ?>", "");
                            str = str.trim();
                            if (str == "")
                                $('#' + event.target.id + "_volcount").html("No one");
                            else
                                $('#' + event.target.id + "_volcount").html(str);

                        } else {
                            $('#' + event.target.id).html(result);
                        }
                    });
                }
            });
                <?php
}
?>
            $('.post, .pcomment').each(function() {
                $(this).html(replaceURLWithHTMLLinks($(this).html()));
            });

    });
</script>
<form id="searchform" class='pull-right' style='margin-top: 10px; margin-bottom: -10px;' method='post'>
    <input id='search' name='search' type='text' class='search-query' placeholder='Search' required/>
</form>
<h1>Request Page</h1><hr/>
<?php
if (isset($_GET['page']) && $_GET['page'] > 0) {
    $page = $_GET['page'];
} else {
    $page = 1;
}
if (isset($_GET['q'])) {
    $search = addslashes($_GET['q']);
} else {
    $search = '';
}
?>
<div class='row'>
    <div class='span7' style='min-height: 400px;'>
        <ul class="nav nav-tabs">
            <li <?php echo ((isset($_GET['code'])) ? ("") : ("class='active'")); ?>><a href="<?php echo SITE_URL; ?>/request">Pending</a></li>
            <li <?php echo ((isset($_GET['code'])) ? ("class='active'") : ("")); ?>><a href="<?php echo SITE_URL; ?>/request/completed">Completed</a></li>
        </ul>
        <?php
        $body = "from dchub_request";
        if (!isset($_GET['code'])) {
            $body .= " where deleted=0";
        } else {
            $body .= " where deleted=1";
        }
        if (isset($_GET['q'])) {
            $clause = array();
            foreach (explode(' ', $search) as $term) {
                array_push($clause, "(request_file like '%$term%' or volunteer like '%$term%')");
            }
            $table = implode(" and ", $clause);
            $body .= " and $table";
        }
        $body .= " order by updatedOn desc";
        $res = DB::findAllWithCount("select *", $body, $page, 10);
        $data = $res['data'];
        if ($data) {
            foreach ($data as $row) {
                $row['request_file'] = stripslashes($row['request_file']);
                echo "<div class='accesslevel'>";
                $vollist = explode(',', $row['volunteer']);
                if (!isset($_GET['code']) && isset($_SESSION['loggedin']) && ($_SESSION['user']['accesslevel'] >= 2 || in_array($_SESSION['user']['nick'], $vollist))) {
                    echo "<a class='btn pull-right delete' id='$row[id]_delete'>Mark Completed</a>";
                }
                $row['request_file'] = preg_replace('/\n/', '<br/>', htmlspecialchars($row['request_file']));
                if ($search != '') {
                    $row['request_file'] = preg_replace('/' . str_replace(' ', '|', trim($search)) . '/i', '<b>$0</b>', stripslashes($row['request_file']));
                }
                $user = DB::findOneFromQuery("select nick1 from dchub_users where id = $row[uid]");
                echo "<h4><a href='" . SITE_URL . "/users/$user[nick1]'>$user[nick1]</a> requested for :</h4>
				<div class='post'>$row[request_file]";
                if($row['completedby'] != ''){
                    echo "<br/> Completed By : <a href='".SITE_URL."/users/$row[completedby]'>$row[completedby]</a>";
                }
                echo "</div>";
                $vol = explode(',', $row['volunteer']);

                echo "<div class='pull-left button'>";
                if (isset($_SESSION['loggedin']) && !in_array($_SESSION['user']['nick'], $vol)) {
                    echo "<a id='$row[id]' class='volunteer' href='#'>Volunteer</a><br/>";
                } else if (isset($_SESSION['loggedin']) && in_array($_SESSION['user']['nick'], $vol)) {
                    echo "<a id='$row[id]' class='chickenout' href='#'>Chicken Out</a><br/>";
                } else {
                    echo "<a href='#' onclick=\"$('#signinbox').modal('show');\">Login to Volunteer</a><br/>";
                }
                echo "</div>
                                    <div style='padding:10px; margin: 5px;'>
			<span class='highlight' id='$row[id]_volcount'>";
                if ($row['volunteer'] != "") {

                    $vol = explode(',', $row['volunteer']);
                    foreach ($vol as $pick) {
                        if ($pick != "")
                            echo "<a href='" . SITE_URL . "/users/$pick'>$pick</a> ";
                    }
                } else {
                    echo "No one";
                }
                echo "</span> has volunteered</div>
				<b>Comments</b>
                                <div id='commentbox_$row[id]'>";
                $getc = DB::findAllFromQuery("select * from dchub_comment where rid = $row[id]");
                foreach ($getc as $comment) {
                    echo "<div class='comment'><b><a href='" . SITE_URL . "/users/$comment[uid]'>$comment[uid]</a> : </b><p class='pcomment'>$comment[comment]</p></div>";
                }
                echo "</div>";
                if (isset($_SESSION['loggedin'])) {
                    echo "<input type='text' id='comment_$row[id]' class='comment' style='margin: 5px; width: 95%;'/>";
                } else {
                    echo "<div class='comment'><a href='#' onclick=\"$('#signinbox').modal('show');\">Login</a> to comment</div>";
                }
                echo "</div>";
            }
            pagination($res['noofpages'], SITE_URL . "/request" . ((isset($_GET['code']) ? ("/completed") : (""))) . ((isset($_GET['q'])) ? ("&q=$search") : ("")), $page, 10);
        } else {
            echo "<br/><br/><br/><br/><h1>No request till now.</h1><h3>Go ahead make one.</h3><br/><br/><br/><br/>";
        }
        ?>
    </div>
    <div class='span5'>
        <div style='position: fixed; width: 350px;'>
            <?php
            if (isset($_SESSION['loggedin'])) {
                echo "<h4>Make a request</h4>
        <b>If possible try to provide URL / magnet links for the content.</b><br/><br/>
        <form action='" . SITE_URL . "/process.php' method='post'>
        <textarea style='width: 100%;' name='data[request_file]' required></textarea><br/>
        <input type='submit' value='Request' name='request' class='btn'/>
        </form>";
            } else {
                echo "<a href='#' onclick=\"$('#signinbox').modal('show');\" class='btn btn-danger btn-block btn-large'>Login to make a Request</a>";
            }
            ?>
        </div>
    </div>
</div>
