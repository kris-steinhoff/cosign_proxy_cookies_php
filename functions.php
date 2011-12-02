<?php
function get_cosign_proxy_cookies() {
    $proxy_db = '/var/cosign/proxy';

    // replace . with _ to match the value in $_COOKIE
    $cookie_name = str_replace('.', '_', $_SERVER['COSIGN_SERVICE']);

    // pull the cookie value out of the $_COOKIE superglobal:
    $cookie_value = $_COOKIE[$cookie_name];

    // replace values decoded by PHP:
    $cookie_value = str_replace(' ', '+', $cookie_value);

    // remove the trailing timestamp:
    preg_match('/^(.*)\//', $cookie_value, $matches);
    $cookie_value = $matches[1];

    // compose the proxy file name:
    $proxy_file = $proxy_db .'/'. $_SERVER['COSIGN_SERVICE'] .'='. $cookie_value;

    // open the proxy file (into $proxy_cookies_raw):
    if ( ! $proxy_cookies_raw = file($proxy_file)) {
        die('could not open proxy file ('. $proxy_file .')'."\n");
    }

    // parse each $proxy_cookies_raw into the $proxy_cookies array:
    $proxy_cookies = array();
    foreach ($proxy_cookies_raw as $proxy_cookies_raw) {
        preg_match('/^x(.*)=(.*) (.*)$/', $proxy_cookies_raw, $matches);
        $proxy_cookies[] = array(
            'service name' => $matches[1],
            'cookie value' => $matches[2],
            'service hostname' => $matches[3]
        );
    }

    return $proxy_cookies;
}
