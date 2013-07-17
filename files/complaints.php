<?php
if(isset($_SESSION['loggedin'])){
	echo "<h1>Complaints</h1>
	<form action='".SITE_URL."/process.php' method='post'>
		<label for='msg'>Message</label>
		<textarea name='msg' id='msg' required></textarea><br/>
		<input type='submit' name='complaints' class='btn' value='Post'/>
	</form>";
} else {
	echo "<br/><br/><br/><h1>Login to Post Complaints</h1>Only registered users can post complaints.<br/><br/><br/>";
}
?>
