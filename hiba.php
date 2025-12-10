<?php

if (!isset($_SESSION['loggedin']) || $_SESSION['pozicio'] !== 'admin') {
    header("Location: login_admin.php");
    exit;
}

$conn = new mysqli("localhost", "root", "", "hibajegyek");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["statusz"])) {
    if (!isset($_GET["id"])) {
        die("Nincs megadva hibakód!");
    }

    $id = $_GET["id"];
    $newStatus = $_POST["statusz"];

    $update = $conn->prepare("UPDATE hibak SET statusz = ? WHERE hiba_kod = ?");
    $update->bind_param("si", $newStatus, $id);
    $update->execute();
    $update->close();

    header("Location: hiba.php?id=" . $id);
    exit;
}

if (!isset($_GET["id"])) {
    die("Nincs megadva hibakód!");
}

$id = $_GET["id"];

$stmt = $conn->prepare("SELECT * FROM hibak WHERE hiba_kod = ?");
$stmt->bind_param("i", $id);
$stmt->execute();

$result = $stmt->get_result();
$hiba = $result->fetch_assoc();

if (!$hiba) {
    die("Nem található ilyen hibajegy!");
}

$stmt->close();
$conn->close();
?>
<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <title>Hibajegy #<?php echo $id; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-4">

    <div class="card border-dark">
        <div class="card-header bg-primary text-white">
            <h3 class="m-0"><?php echo $hiba["hiba_cim"]; ?></h3>
        </div>

        <div class="card-body">
            <p><strong>Hibaleírás:</strong><br><?php echo nl2br($hiba["hiba"]); ?></p>
            <p><strong>Dátum:</strong> <?php echo $hiba["datum"]; ?></p>
            <p><strong>Státusz:</strong> <?php echo $hiba["statusz"]; ?></p>
        </div>

        <div class="card-footer d-flex gap-2 align-items-center">

            <a href="admin.php" class="btn btn-secondary">Vissza</a>

            <form method="POST">
                <div class="dropdown">
                    <button class="btn btn-primary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                        Státusz módosítása
                    </button>
                    <ul class="dropdown-menu">

                        <li>
                            <button class="dropdown-item" type="submit" name="statusz" value="Kezelésre vár">
                                Kezelésre vár
                            </button>
                        </li>

                        <li>
                            <button class="dropdown-item" type="submit" name="statusz" value="Folyamatban">
                                Folyamatban
                            </button>
                        </li>

                        <li>
                            <button class="dropdown-item text-danger" type="submit" name="statusz" value="Lezárva">
                                Lezárva
                            </button>
                        </li>

                    </ul>
                </div>
            </form>
                   <?php
                    $lathato = "hidden";
                    if ($hiba["statusz"] === "Lezárva") {
                        $lathato = "visible";
                    }
                    ?>

                    <a href="torol.php?id=<?= $hiba['hiba_kod'] ?>" 
                    class="btn btn-danger float-right"
                    style="visibility: <?= htmlspecialchars($lathato) ?>;">
                    Törlés
                    </a>


        </div>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
