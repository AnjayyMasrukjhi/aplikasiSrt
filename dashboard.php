<?php
session_start();

// Periksa apakah pengguna sudah login
if (!isset($_SESSION['user_id'])) {
  header('Location: login.php');
  exit();
}

// Periksa peran pengguna
$role = $_SESSION['role'];

// Koneksi ke database
$db = mysqli_connect('localhost', 'username', 'password', 'db_akses');

// Dapatkan daftar izin berdasarkan peran pengguna
$query = "SELECT permission FROM permissions WHERE role='$role'";
$result = mysqli_query($db, $query);
$permissions = mysqli_fetch_all($result, MYSQLI_ASSOC);
$allowedPermissions = array_column($permissions, 'permission');

?>
<?php
// Fungsi untuk memeriksa hak akses pengguna
function checkAccess($userRole, $allowedRoles)
{
    // Cek apakah peran pengguna terdapat dalam daftar peran yang diizinkan
    if (in_array($userRole, $allowedRoles)) {
        return true;
    } else {
        return false;
    }
}

// Contoh penggunaan
$userRole = "admin"; // Peran pengguna
$allowedRoles = array("admin", "editor"); // Daftar peran yang diizinkan

if (checkAccess($userRole, $allowedRoles)) {
    // Menampilkan tombol jika pengguna memiliki hak akses
    echo '<button>Edit Artikel</button>';
}
?>
<!DOCTYPE html>
<html>
<head>
  <title>Dashboard</title>
</head>
<body>
  <h2>Selamat datang, <?php echo $_SESSION['username']; ?></h2>

  <?php if (in_array('view_button', $allowedPermissions)) { ?>
    <button>View Button</button>
  <?php } ?>

  <?php if (in_array('edit_button', $allowedPermissions)) { ?>
    <button>Edit Button</button>
  <?php } ?>

  <?php if (in_array('delete_button', $allowedPermissions)) { ?>
    <button>Delete Button</button>
  <?php } ?>

  <br><br>
  <a href="logout.php">Logout</a>
</body>
</html>
