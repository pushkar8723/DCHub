<h1>Courseware</h1>
<?php
if (isset($_SESSION['loggedin']) && $_SESSION['user']['accesslevel'] >= 9) {
    echo "<form class='form-inline' action='".SITE_URL."/process.php' method='post' enctype='multipart/form-data'>
            <label for='fileadd'>File : </label>
            <input type='file' name='fileadd' id='fileadd'/>
            <input type='submit' name='courseware' class='btn' value='Upload'/>
          <form><br/><br/>";
}
echo "<div class='alert'>This page contains direct links for various courseware.
    If you want something to be added here contact admin.</div>
    <table class='table table-hover'>
    <tr><th>Filename</th><th>Size</th></tr>";
if ($handle = opendir('/srv/http/dchub/course/')) {
    $files = array();
    while (false !== ($entry = readdir($handle))) {
        if ($entry != "." && $entry != "..") {
            array_push($files, $entry);
        }
    }
    closedir($handle);
    natcasesort($files);
    foreach($files as $entry){
        echo "<tr><td><a target='_blank' href='".SITE_URL."/course/$entry'>$entry</a></td><td>".round(filesize("/srv/http/dchub/course/$entry")/(1024*1024), 2)." MB</td></tr>";
    }
}
echo "</table>";
?>