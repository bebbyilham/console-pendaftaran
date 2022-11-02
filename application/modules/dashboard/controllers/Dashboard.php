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
                $data = getenv('BPJS_VCLAIM_CONSID');
                $secretKey = getenv('BPJS_VCLAIM_SIGNATURE');
                $user_key = getenv('BPJS_VCAIM_USERKEY');

                date_default_timezone_set('UTC');
                $tStamp = strval(time() - strtotime('1970-01-01 00:00:00'));

                $signature = hash_hmac('sha256', $data . "&" . $tStamp, $secretKey, true);
                $encodedSignature = base64_encode($signature);

                $headers = [
                    'X-cons-id: ' . $data . '',
                    'X-timestamp: ' . $tStamp . '',
                    'X-signature: ' . $encodedSignature . '',
                    'User-key: ' . $user_key . '',
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

                $dataarray['request']['t_sep'] = [
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

                $postdata = json_encode($dataarray); //ubah data array ke JSON

                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, getenv('BPJS_VCLAIM_URL') . "/SEP/2.0/insert");
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                $content = curl_exec($ch);
                curl_close($ch);

                $key = '' . $data . '' . $secretKey . '' . $tStamp . '';
                $resultarr = json_decode($content, true);
                $response = $this->stringDecrypt($key, $resultarr['response']);
                if ($resultarr['metaData']['code'] == 200) {
                    echo $response;
                } else {
                    echo json_encode([
                        'metadata' => $resultarr['metaData'],
                    ], 201);
                }
                // echo json_encode([
                //     'response' => $databooking,
                //     'metadata' => [
                //         'message' => 'Ok',
                //         'code' => 200
                //     ]
                // ], 200);
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
}
