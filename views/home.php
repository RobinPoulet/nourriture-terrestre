<?php
/** @var \App\Model\Menu $menu */
/** @var bool $canDisplayForm */
?>
<?php if ($menu->is_open) : ?>
    <div class="container my-5">
        <h2 class="text-center display-5 fw-bold mb-5" style="font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;">
            🍽️ Menu du <span class="text-primary"><?= htmlspecialchars($menu->creation_date) ?></span> 🥗
        </h2>

        <div class="row align-items-center justify-content-center g-4">
            <!-- Liste des plats -->
            <div class="col-lg-6">
                <div class="d-flex flex-column gap-3">
                    <?php foreach ($menu->dishes() as $dish) : ?>
                        <div class="card shadow-sm rounded border-0 hover-card">
                            <div class="card-body d-flex align-items-center">
                                <span class="me-3 fs-3 text-primary">🍲</span>
                                <h5 class="card-title mb-0"><?= htmlspecialchars($dish->name) ?></h5>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Image du menu -->
            <div class="col-lg-5 text-center">
                <figure class="figure">
                    <img
                            src="<?= PREFIX ?>/assets/IMG/<?= htmlspecialchars($menu->img_src) ?>"
                            alt="Photo du menu"
                            class="figure-img img-fluid rounded shadow-sm"
                            style="max-height: 320px; object-fit: cover;"
                    >
                    <?php if (!empty($menu->figcaption)) : ?>
                        <figcaption class="figure-caption text-muted mt-3" style="font-style: italic;">
                            <?= htmlspecialchars($menu->figcaption) ?>
                        </figcaption>
                    <?php endif; ?>
                </figure>
            </div>
        </div>
    </div>

    <style>
        .hover-card {
            transition: transform 0.2s ease, box-shadow 0.2s ease;
            cursor: default;
            background-color: white;
        }
        .hover-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.12);
        }
    </style>

<?php else: ?>
    <?php if ($canDisplayForm) : ?>
        <div class="container mt-5">
            <!-- Image du menu -->
            <div class="col-lg-5 text-center">
                <figure class="figure">
                    <img
                            src="<?= PREFIX ?>/assets/IMG/<?= htmlspecialchars($menu->img_src) ?>"
                            alt="Photo du menu"
                            class="figure-img img-fluid rounded shadow-sm"
                            style="max-height: 320px; object-fit: cover;"
                    >
                    <?php if (!empty($menu->figcaption)) : ?>
                        <figcaption class="figure-caption text-muted mt-3" style="font-style: italic;">
                            <?= htmlspecialchars($menu->figcaption) ?>
                        </figcaption>
                    <?php endif; ?>
                </figure>
            </div>
        </div>
    <?php else: ?>
        <div class="alert alert-info text-center mt-4">Pas de nourriture terrestre aujourd'hui</div>
    <?php endif; ?>
<?php endif; ?>