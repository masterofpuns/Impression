<?php
// dertermine classes
$primaryClasses = [];
$secondaryClasses = [];
$tertiaryClasses = [];

// determine submeny variables
$hasSubMenus = is_null($menuItem->submenus) || count($menuItem->submenus) == 0 ? false : true;

// determine primary classes for first element
if (!empty($menuItem->cssClassPrimary)) {
    $primaryClasses[] = $menuItem->cssClassPrimary;
}
if ($menuItem->active && !in_array('active', $primaryClasses)) {
    $primaryClasses[] = 'active';
}
// check if has active submenu items
$subMenuItems = [];
$hasActiveSubMenuItem = false;
if ($hasSubMenus) {
    foreach ($menuItem->submenus as $submenuId) {
        $submenu = $app->menuService->menus[$submenuId];
        foreach ($submenu->children as $submenuItemId) {
            $subMenuItem = $app->menuService->menuItems[$submenuItemId];
            $subMenuItems[$submenuItemId] = $subMenuItem;

            if (!$hasActiveSubMenuItem && $subMenuItem->active) {
                $hasActiveSubMenuItem = true;
            }
        }
    }
}
if ($hasActiveSubMenuItem) {
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
    <a href="<?= ($menuItem->path) ? $menuItem->path : '#' ?>" class="<?= !empty($secondaryClasses) ? implode(' ', $secondaryClasses) : '' ?>">
        <?php if (!empty($menuItem->icon))
        {
            ?>
            <i class='fs-5 icon-padding bi bi-<?= $menuItem->icon ?> text-primary'></i>
            <?php
        }
        ?>
        <span class='<?= !empty($tertiaryClasses) ? implode(' ', $tertiaryClasses) : ''; ?>'><?= t($menuItem->description); ?></span>
    </a>

    <?php
    if ($hasSubMenus) {
        ?>
        <ul class='nav flex-column show mb-3'>
            <?php
            foreach ($subMenuItems as $subMenuItemId => $subMenuItem) {
                $app->renderPartial(
                    'menu/menu-item',
                    [
                        'app' => $app,
                        'menu' => $menu,
                        'menuItemId' => $subMenuItemId,
                        'menuItem' => $subMenuItem
                    ]
                );
            }
            ?>
        </ul>
        <?php
    }
    ?>
</li>