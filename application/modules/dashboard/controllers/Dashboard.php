<?php
defined('BASEPATH') or exit('No direct script access allowed');

use LZCompressor\LZString as LZString;

class Dashboard extends MX_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Antrian_model');
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

    public function index()
    {
        $data['title'] = 'Console Pendaftaran';
        $data['sisa_antrian_pendaftaran'] = $this->Antrian_model->sisa_antrian_pendaftaran();
        $data['sisa_antrian_poli'] = $this->Antrian_model->sisa_antrian_poli();
        $data['sisa_antrian_farmasi'] = $this->Antrian_model->sisa_antrian_farmasi();
        $data['sisa_antrian_batal'] = $this->Antrian_model->sisa_antrian_batal();

        $data['content'] = '';
        $page = 'dashboard/index';
        echo modules::run('template/loadview_console', $data, $page);
    }

    public function cekDataKodebooking()
    {
        $kodebooking = $_POST['kodebooking'];
        $databooking = $this->db->get_where('simrsj_webservice.antrean', ['kodebooking' => $kodebooking])->row_array();
        if ($databooking) {
            if ($databooking['tanggalperiksa'] != date('Y-m-d')) {
                echo json_encode([
                    'response' => $databooking['tanggalperiksa'],
                    'metadata' => [
                        'message' => 'Tanggal Periksa Anda : ' . date("d-m-Y", strtotime($databooking['tanggalperiksa'])),
                        'code' => 201
                    ]
                ], 201);
            } else if ($databooking['jeniskunjungan'] == '2' || $databooking['jeniskunjungan'] == '4') {
                echo json_encode([
                    'response' => 'Jenis Kunjungan Tidak Sesuai',
                    'metadata' => [
                        'message' => 'Silahkan Daftar Langsung Ke Admisi Pendaftaran',
                        'code' => 201
                    ]
                ], 201);
            } else {
                //SEP
                $data_sep = getenv('BPJS_VCLAIM_CONSID');
                $secretKey_sep = getenv('BPJS_VCLAIM_SIGNATURE');
                $user_key_sep = getenv('BPJS_VCAIM_USERKEY');

                date_default_timezone_set('UTC');
                $tStamp_sep = strval(time() - strtotime('1970-01-01 00:00:00'));

                $signature_sep = hash_hmac('sha256', $data_sep . "&" . $tStamp_sep, $secretKey_sep, true);
                $encodedSignature_sep = base64_encode($signature_sep);

                $headers = [
                    'X-cons-id: ' . $data_sep . '',
                    'X-timestamp: ' . $tStamp_sep . '',
                    'X-signature: ' . $encodedSignature_sep . '',
                    'User-key: ' . $user_key_sep . '',
                    'Content-Type: Application/x-www-form-urlencoded',
                ];

                // Parameter POST SEP
                $klsRawat = [
                    'klsRawatHak' => "",
                    'klsRawatNaik' => "",
                    'pembiayaan' => "",
                    'penanggungJawab' => "",
                ];

                $rujukan = [
                    'asalRujukan' => "",
                    'tglRujukan' => "",
                    'noRujukan' => "",
                    'ppkRujukan' => "",
                ];
                $poli = [
                    'tujuan' => $databooking['kodepoli'],
                    'eksekutif' => "0",
                ];
                $cob = [
                    'cob' => "0",
                ];
                $katarak = [
                    'katarak' => "0",
                ];
                $lakaLantas = "0";
                $noLP = "";
                $tglKejadian = "";
                $keterangan = "";
                $suplesi = "";
                $noSepSuplesi = "";
                $kdPropinsi = "";
                $kdKabupaten = "";
                $kdKecamatan = "";
                $jaminan = [
                    'lakaLantas' => $lakaLantas,
                    'noLP' => $noLP,
                    'penjamin' => [
                        'tglKejadian' => $tglKejadian,
                        'keterangan' => $keterangan,
                        'suplesi' => [
                            'suplesi' => $suplesi,
                            'noSepSuplesi' => $noSepSuplesi,
                            'lokasiLaka' => [
                                'kdPropinsi' => $kdPropinsi,
                                'kdKabupaten' => $kdKabupaten,
                                'kdKecamatan' => $kdKecamatan,
                            ]
                        ]
                    ]
                ];

                if ($databooking['jeniskunjungan'] == '1') {
                    $noSurat = "";
                    $tujuanKunj = "0";
                    $flagProcedure = "";
                    $kdPenunjang = "";
                    $assesmentPel = "";
                    $catatan = "Kunjungan Pertama";
                } elseif ($databooking['jeniskunjungan'] == '3') {
                    $noSurat = $databooking['nomorreferensi'];
                    $tujuanKunj = "3";
                    $flagProcedure = "";
                    $kdPenunjang = "";
                    $assesmentPel = "5";
                    $catatan = "Kontrol";
                }

                $skdp = [
                    'noSurat' => $noSurat,
                    'kodeDPJP' => $databooking['kodedokter'],
                ];

                $noKartu = $databooking['nomorkartu'];
                $tglSep = date('Y-m-d');
                $noMR = $databooking['norm'];
                $diagAwal = "";
                $dpjpLayan = $databooking['kodedokter'];
                $noTelp = "0";

                $dataarray_sep['request']['t_sep'] = [
                    'noKartu'       => $noKartu,
                    'tglSep'        => $tglSep,
                    'ppkPelayanan'  => '0301R002',
                    'jnsPelayanan'  => '2',
                    'klsRawat'      => $klsRawat,
                    'noMR'          => $noMR,
                    'rujukan'       => $rujukan,
                    'catatan'       => $catatan,
                    'diagAwal'      => $diagAwal,
                    'poli'          => $poli,
                    'cob'           => $cob,
                    'katarak'       => $katarak,
                    'jaminan'       => $jaminan,
                    'tujuanKunj'    => $tujuanKunj,
                    'flagProcedure' => $flagProcedure,
                    'kdPenunjang'   => $kdPenunjang,
                    'assesmentPel'  => $assesmentPel,
                    'skdp'          => $skdp,
                    'dpjpLayan'     => $dpjpLayan,
                    'noTelp'        => $noTelp,
                    'user'          => "Online",
                ];

                $postdata_sep = json_encode($dataarray_sep); //ubah data array ke JSON

                $ch_sep = curl_init();
                curl_setopt($ch_sep, CURLOPT_URL, getenv('BPJS_VCLAIM_URL') . "/SEP/2.0/insert");
                curl_setopt($ch_sep, CURLOPT_POST, 1);
                curl_setopt($ch_sep, CURLOPT_POSTFIELDS, $postdata_sep);
                curl_setopt($ch_sep, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch_sep, CURLOPT_HTTPHEADER, $headers);
                $content = curl_exec($ch_sep);
                curl_close($ch_sep);

                $key_sep = '' . $data_sep . '' . $secretKey_sep . '' . $tStamp_sep . '';
                $resultarr = json_decode($content, true);
                $response = $this->stringDecrypt($key_sep, $resultarr['response']);
                if ($resultarr['metaData']['code'] == 200) {
                    echo $response;
                } else {
                    echo json_encode([
                        'metadata' => $resultarr['metaData'],
                    ], 201);
                }
            }
        } else {
            echo json_encode([
                'metadata' => [
                    'message' => 'Antrean Tidak Ditemukan',
                    'code' => 201
                ],
            ], 201);
        }
    }

    //update waktu antrean bpjs
    public function waktuBpjsCheckin()
    {
        // $data = "5231";
        // $secretKey = "7rA70A8D69";
        $data = getenv('BPJS_ANTREAN_CONSID');
        $secretKey = getenv('BPJS_ANTREAN_SIGNATURE');
        $user_key = getenv('BPJS_ANTREAN_USERKEY');

        date_default_timezone_set('UTC');
        $tStamp = strval(time() - strtotime('1970-01-01 00:00:00'));

        $signature = hash_hmac('sha256', $data . "&" . $tStamp, $secretKey, true);
        $encodedSignature = base64_encode($signature);

        $headers = [
            'X-cons-id: ' . $data . '',
            'X-timestamp: ' . $tStamp . '',
            'X-signature: ' . $encodedSignature . '',
            'User-key: ' . $user_key . '',
            // 'Content-Type: Application/x-www-form-urlencoded',
        ];

        // $kodetgl = preg_replace("/-/", "", date('Y-m-d'));
        $kodebooking = $_POST['kodebooking'];
        // $kodepoli = $_POST['kode_poli'];
        // $noantrean = $_POST['angka_antrian'];
        $yourdate = date("Y-m-d H:i:s");
        $stamp = strtotime($yourdate);
        $estimasidilayani = $stamp * 1000;

        $dataarray = [
            'kodebooking' => $kodebooking,
            "taskid" => 3,
            "waktu" => $estimasidilayani
        ];



        $postdata = json_encode($dataarray); //ubah data array ke JSON

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, getenv('BPJS_ANTREAN_URL') . "antrean/updatewaktu");
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $content = curl_exec($ch);
        curl_close($ch);

        $resultarr = json_decode($content, true);
        $key = '' . $data . '' . $secretKey . '' . $tStamp . '';
        // if ($resultarr['metaData']['code'] == 200) {
        // $response = $this->stringDecrypt($key, $resultarr['response']);
        echo json_encode($resultarr);
        // } else {
        //     echo json_encode($resultarr['metaData']);
        // }
    }

    public function cariBnokartu()
    {
        $data = getenv('BPJS_VCLAIM_CONSID');
        $secretKey = getenv('BPJS_VCLAIM_SIGNATURE');
        $user_key = getenv('BPJS_VCAIM_USERKEY');

        date_default_timezone_set('UTC');
        $tStamp = strval(time() - strtotime('1970-01-01 00:00:00'));

        $signature = hash_hmac('sha256', $data . "&" . $tStamp, $secretKey, true);
        $encodedSignature = base64_encode($signature);

        $ch = curl_init();
        $headers = [
            'X-cons-id: ' . $data . '',
            'X-timestamp: ' . $tStamp . '',
            'X-signature: ' . $encodedSignature . '',
            'User-key: ' . $user_key . '',
            'Content-Type: application/json; charset=utf-8',
        ];
        if ($_POST['jenis_faskes2'] == 1) {
            $url = getenv('BPJS_VCLAIM_URL') . "Rujukan/Peserta/" . $_POST['no_kartu1'];
        } else {
            $url = getenv('BPJS_VCLAIM_URL') . "Rujukan/RS/Peserta/" . $_POST['no_kartu1'];
        }
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 3);
        curl_setopt($ch, CURLOPT_HTTPGET, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $content = curl_exec($ch);
        // $err = curl_error($ch);
        curl_close($ch);

        $resultarr = json_decode($content, true);
        $key = '' . $data . '' . $secretKey . '' . $tStamp . '';
        if ($resultarr['metaData']['code'] == 200) {
            $response = $this->stringDecrypt($key, $resultarr['response']);
            echo $response;
        } else {
            echo json_encode($resultarr['metaData']['message']);
        }
    }
}
