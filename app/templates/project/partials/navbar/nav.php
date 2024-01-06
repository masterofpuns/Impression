<ul class="nav navbar-nav<?= isset($menu->cssClass) ? ' ' . $menu->cssClass : '' ?>"<?= !empty($id) ? " id=\"" . $id . "\"" : '' ?>>
    <?
    foreach ($menu->children as $menuItemId) {
        $menuItem = $app->menuService->menuItems[$menuItemId];
        $app->renderPartial(
            'menu/menu-item',
            [
                'app' => $app,
                'menu' => $menu,
                'menuItemId' => $menuItemId,
                'menuItem' => $menuItem,
            ]
        );
    }
    ?>

</ul>