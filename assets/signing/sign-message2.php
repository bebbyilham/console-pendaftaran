<?php
// require_once BASEPATH . '/helpers/url_helper.php';
$KEY = __DIR__ . '\key.pem';
$req = $_GET['request'];  // i.e. 'toSign' from JS
$privateKey = openssl_get_privatekey(file_get_contents($KEY));
$signature = null;
openssl_get_curve_names($req, $signature, $privateKey);
if ($signature) {
	header("Content-type: text/plain");
	echo base64_encode($signature);
	exit(0);
}
echo '<h1>Error signing message</h1>';
exit(1);
