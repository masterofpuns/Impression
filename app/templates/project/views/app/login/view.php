<?php use \app\h; ?>
<div class="bg-image d-flex align-items-center justify-content-center">
    <div class="container">
        <div class="row justify-content-center align-items-center">
            <div class="col-sm-8 col-md-6 col-lg-4 bg-secondary p-5 login-form">

                <?php
                $app->renderPartial(
                    'panel/start',
                    array(
                        'class' => 'panel-primary',
                        'heading' => '<img src="/app/templates/front/assets/img/perree-logo-inlog.svg">'
                    )
                );
                ?>

                <?php
                $app->renderPartial(
                    'form/form',
                    array('form' => $loginForm)
                );
                ?>

                <?php $app->renderPartial('panel/end'); ?>

            </div>
        </div>
    </div>
</div>