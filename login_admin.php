<?php
$conn = new mysqli("localhost", "root", "", "hibajegyek");

if ($conn->connect_error) {
    die("Kapcsolódási hiba: " . $conn->connect_error);
}


if (!isset($_COOKIE["loggedin"])
    || !isset($_COOKIE["pozicio"]) || $_COOKIE["pozicio"] == "admin") {
    header("Location: login_admin.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $user = $_POST["username"] ?? "";
    $pass = $_POST["password"] ?? "";

    $stmt = $conn->prepare("SELECT jelszo, pozicio FROM admin WHERE felhasznalo_nev = ?");
    $stmt->bind_param("s", $user);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows === 1) {
        $stmt->bind_result($db_pass, $db_pozicio);
        $stmt->fetch();

        if ($pass === $db_pass) {
            setcookie("loggedin", "1", time() + 86400, "/");
            setcookie("username", $user, time() + 86400, "/");
            setcookie("pozicio", $db_pozicio, time() + 86400, "/");

            header("Location: admin.php");
            exit;
        } else {
            $error = "Hibás jelszó.";
        }
    } else {
        $error = "Nincs ilyen felhasználó.";
    }
}
?>
<!doctype html>
<html lang="hu">
<head>
  <meta charset="utf-8">
  <title>Bejelentkezés</title>
  <link 
    href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" 
    rel="stylesheet">
</head>

<body class="bg-light">

  <div class="container-fluid bg-success d-flex align-items-center" style="height: 80px;">
    <a href="index.html" class="btn btn-dark ms-3">Vissza</a>
  </div>

  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-4">

        <h1 class="text-center mt-5 mb-4">Bejelentkezés</h1>
        <h3 class="text-center mb-5">ADMIN</h3>

        <div class="card p-4 shadow-sm">

          <?php if (isset($error)): ?>
            <div class="alert alert-danger text-center">
              <?= $error ?>
            </div>
          <?php endif; ?>

          <form method="POST" action="login_admin.php">

            <div class="mb-3">
              <label for="username" class="form-label">Felhasználónév</label>
              <input type="text" class="form-control" id="username" name="username" required>
            </div>

            <div class="mb-3">
              <label for="password" class="form-label">Jelszó</label>
              <input type="password" class="form-control" id="password" name="password" required>
            </div>

            <button type="submit" class="btn btn-success w-100">
              Bejelentkezés
            </button>

          </form>

        </div>

      </div>
    </div>
  </div>

  <script 
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js">
  </script>

</body>
</html>
