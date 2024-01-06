<?php
use \app\h;
?>
<!DOCTYPE html>
<html lang="<?= !is_null($app->user) ? $app->user->language : $app->language ?>">

    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="initial-scale=1.0, maximum-scale=1.0, user-scalable=no, width=device-width">
        <title><?= t($app->doc->title); ?></title>
        <link rel="stylesheet" href="<?= $app->doc->environment->template->getStylesheetUrl() ?>">
        <?
        $app->renderJavascriptHook('tpl-primary');
        $app->renderJavascriptHook('doc-primary');
        ?>
        <!--  @todo backend: add dataTables.bootstrap.css and dataTables.bootstrap.js to repository in preferred folder (preferably not IN Bootstrap vendor folder) -->

        <link rel="apple-touch-icon" sizes="180x180" href="href="/app/templates/<?= $app->doc->environment->template->folder ?>/assets/favicon/apple-touch-icon.png">
        <link rel="icon" type="image/png" sizes="32x32" href="/app/templates/<?= $app->doc->environment->template->folder ?>/assets/favicon/favicon-32x32.png">
        <link rel="icon" type="image/png" sizes="16x16" href="/app/templates/<?= $app->doc->environment->template->folder ?>/assets/favicon/favicon-16x16.png">
        <link rel="manifest" href="/app/templates/<?= $app->doc->environment->template->folder ?>/assets/favicon/site.webmanifest">
        <link rel="mask-icon" href="/app/templates/<?= $app->doc->environment->template->folder ?>/assets/favicon/safari-pinned-tab.svg" color="#4c9f70">
        <meta name="msapplication-TileColor" content="#00a300">
        <meta name="theme-color" content="#ffffff">

    </head>

    <body<?= !empty($app->doc->cssId) ? ' id="' . $app->doc->cssId . '"': '' ?>>


    <?php
    if (!empty($app->user))
    {
        $app->renderPartial('navbar/top');
    }
    ?>

    <main class="<?= $app->doc->name !== 'app-login' ? 'container-fluid main-content' : ''; ?> <?= $app->doc->class; ?>">
        <div class="row flex-nowrap">
            <?php
            if (!empty($app->user))
            {
                $app->renderPartial('navbar/side');
            }
            ?>
            <div class="col background">




