<?php
$page = $_SERVER["REQUEST_URI"];
?>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
        <a class="navbar-brand" href="./index.php">
            <img src="./IMG/favicon-32x32.png" alt="" width="32" height="32" class="d-inline-block align-text-top">
            Nourriture Terrestre
          </a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav">
            <li class="nav-item">
                <?php
                echo "<a class=\"nav-link".(str_contains($page, "index") ? " active\"  aria-current=\"page\"" : "\"")." href=\"./index.php\">Le menu</a>";
                ?>
            </li>
            <li class="nav-item">
                <?php
                echo "<a class=\"nav-link".(str_contains($page, "commande") ? " active\"  aria-current=\"page\"" : "\"")." href=\"./commande.php\">Commander</a>";
                ?>
            </li>
            <li class="nav-item">
                <?php
                echo "<a class=\"nav-link".(str_contains($page, "display-orders") ? " active\"  aria-current=\"page\"" : "\"")." href=\"./display-orders.php\">Voir les commandes</a>";
                ?>
            </li>
            <li class="nav-item">
                <?php
                echo "<a class=\"nav-link".(str_contains($page, "users") ? " active\"  aria-current=\"page\"" : "\"")." href=\"./users.php\">Utilisateurs</a>";
                ?>
            </li>
        </ul>
      </div>
    </div>
  </nav>