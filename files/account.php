<?php 
if (!isset($_SESSION['loggedin'])) {
    echo "<br/><br/><br/><h1>Not Logged in :(</h1>You need to login to manage account.<br/><br/><br/>";
    return;
}
?>
<center><h1>Account Settings</h1></center>
<?php if ($_SESSION['user']['accesslevel'] == 0) { ?>
    <div class="auth" style="padding: 15px;">
        <h3>Authentication</h3>
        <hr>
        <h4><b>There are two methods to authenticate yourself</b></h4><br/>
        <div class="row-fluid">
            <div class="span6 auth">
                <h4><b>Method 1:</b> Use your Cyberoam Password</h4><br/>
                <?php
                    $fields = array("cyberpass" => array("Cyberoam Password", "password"));
                    createForm('cyberauth', $fields, 'Authenticate');
                ?>
            </div>
            <div class="span6 auth">
                <h4><b>Method 2:</b> Ask a friend</h4><br/>
                <?php
                $query = "select friend from dchub_users where id = " . $_SESSION['user']['id'];
                $res = DB::findOneFromQuery($query);
                $fields = array("friend" => array("Friend's nick", "text", $res['friend']));
                createForm('friendauth', $fields, 'Submit')
                ?>
            </div>
        </div>
    </div>
    <?php
}
if (!isset($_SESSION['user']['nick2'])){ ?>
    <h3>Add another nick</h3>
    <?php
    $fields = array(
        'data[nick2]' => array("Nick", "text")
    );
     createForm('addnick', $fields, 'Add nick');
}
?>
<h3>Change Password</h3>
<?php
$fields = array(
    "data[oldpassword]" => array("Old Password", "password"), 
    "data[newpassword]" => array("New Password", "password"),
    "data[repassword]" => array("Re Password", "password")
    );
createForm('changepasswd', $fields , "Change");
?>
<h3>Manage Details</h3>
<?php
$fields = array(
    "data[phone]" => array("Mobile", "text", $_SESSION['user']['phone']), 
    "data[email]" => array("email", "text",  $_SESSION['user']['email']),
    );
createForm('updatedetails', $fields , "Update");
?>