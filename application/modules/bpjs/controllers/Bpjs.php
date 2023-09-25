<?php
defined('BASEPATH') or exit('No direct script access allowed');

use LZCompressor\LZString as LZString;

class Bpjs extends MX_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Antrian_model');
        $this->load->model('Rajal_model');
    }

    public function stringDecrypt($key, $content)
    {
        $encrypt_method = 'AES-256-CBC';
        $key_hash = hex2bin(hash('sha256', $key));
        $iv = substr(hex2bin(hash('sha256', $key)), 0, 16);
        $output = openssl_decrypt(base64_decode($content), $encrypt_method, $key_hash, OPENSSL_RAW_DATA, $iv);
        $output2  = \LZCompressor\LZString::decompressFromEncodedURIComponent($output);
        echo $output2;
    }

    public function signatureantreanbpjs()
    {
        $data = getenv('BPJS_ANTREAN_CONSID');
        $secretKey = getenv('BPJS_ANTREAN_SIGNATURE');
        $user_key = getenv('BPJS_ANTREAN_USERKEY');

        date_default_timezone_set('UTC');
        $tStamp = strval(time() - strtotime('1970-01-01 00:00:00'));

        $signature = hash_hmac('sha256', $data . "&" . $tStamp, $secretKey, true);
        $encodedSignature = base64_encode($signature);


        $content = [
            'X-cons-id' => $data,
            'X-timestamp' => $tStamp,
            'X-signature' => $encodedSignature,
            'User-key' => $user_key
        ];
        echo json_encode($content);
    }

    public function signaturevclaimbpjs()
    {
        $data = getenv('BPJS_VCLAIM_CONSID');
        $secretKey = getenv('BPJS_VCLAIM_SIGNATURE');
        $user_key = getenv('BPJS_VCLAIM_USERKEY');

        date_default_timezone_set('UTC');
        $tStamp = strval(time() - strtotime('1970-01-01 00:00:00'));

        $signature = hash_hmac('sha256', $data . "&" . $tStamp, $secretKey, true);
        $encodedSignature = base64_encode($signature);


        $content = [
            'X-cons-id' => $data,
            'X-timestamp' => $tStamp,
            'X-signature' => $encodedSignature,
            'User-key' => $user_key
        ];
        echo json_encode($content);
    }
}
