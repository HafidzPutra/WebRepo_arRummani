<?php
session_start();
include 'connect.php';

// Must be logged in as student
if (!isset($_SESSION['student_id'])) {
    header("Location: studentlogin.php");
    exit();
}

$student_id = $_SESSION['student_id'];

// Handle upload
if (isset($_POST['upload'])) {

    if (isset($_FILES['payment_image']) && $_FILES['payment_image']['error'] == 0) {

        $file = $_FILES['payment_image'];

        // File size check (200 KB = 204800 bytes)
        if ($file['size'] > 204800) {
            echo "<script>alert('Ukuran File terlalu besar! Ukuran file maksimal 200 KB.');</script>";
        } else {

            $image = addslashes(file_get_contents($file['tmp_name']));

            $query = "UPDATE datasiswa SET 
                        gambar_pembayaran='$image',
                        status_siswa='Verifikasi Pembayaran',
                        tanggal_status_baru=CURDATE()
                      WHERE id='$student_id'";

            if (mysqli_query($conn, $query)) {
                echo "<script>alert('Bukti Pembayaran Sukses Diupload!');</script>";
                header("Location: studentaccount.php");
                exit();
            } else {
                echo "<script>alert('Upload Gagal!');</script>";
            }
        }

    } else {
        echo "<script>alert('Pilih Gambarnya!');</script>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="style.css">
    <title>Upload Bukti Pembayaran</title>
</head>
<body>
<div class="form-card">
    <h2>Upload Bukti Pembayaran</h2>
    <p style="color:red;"><b>Peringatan :</b> Ukuran file maksimal 200 KB</p>
    <form method="POST" enctype="multipart/form-data">
        <input type="file" name="payment_image" accept="image/*" required>
        <br>
        <button type="submit" name="upload">Upload</button>
    </form>
</div>
<a href="studentaccount.php"><button>Kembali</button></a>
</body>
</html>