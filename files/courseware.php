<h1>Courseware</h1>
<?php
if (isset($_SESSION['loggedin']) && $_SESSION['user']['accesslevel'] >= 9) {
    ?>
    <script type='text/javascript'>
        $(document).ready(function() {
            $('.update').click(function(event) {
                var id = event.target.id;
                id = id.replace('update_', '');
                $('#update_' + id).html('Processing');
                $.post("<?php echo SITE_URL; ?>/process.php", {
                    "ctagupdate": '',
                    "tags": $('#' + id).val(),
                    "id": id
                }, function(result) {
                    $('#update_' + id).html(result);
                });
            });
            $('.delete').click(function(event) {
                var tr = $(this).closest('tr');
                var id = event.target.id;
                id = id.replace('delete_', '');
                $('#delete_' + id).html('Processing');
                $.post("<?php echo SITE_URL; ?>/process.php", {
                    "cdelete": '',
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
    <?php
}
if (isset($_GET['code'])) {
    $tab = addslashes($_GET['code']);
} else {
    $tab = "cse";
}
if (isset($_SESSION['loggedin']) && $_SESSION['user']['accesslevel'] >= 9) {
    echo "<form class='form-inline' action='" . SITE_URL . "/process.php' method='post' enctype='multipart/form-data'>
            <label for='fileadd'>File : </label>
            <input type='file' name='fileadd' id='fileadd'/>
            <input type='submit' name='courseware' class='btn' value='Upload'/>
          <form><br/><br/>";
}
if ($handle = opendir('/srv/http/dchub/course/')) {
    $files = array();
    while (false !== ($entry = readdir($handle))) {
        if ($entry != "." && $entry != "..") {
            array_push($files, $entry);
        }
    }
    closedir($handle);
}
$dbfiles = array();
$query = "select * from dchub_download";
$res = DB::findAllFromQuery($query);
foreach ($res as $row) {
    array_push($dbfiles, $row['filename']);
}
$diff = array_diff($files, $dbfiles);
$defaultTags = array('CSE' => 'cse', 'IT' => 'it', 'ECE' => 'ece', 'EEE' => 'eee', 'Mech' => 'mech', 'Civil' => 'civil');
$query = "select * from dchub_download where tags = ''";
$res = DB::findAllFromQuery($query);
if ($res) {
    $defaultTags['Uncategorized'] = "na";
}
if (sizeof($diff) > 0) {
    foreach ($diff as $val) {
        DB::insert('dchub_download', array('filename' => $val));
    }
}
echo "<div class='alert'>This page contains direct links for various courseware.
    If you want something to be added here contact admin.</div>";
echo "<ul class='nav nav-tabs'>";
foreach ($defaultTags as $key => $val) {
    echo "<li " . (($val == $tab) ? ("class='active'") : ("")) . "><a href='" . SITE_URL . "/courseware/$val'>$key</a></li>";
}
if (!in_array($tab, $defaultTags)) {
    echo "<li class='active'><a href='#'>$tab</a></li>";
}
echo "</ul>";
echo "<table class='table table-hover'>
        <tr><th>Filename</th><th>Size</th><th>Tags</th></tr>";
$query = "select * from dchub_download where tags " . (($tab == "na") ? ("=''") : ("like '%$tab%'")) . " order by filename";
$res = DB::findAllFromQuery($query);
foreach ($res as $row) {
    $tags = explode(',', $row['tags']);
    $tagstr = "";
    foreach ($tags as $tag) {
        $tagstr .= "<a href='" . SITE_URL . "/courseware/$tag'>$tag</a> ";
    }
    if (isset($_SESSION['loggedin']) && $_SESSION['user']['accesslevel'] >= 9) {
        $tagstr .="<br/>
                <input type='text' id='$row[id]' class='tagsinput' value='$row[tags]'/>
                <a class='btn update' href='#' id='update_$row[id]'>Update</a> 
                <a class='btn btn-danger delete' href='#' id='delete_$row[id]'>Delete</a>";
    }
    echo "<tr><td><a target='_blank' href='" . SITE_URL . "/course/$row[filename]'>$row[filename]</a></td><td>" . round(filesize("/srv/http/dchub/course/$row[filename]") / (1024 * 1024), 2) . " MB</td><td>$tagstr</td></tr>";
}
echo "</table>";
?>