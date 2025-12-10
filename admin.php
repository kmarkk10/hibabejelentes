<?php

if (!isset($_COOKIE["loggedin"])
    || !isset($_COOKIE["pozicio"]) || $_COOKIE["pozicio"] == "admin") {
    header("Location: login_admin.php");
    exit;
}

$conn = new mysqli("localhost", "root", "", "hibajegyek");
if ($conn->connect_error) { die("Connection failed: " . $conn->connect_error); }
$result = $conn->query("SELECT * FROM hibak");
?>
<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <title>Hibajegyek</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

</head>
<body class="bg-white">
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

</div>


    <div class="container">

        <h1 class="fs-1 text-center">Hibák</h1>
    <?php while($row = $result->fetch_assoc()): ?>

        <?php
        $status = $row["statusz"];
        $color = "secondary";

        if ($status === "Kezelésre vár") $color = "warning";
        if ($status === "Folyamatban") $color = "primary";
        if ($status === "Lezárva") $color = "danger";
        ?>

        <div class="bg-light rounded border w-50 d-block m-auto border-dark p-3 mb-3">
            <h1 class="fs-4"><?php echo $row['hiba_kod']; ?> | <?php echo $row["hiba_cim"]; ?></h1>
            <h1 class="fs-6"><?php echo $row["tanar_nev"]; ?></h1>
            <hr>
            <h1 class="fs-5 text-muted"><em><?php echo $row["datum"]; ?></em></h1>
            <hr>

            <a href="hiba.php?id=<?php echo $row['hiba_kod']; ?>" 
            class="btn btn-primary d-block w-25">
            Megnyitás
            </a>

            <span class="badge bg-<?php echo $color; ?> text-light fs-6 mt-2">
                <?php echo $status; ?>
            </span>
        </div>

    <?php endwhile; ?>

    </div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php $conn->close(); ?>
