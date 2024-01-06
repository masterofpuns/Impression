<ul class="nav nav-tabs">
    <li class="nav-item">
        <a class="nav-link <?= $app->doc->name == 'relation-overview' ? 'active' : ''; ?>" href="<?= $app->getDocByName('relation-overview')->url; ?>"><?= t("OVERVIEW"); ?></a>
    </li>
    <li class="nav-item">
        <a class="nav-link <?= $app->doc->name == 'relation-category-overview' ? 'active' : ''; ?>" href="<?= $app->getDocByName('relation-category-overview')->url; ?>"><?= t("CATEGORIES"); ?></a>
    </li>
</ul>