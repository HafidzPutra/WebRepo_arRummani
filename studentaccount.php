<?php
session_start();
include 'connect.php';
if (!isset($_SESSION['student_id'])) {
    header("Location: studentlogin.php");
    exit();}
$student_id = $_SESSION['student_id'];
$query = "SELECT * FROM datasiswa WHERE id='$student_id'";
$result = mysqli_query($conn, $query);
$data = mysqli_fetch_assoc($result);
?>
<!DOCTYPE html>
<html>
    <head>
        <link rel="stylesheet" href="style.css">
        <title>Student Account</title>
    </head>
    <body>
        <div class="container">
        <h2>Student Information</h2>
        <table border="1" cellpadding="10">
            <tr><td>Nama Siswa</td><td><?php echo $data['nama_siswa']; ?></td></tr>
            <tr><td>Tempat Lahir</td><td><?php echo $data['tempat_lahir']; ?></td></tr>
            <tr><td>Tanggal Lahir</td><td><?php echo $data['tanggal_lahir']; ?></td></tr>
            <tr><td>Jemis Kelamin</td><td><?php echo $data['jenis_kelamin']; ?></td></tr>
            <tr><td>Nama Ayah</td><td><?php echo $data['nama_ayah']; ?></td></tr>
            <tr><td>Nama Ibu</td><td><?php echo $data['nama_ibu']; ?></td></tr>
            <tr><td>Nomor Telepon</td><td><?php echo $data['nomor_hp']; ?></td></tr>
            <tr><td>Alamat Email</td><td><?php echo $data['alamat_email']; ?></td></tr>
            <tr><td>Alamat Rumah</td><td><?php echo $data['alamat_rumah']; ?></td></tr>
            <tr><td>Tingkat Siswa</td><td><?php echo $data['tingkat_siswa']; ?></td></tr>
            <tr><td>Tanggal Daftar</td><td><?php echo $data['tanggal_daftar']; ?></td></tr>
        </table>
        <br>
        <h3>Status: <?php echo $data['status_siswa']; ?></h3>
        <br>
        <h3>Catatan Pribadi</h3>
        <textarea rows="5" cols="50" readonly>
        <?php echo $data['catatan_pribadi'] ? $data['catatan_pribadi'] : 'Belum Ada Info';?>
        </textarea>
        <br><br>
        <?php
        if ($data['status_siswa'] == "Menunggu Pembayaran") {
            echo '<a href="tuitionpayment.php"><button>Pembayaran</button></a>';}
        if ($data['status_siswa'] == "Butuh Perbaikan") {
            echo '<a href="studenteditdata.php"><button>Resubmit</button></a>';}
        ?>
        <br><br>
        <a href="logout.php"><button>Logout</button></a>
        </div>
    </body>
</html>