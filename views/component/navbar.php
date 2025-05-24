<?php
$page = $_SERVER['REQUEST_URI'];
$navItems = [
    'Le menu'                => './index',
    'Commander'              => './commande',
    'Afficher les commandes' => './display-orders',
    'Utilisateurs'           => './users',
];
?>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
        <a class="navbar-brand" href="./index">
            <img src="<?= PREFIX ?>/assets/IMG/favicon-32x32.png" alt="" width="32" height="32" class="d-inline-block align-text-top">
            Nourriture Terrestre
          </a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav">
            <?php foreach ($navItems as $name => $path) :?>
                <li class="nav-item">
                    <a 
                        class="nav-link<?= ((str_contains($page, substr($path, 2))) || ($path === './index.php' && strlen($page) === 22) ? " active" : "") ?>"
                        href="<?= $path ?>"
                    ><?= $name ?></a>
                </li>
            <?php endforeach; ?>
        </ul>
      </div>
    </div>
  </nav>