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
        $data['sejiwa_url'] = getenv('SEJIWA_URL');

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
            ->or_like('nomorkartu',  preg_replace("/-/", "", $kodebooking))
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
                $user_key_cariBnokartu = getenv('BPJS_VCLAIM_USERKEY');

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

                    //POST
                    // //SEP
                    $data_sep = getenv('BPJS_VCLAIM_CONSID');
                    $secretKey_sep = getenv('BPJS_VCLAIM_SIGNATURE');
                    $user_key_sep = getenv('BPJS_VCLAIM_USERKEY');

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
                    // var_dump($dataarray_sep);
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
                    // var_dump($content_sep);
                    // $response = $this->stringDecrypt($key_sep, $resultarr['response']);
                    $key_sep = '' . $data_sep . '' . $secretKey_sep . '' . $tStamp_sep . '';
                    $encrypt_method = 'AES-256-CBC';
                    $key_hash = hex2bin(hash('sha256', $key_sep));
                    $iv = substr(hex2bin(hash('sha256', $key_sep)), 0, 16);
                    $output_sep = openssl_decrypt(
                        base64_decode($resultarr1_sep['response']),
                        $encrypt_method,
                        $key_hash,
                        OPENSSL_RAW_DATA,
                        $iv
                    );
                    $output2_sep  = \LZCompressor\LZString::decompressFromEncodedURIComponent($output_sep);
                    $resultarr_sep = json_decode($output2_sep, true);
                    // var_dump($output2_sep);

                    // if ($resultarr_sep['metaData']['code'] == 200) {
                    //     echo $response;
                    // } else {
                    //     echo json_encode($resultarr['metaData']);
                    // }
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
                        if ($ceksep) {
                            echo json_encode([
                                'metadata' => [
                                    'code' => 201,
                                    'message' => 'Kunjungan Sudah Ada',
                                    'nosep' => $ceksep['noSep'],
                                    'kodebooking' => $databooking['kodebooking'],
                                    'nomorkartu' => $databooking['nomorkartu'],
                                    'no_registrasi' => $cekkunjungan['no_registrasi'],
                                    'nomr' => $databooking['norm'],
                                    'noantrean' => $databooking['nomorantrean'],
                                    'namapasien' => $databooking['namapasien'],
                                    'tgllahir' => date('d-m-Y', strtotime($datapasien['tanggal_lahir'])),
                                    'namapoli' => $datapoli['nama_ruangan'],
                                    'jeniskelamin' => $jeniskelamin,
                                    'ut' => $ut,
                                    'dokter' => $datadokter['gelar_depan'] . $datadokter['nama_pegawai'] . $datadokter['gelar_belakang'],
                                ],
                            ], 201);
                        } else {
                            echo json_encode([
                                'metadata' => [
                                    'message' => 'Pendaftaran tidak dapat diproses',
                                    'code' => 201
                                ],
                            ], 201);
                        }
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
                                'uh' => $uh,
                                'ref_antrean' =>  $databooking['kodebooking'],
                            );


                            $kodebooking = $databooking['kodebooking'];

                            $dataantrian = array(
                                'checkin'           => 2,
                            );
                            $this->Antrian_model->ubah_antrian_checkin($kodebooking, $dataantrian);

                            $kodebooking = $databooking['kodebooking'];
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
                            // if ($resultarr_sep['metaData']['code'] == 200) {
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



                            // $msg = $_POST['no_registrasi'] . ' Nama : ' . $_POST['nama_pasien'];
                            // echo $msg;


                            echo json_encode([
                                'metadata' => [
                                    'code' => '200',
                                    'sep' => $resultarr_sep['sep'],
                                    'dbsep' => $dataSep,
                                    'kodebooking' => $databooking['kodebooking'],
                                    'no_registrasi' => $no_registrasi,
                                    'nomorkartu' => $databooking['nomorkartu'],
                                    'nomr' => $databooking['norm'],
                                    'noantrean' => $databooking['nomorantrean'],
                                    'namapasien' => $databooking['namapasien'],
                                    'tgllahir' => date('d-m-Y', strtotime($datapasien['tanggal_lahir'])),
                                    'namapoli' => $datapoli['nama_ruangan'],
                                    'jeniskelamin' => $jeniskelamin,
                                    'ut' => $ut,
                                    'dokter' => $datadokter['gelar_depan'] . $datadokter['nama_pegawai'] . $datadokter['gelar_belakang'],
                                ],
                            ], 201);
                            // } 
                            // else {
                            //     echo json_encode([
                            //         'metadata' => $resultarr_sep['metaData'],
                            //     ], 201);
                            // }
                        } else {
                            echo json_encode([
                                'metadata' => $resultarr['metaData'],
                            ], 201);
                        }
                    }
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

                    //POST
                    // //SEP
                    $data_sep = getenv('BPJS_VCLAIM_CONSID');
                    $secretKey_sep = getenv('BPJS_VCLAIM_SIGNATURE');
                    $user_key_sep = getenv('BPJS_VCLAIM_USERKEY');

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
                    $output_sep = openssl_decrypt(
                        base64_decode($resultarr1_sep['response']),
                        $encrypt_method,
                        $key_hash,
                        OPENSSL_RAW_DATA,
                        $iv
                    );
                    $output2_sep  = \LZCompressor\LZString::decompressFromEncodedURIComponent($output_sep);
                    $resultarr_sep = json_decode($output2_sep, true);
                    // curl_close($ch_sep);
                    if ($resultarr_sep['metaData']['code'] == 200) {
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
                            if ($ceksep) {
                                echo json_encode([
                                    'metadata' => [
                                        'code' => 201,
                                        'message' => 'Kunjungan Sudah Ada',
                                        'nosep' => $ceksep['noSep'],
                                        'kodebooking' => $databooking['kodebooking'],
                                        'no_registrasi' => $cekkunjungan['no_registrasi'],
                                        'nomorkartu' => $databooking['nomorkartu'],
                                        'nomr' => $databooking['norm'],
                                        'noantrean' => $databooking['nomorantrean'],
                                        'namapasien' => $databooking['namapasien'],
                                        'tgllahir' => date('d-m-Y', strtotime($datapasien['tanggal_lahir'])),
                                        'namapoli' => $datapoli['nama_ruangan'],
                                        'jeniskelamin' => $jeniskelamin,
                                        'ut' => $ut,
                                        'dokter' => $datadokter['gelar_depan'] . $datadokter['nama_pegawai'] . $datadokter['gelar_belakang'],
                                    ],
                                ], 201);
                            } else {
                                echo json_encode([
                                    'metadata' => [
                                        'message' => 'Pendaftran tidak dapat diproses',
                                        'code' => 201
                                    ],
                                ], 201);
                            }
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
                                    'uh' => $uh,
                                    'ref_antrean' =>  $databooking['kodebooking'],
                                );


                                $kodebooking = $databooking['kodebooking'];

                                //ANTRIAN
                                $dataantrian = array(
                                    // 'norm'              => $norm,
                                    'checkin'           => 2,
                                    'statusantrean'           => 3,
                                );
                                $this->Antrian_model->ubah_antrian_checkin($kodebooking, $dataantrian);

                                $kodebooking = $databooking['kodebooking'];
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

                                // //CHECKIN
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
                                // $kodebooking_BpjsCheckin = $databooking['kodebooking'];
                                // // $kodepoli = $_POST['kode_poli'];
                                // // $noantrean = $_POST['angka_antrian'];
                                // $yourdate_BpjsCheckin = date("Y-m-d H:i:s");
                                // $stamp_BpjsCheckin = strtotime($yourdate_BpjsCheckin);
                                // $estimasidilayani_BpjsCheckin = $stamp_BpjsCheckin * 1000;

                                // $dataarray_BpjsCheckin = [
                                //     "kodebooking" => $kodebooking_BpjsCheckin,
                                //     "taskid" => 4,
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
                                // // $key_BpjsCheckin = '' . $data_BpjsCheckin . '' . $secretKey_BpjsCheckin . '' . $tStamp_BpjsCheckin . '';
                                // // if ($_BpjsCheckin['metaData']['code'] == 200) {
                                // // $response = $this->stringDecrypt($key, $resultarr['response']);
                                // echo json_encode($resultarr_BpjsCheckin);
                                // //END CHECKIN



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
                                // if ($resultarr_sep['metaData']['code'] == 200) {
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



                                // $msg = $_POST['no_registrasi'] . ' Nama : ' . $_POST['nama_pasien'];
                                // echo $msg;


                                echo json_encode([
                                    'metadata' => [
                                        'code' => '200',
                                        'sep' => $resultarr_sep['sep'],
                                        'noregistrasi' => $no_registrasi,
                                        'dbsep' => $dataSep,
                                        'kodebooking' => $databooking['kodebooking'],
                                        'nomorkartu' => $databooking['nomorkartu'],
                                        'nomr' => $databooking['norm'],
                                        'noantrean' => $databooking['nomorantrean'],
                                        'namapasien' => $databooking['namapasien'],
                                        'tgllahir' => date('d-m-Y', strtotime($datapasien['tanggal_lahir'])),
                                        'namapoli' => $datapoli['nama_ruangan'],
                                        'jeniskelamin' => $jeniskelamin,
                                        'ut' => $ut,
                                        'dokter' => $datadokter['gelar_depan'] . $datadokter['nama_pegawai'] . $datadokter['gelar_belakang'],

                                    ],
                                ], 201);
                                // } 
                                // else {
                                //     echo json_encode([
                                //         'metadata' => $resultarr_sep['metaData'],
                                //     ], 201);
                                // }
                            } else {
                                echo json_encode([
                                    'metadata' => $resultarr['metaData'],
                                ], 201);
                            }
                        }
                    } else {
                        echo json_encode([
                            'metadata' => $resultarr['metaData'],
                        ], 201);
                    }
                } else {
                    echo $resultarr['metaData']['message'];
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
        $user_key = getenv('BPJS_VCLAIM_USERKEY');

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
        $estimasidilayani_BpjsCheckin = ($stamp_BpjsCheckin * 1000);

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
        $user_key_cariBnokartu = getenv('BPJS_VCLAIM_USERKEY');

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

    public function cek_pasien()
    {
        $kodebooking = $_POST['kodebooking'];
        $databooking = $this->db->select('*')
            ->where('tanggalperiksa',  date("Y-m-d"))
            ->order_by('tanggalperiksa', "desc")->limit(1)
            ->like('kodebooking', $kodebooking)
            ->or_like('norm',  preg_replace("/-/", "", $kodebooking))
            ->or_like('nomorkartu',  preg_replace("/-/", "", $kodebooking))

            ->get('simrsj_webservice.antrean')->row_array();
        if ($databooking) {
            $tanggalperiksa = $databooking['tanggalperiksa'];
            if ($tanggalperiksa == date("Y-m-d")) {
                echo json_encode([
                    'metadata' => [
                        'result' => $databooking,
                        'message' => 'Data diproses',
                        'code' => 200
                    ],
                ], 200);
            } else {
                echo json_encode([
                    'metadata' => [
                        'message' => 'Tanggal antrean tidak sesuai.',
                        'code' => 201
                    ],
                ], 201);
            }
        } else {
            echo json_encode([
                'metadata' => [
                    'message' => 'Data tidak terdaftar',
                    'code' => 201
                ],
            ], 201);
        }
    }

    public function cek_kunjungan()
    {
        $kodebooking = $_POST['kodebooking'];
        $cekkunjungan = $this->db->get_where('simrsj_aplikasi.pasien_kunjungan', ['ref_antrean' => $kodebooking])->row_array();

        if ($cekkunjungan) {
            $cekantrean = $this->db->get_where('simrsj_webservice.antrean', ['kodebooking' => $kodebooking])->row_array();
            $ceksep = $this->db->get_where('simrsj_aplikasi.bpjs_sep', ['no_registrasi' => $cekkunjungan['no_registrasi']])->row_array();
            echo json_encode([
                'metadata' => [
                    'result' => [
                        'datakunjungan' => $cekkunjungan,
                        'dataanrean' => $cekantrean,
                        'datasep' => $ceksep,
                    ],
                    'message' => 'Data kunjungan sudah ada',
                    'code' => 201
                ],
            ], 201);
        } else {
            echo json_encode([
                'metadata' => [
                    'message' => 'Data kunjungan belum ada',
                    'code' => 200
                ],
            ], 200);
        }
    }

    public function insert_sep_db()
    {
        $dataSep = array(
            'no_registrasi' => $_POST['noregistrasi'],
            'noSep'         => $_POST['nosep'],
            'tglSep'        => $_POST['tglSep'],
            'jnsPelayanan'  => $_POST['jnsPelayanan'],
            'kelasRawat'    => $_POST['kelasRawat'],
            'kodeDiagnosa'  => $_POST['kodeDiagnosa'],
            'diagnosa'      => $_POST['diagnosa'],
            'noRujukan'     => $_POST['noRujukan'],
            'poli'          => $_POST['poli'],
            'poliEksekutif' => $_POST['poliEksekutif'],
            'catatan'       => $_POST['catatan'],
            'penjamin'      => $_POST['penjamin'],
            'noKartu'       => $_POST['noKartu'],
            'nama'          => $_POST['nama'],
            'tglLahir'      => $_POST['tglLahir'],
            'noMr'          => $_POST['noMr'],
            'kelamin'       => $_POST['kelamin'],
            'jnsPeserta'    => $_POST['jnsPeserta'],
            'hakKelas'      => $_POST['hakKelas'],
            'asuransi'      => $_POST['asuransi'],
            'dinsos'        =>
            $_POST['dinsos'],
            'prolanisPRB'   =>
            $_POST['prolanisPRB'],
            'noSKTM'        =>
            $_POST['noSKTM'],
            'dokter'        => $_POST['namaDokter'],
            'faskesPerujuk' => $_POST['namaPpk'],
            'noTelepon'     => $_POST['nomorTelp'],
            'kelasRawatNaik' =>  $_POST['kelasRawatNaik'],
            'pembiayaan'    =>  $_POST['pembiayaan'],
            'tujuanKunj'    => $_POST['tujuanKunj'],
            'flagProcedure' => $_POST['flagProcedure'],
            'kodePenunjang' => $_POST['kodePenunjang'],
            'assesmentPel'  => $_POST['assestmenPel'],
        );
        $this->Rajal_model->simpan_hasil_sep($dataSep);

        echo json_encode([
            'metaData' => [
                'message' => 'Berhasil',
                'result' => $dataSep,
                'code' => 200
            ],
        ], 200);
    }

    public function cek_surkon()
    {
        $data = getenv('BPJS_VCLAIM_CONSID');
        $secretKey = getenv('BPJS_VCLAIM_SIGNATURE');
        $user_key = getenv('BPJS_VCLAIM_USERKEY');

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

        curl_setopt($ch, CURLOPT_URL, getenv('BPJS_VCLAIM_URL') . "RencanaKontrol/noSuratKontrol/" . $_POST['no_surkon']);
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
        if ($resultarr) {
            if ($resultarr['metaData']['code'] == 200) {
                $response = $this->stringDecrypt($key, $resultarr['response']);
                // echo json_encode($resultarr);
                echo $response;
            } else {
                echo json_encode($resultarr);
            }
        } else {
            echo json_encode([
                'metaData' => [
                    'message' => 'Coba lagi',
                    'code' => 201
                ],
            ], 201);
        }
    }

    public function encrypt_surkon()
    {
        $data = getenv('BPJS_VCLAIM_CONSID');
        $secretKey = getenv('BPJS_VCLAIM_SIGNATURE');

        date_default_timezone_set('UTC');
        $tStamp = strval(time() - strtotime('1970-01-01 00:00:00'));


        $key = '' . $data . '' . $secretKey . '' . $tStamp . '';


        $response = $this->stringDecrypt($key, $_POST['result_surkon']);
        // var_dump($response);
        echo $response;
    }

    public function insertSEP()
    {
        $data = getenv('BPJS_VCLAIM_CONSID');
        $secretKey = getenv('BPJS_VCLAIM_SIGNATURE');
        $user_key = getenv('BPJS_VCLAIM_USERKEY');

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
        $asalRujukan = $_POST['asalRujukan'];
        $tglRujukan = $_POST['tglRujukan'];
        $noRujukan = $_POST['noRujukan'];
        $ppkRujukan = $_POST['kdProviderPerujuk'];
        $poliTujuan = $_POST['poliTujuan'];
        // $noSuratKontrol = $_POST['noSuratKontrol'];
        $kodeDokter = $_POST['kodeDokter'];

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
            'tujuan' => $poliTujuan,
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

        $jeniskunjungan = $_POST['jeniskunjungan'];

        if ($jeniskunjungan == '1') {
            $noSurat = "";
            $tujuanKunj = "0";
            $flagProcedure = "";
            $kdPenunjang = "";
            $assesmentPel = "";
            $catatan = "Kunjungan Pertama";
        } elseif ($jeniskunjungan == '3') {
            $noSurat = $_POST['noSuratKontrol'];
            $tujuanKunj = "2";
            $flagProcedure = "";
            $kdPenunjang = "";
            $assesmentPel = "5";
            $catatan = "Kontrol";
        }

        $skdp = [
            'noSurat' => $noSurat,
            'kodeDPJP' => $kodeDokter,
        ];

        $noKartu = $_POST['noKartu'];
        $tglSep = date('Y-m-d');
        $noMR = $_POST['noMR'];
        $diagAwal = $_POST['diagAwal'];
        $dpjpLayan = $kodeDokter;
        $noTelp = $_POST['noTelp'];

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
            'user'          => $_POST['user'],
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
        // $response = $this->stringDecrypt($key, $resultarr['response']);
        // if ($resultarr['metaData']['code'] == 200) {
        //     echo $response;
        // } else {
        //     echo json_encode($resultarr['metaData']);
        // }

        if ($resultarr) {
            if ($resultarr['metaData']['code'] == 200) {
                $response = $this->stringDecrypt($key, $resultarr['response']);
                // echo json_encode($resultarr);
                echo $response;
            } else {
                echo json_encode($resultarr);
            }
        } else {
            echo json_encode([
                'metaData' => [
                    'message' => 'Coba lagi',
                    'code' => 201
                ],
            ], 201);
        }
    }

    public function cariBnorujukan()
    {
        $data = getenv('BPJS_VCLAIM_CONSID');
        $secretKey = getenv('BPJS_VCLAIM_SIGNATURE');
        $user_key = getenv('BPJS_VCLAIM_USERKEY');

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
        if ($_POST['asalRujukan'] == 1) {
            $url = getenv('BPJS_VCLAIM_URL') . "Rujukan/" . $_POST['noRujukan'];
        } else {
            $url = getenv('BPJS_VCLAIM_URL') . "Rujukan/RS/" . $_POST['noRujukan'];
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
        if ($resultarr) {
            if ($resultarr['metaData']['code'] == 200) {
                $response = $this->stringDecrypt($key, $resultarr['response']);
                // echo json_encode($resultarr);
                echo $response;
            } else {
                echo json_encode($resultarr);
            }
        } else {
            echo json_encode([
                'metaData' => [
                    'message' => 'Coba lagi',
                    'code' => 201
                ],
            ], 201);
        }
    }

    public function cek_jadwaldokter()
    {
        $dokterkode = $_POST['kodeDokter'];
        $kodepoli = $_POST['poliTujuan'];
        $tglkontrol = $_POST['tglRencanaKontrol'];
        $datajadwal = $this->db->select('*')
            ->where('jadwal_dokter.hari', date('N', strtotime($tglkontrol)))
            ->where('dokter_kode', $dokterkode)
            ->where('jadwal_dokter.poli_kdsubspesialis', $kodepoli)
            ->where('jadwal_dokter.statusjadwal', 1)
            ->limit(1)

            ->get('simrsj_webservice.jadwal_dokter')->row_array();
        if ($datajadwal) {
            echo json_encode([
                'metadata' => [
                    'result' => $datajadwal,
                    'message' => 'Jadwal sesuai',
                    'code' => 200
                ],
            ], 200);
        } else {
            echo json_encode([
                'metadata' => [
                    'message' => 'Jadwal dokter tidak sesuai',
                    'code' => 201
                ],
            ], 201);
        }
    }

    public function pendaftaran_pasien()
    {
        $kodepoli = $_POST['kodepoli'];
        $norm = $_POST['norm'];
        $kodedokter = $_POST['kodedokter'];
        $tanggalperiksa = $_POST['tanggalperiksa'];
        $kodebooking = $_POST['kodebooking'];

        $no_sep = $_POST['nosep'];
        $nomorkartu = $_POST['nomorkartu'];

        $datapasien = $this->db->get_where('simrsj_master.pasien', ['no_mr' => $norm])->row_array();
        $datapoli = $this->db->get_where('simrsj_master.ruangan', ['kode_ruangan' => $kodepoli])->row_array();
        $datadokter = $this->db->get_where('simrsj_master.pegawai', ['kode_dpjp' => $kodedokter])->row_array();
        $cekkunjungan = $this->db->get_where('simrsj_aplikasi.pasien_kunjungan', ['id_pasien' => $datapasien['id_pasien'], 'tanggal_registrasi' => $tanggalperiksa])->row_array();
        $databooking = $this->db->get_where('simrsj_webservice.antrean', ['kodebooking' => $kodebooking])->row_array();

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
        $tgl_regis = $tanggalperiksa;


        $last_row = $this->db->select('id_kunjungan')->order_by('id_kunjungan', "desc")->get_where('pasien_kunjungan', ['tanggal_registrasi' => $tgl_regis, 'jenis_layanan' => 2])->num_rows();



        $new_record = sprintf("%03d", $last_row + 1);

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
            'uh' => $uh,
            'ref_antrean' =>   $kodebooking,
        );


        //ANTRIAN
        $dataantrian = array(
            // 'norm'              => $norm,
            'checkin'           => 2,
            'statusantrean'           => 3,
        );
        $this->Antrian_model->ubah_antrian_checkin($kodebooking, $dataantrian);

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

        $this->Rajal_model->tambah_kunjungan($datakunjungan);
        echo json_encode([
            'metadata' => [
                'code' => '200',
                'message' => 'Pendaftaran berhasil',
                'result' => [
                    'sep' =>  $no_sep,
                    'noregistrasi' => $no_registrasi,
                    // 'dbsep' => $dataSep,
                    'kodebooking' => $kodebooking,
                    'nomorkartu' => $nomorkartu,
                    'nomr' => $norm,
                    'noantrean' => $databooking['nomorantrean'],
                    'namapasien' => $databooking['namapasien'],
                    'tgllahir' => date('d-m-Y', strtotime($datapasien['tanggal_lahir'])),
                    'namapoli' => $datapoli['nama_ruangan'],
                    'jeniskelamin' => $jeniskelamin,
                    'ut' => $ut,
                    'ub' => $ub,
                    'uh' => $uh,
                    'dokter' => $datadokter['gelar_depan'] . $datadokter['nama_pegawai'] . $datadokter['gelar_belakang'],
                ]
            ],
        ], 200);

        // echo json_encode([
        //     'metadata' => [
        //         'result' => $datakunjungan,
        //         'message' => 'Jadwal sesuai',
        //         'code' => 200
        //     ],
        // ], 200);

        // if ($datakunjungan) {
        //     echo json_encode([
        //         'metadata' => [
        //             'result' => $datakunjungan,
        //             'message' => 'Jadwal sesuai',
        //             'code' => 200
        //         ],
        //     ], 200);
        // } else {
        //     echo json_encode([
        //         'metadata' => [
        //             'message' => 'Jadwal dokter tidak sesuai',
        //             'code' => 201
        //         ],
        //     ], 201);
        // }
    }
    public function poli()
    {
        $data =
            $this->db->select('
                            *
                        ')
            ->get_where('simrsj_master.ruangan', ['kode_ruangan' => 'JIW'])->result_array();

        echo json_encode($data);
    }

    public function jadwal_poli()
    {
        $koderuangan = $_POST['koderuangan'];
        $idruangan = $_POST['idruangan'];
        if ($idruangan == '2') {
            $jenisjadwal = '2';
        } else {
            $jenisjadwal = '1';
        }

        $data =
            $this->db->select('
                            *
                        ')
            ->join('simrsj_master.pegawai', 'pegawai.kode_dpjp = simrsj_webservice.jadwal_dokter.dokter_kode')
            ->get_where('simrsj_webservice.jadwal_dokter', ['poli_kdsubspesialis' => $koderuangan, 'hari' => date("N"), 'jenisjadwal' => $jenisjadwal, 'statusjadwal' => '1'])->result_array();

        echo json_encode($data);
    }

    public function cek_pasien_onsite()
    {
        $kodebooking = $_POST['no_pengenal'];
        $databooking = $this->db->select('*')
            ->order_by('id_pasien', "desc")->limit(1)
            ->like('nomor_pengenal', $kodebooking)
            ->or_like('no_mr',  preg_replace("/-/", "", $kodebooking))
            ->or_like('no_bpjs',  preg_replace("/-/", "", $kodebooking))

            ->get('simrsj_master.pasien')->row_array();
        if ($databooking) {
            echo json_encode([
                'metadata' => [
                    'result' => $databooking,
                    'message' => 'Data diproses',
                    'code' => 200
                ],
            ], 200);
        } else {
            echo json_encode([
                'metadata' => [
                    'message' => 'Data tidak terdaftar',
                    'code' => 201
                ],
            ], 201);
        }
    }

    public function cek_kunjungan_onsite()
    {
        $kodebooking = $_POST['idpasien'];
        $cekkunjungan = $this->db->get_where('simrsj_aplikasi.pasien_kunjungan', ['id_pasien' => $kodebooking, 'tanggal_registrasi' => date('Y-m-d')])->row_array();

        if ($cekkunjungan) {
            // $cekantrean = $this->db->get_where('simrsj_webservice.antrean', ['kodebooking' => $kodebooking])->row_array();
            // $ceksep = $this->db->get_where('simrsj_aplikasi.bpjs_sep', ['no_registrasi' => $cekkunjungan['no_registrasi']])->row_array();
            echo json_encode([
                'metadata' => [
                    'result' => [
                        'datakunjungan' => $cekkunjungan,
                        // 'dataanrean' => $cekantrean,
                        // 'datasep' => $ceksep,
                    ],
                    'message' => 'Data kunjungan sudah ada',
                    'code' => 201
                ],
            ], 201);
        } else {
            echo json_encode([
                'metadata' => [
                    'message' => 'Data kunjungan belum ada',
                    'code' => 200
                ],
            ], 200);
        }
    }

    public function cari_rujukan_onsite_f2()
    {
        $data = getenv('BPJS_VCLAIM_CONSID');
        $secretKey = getenv('BPJS_VCLAIM_SIGNATURE');
        $user_key = getenv('BPJS_VCLAIM_USERKEY');

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

        $url = getenv('BPJS_VCLAIM_URL') . "Rujukan/Peserta/" . $_POST['noKartu'];

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
        if ($resultarr) {
            if ($resultarr['metaData']['code'] == 200) {
                $response = $this->stringDecrypt($key, $resultarr['response']);
                // echo json_encode($resultarr);
                echo $response;
            } else {
                echo json_encode($resultarr);
            }
        } else {
            echo json_encode([
                'metaData' => [
                    'message' => 'Coba lagi',
                    'code' => 201
                ],
            ], 201);
        }
    }

    public function cari_rujukan_onsite_f1()
    {
        $f1_data = getenv('BPJS_VCLAIM_CONSID');
        $f1_secretKey = getenv('BPJS_VCLAIM_SIGNATURE');
        $f1_user_key = getenv('BPJS_VCLAIM_USERKEY');

        date_default_timezone_set('UTC');
        $f1_tStamp = strval(time() - strtotime('1970-01-01 00:00:00'));

        $f1_signature = hash_hmac('sha256', $f1_data . "&" . $f1_tStamp, $f1_secretKey, true);
        $f1_encodedSignature = base64_encode($f1_signature);

        $f1_ch = curl_init();
        $f1_headers = [
            'X-cons-id: ' . $f1_data . '',
            'X-timestamp: ' . $f1_tStamp . '',
            'X-signature: ' . $f1_encodedSignature . '',
            'User-key: ' . $f1_user_key . '',
            'Content-Type: application/json; charset=utf-8',
        ];
        $f1_url = getenv('BPJS_VCLAIM_URL') . "Rujukan/RS/Peserta/" . $_POST['noKartu'];

        curl_setopt($f1_ch, CURLOPT_URL, $f1_url);
        curl_setopt($f1_ch, CURLOPT_HTTPHEADER, $f1_headers);
        curl_setopt($f1_ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($f1_ch, CURLOPT_TIMEOUT, 3);
        curl_setopt($f1_ch, CURLOPT_HTTPGET, 1);
        curl_setopt($f1_ch, CURLOPT_SSL_VERIFYPEER, false);
        $f1_content = curl_exec($f1_ch);
        // $f1_err = curl_error($f1_ch);
        curl_close($f1_ch);

        $f1_resultarr = json_decode($f1_content, true);
        $f1_key = '' . $f1_data . '' . $f1_secretKey . '' . $f1_tStamp . '';
        if ($f1_resultarr) {
            if ($f1_resultarr['metaData']['code'] == 200) {
                $response = $this->stringDecrypt($f1_key, $f1_resultarr['response']);
                // echo json_encode($f1_resultarr);
                echo $response;
            } else {
                $f2_data = getenv('BPJS_VCLAIM_CONSID');
                $f2_secretKey = getenv('BPJS_VCLAIM_SIGNATURE');
                $f2_user_key = getenv('BPJS_VCLAIM_USERKEY');

                date_default_timezone_set('UTC');
                $f2_tStamp = strval(time() - strtotime('1970-01-01 00:00:00'));

                $f2_signature = hash_hmac('sha256', $f2_data . "&" . $f2_tStamp, $f2_secretKey, true);
                $f2_encodedSignature = base64_encode($f2_signature);

                $f2_ch = curl_init();
                $f2_headers = [
                    'X-cons-id: ' . $f2_data . '',
                    'X-timestamp: ' . $f2_tStamp . '',
                    'X-signature: ' . $f2_encodedSignature . '',
                    'User-key: ' . $f2_user_key . '',
                    'Content-Type: application/json; charset=utf-8',
                ];
                $f2_url = getenv('BPJS_VCLAIM_URL') . "Rujukan/Peserta/" . $_POST['noKartu'];

                curl_setopt($f2_ch, CURLOPT_URL, $f2_url);
                curl_setopt($f2_ch, CURLOPT_HTTPHEADER, $f2_headers);
                curl_setopt($f2_ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($f2_ch, CURLOPT_TIMEOUT, 3);
                curl_setopt($f2_ch, CURLOPT_HTTPGET, 1);
                curl_setopt($f2_ch, CURLOPT_SSL_VERIFYPEER, false);
                $f2_content = curl_exec($f2_ch);
                // $f2_err = curl_error($f2_ch);
                curl_close($f2_ch);

                $f2_resultarr = json_decode($f2_content, true);
                $f2_key = '' . $f2_data . '' . $f2_secretKey . '' . $f2_tStamp . '';

                if ($f2_resultarr) {
                    if ($f2_resultarr['metaData']['code'] == 200) {
                        $response = $this->stringDecrypt($f2_key, $f2_resultarr['response']);
                        // echo json_encode($f2_resultarr);
                        echo $response;
                    } else {
                        echo json_encode($f2_resultarr);
                    }
                } else {
                    echo json_encode([
                        'metaData' => [
                            'message' => 'Coba lagi',
                            'code' => 201
                        ],
                    ], 201);
                }
            }
        } else {
            echo json_encode([
                'metaData' => [
                    'message' => 'Coba lagi',
                    'code' => 201
                ],
            ], 201);
        }
    }

    public function cek_jumlah_sep()
    {
        $data = getenv('BPJS_VCLAIM_CONSID');
        $secretKey = getenv('BPJS_VCLAIM_SIGNATURE');
        $user_key = getenv('BPJS_VCLAIM_USERKEY');

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
            'Content-Type: Application/x-www-form-urlencoded',
        ];

        curl_setopt($ch, CURLOPT_URL, getenv('BPJS_VCLAIM_URL') . "Rujukan/JumlahSEP/" . $_POST['asalFaskes'] . "/" . $_POST['noRujukan']);
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

        if ($resultarr) {
            if ($resultarr['metaData']['code'] == 200) {
                $response = $this->stringDecrypt($key, $resultarr['response']);
                // echo json_encode($resultarr);
                echo $response;
            } else {
                echo json_encode($resultarr);
            }
        } else {
            echo json_encode([
                'metaData' => [
                    'message' => 'Coba lagi',
                    'code' => 201
                ],
            ], 201);
        }
    }

    public function dataSuratKontrol()
    {
        $data = getenv('BPJS_VCLAIM_CONSID');
        $secretKey = getenv('BPJS_VCLAIM_SIGNATURE');
        $user_key = getenv('BPJS_VCLAIM_USERKEY');

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

        curl_setopt($ch, CURLOPT_URL, getenv('BPJS_VCLAIM_URL') . "RencanaKontrol/ListRencanaKontrol/Bulan/" . date('m') . "/Tahun/" . date('Y') . "/Nokartu/" . $_POST['noKartu'] . "/filter/2");
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

    public function tambah_antrean()
    {

        $norm = $_POST['no_mr'];
        $kodepoli = $_POST['kode_poli'];
        $kodedokter = $_POST['dokter3'];
        $cekantrian = $this->db->select('*')
            ->where('norm', $norm)
            ->where('tanggalperiksa', date('Y-m-d'))
            ->where('kodepoli', $kodepoli)
            // ->where('kodedokter', $kodedokter)
            ->get('simrsj_webservice.antrean', 1)->row_array();
        if ($cekantrian) {

            //update antrean
            $kodebooking = $cekantrian['kodebooking'];
            $dataupdate = [
                'statusantrean' => 3,
            ];
            $this->db->where('kodebooking', $kodebooking);
            $this->db->update('simrsj_webservice.antrean', $dataupdate);

            //cek task antrean
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
            $dataantrian = array(
                'kodepoli' => $cekantrian['kodepoli'],
                'nik' => $cekantrian['nik'],
                'norm' => $cekantrian['norm'],
                'namapasien' => $cekantrian['namapasien'],
                'nomorkartu' => $cekantrian['nomorkartu'],
                'nomorantrean' => $cekantrian['nomorantrean'],
                'nomorantreanpoli' => $cekantrian['nomorantreanpoli'],
                'angkaantrean' => $cekantrian['angkaantrean'],
                'angkaantreanpoli' => $cekantrian['angkaantreanpoli'],
                'jeniskunjungan' => $cekantrian['jeniskunjungan'],
                'kodedokter' => $cekantrian['kodedokter'],
                'jampraktek' => $cekantrian['jampraktek'],
                'kodebooking' => $cekantrian['kodebooking'],
                'estimasidilayani' => $cekantrian['estimasidilayani'],
                'waktu' => $cekantrian['waktu'],
                'statusantrean' => $cekantrian['statusantrean'],
                'checkin' => $cekantrian['checkin'],
                'tanggalperiksa' => $cekantrian['tanggalperiksa'],
                'nomorreferensi' => $cekantrian['nomorreferensi'],
                'created_at' => $cekantrian['created_at']
            );
            $msg = $dataantrian;
        } else {
            $kodetgl = preg_replace("/-/", "", date('Y-m-d'));

            $kodeantrean = $_POST['kodeantrean'];

            $last_row = $this->db->select('*')->where('tanggalperiksa', date('Y-m-d'))->like('nomorantreanpoli', $kodeantrean . '-', 'after')->order_by('nomorantreanpoli', "desc")->get('simrsj_webservice.antrean', 1)->result();


            foreach ($last_row as $row) {
                $output = sprintf("%03d", $row->angkaantreanpoli + 1);
            }
            if (!$last_row) {
                $output = sprintf("%03d", +1);
            }

            $new_record = $output;
            $noantrean = $new_record;
            $nik = $_POST['nik'];

            $pasien_prioritas = $_POST['pasien_prioritas'];
            $nomorreferensi = $_POST['nomorreferensi'];

            $namapasien = $_POST['nama_pasien'];
            $kodedokter = $_POST['dokter3'];
            $jampraktek = $_POST['shift2'];
            $yourdate = date("Y-m-d H:i:s");
            $stamp = strtotime($yourdate);
            $estimasidilayani = $stamp * 1000;
            $nomorkartu = $_POST['no_kartu'];
            $dataantrian = array(
                'kodepoli' => $kodepoli,
                'nik' => $nik,
                'norm' => $norm,
                'namapasien' => $namapasien,
                'nomorkartu' => $nomorkartu,
                'nomorantrean' => $kodeantrean . "-" . $noantrean,
                'nomorantreanpoli' => $kodeantrean . "-" . $noantrean,
                'angkaantrean' => $noantrean,
                'angkaantreanpoli' => $noantrean,
                'jeniskunjungan' => 1,
                'kodedokter' => $kodedokter,
                'jampraktek' => $jampraktek,
                'kodebooking' => substr($kodepoli, 0, 1) . $kodeantrean . $kodetgl . sprintf("%03d", $noantrean),
                'estimasidilayani' => $estimasidilayani,
                'waktu' => $estimasidilayani,
                'statusantrean' => 3,
                'checkin' => 2,
                'tanggalperiksa' => date('Y-m-d'),
                'pasien_prioritas' => $pasien_prioritas,
                'nomorreferensi' => $nomorreferensi,
                'created_at' => date('Y-m-d H:i:s')
            );


            $this->Antrian_model->tambah_antrian($dataantrian);

            $kodebooking = substr($kodepoli, 0, 1) . $kodeantrean . $kodetgl . sprintf("%03d", $noantrean);
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
            $msg = $dataantrian;
        }

        // echo json_encode($msg);
        echo json_encode([
            'metadata' => [
                'result' => [
                    'dataantrian' => $msg,
                ],
                'message' => 'Data antrean disimpan',
                'code' => 200
            ],
        ], 200);
    }

    //Tambah antrian BPJS
    public function add_antrean()
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




        $kodepoli = $_POST['kode_poli'];
        $nik = $_POST['nik'];
        $noantrean = $_POST['nomorantreanpoli'];
        $nomr = $_POST['no_mr'];
        $kodedokter = $_POST['dokter3'];
        $namadokter = $_POST['nama_dokter'];
        $jampraktek = $_POST['shift2'];
        $kuotajkn = $_POST['kuotajkn'];
        $kuotanonjkn = $_POST['kuotanonjkn'];
        $angkaantrean = $_POST['angkaantrian'];
        // $kodeantrean = $_POST['kodeantrean'];
        $yourdate = date("Y-m-d H:i:s");
        $stamp = strtotime($yourdate);
        $estimasidilayani = $stamp * 1000;
        $kodebooking = $_POST['kodebooking'];

        $jumlahjkn = $this->db->select('*')->order_by('nomorantrean', "desc")->limit(1)->get_where('simrsj_webservice.antrean', ['kodedokter' => $kodedokter], ['tanggalperiksa' => $yourdate], ['statusantrean' => [3, 4, 5, 6, 7]])->num_rows();
        $sisakuotajkn = $kuotajkn - $jumlahjkn;

        // $tkunjungan = $_POST['tkunjungan'];
        // $arujukan = $_POST['arujukan'];
        // if ($_POST['pasca_ranap'] == 1) {
        //     $jeniskunjungan = 3; //konsul dokter
        //     $noreferensi = $_POST['surkon'];
        // } else {
        //     if ($tkunjungan == '0' && $arujukan == '2') {
        //         $noreferensi = $_POST['nomorreferensi'];
        //         $jeniskunjungan = 4;
        //     } else if ($tkunjungan == '2' && $arujukan == '2') {
        //         $jeniskunjungan = 3; //konsul dokter
        //         $noreferensi = $_POST['surkon'];
        //     } else {
        //         if ($jmlh_sep == '0' && $tj_poli == $kodepoli) {
        //             $jeniskunjungan = 1;
        //             $noreferensi = $_POST['nomorreferensi'];
        //         } else if ($jmlh_sep > '0' && $tj_poli == $kodepoli) {
        //             $jeniskunjungan = 3;
        //             $noreferensi = $_POST['surkon'];
        //         } else if ($jmlh_sep > '0' && $tj_poli != $kodepoli) {
        //             $jeniskunjungan = 2;
        //             $noreferensi = '';
        //         } else {
        //             $tkunjungan = $_POST['tkunjungan'];
        //             $arujukan = $_POST['arujukan'];
        //             if ($tkunjungan == '0') {
        //                 $noreferensi = $_POST['nomorreferensi'];
        //                 if ($arujukan == 2) {
        //                     $jeniskunjungan = 4;
        //                 } else {
        //                     $jeniskunjungan = 1;
        //                 }
        //             } else {
        //                 $jeniskunjungan = 3; //konsul dokter
        //                 $noreferensi = $_POST['surkon'];
        //             }
        //         }
        //     }
        // }


        $dataarray = [
            'kodebooking' => $kodebooking,
            'jenispasien' => "JKN",
            'nomorkartu' => $_POST['no_kartu'],
            'nik' => $nik,
            'nohp' => 0,
            'kodepoli' => $kodepoli,
            'norm' => $nomr,
            'tanggalperiksa' => date('Y-m-d'),
            'kodedokter' => $kodedokter,
            'namadokter' => $namadokter,
            'jampraktek' => $jampraktek,
            'jeniskunjungan' => $_POST['jeniskunjungan'], //update jnskunjungan
            'nomorreferensi' => $_POST['nomorreferensi'], //update noref
            'nomorantrean' => $noantrean,
            'angkaantrean' => $angkaantrean,
            // 'kodebooking' => substr($kodepoli, 0, 1) . '-' . sprintf("%03d", $noantrean),
            'estimasidilayani' => $estimasidilayani,
            'sisakuotajkn' => $sisakuotajkn,
            'kuotajkn' => $kuotajkn,
            'sisakuotanonjkn' => 15,
            'kuotanonjkn' => $kuotanonjkn,
            'keterangan' => "",
            'namapoli' => $kodepoli,
        ];
        // echo json_encode($dataarray);


        $postdata = json_encode($dataarray); //ubah data array ke JSON

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, getenv('BPJS_ANTREAN_URL') . "antrean/add");
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $content = curl_exec($ch);
        curl_close($ch);

        $resultarr = json_decode($content, true);
        $key = '' . $data . '' . $secretKey . '' . $tStamp . '';

        echo json_encode($resultarr);
        // $resultarr = json_decode($content, true);
        // $key = '' . $data . '' . $secretKey . '' . $tStamp . '';
        // if ($resultarr['metadata']['code'] == 200) {
        //     $response = $this->stringDecrypt($key, $resultarr['response']);
        //     echo $response;
        // } else {
        //     echo json_encode($resultarr['metaData']['message']);
        // }
    }

    public function logAntreanBpjs()
    {
        $data = array(
            'log'       => $_POST['log'],
            'no_mr'       => $_POST['no_mr'],
            'nama_pasien'       => $_POST['nama_pasien'],
            'kodebooking'       => $_POST['kodebooking'],
        );

        $this->Antrian_model->log_antrean_bpjs($data);
        echo json_encode($data);
    }

    //update waktu antrean bpjs
    public function update_task()
    {
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
        ];

        $yourdate = date("Y-m-d H:i:s");
        $stamp = strtotime($yourdate);
        $estimasidilayani = $stamp * 1000;
        $kodebooking = $_POST['kodebooking'];

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
        echo json_encode($resultarr);
    }

    public function insertHasilSEP()
    {
        $data = array(
            'no_registrasi' => $_POST['no_registrasi'],
            'noSep'         => $_POST['noSep'],
            'tglSep'        => $_POST['tglSep'],
            'jnsPelayanan'  => $_POST['jnsPelayanan'],
            'kelasRawat'    => $_POST['kelasRawat'],
            'kodeDiagnosa'  => $_POST['diagAwal'],
            'diagnosa'      => $_POST['diagnosa'],
            'noRujukan'     => $_POST['noRujukan'],
            'poli'          => $_POST['poli'],
            'poliEksekutif' => $_POST['poliEksekutif'],
            'catatan'       => $_POST['catatan'],
            'penjamin'      => $_POST['penjamin'],
            'noKartu'       => $_POST['noKartu'],
            'nama'          => $_POST['nama'],
            'tglLahir'      => $_POST['tglLahir'],
            'noMr'          => $_POST['noMr'],
            'kelamin'       => $_POST['kelamin'],
            'jnsPeserta'    => $_POST['jnsPeserta'],
            'hakKelas'      => $_POST['hakKelas'],
            'asuransi'      => $_POST['asuransi'],
            'dinsos'        => $_POST['dinsos'],
            'prolanisPRB'   => $_POST['prolanisPRB'],
            'noSKTM'        => $_POST['noSKTM'],
            'dokter'        => $_POST['dokter'],
            'faskesPerujuk' => $_POST['faskesPerujuk'],
            'noTelepon'     => $_POST['noTelepon'],
            'kelasRawatNaik' => $_POST['kelasRawatNaik'],
            'pembiayaan'    => $_POST['pembiayaan'],
            'tujuanKunj'    => $_POST['tujuanKunj'],
            'flagProcedure' => $_POST['flagProcedure'],
            'kodePenunjang' => $_POST['kodePenunjang'],
            'assesmentPel'  => $_POST['assesmentPel'],
        );

        $this->Rajal_model->simpan_hasil_sep($data);

        echo json_encode(
            [
                'metadata' => [
                    'result' => [
                        'dataasep' => $data,
                    ],
                    'message' => 'Data SEP disimpan',
                    'code' => 200
                ],
            ],
            200
        );
    }

    public function cetak_registrasi_bpjs($noRegis)
    {
        $data['title'] = 'Lembar Registrasi APM BPJS';
        $data['user'] = $this->db->get_where('user', ['username' => $this->session->userdata('username')])->row_array();
        $bpjsSep = $this->db
            ->select('
            noSep
            ')
            ->get_where('bpjs_sep', ['no_registrasi' => $noRegis])->row_array();
        $noSep = $bpjsSep['noSep'];
        $dataconsid = getenv('BPJS_VCLAIM_CONSID');
        $secretKey = getenv('BPJS_VCLAIM_SIGNATURE');
        $user_key = getenv('BPJS_VCLAIM_USERKEY');

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

        $data['data_kunjungan'] = $this->db
            ->select('
            pasien_kunjungan.id_kunjungan, 
            pasien_kunjungan.no_registrasi AS noregistrasi, 
            pasien_kunjungan.ref_antrean, 
            pasien.nama_pasien AS namapasien, 
            pasien.tempat_lahir, 
            pasien.no_mr AS nomr, 
            ruangan.id_ruangan,
            ruangan.nama_ruangan AS namapoli,
            pegawai.id_pegawai,
            pegawai.gelar_depan AS gelardepan,
            pegawai.nama_pegawai AS namadokter,
            pegawai.gelar_belakang AS gelarbelakang, 
            pasien_kunjungan.tanggal_registrasi AS wakturegistrasi, 
            referensi_penjamin.nama_penjamin AS jenispasien,
           
            pasien_kunjungan.final_kunjungan,
            pasien.tanggal_lahir AS tgllahir, 
            pasien_kunjungan.ut AS ut,
            pasien_kunjungan.ub AS ub,
            pasien_kunjungan.uh AS uh,
            pasien_kunjungan.penjamin_pasien,
            pasien_kunjungan.final_kunjungan,
            pasien.jenis_kelamin AS JK, 
            ')
            ->join('simrsj_master.pasien', 'pasien.id_pasien = pasien_kunjungan.id_pasien', 'LEFT')
            ->join('simrsj_master.pegawai', 'pegawai.id_pegawai = pasien_kunjungan.id_dokter')
            ->join('simrsj_master.ruangan', 'ruangan.id_ruangan = pasien_kunjungan.id_poli', 'LEFT')
            ->join('simrsj_master.referensi_penjamin', 'referensi_penjamin.id_penjamin = pasien_kunjungan.penjamin_pasien', 'LEFT')
            ->get_where('simrsj_aplikasi.pasien_kunjungan', ['no_registrasi' => $noRegis])->row_array();
        $data['dataantrean'] = $this->db
            ->select('
            nomorantreanpoli
            ')
            ->get_where('simrsj_webservice.antrean', ['kodebooking' => $data['data_kunjungan']['ref_antrean']])->row_array();

        // //rujukan

        // $f1_data = getenv('BPJS_VCLAIM_CONSID');
        // $f1_secretKey = getenv('BPJS_VCLAIM_SIGNATURE');
        // $f1_user_key = getenv('BPJS_VCLAIM_USERKEY');

        // date_default_timezone_set('UTC');
        // $f1_tStamp = strval(time() - strtotime('1970-01-01 00:00:00'));

        // $f1_signature = hash_hmac('sha256', $f1_data . "&" . $f1_tStamp, $f1_secretKey, true);
        // $f1_encodedSignature = base64_encode($f1_signature);

        // $f1_ch = curl_init();
        // $f1_headers = [
        //     'X-cons-id: ' . $f1_data . '',
        //     'X-timestamp: ' . $f1_tStamp . '',
        //     'X-signature: ' . $f1_encodedSignature . '',
        //     'User-key: ' . $f1_user_key . '',
        //     'Content-Type: application/json; charset=utf-8',
        // ];
        // $f1_url = getenv('BPJS_VCLAIM_URL') . "Rujukan/" .  $data['data_sep']['noRujukan'];


        // curl_setopt($f1_ch, CURLOPT_URL, $f1_url);
        // curl_setopt($f1_ch, CURLOPT_HTTPHEADER, $f1_headers);
        // curl_setopt($f1_ch, CURLOPT_RETURNTRANSFER, 1);
        // curl_setopt($f1_ch, CURLOPT_TIMEOUT, 3);
        // curl_setopt($f1_ch, CURLOPT_HTTPGET, 1);
        // curl_setopt($f1_ch, CURLOPT_SSL_VERIFYPEER, false);
        // $f1_content = curl_exec($f1_ch);
        // // $f1_err = curl_error($f1_ch);
        // curl_close($f1_ch);

        // $f1_resultarr = json_decode($f1_content, true);
        // $f1_key = '' . $f1_data . '' . $f1_secretKey . '' . $f1_tStamp . '';


        // if ($f1_resultarr) {
        //     if ($f1_resultarr['metaData']['code'] == 200) {
        //         $f1_encrypt_method = 'AES-256-CBC';
        //         $f1_key_hash = hex2bin(hash('sha256', $f1_key));
        //         $f1_iv = substr(hex2bin(hash('sha256', $f1_key)), 0, 16);
        //         $f1_output = openssl_decrypt(base64_decode($resultarr5['response']), $f1_encrypt_method, $f1_key_hash, OPENSSL_RAW_DATA, $f1_iv);
        //         $f1_output5  = \LZCompressor\LZString::decompressFromEncodedURIComponent($f1_output);
        //         $f1_response = json_decode($f1_output5, true);
        //         $response_f1 = $f1_response;
        //         $data['$response_f1'] = $response_f1;
        //         $response = $data['$response_f1']['tglKunjungan'];
        //         // $response = $this->stringDecrypt($f1_key, $f1_resultarr['response']);
        //         // echo json_encode($f1_resultarr);
        //         // echo $response;
        //     } else {
        //         $f2_data = getenv('BPJS_VCLAIM_CONSID');
        //         $f2_secretKey = getenv('BPJS_VCLAIM_SIGNATURE');
        //         $f2_user_key = getenv('BPJS_VCLAIM_USERKEY');

        //         date_default_timezone_set('UTC');
        //         $f2_tStamp = strval(time() - strtotime('1970-01-01 00:00:00'));

        //         $f2_signature = hash_hmac('sha256', $f2_data . "&" . $f2_tStamp, $f2_secretKey, true);
        //         $f2_encodedSignature = base64_encode($f2_signature);

        //         $f2_ch = curl_init();
        //         $f2_headers = [
        //             'X-cons-id: ' . $f2_data . '',
        //             'X-timestamp: ' . $f2_tStamp . '',
        //             'X-signature: ' . $f2_encodedSignature . '',
        //             'User-key: ' . $f2_user_key . '',
        //             'Content-Type: application/json; charset=utf-8',
        //         ];
        //         $f2_url = getenv('BPJS_VCLAIM_URL') . "Rujukan/RS/" . $data['data_sep']['noRujukan'];

        //         curl_setopt($f2_ch, CURLOPT_URL, $f2_url);
        //         curl_setopt($f2_ch, CURLOPT_HTTPHEADER, $f2_headers);
        //         curl_setopt($f2_ch, CURLOPT_RETURNTRANSFER, 1);
        //         curl_setopt($f2_ch, CURLOPT_TIMEOUT, 3);
        //         curl_setopt($f2_ch, CURLOPT_HTTPGET, 1);
        //         curl_setopt($f2_ch, CURLOPT_SSL_VERIFYPEER, false);
        //         $f2_content = curl_exec($f2_ch);
        //         // $f2_err = curl_error($f2_ch);
        //         curl_close($f2_ch);

        //         $f2_resultarr = json_decode($f2_content, true);
        //         $f2_key = '' . $f2_data . '' . $f2_secretKey . '' . $f2_tStamp . '';

        //         if ($f2_resultarr) {
        //             if ($f2_resultarr['metaData']['code'] == 200) {
        //                 // $response = $this->stringDecrypt($f2_key, $f2_resultarr['response']);
        //                 $f2_encrypt_method = 'AES-256-CBC';
        //                 $f2_key_hash = hex2bin(hash('sha256', $f2_key));
        //                 $f2_iv = substr(hex2bin(hash('sha256', $f2_key)), 0, 16);
        //                 $f2_output = openssl_decrypt(base64_decode($resultarr5['response']), $f2_encrypt_method, $f2_key_hash, OPENSSL_RAW_DATA, $f2_iv);
        //                 $f2_output5  = \LZCompressor\LZString::decompressFromEncodedURIComponent($f2_output);
        //                 $f2_response = json_decode($f2_output5, true);
        //                 $response = $f2_response;
        //                 // echo json_encode($f2_resultarr);

        //             } else {
        //                 $response = '-';
        //             }
        //         } else {
        //             $response = '-';
        //         }
        //     }
        // } else {
        //     $response = '-';
        // }

        // $data['rujukan'] = $response;
        $data['content'] = '';
        $page = 'dashboard/cetak_registrasi_bpjs';
        $this->load->view($page, $data);
    }
}
