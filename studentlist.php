<?php
session_start();
include 'connect.php';
if (!isset($_SESSION['admin_id'])) {
    header("Location: adminlogin.php");
    exit();}
    
$search = isset($_GET['search']) ? $_GET['search'] : '';

$total_students = mysqli_fetch_assoc( // Total students
    mysqli_query($conn, "SELECT COUNT(*) as total FROM datasiswa")
)['total'];
$registering = mysqli_fetch_assoc( // Registering
    mysqli_query($conn, "SELECT COUNT(*) as total FROM datasiswa WHERE status_siswa='Baru Mendaftar'")
)['total'];
$need_some_fix = mysqli_fetch_assoc( // Need some fix
    mysqli_query($conn, "SELECT COUNT(*) as total FROM datasiswa WHERE status_siswa='Butuh Perbaikan'")
)['total'];
$awaiting_test = mysqli_fetch_assoc( // Awaiting test
    mysqli_query($conn, "SELECT COUNT(*) as total FROM datasiswa WHERE status_siswa='Menunggu Test'")
)['total'];
$awaiting_payment = mysqli_fetch_assoc( // Awaiting payment
    mysqli_query($conn, "SELECT COUNT(*) as total FROM datasiswa WHERE status_siswa='Menunggu Pembayaran'")
)['total'];
$verify_payment = mysqli_fetch_assoc( // Verify payment
    mysqli_query($conn, "SELECT COUNT(*) as total FROM datasiswa WHERE status_siswa='Verifikasi Pembayaran'")
)['total'];
$completed = mysqli_fetch_assoc( // Completed
    mysqli_query($conn, "SELECT COUNT(*) as total FROM datasiswa WHERE status_siswa='Pendaftaran Selesai'")
)['total'];
$rejected = mysqli_fetch_assoc( // Rejected
    mysqli_query($conn, "SELECT COUNT(*) as total FROM datasiswa WHERE status_siswa='Gagal Daftar'")
)['total'];

$limit = 20; // Pagination setup
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if ($page < 1) $page = 1;
$offset = ($page - 1) * $limit;

$status_filter = isset($_GET['status']) ? $_GET['status'] : ''; // Filter setup

$sort = isset($_GET['sort']) ? $_GET['sort'] : 'id_desc'; // Sorting setup

switch ($sort) {
    case "status":
        $order_by = "status_siswa ASC";
        break;
    case "submit_date":
        $order_by = "tanggal_daftar DESC";
        break;
    case "status_update":
        $order_by = "tanggal_status_baru DESC";
        break;
    default:
        $order_by = "id DESC";}

$total_query = "SELECT COUNT(*) as total FROM datasiswa WHERE 1=1 ";
if ($status_filter != '') {
    $total_query .= "AND status_siswa='$status_filter' ";}
if ($search != '') {
    $total_query .= "AND nama_siswa LIKE '%$search%' ";}

$total_result = mysqli_query($conn, $total_query);
$total_data = mysqli_fetch_assoc($total_result)['total'];
$total_pages = ceil($total_data / $limit);

$query = "SELECT * FROM datasiswa WHERE 1=1 ";
if ($status_filter != '') {
    $query .= "AND status_siswa='$status_filter' ";}
if ($search != '') {
    $query .= "AND nama_siswa LIKE '%$search%' ";}

$query .= "ORDER BY $order_by LIMIT $limit OFFSET $offset";

$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html>
    <head>
        <link rel="stylesheet" href="style.css">
        <title>Student List</title>
    </head>
    <body>
        <div class="dashboard">
            <!-- SIDEBAR -->
            <div class="sidebar">
                <h2>Pesantren Ar-Rummani</h2>
                <p style="text-align: center;">Sistem Pendaftaran</p>
                <a href="studentlist.php">Daftar Siswa</a>
                <a href="rekapmenu.php">Rekap Data Siswa</a>
                <a href="changepass.php">Ubah Password</a>
                <a href="logout.php">Logout</a>
                <br><br>
                <p>Jumlah Siswa : <?php echo $total_students; ?></p>
                <p>Baru Mendaftar : <?php echo $registering; ?></p>
                <p>Butuh Perbaikan : <?php echo $need_some_fix; ?></p>
                <p>Menunggu Test : <?php echo $awaiting_test; ?></p>
                <p>Menunggu Pembayaran : <?php echo $awaiting_payment; ?></p>
                <p>Verifikasi Pembayaran : <?php echo $verify_payment; ?></p>
                <p>Pendaftaran Selesai : <?php echo $completed; ?></p>
                <p>Gagal Daftar : <?php echo $rejected; ?></p>
                <br>
            </div>
            <!-- MAIN CONTENT -->
            <div class="main">
                <div class="header">
                    <h2 style="text-align:center;">
                        Daftar Siswa
                    </h2>
                </div>
                <form method="GET">
                    <label>Tampilkan Berdasarkan Status : </label>
                    <select name="status">
                        <option value="">Semua</option>
                        <option value="Baru Mendaftar">Baru Mendaftar</option>
                        <option value="Butuh Perbaikan">Butuh Perbaikan</option>
                        <option value="Menunggu Test">Menunggu Test</option>
                        <option value="Menunggu Pembayaran">Menunggu Pembayaran</option>
                        <option value="Verifikasi Pembayaran">Verifikasi Pembayaran</option>
                        <option value="Pendaftaran Selesai">Pendaftaran Selesai</option>
                        <option value="Gagal Daftar">Gagal Daftar</option>
                    </select>
                    <br>
                    <label>Urutkan Dari : </label>
                    <select name="sort">
                        <option value="id_desc">Terbaru</option>
                        <option value="status">Status Siswa (A ke Z)</option>
                        <option value="submit_date">Tanggal Daftar</option>
                        <option value="status_update">Tanggal Status Baru</option>
                    </select>
                    <button type="submit">Terapkan</button>
                </form>
                <form method="GET" style="margin-bottom:15px;">
                    <input type="text" name="search" placeholder="Cari nama siswa..." 
                        value="<?php echo $search; ?>">
                    <input type="hidden" name="status" value="<?php echo $status_filter; ?>">
                    <input type="hidden" name="sort" value="<?php echo $sort; ?>">
                    <button type="submit">Search</button>
                </form>
                <table border="1" cellpadding="10">
                    <tr>
                        <th>Nama Siswa</th>
                        <th>Tanggal Daftar</th>
                        <th>Status Siswa</th>
                        <th>Tanggal Status Baru</th>
                        <th>Tindakan</th>
                    </tr>
                    <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                        <tr>
                            <td><?php echo $row['nama_siswa']; ?></td>
                            <td><?php echo $row['tanggal_daftar']; ?></td>
                            <td><?php
                                    $status = $row['status_siswa'];
                                    $class = "status";
                                    if ($status == "Baru Mendaftar") $class .= " registering";
                                    elseif ($status == "Butuh Perbaikan") $class .= " fix";
                                    elseif ($status == "Menunggu Test") $class .= " awaiting";
                                    elseif ($status == "Menunggu Pembayaran") $class .= " awaiting";
                                    elseif ($status == "Verifikasi Pembayaran") $class .= " payment";
                                    elseif ($status == "Pendaftaran Selesai") $class .= " complete";
                                    elseif ($status == "Gagal Daftar") $class .= " rejected";
                                    echo "<span class='$class'>$status</span>";
                                    ?>
                                </td>
                            <td><?php echo $row['tanggal_status_baru']; ?></td>
                            <td>
                                <?php
                                $status = $row['status_siswa'];
                                if ($status == "Baru Mendaftar") {
                                    echo '<a href="reviewregistry.php?id=' . $row['id'] . '">
                                            <button>Lihat Data</button>
                                        </a>';
                                } elseif ($status == "Butuh Perbaikan") {
                                    echo '<button disabled>Menunggu Perbaikan</button>';
                                } elseif ($status == "Menunggu Test") {
                                    echo '<a href="testresult.php?id=' . $row['id'] . '">
                                            <button>Buat Keputusan Test</button>
                                        </a>';
                                } elseif ($status == "Menunggu Pembayaran") {
                                    echo '<button disabled>Menunggu Pembayaran</button>';
                                } elseif ($status == "Verifikasi Pembayaran") {
                                    echo '<a href="tuitionverify.php?id=' . $row['id'] . '">
                                            <button>Verifikasi Pembayaran</button>
                                        </a>';
                                } elseif ($status == "Pendaftaran Selesai") {
                                    echo '<button disabled>Selesai</button>';
                                } elseif ($status == "Gagal Daftar") {
                                    echo '<button disabled>Data Akan Dihapus</button>';
                                } else {
                                    echo '<button disabled>No Action</button>';
                                }?>
                            </td>
                        </tr>
                    <?php } ?>
                    <?php
                    if (mysqli_num_rows($result) == 0) { ?>
                    <tr>
                        <td colspan="5">Tidak ada siswa yang mendaftar</td>
                    </tr>
                    <?php } ?>
                </table>
            </div>
        </div>
        <br>
        <!-- Previous button -->
        <?php if ($page > 1) { ?>
            <a href="?page=<?php echo $page - 1; ?>&status=<?php echo $status_filter; ?>&sort=<?php echo $sort; ?>&search=<?php echo $search; ?>">
                <button>Sebelumnya</button>
            </a>
        <?php } ?>
        <!-- Next button -->
        <?php if ($page < $total_pages) { ?>
            <a href="?page=<?php echo $page + 1; ?>&status=<?php echo $status_filter; ?>&sort=<?php echo $sort; ?>&search=<?php echo $search; ?>">
                <button>Berikutnya</button>
            </a>
        <?php } ?>
        <br>
    </body>
</html>