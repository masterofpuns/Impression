<?php
use app\h;
?>

<ul class="nav nav-tabs">
    <li class="nav-item">
        <a class="nav-link <?= $app->doc->name == 'fund-view' ? 'active' : ''; ?>" href="<?= $app->getDocByName('fund-view')->getUrl([$fund->id]); ?>"><?= t("DATA"); ?></a>
    </li>
    <li class="nav-item">
        <a class="nav-link <?= $app->doc->name == 'registrations-fund-overview' ? 'active' : ''; ?>" href="<?= $app->getDocByName('registrations-fund-overview')->getUrl([$fund->id]); ?>"><?= t("REGISTRATION_LIST"); ?></a>
    </li>
    <li class="nav-item">
        <a class="nav-link <?= $app->doc->name == 'participations-fund-overview' ? 'active' : ''; ?>" href="<?= $app->getDocByName('participations-fund-overview')->getUrl([$fund->id]); ?>"><?= t("PARTICIPATION_LIST"); ?></a>
    </li>
</ul>