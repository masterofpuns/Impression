<?php
// dertermine classes
$primaryClasses = [];
$secondaryClasses = [];
$tertiaryClasses = [];

// determine submeny variables
$hasSubMenus = is_null($menuItem->submenus) || count($menuItem->submenus) == 0 ? false : true;
$subMenuType = $hasSubMenus && !empty($menu->submenuType) ? $menu->submenuType : '';
$subMenuExpanded = $hasSubMenus && !empty($menu->submenuType) && $menu->submenuExpand ? true : false;

// determine primary classes for first element
if (!empty($menuItem->cssClassPrimary)) {
    $primaryClasses[] = $menuItem->cssClassPrimary;
}
if ($menuItem->active && !in_array('active', $primaryClasses)) {
    $primaryClasses[] = 'active';
}
if ($menuItem->disabled) {
    $primaryClasses[] = 'disabled';
}

// determine secondary classes for secondary element
if (!empty($menuItem->cssClassSecondary)) {
    $secondaryClasses[] = $menuItem->cssClassSecondary;
}

// determine tertiary classes for tertiary element
if (!empty($menuItem->cssClassTertiary)) {
    $tertiaryClasses[] = $menuItem->cssClassTertiary;
}
?>
<li class="<?= !empty($primaryClasses) ? implode(' ', $primaryClasses) : ''; ?>">
    <span class='input-group'>
    <?php
    if ($hasSubMenus) {
        ?>
        <a href="<?= ($menuItem->path) ? $menuItem->path : '#' ?>" class="<?= !empty($secondaryClasses) ? implode(' ', $secondaryClasses) : '' ?>">
            <?php if (!empty($menuItem->icon))
            {
                ?>
                <i class='fs-4 bi bi-<?= $menuItem->icon ?> text-primary'></i>
                <?php
            }
            ?>
            <span class='<?= !empty($tertiaryClasses) ? implode(' ', $tertiaryClasses) : ''; ?>'><?= t($menuItem->description); ?></span>
        </a>
        <span class="<?= ($subMenuExpanded ? ' active' : ''); ?>" data-bs-target="#subMenu<?= $menuItemId ?>" data-bs-toggle='<?= $subMenuType; ?>' aria-controls="subMenu<?= $menuItemId ?>">
            <i class='bi bi-caret-down text-primary'></i>
        </span>
        <?php
    } else {
        ?>
        <a href="<?= ($menuItem->path) ? $menuItem->path : '#' ?>" class="<?= !empty($secondaryClasses) ? implode(' ', $secondaryClasses) : '' ?>">
            <?php if (!empty($menuItem->icon))
            {
                ?>
                <i class='fs-4 bi bi-<?= $menuItem->icon ?> text-primary'></i>
                <?php
            }
            ?>
            <span class='<?= !empty($tertiaryClasses) ? implode(' ', $tertiaryClasses) : ''; ?>'><?= t($menuItem->description); ?></span>
        </a>
        <?php
    }
    ?>
    </span>

    <?php
    if ($hasSubMenus) {
    ?>
    <ul class='<?= $subMenuType; ?> nav flex-column ms-1 <?= ($subMenuExpanded ? ' show' : ''); ?>' id='subMenu<?= $menuItemId ?>'>
        <?php
        foreach ($menuItem->submenus as $submenuId) {
            $submenu = $app->menuService->menus[$submenuId];
            foreach ($submenu->children as $submenuItemId) {
                $submenuItem = $app->menuService->menuItems[$submenuItemId];
                $app->renderPartial(
                    'menu/menu-item',
                    [
                        'app' => $app,
                        'menu' => $menu,
                        'menuItemId' => $submenuItemId,
                        'menuItem' => $submenuItem
                    ]
                );
            }
        }
        ?>
    </ul>
    <?php
    }
    ?>
</li>