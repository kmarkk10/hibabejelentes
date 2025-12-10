<?php
if (!isset($_SESSION['loggedin']) || $_SESSION['pozicio'] !== 'admin') {
    header("Location: login_admin.php");
    exit;
}

$conn = new mysqli("localhost", "root", "", "hibajegyek");

if ($conn->connect_error) {
    die("Kapcsolódási hiba: " . $conn->connect_error);
}

if (!isset($_GET["id"])) {
    die("Hiányzik az ID!");
}

$id = intval($_GET["id"]);

$stmt = $conn->prepare("DELETE FROM hibak WHERE hiba_kod = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$stmt->close();

$conn->close();

header("Location: admin.php?deleted=1");
exit;
?>
