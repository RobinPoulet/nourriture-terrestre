<?php
/** @var string $content */
session_start();
$tabFlashMessage = $_SESSION["tab_flash_message"] ?? null;
unset($_SESSION["tab_flash_message"]);
?>
<!DOCTYPE html>
<html lang="fr">
<?php require(__DIR__ . "/component/head.php"); ?>
<body>
<?php require(__DIR__ . "/component/navbar.php"); ?>

<?php if (!empty($tabFlashMessage["errors"])): ?>
    <?php foreach ($tabFlashMessage["errors"] as $error): ?>
        <div class="alert alert-danger mt-3 text-center ms-2 me-2"><?= htmlspecialchars($error) ?></div>
    <?php endforeach; ?>
<?php endif; ?>

<?php if (isset($tabFlashMessage["success"])): ?>
    <div class="alert alert-success mt-3 text-center ms-2 me-2"><?= htmlspecialchars($tabFlashMessage["success"]) ?></div>
<?php endif; ?>


<?= $content ?>

</body>
</html>

