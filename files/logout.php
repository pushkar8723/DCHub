<h1>Logout</h1>
<?php
if (isset($_POST['logoutcyber'])) {
    $url = 'https://172.16.1.1:8090/login.xml';
    $data = array('mode' => '193', 'username' => $_POST['id'], 'a' => (string) (time() * 1000));
    $options = array(
        'http' => array(
            'header' => "Content-type: application/x-www-form-urlencoded\r\n",
            'method' => 'POST',
            'content' => http_build_query($data),
        ),
    );
    $context = stream_context_create($options);
    $result = file_get_contents($url, false, $context);
    $xml = simplexml_load_string($result) or $_SESSION['msg'] = 'Error! contact the Admins';
    echo "<h3>$xml->message</h3>";
}
?>
<form action="<?php echo SITE_URL; ?>/logout" method="post">
    Cyberoam ID:<br/>
    <input type="text" name ="id"><br/>
    <input type="submit" name="logoutcyber" class="btn"/>
</form>
