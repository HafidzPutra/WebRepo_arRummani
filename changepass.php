<?php
session_start();
include 'connect.php';
if (!isset($_SESSION['admin_id'])) {
    header("Location: adminlogin.php");
    exit();}
if (isset($_POST['changepw'])) {
    $id = $_SESSION['admin_id'];
    $newpassword = $_POST['password'];
    if (empty($newpassword)) {
        echo "<script>alert('Isilah Datanya!');</script>";
    } else {
        $hashed_password = password_hash($newpassword, PASSWORD_DEFAULT);
        $query = "UPDATE dataadmin SET password = ? WHERE id = ?";
        $stmt = mysqli_prepare($conn, $query);
        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "ss", $hashed_password, $id);
            if (mysqli_stmt_execute($stmt)) {
                echo "<script>
                        alert('Password berhasil diubah!');
                        window.location.href='studentlist.php';
                      </script>";
            } else {echo "<script>alert('Gagal mengubah password!');</script>";}
            mysqli_stmt_close($stmt);
        } else {echo "Terjadi kesalahan pada database.";}
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="style.css">
    <title>Ubah Password</title>
</head>
<body>
    <div class="auth-container">
        <div class="sidebar">
            <h2>Pesantren Ar-Rummani</h2>
            <p style="text-align: center;">Ubah Password</p>
        </div>
        <div class="auth-right">
            <div class="auth-box">
                <h2 style="text-align:center;">Ubah Password</h2>
                <br>
                <form method="POST">
                    <label>Password Baru : </label>
                    <input type="password" name="password" required>
                    <button type="submit" name="changepw">Ubah</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>