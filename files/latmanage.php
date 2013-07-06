<?php
if (!isset($_SESSION['loggedin'])) {
    echo "<br/><br/><br/><h1>Not Logged in :(</h1>You need to login to access this page.<br/><br/><br/>";
    return;
}
if ($_SESSION['user']['accesslevel'] < 3) {
    echo "<br/><br/><br/><h1>Permission Denied :(</h1>You don't have enough previleges.<br/><br/><br/>";
    return;
}
if (isset($_GET['page']) && $_GET['page'] > 0) {
    $page = $_GET['page'];
} else {
    $page = 1;
}
$body = "from dchub_content where deleted=0 order by createdOn desc";
$rec = DB::findAllWithCount("select *", $body, $page, 20);
$data = $rec['data'];
?>
<script type='text/javascript'>
    $(document).ready(function() {
        $('.update').click(function(event) {
            var id = event.target.id;
            id = id.replace('_update', '');
            $('#' + id + '_update').html('Processing');
            $.post("<?php echo SITE_URL; ?>/process.php", {
                "updatelat": '',
                "data[title]": $('#' + id + "_filename").val(),
                "data[tag]": $('#' + id + "_tag").val(),
                "data[cid]": id
            }, function(result) {
                if (result === '1') {
                    $('#' + id + '_update').html('Update');
                }
                else {
                    $('#' + id + '_update').html(result);
                }
            });
        });
        $('.delete').click(function(event) {
            var tr = $(this).closest('tr');
            var id = event.target.id;
            id = id.replace('_delete', '');
            $('#' + id + '_delete').html('Processing');
            $.post("<?php echo SITE_URL; ?>/process.php", {
                "deletelat": '',
                "cid": id
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
        $('.feature').click(function(event) {
            var id = event.target.id;
            id = id.replace('_feature', '');
            $('#' + id + '_feature').html('Processing');
            $.post("<?php echo SITE_URL; ?>/process.php", {
                "featurelat": '',
                "cid": id
            }, function(result) {
                if (result === '1') {
                    $('#' + id + '_feature').html('Featured');
                } else if (result === '2') {
                    $('#' + id + '_feature').html('Feature');
                } else {
                    $('#' + id + '_feature').html(result);
                }
            });
        });
    });
</script>
<h1>Manage Recommendation</h1>
<table class='table table-hover'>
    <tr><th>File name</th><th>Tags</th><th>Shared By</th><th>Options</th></tr>
    <?php
    foreach ($data as $row) {
        $user = DB::findOneFromQuery("select nick1 from dchub_users where id = $row[uid]");
        echo "<tr>
        <td>
            <input type='text' value='$row[title]' id='$row[cid]_filename'/>
        </td>
        <td>
            <input type='text' id='$row[cid]_tag' value='$row[tag]'/>
        </td>
        <td>
            $user[nick1]
        </td>
        <td>
            <a href='#' class='update btn' id='$row[cid]_update'>Update</a>
            <a href='#' class='delete btn btn-danger' id='$row[cid]_delete'>Delete</a>";
        if ($row['priority'] == '0') {
            echo " <a href='#' class='feature btn btn-danger' id='$row[cid]_feature'>Feature</a>";
        } else {
            echo " <a href='#' class='feature btn btn-danger' id='$row[cid]_feature'>Featured</a>";
        }
        echo "</td>
        </tr>";
    }
    ?>
</table>
<?php
pagination($rec['noofpages'], SITE_URL . "/latmanage", $page, 10);
?>