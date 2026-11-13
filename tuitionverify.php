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
        status_siswa='Pendaftaran Selesai',
        catatan_pribadi='$info',
        tanggal_status_baru=CURDATE()
        WHERE id='$student_id'";
    mysqli_query($conn, $update);
    header("Location: studentlist.php");
    exit();}
if (isset($_POST['reject'])) { //reject
    $info = $_POST['info'];
    $update = "UPDATE datasiswa SET 
        status_siswa='Menunggu Pembayaran',
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
        <title>Verifikasi Pembayaran</title>
    </head>
    <body>
        <div class="form-card">
            <h2 style="text-align:center;">
                Verifikasi Pembayaran
            </h2>
            <table border="1" cellpadding="10">
                <tr><td>Nama Siswa</td><td><?php echo $student['nama_siswa']; ?></td></tr>
                <tr><td>Tingkat Siswa</td><td><?php echo $student['tingkat_siswa']; ?></td></tr>
                <tr><td>Tanggal Lahir</td><td><?php echo $student['tanggal_lahir']; ?></td></tr>
                <tr><td>Tempat Lahir</td><td><?php echo $student['tempat_lahir']; ?></td></tr>
                <tr><td>Status Siswa</td><td><?php echo $student['status_siswa']; ?></td></tr>
            </table>
            <br>
            <h3> Foto Bukti Pembayaran</h3>
            <?php if (!empty($student['gambar_pembayaran'])) { ?>
            <img src="data:image/jpeg;base64,<?php echo base64_encode($student['gambar_pembayaran']); ?>" 
                width="300">
            <?php } else { ?>
            <p>Tidak Ada Foto Bukti Pembayaran Diupload.</p>
            <?php } ?>
            <br>
            <form method="POST">
                <h3>Catatan, Komentar, atau Instruksi Selanjutnya dari Pihak Sekolah</h3>
                <textarea name="info" rows="6" cols="50" placeholder="Jika pembayaran sukses, Tuliskan hal yang perlu dipersiapkan untuk kegiatan sekolah. Jika pembayaran gagal, Tuliskan kesalahan pada pembayaran dan instruksi lebih lanjut untuk penyelesaian masalah pembayaran."></textarea>
                <br>
                <button type="submit" name="approve">Pembayaran Sukses</button>
                <button type="submit" name="reject" class="gold">Pembayaran Gagal</button>
            </form>
        </div>
        <br>
        <a href="studentlist.php"><button>Kembali</button></a>
    </body>
</html>