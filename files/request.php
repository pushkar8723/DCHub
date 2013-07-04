<script type='text/javascript'>
    $(document).ready(function() {
        $('.volunteer, .chickenout').click(function(event) {
            $('#' + event.target.id).html("Processing...");
            if ($('#' + event.target.id).attr('class') == "volunteer") {
                $.post("<?php echo SITE_URL; ?>/process.php", {
                    "volunteer": '',
                    "cid": event.target.id
                }, function(result) {
                    if (result == '1') {
                        $('#' + event.target.id).removeClass('volunteer').addClass('chickenout').html('Chicken Out');
                        if($('#' + event.target.id + "_volcount").html() == "No one")
                            $('#' + event.target.id + "_volcount").html("<?php if (isset($_SESSION['loggedin'])) echo "<a href=\\\"".SITE_URL."/users/".$_SESSION['user']['nick']."\\\">".$_SESSION['user']['nick']."</a>"; ?>");
                        else
                            $('#' + event.target.id + "_volcount").html($('#' + event.target.id + "_volcount").html()+" <?php if (isset($_SESSION['loggedin'])) echo "<a href='".SITE_URL."/users/".$_SESSION['user']['nick']."'>".$_SESSION['user']['nick']."</a>"; ?>");
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
                        str = str.replace("<?php if (isset($_SESSION['loggedin'])) echo "<a href=\\\"".SITE_URL."/users/".$_SESSION['user']['nick']."\\\">".$_SESSION['user']['nick']."</a>"; ?>", "");
                        str = str.trim();
                        if(str == "")
                            $('#' + event.target.id + "_volcount").html("No one");
                        else
                            $('#' + event.target.id + "_volcount").html(str);

                    } else {
                        $('#' + event.target.id).html(result);
                    }
                });
            }
        });
    });
</script>
<h1>Request Page</h1>
<?php
if (isset($_GET['page']) && $_GET['page'] > 0) {
    $page = $_GET['page'];
} else {
    $page = 1;
}
if(isset($_SESSION['loggedin'])){
    echo "<h4>Make a request</h4>
        <form action='".SITE_URL."/process.php' method='post'>
        <textarea name='data[request_file]'></textarea><br/>
        <input type='submit' value='Request' name='request' class='btn'/>
        </form>";
} else {
    echo "<a href='#' onclick=\"$('#signin').popover('show');\" class='btn btn-danger btn-block btn-large'>Login to make a Request</a>";
}
$body = "from dchub_request where deleted = 0 order by id desc";
$res = DB::findAllWithCount("select *", $body, $page, 20);
$data = $res['data'];
foreach($data as $row){
    $row['request_file'] = htmlspecialchars(preg_replace('/\n/', '<br/>', $row['request_file']));
    $user = DB::findOneFromQuery("select nick1 from dchub_users where id = $row[uid]");
    echo "<hr/><b><a href='".SITE_URL."/users/$user[nick1]'>$user[nick1]</a> requested for :</b><br/>
            $row[request_file]<br/>";
    $vol = explode(',', $row['volunteer']);
    if(isset($_SESSION['loggedin']) && !in_array($_SESSION['user']['nick'], $vol)){
        echo "<a id='$row[id]' class='volunteer' href='#'>Volunteer</a><br/>";
    } else if(isset($_SESSION['loggedin']) && in_array($_SESSION['user']['nick'], $vol)){
        echo "<a id='$row[id]' class='chickenout' href='#'>Chicken Out</a><br/>";
    } else {
        echo "<a href='".SITE_URL."'>Login to Volunteer</a><br/>";
    }
    echo "<span id='$row[id]_volcount'>";
    if($row['volunteer'] != ""){
        
        $vol = explode(',', $row['volunteer']);
        foreach ($vol as $pick){
            if($pick != "")
                echo "<a href='".SITE_URL."/users/$pick'>$pick</a> ";
        }
    } else {
        echo "No one";
    }
    echo "</span> has volunteered<br/>";
}
?>