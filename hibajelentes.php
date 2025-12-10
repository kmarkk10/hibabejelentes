<?php

if (!isset($_SESSION['loggedin'])) {
    header("Location: login_tanar.php");
    exit;
}


if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $conn = new mysqli("localhost", "root", "", "hibajegyek");

    if ($conn->connect_error) {
        die("Hiba az adatbázishoz kapcsolódáskor: " . $conn->connect_error);
    }

    $tanar = $_COOKIE["username"];

    $cim = $_POST["hiba_cim"];
    $leiras = $_POST["leiras"];

    $sql = $conn->prepare("
        INSERT INTO hibak (tanar_nev, hiba_cim, hiba, datum, statusz)
        VALUES (?, ?, ?, NOW(), 'Kezelésre vár')
    ");

    $sql->bind_param("sss", $tanar, $cim, $leiras);

    if ($sql->execute()) {
        header("Location: hibajelentes.php");
        exit;
    } else {
        die("Hiba történt a mentés során: " . $conn->error);
    }

    $conn->close();
}

?>


<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <title>Hiba bejelentése</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
</head>
<body class="bg-light">

<div class="p-3 mb-4 bg-success d-flex justify-content-between align-items-center">
        <h1 class="text-white m-0">Hibajegyek</h1>

        <div class="dropdown">
            <button class="btn btn-success dropdown-toggle d-flex align-items-center" 
                    type="button" 
                    data-bs-toggle="dropdown" 
                    aria-expanded="false"
                    style="background: none; border: none;">

                <h3 class="text-white m-0 me-2">
                    <?php echo $_COOKIE["username"]; ?>
                </h3>

                <i class="bi bi-person-circle fs-2 text-white"></i>
            </button>

            <ul class="dropdown-menu dropdown-menu-end">
                <li><a class="dropdown-item text-danger" href="logout.php">Kijelentkezés</a></li>
            </ul>
        </div>
    </div>

<div class="container mt-5">



    <h1 class="text-center mb-4">Hiba bejelentése</h1>

    <?php if (isset($_GET["success"])): ?>
        <div class="alert alert-success text-center">
            A hibabejelentés sikeresen elmentve!
        </div>
    <?php endif; ?>

    <form action="hibajelentes.php" method="POST" class="bg-white p-4 rounded shadow w-50 m-auto">

        <label class="form-label mt-3">Tanár neve:</label>
        <input type="text" class="form-control" value="<?php echo $_COOKIE['username']; ?>" disabled>

        <label class="form-label mt-3">Hiba címe:</label>
        <input type="text" name="hiba_cim" class="form-control" required>

        <label class="form-label mt-3">Leírás:</label>
        <textarea name="leiras" class="form-control" rows="4" required></textarea>

        <button class="btn btn-primary mt-4 w-100">Bejelentés küldése</button>
    </form>

</div>
<script 
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js">
  </script>
</body>
</html>
