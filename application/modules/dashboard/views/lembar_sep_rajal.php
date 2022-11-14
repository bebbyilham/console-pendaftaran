<?php
ob_start();
class SEP extends TCPDF
{

    public function Header()
    {
        // Logo
        $image_file = base_url('assets/img/bpjs-kesehatan.png');
        $this->Image($image_file, 10, 6, 50, '', 'PNG', '', '', true, 150, '', false, false, 0, false, false, false);
        // Set font
        $this->SetFont('helvetica', '', 12);
        // Title
        $this->Cell(55, 0, '', 0, 1, 'C');
        $this->Cell(55, 0, '', 0, 0, 'C');
        $this->Cell(130, 5, 'SURAT ELEGIBILITAS PESERTA', 0, 1, 'L');
        $this->Cell(55, 0, '', 0, 0, 'C');
        $this->Cell(130, 0, 'RSJ Prof HB Sa`anin Padang', 0, 0, 'L');
        // pdf->Cell(0, 10, $pasien['jenis_kelamin'], 0, 0, 'L');
    }
    //  Page footer
    public function Footer()
    {
        $tgl_cetak = date('d-m-Y H:i:s');
        // Position at 15 mm from bottom
        $this->SetY(-20);
        // Set font
        $this->SetFont('helvetica', '', 11);
        $this->Cell(116, 1, '', 0, 0, 'L');
        $this->Cell(0, 1, 'Pasien/Keluarga Pasien', 0, 1, 'L');
        $this->SetFont('helvetica', 'I', 6);
        // Page number
        $this->Cell(105, 1, '*Saya Menyetujui BPJS Kesehatan menggunakan Informasi Media pasien jika diperlukan.', 0, 1, 'L');
        $this->Cell(0, 1, '*SEP bukan sebagai bukti penjamin peserta.', 0, 1, 'L');
        $this->Cell(0, 1, 'CETAKAN ke 1 | ' . $tgl_cetak . '', 0, 0, 'L');
    }
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

    // variabel pecahkan 0 = tanggal
    // variabel pecahkan 1 = bulan
    // variabel pecahkan 2 = tahun

    return $pecahkan[2] . ' ' . $bulan[(int)$pecahkan[1]] . ' ' . $pecahkan[0];
}
date_default_timezone_set('Asia/Jakarta');

$pdf = new SEP(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set page format (read source code documentation for further information)
// MediaBox - width = urx - llx 210 (mm), height = ury - lly = 297 (mm) this is A4
$page_format = array(
    'MediaBox' => array('llx' => 0, 'lly' => 0, 'urx' => 91.694, 'ury' => 209.8),
    //'CropBox' => array ('llx' => 0, 'lly' => 0, 'urx' => 210, 'ury' => 297),
    //'BleedBox' => array ('llx' => 5, 'lly' => 5, 'urx' => 205, 'ury' => 292),
    //'TrimBox' => array ('llx' => 10, 'lly' => 10, 'urx' => 200, 'ury' => 287),
    //'ArtBox' => array ('llx' => 15, 'lly' => 15, 'urx' => 195, 'ury' => 282),
    'Dur' => 0,
    'Rotate' => 0,
    'PZ' => 0,
);
$pdf->SetMargins(10, 10, 1, true);
// add first page ---
$pdf->AddPage('L', $page_format, false, false);
$pdf->SetFont('', '', 9);
$pdf->Cell(30, 8, '', 0, 1, 'L');
$pdf->Cell(30, 1, 'No. SEP', 0, 0, 'L');
$pdf->Cell(60, 1, ': ' . $data_sep['noSep'], 0, 0, 'L');
$pdf->Cell(30, 1, '', 0, 0, 'L');
$pdf->Cell(60, 1, '', 0, 1, 'L');

$pdf->Cell(30, 1, 'Tgl. SEP', 0, 0, 'L');
$pdf->Cell(90, 1, ': ' . $data_sep['tglSep'], 0, 0, 'L');
$pdf->Cell(30, 1, 'Jns. Peserta', 0, 0, 'L');
$pdf->Cell(50, 1, ': ' . $data_sep['peserta']['jnsPeserta'], 0, 1, 'L');

$pdf->Cell(30, 1, 'No. Kartu', 0, 0, 'L');
$pdf->Cell(90, 1, ': ' . $data_sep['peserta']['noKartu'], 0, 0, 'L');
$pdf->Cell(30, 1, 'Jns. Rawat', 0, 0, 'L');
$pdf->Cell(50, 1, ': ' . $data_sep['jnsPelayanan'], 0, 1, 'L');

$pdf->Cell(30, 1, 'Nama Peserta', 0, 0, 'L');
$pdf->Cell(90, 1, ': ' . $data_sep['peserta']['nama'] . ' / ' . $data_sep['peserta']['noMr'], 0, 0, 'L');
$pdf->Cell(30, 1, 'Jns. Kunjungan', 0, 0, 'L');
$pdf->Cell(50, 1, ': ' . $data_sep['tujuanKunj'], 0, 1, 'L');

$pdf->Cell(30, 1, 'Tgl. Lahir', 0, 0, 'L');
$pdf->Cell(25, 1, ': ' . $data_sep['peserta']['tglLahir'], 0, 0, 'L');
$pdf->Cell(15, 1, 'Kelamin', 0, 0, 'L');
$pdf->Cell(50, 1, ': ' . $data_sep['peserta']['kelamin'], 0, 0, 'L');
$pdf->Cell(30, 1, '', 0, 0, 'L');
if ($data_sep['assesmentPel'] == 'Tidak ada') {
    $pdf->Cell(50, 1, ': -', 0, 1, 'L');
} else {
    $pdf->Cell(50, 1, ': ' . $data_sep['assesmentPel'], 0, 1, 'L');
}

$pdf->Cell(30, 1, 'No. Telepon', 0, 0, 'L');
$pdf->Cell(90, 1, ': ' . $data_peserta['peserta']['mr']['noTelepon'], 0, 0, 'L');
$pdf->Cell(30, 1, 'Poli Perujuk', 0, 0, 'L');
$pdf->Cell(50, 1, ': -', 0, 1, 'L');
if ($data_sep['jnsPelayanan'] == "Rawat Inap") {
    $pdf->Cell(30, 1, 'Sub/Spesialis', 0, 0, 'L');
    $pdf->Cell(90, 1, ': -', 0, 0, 'L');
    $pdf->Cell(30, 1, 'Kls. Hak', 0, 0, 'L');
    $pdf->Cell(50, 1, ': ' . $data_sep['peserta']['hakKelas'], 0, 1, 'L');

    $pdf->Cell(30, 1, 'Dokter', 0, 0, 'L');
    $pdf->Cell(90, 1, ': ' . $data_sep['kontrol']['nmDokter'], 0, 0, 'L');
    $pdf->Cell(30, 1, 'Kls. Rawat', 0, 0, 'L');
    $pdf->Cell(50, 1, ': ' . $data_sep['kelasRawat'], 0, 1, 'L');
    $pdf->Cell(30, 1, 'Faskes Perujuk', 0, 0, 'L');
    $pdf->Cell(90, 1, ': RSJ. Prof. HB. Saanin Padang', 0, 0, '');
} else {
    $pdf->Cell(30, 1, 'Sub/Spesialis', 0, 0, 'L');
    $pdf->Cell(90, 1, ': ' . $data_sep['poli'], 0, 0, 'L');
    $pdf->Cell(30, 1, 'Kls. Hak', 0, 0, 'L');
    $pdf->Cell(50, 1, ': ' . $data_sep['peserta']['hakKelas'], 0, 1, 'L');

    $pdf->Cell(30, 1, 'Dokter', 0, 0, 'L');
    $pdf->Cell(90, 1, ': ' . $data_sep['dpjp']['nmDPJP'], 0, 0, 'L');
    $pdf->Cell(30, 1, 'Kls. Rawat', 0, 0, 'L');
    $pdf->Cell(50, 1, ': ' . $data_sep['kelasRawat'], 0, 1, 'L');
    $pdf->Cell(30, 1, 'Faskes Perujuk', 0, 0, 'L');
    if ($data_sep['poli'] == 'INSTALASI GAWAT DARURAT') {
        $pdf->Cell(90, 1, ': RSJ. Prof. HB. Saanin Padang', 0, 0, 'L');
    } else {
        $pdf->Cell(90, 1, ': ' . $data_peserta['peserta']['provUmum']['kdProvider'] . '-' . $data_peserta['peserta']['provUmum']['nmProvider'], 0, 0, '');
    }
}

$pdf->Cell(30, 1, 'No. Rujukan', 0, 0, 'L');
$pdf->Cell(90, 1, ': ' . $data_sep['noRujukan'], 0, 0, 'L');
$pdf->Cell(30, 1, 'Penjamin', 0, 0, 'L');
$pdf->Cell(50, 1, ': ' . $data_sep['penjamin'], 0, 1, 'L');
$pdf->Cell(30, 1, 'Diagnosa Awal', 0, 0, 'L');
$pdf->Cell(60, 1, ': ' . $data_sep['diagnosa'], 0, 0, 'L');
$pdf->Cell(30, 1, '', 0, 0, 'L');
$pdf->Cell(60, 1, '', 0, 1, 'L');

$pdf->Cell(30, 1, 'Catatan', 0, 0, 'L');
$pdf->Cell(60, 1, ': ' . $data_sep['catatan'], 0, 0, 'L');
$pdf->Cell(30, 1, '', 0, 0, 'L');
$pdf->Cell(50, 1, '', 0, 1, 'L');
$style = array('width' => 0.5, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(0, 0, 0));
$pdf->Line(125, 85, 169, 85, $style);
$pdf->writeHTML($content, true, 0, true, 0);
ob_end_clean();
$pdf->Output('LEMBAR SEP RAJAL.pdf');
