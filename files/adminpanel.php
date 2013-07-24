<?php
if (!isset($_SESSION['loggedin']) || $_SESSION['user']['accesslevel'] < 10) {
    echo "<br/><br/><br/><h1>Permission Denied :(</h1>You don't have enough previledges.<br/><br/><br/>";
    return;
}
?>
<script type="text/javascript">
    $(document).ready(function() {
        $('#submit').click(function() {
            adminsearch();
        });
        $(document).keypress(function(e) {
            if (e.which == 13) {
                adminsearch();
            }
        });
        $('#myTab a').click(function(e) {
            alert('hi');
            e.preventDefault();
            $(this).tab('show');
        });
    });
    function adminsearch() {
        var nick, fullname, ip, roll;
        nick = $('#nick').val();
        fullname = $('#fullname').val();
        ip = $('#ip').val();
        roll = $('#rollno').val();
        $('#results').html("Loading...");
        $.post("<?php echo SITE_URL; ?>/process.php", {
            "adminsearch": "",
            "data[nick]": nick,
            "data[fullname]": fullname,
            "data[ip]": ip,
            "data[roll]": roll
        }, function(data) {
            $('#results').html(data);
        });
    }
    function adminselect(id) {
        $('#results').html("Loading massive data... Please Wait");
        $.post("<?php echo SITE_URL; ?>/process.php", {
            "adminselect": "",
            "id": id
        }, function(data) {
            $('#results').html(data);
        });
    }
</script>
<center><h1>Admin Panel</h1></center>
<div class="alert" style="text-align: center;">This is official admin stalking center :P customized to meet all your needs. :D</div>
<div class="form-inline">
    <input type="text" id="nick" placeholder="nick" />
    <input type="text" id="fullname" placeholder="Full Name" />
    <input type="text" id="ip" placeholder="IP Address" />
    <input type="text" id="rollno" placeholder="Roll No (without slashes)" />
    <input type="button" class="btn btn-large" value="Search" id="submit">
</div>
<hr/>
<div id="results">
    <center><h1>Results will appear here</h1></center>
</div>