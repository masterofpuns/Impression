<div class='form-group mb-2' data-hook="form-phone_number<?= isset($placeholder) && $placeholder ? '-placeholder' : ''; ?>">
    <div class="row">
        <div class="col-12">
            <div class='input-group mb-2'>
                <input type='text' name='<?= !empty($prefix) ? $prefix : 'PREFIX' ?>[PhoneNumbers][<?= isset($phoneNumberIdx) ? $phoneNumberIdx : 'PN_IDX'; ?>][number]' class='form-control' value="<?= !empty($phoneNumber) && !empty($phoneNumber->number) ? $phoneNumber->number : ''; ?>">
                <button class='btn btn-outline-danger' type='button' data-hook="form-delete_phone_number"><i class="bi bi-trash"></i></button>
            </div>
        </div>
    </div>
</div>