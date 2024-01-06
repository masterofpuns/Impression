<div class="form-group" data-hook="form-email_address<?= isset($placeholder) && $placeholder ? '-placeholder' : ''; ?>">
    <div class="row">
        <div class="input-group col-12 mb-2">
            <input type="text" name="<?= !empty($prefix) ? $prefix : 'PREFIX' ?>[EmailAddresses][<?= isset($emailAddressIdx) ? $emailAddressIdx : 'EA_IDX'; ?>][address]" class="form-control" value="<?= !empty($emailAddress) && !empty($emailAddress->address) ? $emailAddress->address : ''; ?>">
            <button class='btn btn-outline-danger' type='button' data-hook='form-delete_email_address'><i class='bi bi-trash'></i></button>
        </div>
    </div>
</div>