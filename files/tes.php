<h1>Test</h1>
<?php
$url = 'https://172.16.1.1:8090/login.xml';
$data = array('mode' => '191', 'username' => 'be13222010', 'password' => 'abs', 'a' => (string) (time() * 1000));

// use key 'http' even if you send the request to https://...
$options = array(
    'http' => array(
        'header' => "Content-type: application/x-www-form-urlencoded\r\n",
        'method' => 'POST',
        'content' => http_build_query($data),
    ),
);
$context = stream_context_create($options);
$result = file_get_contents($url, false, $context);
$xml = simplexml_load_string($result) 
        or die("Error: Can not create object");
print_r($xml->message);
?>