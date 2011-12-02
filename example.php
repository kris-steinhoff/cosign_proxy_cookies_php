<?php
include 'functions.php';

// get the proxy cookies:
$proxy_cookies = get_cosign_proxy_cookies();

// try to connect to the first host found in the proxy file (adapted from
// http://www.php.net/manual/en/function.stream-context-create.php, example 1):
$opts = array(
    'http'=>array(
        'method'=>"GET",
        'header'=> 'Cookie: '. $proxy_cookies[0]['service name'] .'='. $proxy_cookies[0]['cookie value'] ."\r\n"
    )
);
$context = stream_context_create($opts);
$contents = file_get_contents('https://'. $proxy_cookies[0]['service hostname'] .'/', false, $context);

// display the fetched contents:
echo '<strong>Contents of '. 'https://'. $proxy_cookies[0]['service hostname'] .'/' .':</strong>'."\n";
echo '<pre>';
echo htmlentities($contents);
echo '</pre>';
?>
