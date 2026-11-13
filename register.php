<?php
session_start();
include 'connect.php';
if (isset($_POST['submit'])) { 
    $name       = $_POST['student_name'];
    $birthplace = $_POST['birthplace'];
    $birthdate  = $_POST['birthdate'];
    $gender     = $_POST['gender'] ?? '';
    $father     = $_POST['father_name'];
    $mother     = $_POST['mother_name'];
    $phone      = $_POST['phone'];
    $email      = $_POST['email'];
    $address      = $_POST['address'];
    $raw_password = $_POST['password'];
    $class      = $_POST['class_type'] ?? '';
    if (empty($name) || empty($birthplace) || empty($birthdate) || empty($gender) || empty($father) || empty($mother) || empty($phone) || empty($address) || empty($raw_password) || empty($class)) {
        echo "<script>alert('Mohon diisi semua kolom yang wajib!');</script>";} 
    elseif (!preg_match("/^[0-9]{10,14}$/", $phone)) {
        echo "<script>alert('Format nomor HP tidak valid! Harus berupa angka dengan panjang 10-14 karakter.');</script>";}
    elseif (!empty($email) && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "<script>alert('Format email tidak valid!');</script>";}
    elseif (strlen($raw_password) < 8 || strlen($raw_password) > 16 || !preg_match("/[A-Z]/", $raw_password) || !preg_match("/[0-9]/", $raw_password)) {
        echo "<script>alert('Password harus berukuran 8-16 karakter, mengandung setidaknya 1 huruf kapital, dan 1 angka!');</script>";} 
    else {$password = password_hash($raw_password, PASSWORD_DEFAULT);
        $sql = "INSERT INTO datasiswa 
                (nama_siswa, tempat_lahir, tanggal_lahir, jenis_kelamin, nama_ayah, nama_ibu, nomor_hp, alamat_email, alamat_rumah, password_siswa, tingkat_siswa, tanggal_daftar, status_siswa, tanggal_status_baru)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, CURDATE(), 'Baru Mendaftar', CURDATE())";
        $stmt = mysqli_prepare($conn, $sql);
        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "sssssssssss", $name, $birthplace, $birthdate, $gender, $father, $mother, $phone, $email, $address, $password, $class);
            if (mysqli_stmt_execute($stmt)) {
                $student_id = mysqli_insert_id($conn);
                $_SESSION['student_id'] = $student_id;
                header("Location: studentaccount.php");
                exit();
            } else {echo "<script>alert('Error: Gagal mendaftarkan siswa.');</script>";}
            mysqli_stmt_close($stmt);
        } else {echo "<script>alert('Error: Masalah pada sistem database.');</script>";}
    }
}
?>

<!DOCTYPE html>
<html>
    <head>
        <link rel="stylesheet" href="style.css">
        <title>Register</title>
    </head>
    <body>
        <div class="container">
            <h2>Registrasi Siswa</h2>
            <form method="POST">
                Nama Siswa : <br>
                <input type="text" name="student_name"><br><br>
                Tempat Lahir : <br>
                <input type="text" name="birthplace"><br><br>
                Tanggal Lahir : <br>
                <input type="date" name="birthdate"><br><br>
                Jenis Kelamin : <br>
                <input type="radio" name="gender" value="laki-laki"> Laki-Laki
                <input type="radio" name="gender" value="perempuan"> Perempuan
                <br><br>
                Nama Ayah : <br>
                <input type="text" name="father_name"><br><br>
                Nama Ibu : <br>
                <input type="text" name="mother_name"><br><br>
                Nomor Telepon : <br>
                <input type="text" name="phone"><br><br>
                Alamat Email (tidak wajib): <br>
                <input type="email" name="email"><br><br>
                Alamat Rumah : <br>
                <input type="text" name="address"><br><br>
                Password Akun Siswa: <br>
                <input type="password" name="password"><br><br>
                Tingkat Siswa : <br>
                <input type="radio" name="class_type" value="SMP"> SMP
                <input type="radio" name="class_type" value="MA"> MA
                <br><br>
                <button type="submit" name="submit">Daftar</button>
            </form>
        </div>
    </body>
</html>