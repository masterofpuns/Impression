<div class="data-flex-container m-0">
    <div>
        <?= $address->street . ' ' . $address->number ?><?= $address->numberSuffix = $address->numberSuffix ?? ''; ?>
    </div>
    <div>
        <?= $address->postalCode . ' ' . $address->city ?>
    </div>
    <div>
        <?= $address->getCountry()->name ?>
    </div>
</div>