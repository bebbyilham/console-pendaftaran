<?php

use Zend\Barcode\Barcode;

ob_start();
class SEP extends TCPDF
{

    public function Header()
    {
        // Logo
        $image_file = base_url('assets/logo/logorsj.png');
        $this->Image($image_file, 4, 4, 10, '', 'PNG', '', '', true, 150, '', false, false, 0, false, false, false);
        // Set font
        $this->SetFont('dejavusans', '', 10);
        // Title
        $this->Cell(8, 0, '', 0, 1, 'C');
        $this->Cell(8, 0, '', 0, 0, 'C');
        $this->Cell(110, 4, 'Pemerintah Sumatera Barat', 0, 1, 'L');
        $this->Cell(8, 0, '', 0, 0, 'C');
        $this->Cell(1, 0, 'RS Jiwa Prof HB Saanin Padang', 0, 0, 'L');
        // pdf->Cell(0, 10, $pasien['jenis_kelamin'], 0, 0, 'L');
    }
    // //  Page footer
    // public function Footer()
    // {

    //     $tgl_cetak = date('d-m-Y H:i:s');
    //     // Position at 15 mm from bottom
    //     $this->SetY(-20);
    //     // Set font
    //     // $this->SetFont('helvetica', '', 11);
    //     $this->SetFont('helvetica', 'I', 6);
    //     // Page number
    //     $this->Cell(0, 1, '*Saya Menyetujui BPJS Kesehatan menggunakan Informasi Medis pasien jika diperlukan.', 0, 1, 'L');
    //     $this->Cell(0, 1, '*SEP bukan sebagai bukti penjamin peserta.', 0, 1, 'L');
    //     $this->Cell(0, 1, '**Dengan Tampilnya luaran SEP Elektronik Ini merupakan hasil validasi terhadap elegibilitas Pasien secara elektronik', 0, 1, 'L');
    //     $this->Cell(0, 1, '(validasi finger print atau biometrik / sistem validasi lain)', 0, 1, 'L');
    //     $this->Cell(0, 1, 'dan selanjutnya pasien dapat mengakses pelayanan kesehatan rujukan sesuai ketentuan berlaku', 0, 1, 'L');
    //     $this->Cell(0, 1, 'Kebenaran dan keaslian atas informasi data Pasien menjadi tanggung jawab penuh FKTRL', 0, 1, 'L');
    //     $this->Cell(0, 1, 'CETAKAN ke 1 | ' . $tgl_cetak . '', 0, 0, 'L');
    // }
}
function tgl_indo($tanggal)
{
    $bulan = array(
        1 =>   'Januari',
        'Februari',
        'Maret',
        'April',
        'Mei',
        'Juni',
        'Juli',
        'Agustus',
        'September',
        'Oktober',
        'November',
        'Desember'
    );
    $pecahkan = explode('-', $tanggal);



    return $pecahkan[2] . ' ' . $bulan[(int)$pecahkan[1]] . ' ' . $pecahkan[0];
}
date_default_timezone_set('Asia/Jakarta');

$pdf = new SEP(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set page format (read source code documentation for further information)
// MediaBox - width = urx - llx 210 (mm), height = ury - lly = 297 (mm) this is A4
// $page_format = array(
//     'MediaBox' => array('llx' => 0, 'lly' => 0, 'urx' => 58.1, 'ury' => 158.1),
//     //'CropBox' => array ('llx' => 0, 'lly' => 0, 'urx' => 210, 'ury' => 297),
//     //'BleedBox' => array ('llx' => 5, 'lly' => 5, 'urx' => 205, 'ury' => 292),
//     //'TrimBox' => array ('llx' => 10, 'lly' => 10, 'urx' => 200, 'ury' => 287),
//     //'ArtBox' => array ('llx' => 15, 'lly' => 15, 'urx' => 195, 'ury' => 282),
//     'Dur' => 0,
//     'Rotate' => 0,
//     'PZ' => 0,
// );
// Add a page
$width = 80;  // Set the width of the thermal paper in millimeters
$height = 135;  // Set the height of the thermal paper in millimeters
$pdf->SetMargins(10, 5, 1, true);
$pdf->AddPage('Thermal', [$width, $height]);
$pdf->SetAutoPageBreak(true, 0);
// $pdf->AddPage('L', $page_format, false, false);
$pdf->SetFont('dejavusans', '', 10);

// $pdf->Cell(60, 1, ': ' . $data_sep['noSep'], 0, 0, 'L');
// $pdf->Cell(30, 1, '', 0, 0, 'L');
// $pdf->Cell(60, 1, '', 0, 1, 'L');

// $pdf->Cell(30, 1, 'Tgl. SEP', 0, 0, 'L');
// $pdf->Cell(90, 1, ': ' . $data_sep['tglSep'], 0, 0, 'L');
// $pdf->Cell(30, 1, 'Jns. Peserta', 0, 0, 'L');
// $pdf->Cell(50, 1, ': ' . $data_sep['peserta']['jnsPeserta'], 0, 1, 'L');

// $pdf->Cell(30, 1, 'No. Kartu', 0, 0, 'L');
// $pdf->Cell(90, 1, ': ' . $data_sep['peserta']['noKartu'], 0, 0, 'L');
// $pdf->Cell(30, 1, 'Jns. Rawat', 0, 0, 'L');
// $pdf->Cell(50, 1, ': ' . $data_sep['jnsPelayanan'], 0, 1, 'L');

// $pdf->Cell(30, 1, 'Nama Peserta', 0, 0, 'L');
// $pdf->Cell(90, 1, ': ' . $data_sep['peserta']['nama'] . ' / ' . $data_sep['peserta']['noMr'], 0, 0, 'L');
// $pdf->Cell(30, 1, 'Jns. Kunjungan', 0, 0, 'L');
// $pdf->Cell(50, 1, ': ' . $data_sep['tujuanKunj']['nama'], 0, 1, 'L');

// $pdf->Cell(30, 1, 'Tgl. Lahir', 0, 0, 'L');
// $pdf->Cell(25, 1, ': ' . $data_sep['peserta']['tglLahir'], 0, 0, 'L');
// $pdf->Cell(15, 1, 'Kelamin', 0, 0, 'L');
// $pdf->Cell(50, 1, ': ' . $data_sep['peserta']['kelamin'], 0, 0, 'L');
// $pdf->Cell(30, 1, '', 0, 0, 'L');
// if ($data_sep['assesmentPel'] == 'Tidak ada') {
//     $pdf->Cell(50, 1, ': -', 0, 1, 'L');
// } else {
//     $pdf->Cell(50, 1, ': ' . $data_sep['assesmentPel'], 0, 1, 'L');
// }

// $pdf->Cell(30, 1, 'No. Telepon', 0, 0, 'L');
// $pdf->Cell(90, 1, ': ' . $data_peserta['peserta']['mr']['noTelepon'], 0, 0, 'L');
// $pdf->Cell(30, 1, 'Poli Perujuk', 0, 0, 'L');
// $pdf->Cell(50, 1, ': -', 0, 1, 'L');
// if ($data_sep['jnsPelayanan'] == "Rawat Inap") {
//     $pdf->Cell(30, 1, 'Sub/Spesialis', 0, 0, 'L');
//     $pdf->Cell(90, 1, ': -', 0, 0, 'L');
//     $pdf->Cell(30, 1, 'Kls. Hak', 0, 0, 'L');
//     $pdf->Cell(50, 1, ': ' . $data_sep['peserta']['hakKelas'], 0, 1, 'L');

//     $pdf->Cell(30, 1, 'Dokter', 0, 0, 'L');
//     $pdf->Cell(90, 1, ': ' . $data_sep['kontrol']['nmDokter'], 0, 0, 'L');
//     $pdf->Cell(30, 1, 'Kls. Rawat', 0, 0, 'L');
//     $pdf->Cell(50, 1, ': ' . $data_sep['kelasRawat'], 0, 1, 'L');
//     $pdf->Cell(30, 1, 'Faskes Perujuk', 0, 0, 'L');
//     $pdf->Cell(90, 1, ': RSJ. Prof. HB. Saanin Padang', 0, 0, '');
// } else {
//     $pdf->Cell(30, 1, 'Sub/Spesialis', 0, 0, 'L');
//     $pdf->Cell(90, 1, ': ' . $data_sep['poli'], 0, 0, 'L');
//     $pdf->Cell(30, 1, 'Kls. Hak', 0, 0, 'L');
//     $pdf->Cell(50, 1, ': ' . $data_sep['peserta']['hakKelas'], 0, 1, 'L');

//     $pdf->Cell(30, 1, 'Dokter', 0, 0, 'L');
//     $pdf->Cell(90, 1, ': ' . $data_sep['dpjp']['nmDPJP'], 0, 0, 'L');
//     $pdf->Cell(30, 1, 'Kls. Rawat', 0, 0, 'L');
//     $pdf->Cell(50, 1, ': ' . $data_sep['kelasRawat'], 0, 1, 'L');
//     $pdf->Cell(30, 1, 'Faskes Perujuk', 0, 0, 'L');
//     if ($data_sep['poli'] == 'INSTALASI GAWAT DARURAT') {
//         $pdf->Cell(90, 1, ': RSJ. Prof. HB. Saanin Padang', 0, 0, 'L');
//     } else {
//         $pdf->Cell(90, 1, ': ' . $data_peserta['peserta']['provUmum']['kdProvider'] . '-' . $data_peserta['peserta']['provUmum']['nmProvider'], 0, 0, '');
//     }
// }

// $pdf->Cell(30, 1, 'No. Rujukan', 0, 0, 'L');
// $pdf->Cell(90, 1, ': ' . $data_sep['noRujukan'], 0, 0, 'L');
// $pdf->Cell(30, 1, 'Penjamin', 0, 0, 'L');
// $pdf->Cell(50, 1, ': ' . $data_sep['penjamin'], 0, 1, 'L');
// $pdf->Cell(30, 1, 'Diagnosa Awal', 0, 0, 'L');
// $pdf->Cell(88, 1, ': ' . $data_sep['diagnosa'], 0, 0, 'L');
// $pdf->SetFont('helvetica', 'B', 6);
// $pdf->Cell(30, 1, 'Pasien/Keluarga Pasien', 0, 0, 'L');
// $pdf->Cell(60, 1, '', 0, 1, 'L');
// $pdf->SetFont('helvetica', '', 9);
// $pdf->Cell(30, 1, 'Catatan', 0, 0, 'L');
// $pdf->Cell(60, 1, ': ' . $data_sep['catatan'], 0, 1, 'L');
// $pdf->Cell(30, 1, '', 0, 1, 'L');
// $pdf->Cell(30, 1, '', 0, 1, 'L');
// $pdf->Cell(124, 1, '', 0, 0, 'L');
// $pdf->SetFont('helvetica', 'B', 6);
// $pdf->Cell(60, 1,  $data_sep['peserta']['nama'], 0, 0, 'L');


// $pdf->write2DBarcode($data_sep['peserta']['noKartu'], 'QRCODE,Q', 135, 52, 11, 11, $style, 'N');
// $style = array('width' => 0.5, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(0, 0, 0));

$pdf->Cell(0, 0, '', 0, 1, 'L');
$pdf->SetFont('dejavusans', '', 8);
$content .= '<div>
            <p class="centered">BUKTI REGISTRASI
                <br>ANJUNGAN PENDAFTARAN MANDIRI</p>
            <p>RAWAT JALAN
                <br>' . $tgl_cetak = date('d-m-Y H:i:s') . '</p>
            <table>
              
                <tbody>
                    <tr>
                        <td style="width: 30%">No. Registrasi</td>
                        <td style="width: 70%">: ' . $data_kunjungan['noregistrasi'] . '</td>
                    </tr>
                    <tr>
                        <td>No. RM</td>
                        <td>: ' . $data_kunjungan['nomr'] . '</td>
                    </tr>
                    <tr>
                        <td>Nama Pasien</td>
                        <td>: ' . $data_kunjungan['namapasien'] . '</td>
                    </tr>
                    <tr>
                        <td>Tanggal Lahir</td>
                        <td>: ' . $data_kunjungan['tanggal_lahir'] . '</td>
                    </tr>
                    <tr>
                        <td>Umur</td>
                        <td>: ' . $data_kunjungan['ut'] . ' tahun ' . $data_kunjungan['ub'] . ' bulan ' . $data_kunjungan['uh'] . ' hari ' . '</td>
                    </tr>
                    <tr>
                        <td>Tujuan</td>
                        <td>: ' . $data_kunjungan['namapoli'] . '</td>
                    </tr>
                    <tr>
                        <td>Dokter</td>
                        <td>: ' . $data_kunjungan['gelardepan'] . ' ' . $data_kunjungan['namadokter'] . ' ' . $data_kunjungan['gelarbelakang'] . '</td>
                    </tr>
                    <tr>
                        <td>Tgl Kunjungan</td>
                        <td>: ' . date('d-m-Y', strtotime($data_kunjungan['wakturegistrasi'])) . '</td>
                    </tr>
                    <tr>
                        <td>No. Rujukan</td>
                        <td>: ' . $data_sep['noRujukan'] . '</td>
                    </tr>
                   
                   
                    <tr>
                        <td>No. SEP</td>
                        <td>: ' . $data_sep['noSep'] . '</td>
                    </tr>
                   
                </tbody>
            </table>
            <h1><b>NO. ANTRIAN : ' . $dataantrean['nomorantreanpoli'] . '</b></h1>
            <h1><b>SILAHKAN KE LOKET 4</b></h1>
            <p><b>BUKTI INI TIDAK BOLEH HILANG.</b></p>
        </div>';


$pdf->writeHTML($content, true, 0, true, 0);

$style = array(
    'position' => 'S',
    'align' => 'C',
    'stretch' => false,
    'fitwidth' => false,
    'cellfitalign' => '',
    'border' => false,
    // 'hpadding' => 'auto',
    // 'vpadding' => 'auto',
    'fgcolor' => array(0, 0, 0),
    'bgcolor' => false, //array(255,255,255),
    'text' => false,
    'font' => 'helvetica',
    'fontsize' => 8,
    'stretchtext' => 4
);
// $pdf->Cell(0, 40, '', 0, 1, 'L');
// $pdf->Cell(1, 1, 'SCAN', 0, 1, 'C');
$pdf->write1DBarcode($data_sep['noRujukan'], 'C128', 28, 90, 25, 10, 1.1, $style, 'N');
$pdf->SetFont('dejavusans', '', 4);
$pdf->Cell(62, 1, 'Rujukan', 0, 1, 'C');
$pdf->write1DBarcode($data_sep['noSep'], 'C128', 28, 105, 25, 10, 1.1, $style, 'N');
$pdf->Cell(62, 1, 'SEP', 0, 1, 'C');
$pdf->write1DBarcode($data_kunjungan['nomr'], 'C128', 28, 120, 25, 10, 1.1, $style, 'N');
$pdf->Cell(62, 1, 'No. RM', 0, 1, 'C');
ob_end_clean();
$pdf->Output('LEMBAR SEP RAJAL.pdf');
