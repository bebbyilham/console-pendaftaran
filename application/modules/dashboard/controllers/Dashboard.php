<?php
defined('BASEPATH') or exit('No direct script access allowed');

use LZCompressor\LZString as LZString;

class Dashboard extends MX_Controller
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
        // $databooking = $this->db->select('*')->from('simrsj_webservice.antrean')->where(['kodebooking' => $kodebooking])->or_where(['norm' => $kodebooking])->result();
        $databooking = $this->db->select('*')
            ->order_by('tanggalperiksa', "desc")->limit(1)
            ->like('kodebooking', $kodebooking)
            ->or_like('norm',  preg_replace("/-/", "", $kodebooking))
            ->get('simrsj_webservice.antrean')->row_array();
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
                // $cekkunjungan = $this->db->get_where('simrsj_webservice.antrean', ['kodebooking' => $kodebooking])->row_array();
                // if ($cekkunjungan) {
                //     echo json_encode([
                //         'metadata' => [
                //             'message' => 'Kunjungan Sudah Ada',
                //             'code' => 201
                //         ],
                //     ], 201);
                // } else {
                // }
                //CARI RUJUKAN
                $data_cariBnokartu = getenv('BPJS_VCLAIM_CONSID');
                $secretKey_cariBnokartu = getenv('BPJS_VCLAIM_SIGNATURE');
                $user_key_cariBnokartu = getenv('BPJS_VCAIM_USERKEY');

                date_default_timezone_set('UTC');
                $tStamp_cariBnokartu = strval(time() - strtotime('1970-01-01 00:00:00'));

                $signature_cariBnokartu = hash_hmac('sha256', $data_cariBnokartu . "&" . $tStamp_cariBnokartu, $secretKey_cariBnokartu, true);
                $encodedSignature_cariBnokartu = base64_encode($signature_cariBnokartu);

                $ch_cariBnokartu = curl_init();
                $headers_cariBnokartu = [
                    'X-cons-id: ' . $data_cariBnokartu . '',
                    'X-timestamp: ' . $tStamp_cariBnokartu . '',
                    'X-signature: ' . $encodedSignature_cariBnokartu . '',
                    'User-key: ' . $user_key_cariBnokartu . '',
                    'Content-Type: application/json; charset=utf-8',
                ];
                // if ($_POST['jenis_faskes2'] == 1) {
                //     $url_cariBnokartu = getenv('BPJS_VCLAIM_URL') . "Rujukan/Peserta/" . $databooking['nomorkartu'];
                // } else {
                //     $url_cariBnokartu = getenv('BPJS_VCLAIM_URL') . "Rujukan/RS/Peserta/" . $databooking['nomorkartu'];
                // }

                $url_cariBnokartu = getenv('BPJS_VCLAIM_URL') . "Rujukan/Peserta/" . $databooking['nomorkartu'];
                curl_setopt($ch_cariBnokartu, CURLOPT_URL, $url_cariBnokartu);
                curl_setopt($ch_cariBnokartu, CURLOPT_HTTPHEADER, $headers_cariBnokartu);
                curl_setopt($ch_cariBnokartu, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch_cariBnokartu, CURLOPT_TIMEOUT, 3);
                curl_setopt($ch_cariBnokartu, CURLOPT_HTTPGET, 1);
                curl_setopt($ch_cariBnokartu, CURLOPT_SSL_VERIFYPEER, false);
                $content_cariBnokartu = curl_exec($ch_cariBnokartu);

                $resultarr = json_decode($content_cariBnokartu, true);
                // $key_cariBnokartu = '' . $data_cariBnokartu . '' . $secretKey_cariBnokartu . '' . $tStamp_cariBnokartu . '';
                if ($resultarr['metaData']['code'] == 200) {
                    $key_cariBnokartu = '' . $data_cariBnokartu . '' . $secretKey_cariBnokartu . '' . $tStamp_cariBnokartu . '';
                    $encrypt_method = 'AES-256-CBC';
                    $key_hash = hex2bin(hash('sha256', $key_cariBnokartu));
                    $iv = substr(hex2bin(hash('sha256', $key_cariBnokartu)), 0, 16);
                    $output = openssl_decrypt(base64_decode($resultarr['response']), $encrypt_method, $key_hash, OPENSSL_RAW_DATA, $iv);
                    $output2  = \LZCompressor\LZString::decompressFromEncodedURIComponent($output);
                    $resultarr_cariBnokartu = json_decode($output2, true);
                    curl_close($ch_cariBnokartu);
                    // echo $resultarr_cariBnokartu;

                    // if ($resultarr_cariBnokartu == 'Rujukan Tidak Ada') {
                    // var_dump($resultarr_cariBnokartu['rujukan']['noKunjungan']);
                    // } else {
                    //     var_dump('OK');
                    // }

                    $noRujukan = $resultarr_cariBnokartu['rujukan']['noKunjungan'];
                    $tglRujukan = $resultarr_cariBnokartu['rujukan']['tglKunjungan'];
                    $asalRujukan = $resultarr_cariBnokartu['asalFaskes'];
                    $ppkRujukan = $resultarr_cariBnokartu['rujukan']['provPerujuk']['kode'];
                    $diagAwalcariBnokartu = $resultarr_cariBnokartu['rujukan']['diagnosa']['kode'];
                    $noTelepon = $resultarr_cariBnokartu['rujukan']['peserta']['mr']['noTelepon'];
                    $namappkRujukan = $resultarr_cariBnokartu['rujukan']['provPerujuk']['nama'];
                    // $data['data_peserta'] = $resultarr6;
                } else if ($resultarr['metaData']['message'] == 'Rujukan Tidak Ada') {
                    // echo json_encode($resultarr['metaData']['message']);
                    // echo $resultarr['metaData']['message'];
                    $url_cariBnokartu = getenv('BPJS_VCLAIM_URL') . "Rujukan/RS/Peserta/" . $databooking['nomorkartu'];
                    curl_setopt($ch_cariBnokartu, CURLOPT_URL, $url_cariBnokartu);
                    curl_setopt($ch_cariBnokartu, CURLOPT_HTTPHEADER, $headers_cariBnokartu);
                    curl_setopt($ch_cariBnokartu, CURLOPT_RETURNTRANSFER, 1);
                    curl_setopt($ch_cariBnokartu, CURLOPT_TIMEOUT, 3);
                    curl_setopt($ch_cariBnokartu, CURLOPT_HTTPGET, 1);
                    curl_setopt($ch_cariBnokartu, CURLOPT_SSL_VERIFYPEER, false);
                    $content_cariBnokartu = curl_exec($ch_cariBnokartu);

                    $resultarr = json_decode($content_cariBnokartu, true);
                    $key_cariBnokartu = '' . $data_cariBnokartu . '' . $secretKey_cariBnokartu . '' . $tStamp_cariBnokartu . '';
                    $encrypt_method = 'AES-256-CBC';
                    $key_hash = hex2bin(hash('sha256', $key_cariBnokartu));
                    $iv = substr(hex2bin(hash('sha256', $key_cariBnokartu)), 0, 16);
                    $output = openssl_decrypt(base64_decode($resultarr['response']), $encrypt_method, $key_hash, OPENSSL_RAW_DATA, $iv);
                    $output2  = \LZCompressor\LZString::decompressFromEncodedURIComponent($output);
                    $resultarr_cariBnokartu = json_decode($output2, true);
                    curl_close($ch_cariBnokartu);
                    // var_dump($resultarr_cariBnokartu['rujukan']['noKunjungan']);
                    $noRujukan = $resultarr_cariBnokartu['rujukan']['noKunjungan'];
                    $tglRujukan = $resultarr_cariBnokartu['rujukan']['tglKunjungan'];
                    $asalRujukan = $resultarr_cariBnokartu['asalFaskes'];
                    $ppkRujukan = $resultarr_cariBnokartu['rujukan']['provPerujuk']['kode'];
                    $diagAwalcariBnokartu = $resultarr_cariBnokartu['rujukan']['diagnosa']['kode'];
                    $noTelepon = $resultarr_cariBnokartu['rujukan']['peserta']['mr']['noTelepon'];
                    $namappkRujukan = $resultarr_cariBnokartu['rujukan']['provPerujuk']['nama'];
                } else {
                    echo $resultarr['metaData']['message'];
                }

                //CHECKIN
                // $data_BpjsCheckin = getenv('BPJS_ANTREAN_CONSID');
                // $secretKey_BpjsCheckin = getenv('BPJS_ANTREAN_SIGNATURE');
                // $user_key_BpjsCheckin = getenv('BPJS_ANTREAN_USERKEY');

                // date_default_timezone_set('UTC');
                // $tStamp_BpjsCheckin = strval(time() - strtotime('1970-01-01 00:00:00'));

                // $signature_BpjsCheckin = hash_hmac('sha256', $data_BpjsCheckin . "&" . $tStamp_BpjsCheckin, $secretKey_BpjsCheckin, true);
                // $encodedSignature_BpjsCheckin = base64_encode($signature_BpjsCheckin);

                // $headers_BpjsCheckin = [
                //     'X-cons-id: ' . $data_BpjsCheckin . '',
                //     'X-timestamp: ' . $tStamp_BpjsCheckin . '',
                //     'X-signature: ' . $encodedSignature_BpjsCheckin . '',
                //     'User-key: ' . $user_key_BpjsCheckin . '',
                //     // 'Content-Type: Application/x-www-form-urlencoded',
                // ];

                // // $kodetgl = preg_replace("/-/", "", date('Y-m-d'));
                // $kodebooking_BpjsCheckin = $_POST['kodebooking'];
                // // $kodepoli = $_POST['kode_poli'];
                // // $noantrean = $_POST['angka_antrian'];
                // $yourdate_BpjsCheckin = date("Y-m-d H:i:s");
                // $stamp_BpjsCheckin = strtotime($yourdate_BpjsCheckin);
                // $estimasidilayani_BpjsCheckin = $stamp_BpjsCheckin * 1000;

                // $dataarray_BpjsCheckin = [
                //     'kodebooking' => $kodebooking_BpjsCheckin,
                //     "taskid" => 3,
                //     "waktu" => $estimasidilayani_BpjsCheckin
                // ];



                // $postdata_BpjsCheckin = json_encode($dataarray_BpjsCheckin); //ubah data array ke JSON

                // $ch_BpjsCheckin = curl_init();
                // curl_setopt(
                //     $ch_BpjsCheckin,
                //     CURLOPT_URL,
                //     getenv('BPJS_ANTREAN_URL') . "antrean/updatewaktu"
                // );
                // curl_setopt($ch_BpjsCheckin, CURLOPT_POST, 1);
                // curl_setopt($ch_BpjsCheckin, CURLOPT_POSTFIELDS, $postdata_BpjsCheckin);
                // curl_setopt($ch_BpjsCheckin, CURLOPT_RETURNTRANSFER, 1);
                // curl_setopt($ch_BpjsCheckin, CURLOPT_HTTPHEADER, $headers_BpjsCheckin);
                // $content = curl_exec($ch_BpjsCheckin);
                // curl_close($ch_BpjsCheckin);

                // $resultarr_BpjsCheckin = json_decode($content, true);
                // $key_BpjsCheckin = '' . $data_BpjsCheckin . '' . $secretKey_BpjsCheckin . '' . $tStamp_BpjsCheckin . '';
                // // if ($_BpjsCheckin['metaData']['code'] == 200) {
                // // $response = $this->stringDecrypt($key, $resultarr['response']);
                // echo json_encode($resultarr_BpjsCheckin);

                // //SEP
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
                    'asalRujukan' => $asalRujukan,
                    'tglRujukan' => $tglRujukan,
                    'noRujukan' => $noRujukan,
                    'ppkRujukan' => $ppkRujukan,
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
                    $tujuanKunj = "2";
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
                $diagAwal = $diagAwalcariBnokartu;
                $dpjpLayan = $databooking['kodedokter'];
                $noTelp = "00000000";

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
                $content_sep = curl_exec($ch_sep);
                curl_close($ch_sep);

                $key_sep = '' . $data_sep . '' . $secretKey_sep . '' . $tStamp_sep . '';
                $resultarr1_sep = json_decode($content_sep, true);
                // $response = $this->stringDecrypt($key_sep, $resultarr['response']);
                $key_sep = '' . $data_sep . '' . $secretKey_sep . '' . $tStamp_sep . '';
                $encrypt_method = 'AES-256-CBC';
                $key_hash = hex2bin(hash('sha256', $key_sep));
                $iv = substr(hex2bin(hash('sha256', $key_sep)), 0, 16);
                $output_sep = openssl_decrypt(base64_decode($resultarr1_sep['response']), $encrypt_method, $key_hash, OPENSSL_RAW_DATA, $iv);
                $output2_sep  = \LZCompressor\LZString::decompressFromEncodedURIComponent($output_sep);
                $resultarr_sep = json_decode($output2_sep, true);
                // curl_close($ch_sep);
                $datapasien = $this->db->get_where('simrsj_master.pasien', ['no_mr' => $databooking['norm']])->row_array();
                $datapoli = $this->db->get_where('simrsj_master.ruangan', ['kode_ruangan' => $databooking['kodepoli']])->row_array();
                $datadokter = $this->db->get_where('simrsj_master.pegawai', ['kode_dpjp' => $databooking['kodedokter']])->row_array();
                $cekkunjungan = $this->db->get_where('simrsj_aplikasi.pasien_kunjungan', ['id_pasien' => $datapasien['id_pasien'], 'tanggal_registrasi' => $databooking['tanggalperiksa']])->row_array();
                if ($datapasien['tanggal_lahir'] == '0') {
                    $jeniskelamin = 'Perempuan';
                } else {
                    $jeniskelamin = 'Laki-laki';
                }
                $birthDate = new DateTime($datapasien['tanggal_lahir']);
                $today = new DateTime("today");
                if ($birthDate > $today) {
                    exit("0 tahun 0 bulan 0 hari");
                }
                $ut = $today->diff($birthDate)->y;
                $ub = $today->diff($birthDate)->m;
                $uh = $today->diff($birthDate)->d;
                if ($cekkunjungan) {
                    $ceksep = $this->db->get_where('simrsj_aplikasi.bpjs_sep', ['no_registrasi' => $cekkunjungan['no_registrasi']])->row_array();
                    echo json_encode([
                        'metadata' => [
                            'code' => 201,
                            'message' => 'Kunjungan Sudah Ada',
                            'nosep' => $ceksep['noSep'],
                            'kodebooking' => $databooking['kodebooking'],
                            'nomorkartu' => $databooking['nomorkartu'],
                            'nomr' => $databooking['norm'],
                            'noantrean' => $databooking['nomorantrean'],
                            'namapasien' => $databooking['namapasien'],
                            'tgllahir' => $datapasien['tanggal_lahir'],
                            'jeniskelamin' => $jeniskelamin,
                            'ut' => $ut,
                            'dokter' => $datadokter['gelar_depan'] . $datadokter['nama_pegawai'] . $datadokter['gelar_belakang'],
                        ],
                    ], 201);
                } else {
                    if ($resultarr['metaData']['code'] == 200) {

                        //NOREGIS
                        $tgl_regis = $databooking['tanggalperiksa'];
                        $last_row = $this->db->select('new_record')->order_by('id_kunjungan', "desc")->limit(1)->get_where('pasien_kunjungan', ['tanggal_registrasi' => $tgl_regis, 'jenis_layanan' => 2])->result();



                        if ($last_row) {
                            foreach ($last_row as $row) {
                                $output_new_record = sprintf("%03d", $row->new_record + 1);
                            }
                        } else {
                            $output_new_record = sprintf("%03d", +1);
                        }
                        $new_record = $output_new_record;

                        // if ($_POST['action'] == 'tambah') {
                        // $data = array(
                        //     'new_record' => $new_record,
                        //     'tanggal_registrasi' => $tgl_regis,
                        //     'jenis_layanan' => 2,
                        // );

                        // $this->Rajal_model->proses_no_regis_bpjs($data);
                        // echo $new_record;
                        // }
                        $no_registrasi = "RJBPJS" . date('dmy') . "K" . $new_record;

                        //KUNJUNGAN
                        $datakunjungan = array(
                            'new_record' => $new_record,
                            'tanggal_registrasi' => $tgl_regis,
                            'no_registrasi' => $no_registrasi,
                            'id_pasien' => $datapasien['id_pasien'],
                            'jenis_layanan' => 2,
                            'id_poli' => $datapoli['id_ruangan'],
                            'id_dokter' => $datadokter['id_pegawai'],
                            'shift' => 0,
                            'penjamin_pasien' => 1,
                            'petugas' => '0',
                            'status_kunjungan' => 1,
                            'ut' => $ut,
                            'ub' => $ub,
                            'uh' => $uh
                        );

                        // $kodetgl = preg_replace("/-/", "", date('Y-m-d'));
                        // $kodepoli = $_POST['kode_poli'];
                        // $last_row = $this->db->select('*')->where('tanggalperiksa', date('Y-m-d'))->like('nomorantrean', $kodepoli . '-', 'after')->order_by('nomorantrean', "desc")->get('simrsj_webservice.antrean', 1)->result();


                        // foreach ($last_row as $row) {
                        //     $output = sprintf("%03d", $row->angkaantrean + 1);
                        // }
                        // if (!$last_row) {
                        //     $output = sprintf("%03d", +1);
                        // }

                        // $new_record = $output;
                        // $noantrean = $new_record;
                        // $norm = $_POST['no_mr'];
                        $kodebooking = $_POST['kodebooking'];
                        // $yourdate = date("Y-m-d H:i:s");
                        // $stamp = strtotime($yourdate);
                        // $estimasidilayani = $stamp * 1000;
                        $dataantrian = array(
                            // 'norm'              => $norm,
                            'checkin'           => 2,
                        );



                        if ($tujuanKunj == '1') {
                            $tk = 'Kunjungan Pertama';
                            $fp = 'Tidak ada';
                            $kp = 'Tidak ada';
                            $kp = 'Tidak ada';
                            $ap = 'Tidak ada';
                        } else {
                            $tk = 'Konsul Dokter';
                            $fp = 'Tidak ada';
                            $kp = 'Tidak ada';
                            $ap = 'Tujuan Kontrol';
                        }
                        if ($resultarr_sep['metaData']['code'] == 200) {
                            $dataSep = array(
                                'no_registrasi' => $no_registrasi,
                                'noSep'         => $resultarr_sep['sep']['noSep'],
                                'tglSep'        => $resultarr_sep['sep']['tglSep'],
                                'jnsPelayanan'  => $resultarr_sep['sep']['jnsPelayanan'],
                                'kelasRawat'    => $resultarr_sep['sep']['kelasRawat'],
                                'kodeDiagnosa'  => $diagAwal,
                                'diagnosa'      => $resultarr_sep['sep']['diagnosa'],
                                'noRujukan'     => $resultarr_sep['sep']['noRujukan'],
                                'poli'          => $resultarr_sep['sep']['poli'],
                                'poliEksekutif' => $resultarr_sep['sep']['poliEksekutif'],
                                'catatan'       => $resultarr_sep['sep']['catatan'],
                                'penjamin'      => $resultarr_sep['sep']['penjamin'],
                                'noKartu'       => $resultarr_sep['sep']['peserta']['noKartu'],
                                'nama'          => $resultarr_sep['sep']['peserta']['nama'],
                                'tglLahir'      => $resultarr_sep['sep']['peserta']['tglLahir'],
                                'noMr'          => $resultarr_sep['sep']['peserta']['noMr'],
                                'kelamin'       => $resultarr_sep['sep']['peserta']['kelamin'],
                                'jnsPeserta'    => $resultarr_sep['sep']['peserta']['jnsPeserta'],
                                'hakKelas'      => $resultarr_sep['sep']['peserta']['hakKelas'],
                                'asuransi'      => $resultarr_sep['sep']['peserta']['asuransi'],
                                'dinsos'        => '-',
                                'prolanisPRB'   => '-',
                                'noSKTM'        => '-',
                                'dokter'        => $datadokter['gelar_depan'] . $datadokter['nama_pegawai'] . $datadokter['gelar_belakang'],
                                'faskesPerujuk' => $namappkRujukan,
                                'noTelepon'     => $noTelepon,
                                'kelasRawatNaik' => '-',
                                'pembiayaan'    => '-',
                                'tujuanKunj'    => $tk,
                                'flagProcedure' => $fp,
                                'kodePenunjang' => $kp,
                                'assesmentPel'  => $ap,
                            );
                            $this->Rajal_model->tambah_kunjungan($datakunjungan);

                            $this->Rajal_model->simpan_hasil_sep($dataSep);

                            $this->Antrian_model->ubah_antrian_checkin($kodebooking, $dataantrian);

                            $kodebooking = $_POST['kodebooking'];
                            $cektask = $this->db->get_where('simrsj_webservice.task_antrean', ['kode_booking' => $kodebooking])->result();
                            if ($cektask) {
                                $data1 = array(
                                    'task_3' => date("Y-m-d H:i:s")
                                );
                                $this->Antrian_model->update_task_antrian($kodebooking, $data1);
                            } else {
                                $data2 = array(
                                    'kode_booking' => $kodebooking,
                                    'task_3' => date("Y-m-d H:i:s"),
                                    'tgl_kunjungan' => date('Y-m-d')
                                );
                                $this->Antrian_model->create_task_antrian($data2);
                            }

                            // $msg = $_POST['no_registrasi'] . ' Nama : ' . $_POST['nama_pasien'];
                            // echo $msg;


                            echo json_encode([
                                'metadata' => [
                                    'code' => '200',
                                    'sep' => $resultarr_sep['sep'],
                                    'dbsep' => $dataSep,
                                    'kodebooking' => $databooking['kodebooking'],
                                    'nomorkartu' => $databooking['nomorkartu'],
                                    'nomr' => $databooking['norm'],
                                    'noantrean' => $databooking['nomorantrean'],
                                    'namapasien' => $databooking['namapasien'],
                                    'tgllahir' => $datapasien['tanggal_lahir'],
                                    'jeniskelamin' => $jeniskelamin,
                                    'ut' => $ut,
                                    'dokter' => $datadokter['gelar_depan'] . $datadokter['nama_pegawai'] . $datadokter['gelar_belakang'],
                                ],
                            ], 201);
                        } else {
                            echo json_encode([
                                'metadata' => $resultarr_sep['metaData'],
                            ], 201);
                        }
                    } else {
                        echo json_encode([
                            'metadata' => $resultarr['metaData'],
                        ], 201);
                    }
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

    public function lembarSepPrint($noSep)
    {
        $data['title'] = 'Lembar SEP';
        $data['user'] = $this->db->get_where('user', ['username' => $this->session->userdata('username')])->row_array();
        $dataconsid = getenv('BPJS_VCLAIM_CONSID');
        $secretKey = getenv('BPJS_VCLAIM_SIGNATURE');
        $user_key = getenv('BPJS_VCAIM_USERKEY');

        date_default_timezone_set('UTC');
        $tStamp = strval(time() - strtotime('1970-01-01 00:00:00'));

        $signature = hash_hmac('sha256', $dataconsid . "&" . $tStamp, $secretKey, true);
        $encodedSignature = base64_encode($signature);

        $ch = curl_init();
        $ch2 = curl_init();
        $headers = [
            'X-cons-id: ' . $dataconsid . '',
            'X-timestamp: ' . $tStamp . '',
            'X-signature: ' . $encodedSignature . '',
            'User-key: ' . $user_key . '',
            'Content-Type: Application/x-www-form-urlencoded',
        ];
        $tgl = date('Y-m-d');
        curl_setopt($ch, CURLOPT_URL, getenv('BPJS_VCLAIM_URL') . "SEP/" . $noSep);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 3);
        curl_setopt($ch, CURLOPT_HTTPGET, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $content = curl_exec($ch);
        curl_close($ch);



        $resultarr = json_decode($content, true);
        $key = '' . $dataconsid . '' . $secretKey . '' . $tStamp . '';
        $encrypt_method = 'AES-256-CBC';
        $key_hash = hex2bin(hash('sha256', $key));
        $iv = substr(hex2bin(hash('sha256', $key)), 0, 16);
        $output = openssl_decrypt(base64_decode($resultarr['response']), $encrypt_method, $key_hash, OPENSSL_RAW_DATA, $iv);
        $output2  = \LZCompressor\LZString::decompressFromEncodedURIComponent($output);
        $resultarr2 = json_decode($output2, true);

        curl_setopt($ch2, CURLOPT_URL, getenv('BPJS_VCLAIM_URL') . "Peserta/nokartu/" . $resultarr2['peserta']['noKartu'] . "/tglSEP/" . $tgl);
        curl_setopt($ch2, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch2, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch2, CURLOPT_TIMEOUT, 3);
        curl_setopt($ch2, CURLOPT_HTTPGET, 1);
        curl_setopt($ch2, CURLOPT_SSL_VERIFYPEER, false);
        $content2 = curl_exec($ch2);
        // $err = curl_error($ch);
        curl_close($ch2);

        $resultarr5 = json_decode($content2, true);
        $key = '' . $dataconsid . '' . $secretKey . '' . $tStamp . '';
        $encrypt_method = 'AES-256-CBC';
        $key_hash = hex2bin(hash('sha256', $key));
        $iv = substr(hex2bin(hash('sha256', $key)), 0, 16);
        $output = openssl_decrypt(base64_decode($resultarr5['response']), $encrypt_method, $key_hash, OPENSSL_RAW_DATA, $iv);
        $output5  = \LZCompressor\LZString::decompressFromEncodedURIComponent($output);
        $resultarr6 = json_decode($output5, true);

        $data['data_sep'] = $resultarr2;
        $data['data_peserta'] = $resultarr6;

        $data['content'] = '';
        $page = 'dashboard/lembar_sep_rajal';
        $this->load->view($page, $data);
    }

    public function prosesNoRegistrasiBPJS()
    {
        $tgl_regis = $_POST['tgl_regis'];
        $last_row = $this->db->select('new_record')->order_by('id_kunjungan', "desc")->limit(1)->get_where('pasien_kunjungan', ['tanggal_registrasi' => $tgl_regis, 'jenis_layanan' => 2])->result();

        if ($last_row) {
            foreach ($last_row as $row) {
                $output = sprintf("%03d", $row->new_record + 1);
            }
        } else {
            $output = sprintf("%03d", +1);
        }
        $new_record = $output;

        if ($_POST['action'] == 'tambah') {
            $data = array(
                'new_record' => $new_record,
                'tanggal_registrasi' => $tgl_regis,
                'jenis_layanan' => 2,
            );

            $this->Rajal_model->proses_no_regis_bpjs($data);
            echo $new_record;
        }
    }

    //update waktu antrean bpjs
    public function waktuBpjsCheckin()
    {
        // $data = "5231";
        // $secretKey = "7rA70A8D69";
        $data_BpjsCheckin = getenv('BPJS_ANTREAN_CONSID');
        $secretKey_BpjsCheckin = getenv('BPJS_ANTREAN_SIGNATURE');
        $user_key_BpjsCheckin = getenv('BPJS_ANTREAN_USERKEY');

        date_default_timezone_set('UTC');
        $tStamp_BpjsCheckin = strval(time() - strtotime('1970-01-01 00:00:00'));

        $signature_BpjsCheckin = hash_hmac('sha256', $data_BpjsCheckin . "&" . $tStamp_BpjsCheckin, $secretKey_BpjsCheckin, true);
        $encodedSignature_BpjsCheckin = base64_encode($signature_BpjsCheckin);

        $headers_BpjsCheckin = [
            'X-cons-id: ' . $data_BpjsCheckin . '',
            'X-timestamp: ' . $tStamp_BpjsCheckin . '',
            'X-signature: ' . $encodedSignature_BpjsCheckin . '',
            'User-key: ' . $user_key_BpjsCheckin . '',
            // 'Content-Type: Application/x-www-form-urlencoded',
        ];

        // $kodetgl = preg_replace("/-/", "", date('Y-m-d'));
        $kodebooking_BpjsCheckin = $_POST['kodebooking'];
        // $kodepoli = $_POST['kode_poli'];
        // $noantrean = $_POST['angka_antrian'];
        $yourdate_BpjsCheckin = date("Y-m-d H:i:s");
        $stamp_BpjsCheckin = strtotime($yourdate_BpjsCheckin);
        $estimasidilayani_BpjsCheckin = $stamp_BpjsCheckin * 1000;

        $dataarray_BpjsCheckin = [
            'kodebooking' => $kodebooking_BpjsCheckin,
            "taskid" => 3,
            "waktu" => $estimasidilayani_BpjsCheckin
        ];



        $postdata_BpjsCheckin = json_encode($dataarray_BpjsCheckin); //ubah data array ke JSON

        $ch_BpjsCheckin = curl_init();
        curl_setopt($ch_BpjsCheckin, CURLOPT_URL, getenv('BPJS_ANTREAN_URL') . "antrean/updatewaktu");
        curl_setopt($ch_BpjsCheckin, CURLOPT_POST, 1);
        curl_setopt($ch_BpjsCheckin, CURLOPT_POSTFIELDS, $postdata_BpjsCheckin);
        curl_setopt($ch_BpjsCheckin, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch_BpjsCheckin, CURLOPT_HTTPHEADER, $headers_BpjsCheckin);
        $content = curl_exec($ch_BpjsCheckin);
        curl_close($ch_BpjsCheckin);

        $resultarr_BpjsCheckin = json_decode($content, true);
        $key_BpjsCheckin = '' . $data_BpjsCheckin . '' . $secretKey_BpjsCheckin . '' . $tStamp_BpjsCheckin . '';
        // if ($_BpjsCheckin['metaData']['code'] == 200) {
        // $response = $this->stringDecrypt($key, $resultarr['response']);
        echo json_encode($resultarr_BpjsCheckin);
        // } else {
        //     echo json_encode($resultarr['metaData']);
        // }
    }

    public function cariBnokartu()
    {
        $data_cariBnokartu = getenv('BPJS_VCLAIM_CONSID');
        $secretKey_cariBnokartu = getenv('BPJS_VCLAIM_SIGNATURE');
        $user_key_cariBnokartu = getenv('BPJS_VCAIM_USERKEY');

        date_default_timezone_set('UTC');
        $tStamp_cariBnokartu = strval(time() - strtotime('1970-01-01 00:00:00'));

        $signature_cariBnokartu = hash_hmac('sha256', $data_cariBnokartu . "&" . $tStamp_cariBnokartu, $secretKey_cariBnokartu, true);
        $encodedSignature_cariBnokartu = base64_encode($signature_cariBnokartu);

        $ch_cariBnokartu = curl_init();
        $headers_cariBnokartu = [
            'X-cons-id: ' . $data_cariBnokartu . '',
            'X-timestamp: ' . $tStamp_cariBnokartu . '',
            'X-signature: ' . $encodedSignature_cariBnokartu . '',
            'User-key: ' . $user_key_cariBnokartu . '',
            'Content-Type: application/json; charset=utf-8',
        ];
        if ($_POST['jenis_faskes2'] == 1) {
            $url_cariBnokartu = getenv('BPJS_VCLAIM_URL') . "Rujukan/Peserta/" . $_POST['no_kartu1'];
        } else {
            $url_cariBnokartu = getenv('BPJS_VCLAIM_URL') . "Rujukan/RS/Peserta/" . $_POST['no_kartu1'];
        }
        curl_setopt($ch_cariBnokartu, CURLOPT_URL, $url_cariBnokartu);
        curl_setopt($ch_cariBnokartu, CURLOPT_HTTPHEADER, $headers_cariBnokartu);
        curl_setopt($ch_cariBnokartu, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch_cariBnokartu, CURLOPT_TIMEOUT, 3);
        curl_setopt($ch_cariBnokartu, CURLOPT_HTTPGET, 1);
        curl_setopt($ch_cariBnokartu, CURLOPT_SSL_VERIFYPEER, false);
        $content_cariBnokartu = curl_exec($ch_cariBnokartu);
        // $err = curl_error($ch_cariBnokartu);
        curl_close($ch_cariBnokartu);

        $resultarr = json_decode($content_cariBnokartu, true);
        $key_cariBnokartu = '' . $data_cariBnokartu . '' . $secretKey_cariBnokartu . '' . $tStamp_cariBnokartu . '';
        if ($resultarr['metaData']['code'] == 200) {
            $response_cariBnokartu = $this->stringDecrypt($key_cariBnokartu, $resultarr['response']);
            echo $response_cariBnokartu;
        } else {
            echo json_encode($resultarr['metaData']['message']);
        }
    }
}
