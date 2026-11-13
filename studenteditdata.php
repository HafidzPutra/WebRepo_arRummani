<?php
session_start();
include 'connect.php';
if (!isset($_SESSION['student_id'])) {
    header("Location: studentlogin.php");
    exit();}
$student_id = $_SESSION['student_id'];
$query = "SELECT * FROM datasiswa WHERE id = ?";
$stmt = mysqli_prepare($conn, $query);
if ($stmt) {
    mysqli_stmt_bind_param($stmt, "i", $student_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $data = mysqli_fetch_assoc($result);
    mysqli_stmt_close($stmt);}
if (isset($_POST['resubmit'])) {
    $name = $_POST['student_name'];
    $birthplace = $_POST['birthplace'];
    $birthdate = $_POST['birthdate'];
    $gender = $_POST['gender'];
    $father = $_POST['father_name'];
    $mother = $_POST['mother_name'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $address = $_POST['address'];
    $class = $_POST['class_type'];
    if (empty($name) || empty($birthplace) || empty($birthdate) || empty($gender) || empty($father) || empty($mother) || empty($phone) || empty($address) || empty($class)) { 
        echo "<script>alert('Mohon diisi semua kolom yang wajib!');</script>";
    } else {
        $update = "UPDATE datasiswa SET 
            nama_siswa = ?,
            tempat_lahir = ?,
            tanggal_lahir = ?,
            jenis_kelamin = ?,
            nama_ayah = ?,
            nama_ibu = ?,
            nomor_hp = ?,
            alamat_email = ?,
            alamat_rumah = ?,
            tingkat_siswa = ?,
            status_siswa = 'Baru Mendaftar',
            tanggal_status_baru = CURDATE()
            WHERE id = ?";
        $stmt_update = mysqli_prepare($conn, $update);
        if ($stmt_update) {
            mysqli_stmt_bind_param($stmt_update, "ssssssssssi", 
                $name, $birthplace, $birthdate, $gender, $father, $mother, $phone, $email, $address, $class, $student_id);
            if (mysqli_stmt_execute($stmt_update)) {
                mysqli_stmt_close($stmt_update);
                echo "<script>alert('Data berhasil diupdate!'); window.location.href='studentaccount.php';</script>";
                exit();
            } else {echo "<script>alert('Gagal mengupdate data!');</script>";}
            mysqli_stmt_close($stmt_update);
        } else {echo "<script>alert('Terjadi kesalahan pada sistem!');</script>";}
    }
}
?>
<!DOCTYPE html>
<html>
    <head>
        <link rel="stylesheet" href="style.css">
        <title>Ubah Data Siswa</title>
    </head>
    <body>
        <h2>Ubah Data Siswa</h2>
        <form method="POST">
            Nama Siswa : <br>
            <input type="text" name="student_name" value="<?php echo $data['nama_siswa']; ?>"><br><br>
            Tempat Lahir : <br>
            <input type="text" name="birthplace" value="<?php echo $data['tempat_lahir']; ?>"><br><br>
            Tanggal Lahir : <br>
            <input type="date" name="birthdate" value="<?php echo $data['tanggal_lahir']; ?>"><br><br>
            Jenis Kelamin : <br>
            <input type="radio" name="gender" value="laki-laki" 
                <?php if ($data['jenis_kelamin'] == 'laki-laki') echo 'checked'; ?>> Laki-laki
            <input type="radio" name="gender" value="perempuan" 
                <?php if ($data['jenis_kelamin'] == 'perempuan') echo 'checked'; ?>> Perempuan
            <br><br>
            Nama Ayah : <br>
            <input type="text" name="father_name" value="<?php echo $data['nama_ayah']; ?>"><br><br>
            Nama Ibu : <br>
            <input type="text" name="mother_name" value="<?php echo $data['nama_ibu']; ?>"><br><br>
            Nomor Telepon : <br>
            <input type="text" name="phone" value="<?php echo $data['nomor_hp']; ?>"><br><br>
            Alamat Email : <br>
            <input type="email" name="email" value="<?php echo $data['alamat_email']; ?>"><br><br>
            Alamat Rumah : <br>
            <input type="text" name="address" value="<?php echo $data['alamat_rumah']; ?>"><br><br>
            Tingkat Siswa : <br>
            <input type="radio" name="class_type" value="SMP" 
                <?php if ($data['tingkat_siswa'] == 'SMP') echo 'checked'; ?>> SMP
            <input type="radio" name="class_type" value="MA" 
                <?php if ($data['tingkat_siswa'] == 'MA') echo 'checked'; ?>> MA
            <br><br>
            <button type="submit" name="resubmit">Update</button>
        </form>
    </body>
</html>