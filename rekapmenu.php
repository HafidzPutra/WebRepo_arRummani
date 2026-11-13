<?php
session_start();
include 'connect.php';
if (!isset($_SESSION['admin_id'])) {
    header("Location: adminlogin.php");
    exit();}
require('fpdf/fpdf.php');
$err = '';
$tahunmark = $tingkatmark = '';
if (isset($_POST['action']) && $_POST['action'] === 'rekap') {
    $tingkat = $_POST['grade'] ?? '';
    $tahun = $_POST['year'] ?? '';
    if (!empty($_POST['allyear'])) {$tahun = '';}
    $sql = "SELECT * FROM datasiswa WHERE 1=1";
    if ($tahun !== '') {
        $tahunEsc = $conn->real_escape_string($tahun);
        $sql .= " AND YEAR(tanggal_daftar) = '$tahunEsc'";
        $tahunmark = 'Tahun ' . $tahun;}
    if ($tingkat !== '') {
        $tingkatEsc = $conn->real_escape_string($tingkat);
        $sql .= " AND tingkat_siswa = '$tingkatEsc'";
        $tingkatmark = 'Tingkat ' . $tingkat;}
    $result = $conn->query($sql);
    if (!$result) {die("Query Error: " . $conn->error);}
    class PDF extends FPDF {
        var $widths;
        var $aligns;
        function SetWidths($w) { $this->widths = $w; }
        function SetAligns($a) { $this->aligns = $a; }
        function Row($data) {
            $nb = 0;
            for ($i = 0; $i < count($data); $i++) {
                $nb = max($nb, $this->NbLines($this->widths[$i], $data[$i]));}
            $h = 6 * $nb;
            if ($this->GetY() + $h > $this->PageBreakTrigger)
                $this->AddPage();
            for ($i = 0; $i < count($data); $i++) {
                $w = $this->widths[$i];
                $a = $this->aligns[$i] ?? 'L';
                $x = $this->GetX();
                $y = $this->GetY();
                $this->Rect($x, $y, $w, $h);
                $this->MultiCell($w, 6, $data[$i], 0, $a);
                $this->SetXY($x + $w, $y);}
            $this->Ln($h);}
        function NbLines($w, $txt) {
            $cw = $this->CurrentFont['cw'];
            if ($w == 0)
                $w = $this->w - $this->rMargin - $this->x;
            $wmax = ($w - 2) * 1000 / $this->FontSize;
            $s = str_replace("\r", '', $txt);
            $nb = strlen($s);
            if ($nb > 0 && $s[$nb - 1] == "\n")
                $nb--;
            $sep = -1; $i = 0; $j = 0; $l = 0; $nl = 1;
            while ($i < $nb) {
                $c = $s[$i];
                if ($c == "\n") {
                    $i++; $sep = -1; $j = $i; $l = 0; $nl++;
                    continue;}
                if ($c == ' ')
                    $sep = $i;
                $l += $cw[$c];
                if ($l > $wmax) {
                    if ($sep == -1) {if ($i == $j) $i++;} 
                    else {$i = $sep + 1;}
                    $sep = -1; $j = $i; $l = 0; $nl++;} 
                else {$i++;}
            } return $nl;
        }
    }
    $pdf = new PDF('L', 'mm', 'A4');
    $pdf->AddPage();
    $pdf->SetFont('Arial', 'B', 14);
    $pdf->Cell(0, 10, 'Rekap Data Siswa Pondok Pesantren Ar-Rummani Boarding School ' . $tahunmark . ' ' . $tingkatmark, 0, 1, 'C');
    $pdf->Ln(3);
    $pdf->SetFont('Arial', 'B', 10);
    $pdf->SetFillColor(0, 159, 255);
    $pdf->SetTextColor(255, 255, 255);
    $header = ['Tingkat','Nama Siswa','Tempat Lahir','Tanggal Lahir','Jenis Kelamin','Nama Ayah','Nama Ibu','No. HP','Alamat Rumah','Tahun'];
    $w = [15, 35, 25, 25, 25, 35, 35, 25, 40, 15];
    foreach ($header as $i => $h) {$pdf->Cell($w[$i], 10, $h, 1, 0, 'C', true);}
    $pdf->Ln();
    $pdf->SetTextColor(0, 0, 0);
    $pdf->SetWidths($w);
    while ($row = $result->fetch_assoc()) {
        $pdf->SetFont('Arial', '', 9);
        $tahunTampil = !empty($row['tanggal_daftar']) ? date('Y', strtotime($row['tanggal_daftar'])) : '-';
        $pdf->Row([
            $row['tingkat_siswa'],
            $row['nama_siswa'],
            $row['tempat_lahir'],
            $row['tanggal_lahir'],
            $row['jenis_kelamin'],
            $row['nama_ayah'],
            $row['nama_ibu'],
            $row['nomor_hp'],
            $row['alamat_rumah'],
            $tahunTampil 
        ]);}
    $pdf->Output('I', 'rekap_siswa.pdf');
    exit;}
?>
<!DOCTYPE html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Result</title>
    <link rel="stylesheet" href="style.css"/>
</head>
<body>
    <div class="dashboard">
        <div class="sidebar">
            <h2>Pesantren Ar-Rummani</h2>
            <p style="text-align: center;">Sistem Rekap Data</p>
            <a href="studentlist.php">Daftar Siswa</a>
            <a href="rekapmenu.php">Rekap Data Siswa</a>
            <a href="logout.php">Logout</a>
        </div>
        <div class="main">
            <div class="header">
                <h2 style="text-align:center;">
                    Rekap Daftar Siswa
                </h2>
            </div>
            <p>Masukkan tahun dan tingkat siswa untuk ditampilkan!</p>
            <form action="rekapmenu.php" method="POST">
                <div class="io">
                <label for="grade">Tingkat/Jenjang Pendaftaran </label>
                <select name="grade" id="grade" class="x">
                    <option value=''>semua tingkat</option>
                    <option value="SMP">SMP</option>
                    <option value="MA">MA</option>
                </select>
                <br>
                <label for="year">Tahun Pendaftaran </label>
                <input type="number" id="year" name="year" class="x"><br>
                <label for="allyear" style="color: red;">Centang kotak dibawah untuk "semua tahun"</label>
                <input type="checkbox" id="allyear" name="allyear" value='true'><br>
                </div>
                <br>
                <button type="submit" name="action" value="rekap" >Tampilkan</button>
                <p class="err"><b><?php echo $err;?></b></p>
            </form>
        </div>
    </div>
</body>
</html>