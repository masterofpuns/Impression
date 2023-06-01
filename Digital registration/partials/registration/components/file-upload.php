<div class="registration-file_upload-container">
	<? if (!empty($fileParams)): ?>
        <div class="formfield form-control">
            <?= $fileParams['filename']; ?>
            <i data-hook="registration-upload-file_delete-wrapper" class="glyphicon glyphicon-trash"></i>
        </div>
	<? endif; ?>
    
    <div class="relative <?= !empty($fileParams) ? 'hide' : ''; ?>" data-hook="registration-file_upload-input-wrapper">
        <div class="formfield form-control"></div>
        <div data-hook="custom-errormsg">
            <input class="hide" type="file" name="<?= $name; ?>" data-hook="registration-upload-file_input" <?= !isset($required) ? 'required' : (isset($required) && $required ? 'required' : ''); ?> />
            <div class="customErrorContainer"></div>
        </div>
        <a class="btn btn-secondary upload-file" data-hook="registration-upload-file_trigger">
            <img src="/app/templates/front/assets/images/icons/upload.svg"><span class="btn-text">Bestand uploaden</span>
        </a>
    </div>
</div>