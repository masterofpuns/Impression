<?php

use \app\h;

?>

<?php
if (count($app->breadcrumb)) { ?>
    <ol class="breadcrumb">
        <?php
        foreach ($app->breadcrumb as $breadcrumb) { ?>
            <?php
            $activeElement = $breadcrumb->activeElement; ?>
            <li class="breadcrumb-text"> <?php
                if (!$activeElement->disabled) { ?> <a href="<?= $activeElement->uri ?>"><?php
                    } ?><?= t($activeElement->description) ?><?php
                    if (!$activeElement->disabled) { ?> </a><span class="breadcrumb-linker"> /&nbsp</span><?php
            } ?></li>
        <?php
        } ?>
    </ol>
<?php
} ?>