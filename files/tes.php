<h1>Test</h1>
<pre>
<?php
$str = "212,1212,12,12,5,53,668";
$ex = explode(',', $str);
print_r($ex);
$im = implode(',', $ex);
echo $im."<br/>";
print_r($_SESSION);
?>
</pre>