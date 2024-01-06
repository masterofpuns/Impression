<div class='container-fluid ps-4 pe-5 mt-4'>
    <div class='d-flex justify-content-start flex-row  mb-4'>
        <div class='col d-flex flex-row gap-5 align-items-center'>
            <?php $app->renderPartial(
                'page-header/page-header',
                ['heading' => isset($heading) ? $heading : $app->doc->heading]
            ); ?>
        </div>
    </div>
    <div class='container-content container-forms w-50'>
        <form action="<?= $app->getDocByName('relation-contactperson-save')->getUrl([$parentRelation->id]) ?>" method='post' data-hook='form-contact_person_relation' enctype='multipart/form-data'>
            <div class="section relation_form-components" data-section="contact-person">
                <?php
                $app->renderPartial(
                    'relation/form/components/phonenumber',
                    [
                        'placeholder' => true
                    ]
                );
                $app->renderPartial(
                    'relation/form/components/email-address',
                    [
                        'placeholder' => true
                    ]
                );
                $app->renderPartial(
                    'relation/form/components/contact-person',
                    [
                        'contactPersonRelation' => $contactPersonRelation ?? null,
                        'contactPersonIdx' => 0
                    ]
                );
                ?>
            </div>
            <div class='section well d-flex justify-content-between'>
                <div class='d-flex gap-2'>
                    <a
                        href="<?= !empty($contactPersonRelation) && !empty($contactPersonRelation->id) ? $contactPersonRelation->getUrlView() : \app\m::app()->getDocByName('relation-overview')->url; ?>"
                        class='btn grey-hover btn-outline-secondary-600'
                        >
                        Annuleren
                    </a>
                    <button type='submit' class='btn btn-success'>
                        <i class='bi bi-check-circle me-2'></i>
                        Contactpersoon <?= !empty($contactPersonRelation->id) ? 'bewerken' : 'toevoegen'; ?>
                    </button>
                </div>
                <div class="d-flex justify-content-end"></div>
            </div>
        </form>
    </div>
</div>