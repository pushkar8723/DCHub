<?php
if (!isset($_SESSION['loggedin'])) {
    echo "<br/><br/><br/><h1>Not Logged in :(</h1>You need to login to access this page.<br/><br/><br/>";
    return;
}
if ($_SESSION['user']['accesslevel'] < 2) {
    echo "<br/><br/><br/><h1>Permission Denied :(</h1>You don't have enough previleges.<br/><br/><br/>";
    return;
}
if (isset($_GET['page']) && $_GET['page'] > 0) {
    $page = $_GET['page'];
} else {
    $page = 1;
}
$body = "from dchub_request where deleted=0 order by createdOn desc";
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
                "updatereq": '',
                "data[request_file]": $('#' + id + "_filename").val(),
                "data[id]": id
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
    });
</script>
<h1>Manage Recommendation</h1>
<table class='table table-hover'>
    <tr><th>File name</th><th>Requested By</th><th>Options</th></tr>
    <?php
    foreach ($data as $row) {
        $row['request_file'] = stripslashes($row['request_file']);
        $user = DB::findOneFromQuery("select nick1 from dchub_users where id = $row[uid]");
        echo "<tr>
        <td>
            <textarea id='$row[id]_filename'>$row[request_file]</textarea>
        </td>
        <td>
            $user[nick1]
        </td>
        <td>
            <a href='#' class='update btn' id='$row[id]_update'>Update</a>
            <a href='#' class='delete btn btn-danger' id='$row[id]_delete'>Delete</a>
        </td>
        </tr>";
    }
    ?>
</table>
<?php
    pagination($rec['noofpages'], SITE_URL."/reqmanage", $page, 10);
?>