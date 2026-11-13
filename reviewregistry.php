<?php
session_start();
include 'connect.php';
if (!isset($_SESSION['admin_id'])) {
    header("Location: adminlogin.php");
    exit();}
if (!isset($_GET['id'])) {
    die("Tidak ada siswa yang dipilih.");}
$student_id = (int)$_GET['id'];
$query = "SELECT * FROM datasiswa WHERE id='$student_id'";
$result = mysqli_query($conn, $query);
$student = mysqli_fetch_assoc($result);
if (isset($_POST['approve'])) { //approve
    $info = $_POST['info'];
    $update = "UPDATE datasiswa SET 
        status_siswa='Menunggu Test',
        catatan_pribadi='$info',
        tanggal_status_baru=CURDATE()
        WHERE id='$student_id'";
    mysqli_query($conn, $update);
    header("Location: studentlist.php");
    exit();}
if (isset($_POST['reject'])) { //reject
    $info = $_POST['info'];
    $update = "UPDATE datasiswa SET 
        status_siswa='Butuh Perbaikan',
        catatan_pribadi='$info',
        tanggal_status_baru=CURDATE()
        WHERE id='$student_id'";
    mysqli_query($conn, $update);
    header("Location: studentlist.php");
    exit();}
?>
<!DOCTYPE html>
<html>
    <head>
        <link rel="stylesheet" href="style.css">
        <title>Review Registrasi Siswa</title>
    </head>
    <body>
        <div class="form-card">
            <h2 style="text-align:center;">
                Review Registrasi Siswa
            </h2>
            <table border="1" cellpadding="10">
                <tr><td>Nama Siswa</td><td><?php echo $student['nama_siswa']; ?></td></tr>
                <tr><td>Tempat Lahir</td><td><?php echo $student['tempat_lahir']; ?></td></tr>
                <tr><td>Tanggal Lahir</td><td><?php echo $student['tanggal_lahir']; ?></td></tr>
                <tr><td>Jemis Kelamin</td><td><?php echo $student['jenis_kelamin']; ?></td></tr>
                <tr><td>Nama Ayah</td><td><?php echo $student['nama_ayah']; ?></td></tr>
                <tr><td>Nama Ibu</td><td><?php echo $student['nama_ibu']; ?></td></tr>
                <tr><td>Nomor Telepon</td><td><?php echo $student['nomor_hp']; ?></td></tr>
                <tr><td>Alamat Email</td><td><?php echo $student['alamat_email']; ?></td></tr>
                <tr><td>Alamat Rumah</td><td><?php echo $student['alamat_rumah']; ?></td></tr>
                <tr><td>Tingkat Siswa</td><td><?php echo $student['tingkat_siswa']; ?></td></tr>
                <tr><td>Status Siswa</td><td><?php echo $student['status_siswa']; ?></td></tr>
            </table>
            <form method="POST">
                <h3>Catatan, Komentar, atau Instruksi Selanjutnya dari Pihak Sekolah</h3>
                <textarea name="info" rows="6" cols="50" placeholder="Jika setuju, Tuliskan instruksi tes untuk selanjutnya. Jika butuh perbaikan, Tuliskan kesalahan pada informasi siswa yang perlu diperbaiki."></textarea>
                <br>
                <button type="submit" name="approve">Setuju</button>
                <button type="submit" name="reject" class="gold">Perbaikan</button>
            </form>
        </div>
        <br>
        <a href="studentlist.php"><button>Kembali</button></a>
    </body>
</html>