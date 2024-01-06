<?php
    use app\h;
?>

<ul class="nav nav-tabs">
    <li class="nav-item">
        <a class="nav-link <?= $app->doc->name == 'funds-overview' ? 'active' : ''; ?>" href="<?= $app->getDocByName('funds-overview')->url; ?>"><?= t("OVERVIEW"); ?></a>
    </li>
    <li class="nav-item">
        <a class="nav-link <?= $app->doc->name == 'curators-overview' ? 'active' : ''; ?>" href="<?= $app->getDocByName('curators-overview')->url; ?>"><?= t("CURATORS"); ?></a>
    </li>
    <li class="nav-item">
        <a class="nav-link <?= $app->doc->name == 'foundations-overview' ? 'active' : ''; ?>" href="<?= $app->getDocByName('foundations-overview')->url; ?>"><?= t("FOUNDATIONS"); ?></a>
    </li>
</ul>