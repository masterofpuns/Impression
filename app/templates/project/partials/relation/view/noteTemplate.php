<?php
/*
 * Aanpassingen die hieronder gedaan worden moeten mogelijk ook toegepast worden in de note.php
 * met uitzondering van de updated note-block-updated. Deze wordt in modal-relation-note.js toegevoegd
 */
?>
<template id="note-block-item-template">
    <div class="note-block-item justify-content-between d-flex flex-row">
        <div class="d-flex flex-column">
           <strong><div class="note-block-item-created mb-2"></div></strong>
            <div class="note-block-item-description" data-hook=""></div>
        </div>
        <div class="flex-nowrap d-flex align-self-start">
            <button type="button" id="modal-relation-note-edit" data-bs-toggle="modal" data-bs-target="#modal-note-add-edit"
                    class="btn btn-outline-secondary-700 border-0 blue-hover note-block-item-btn-edit" data-confirm-url="<?= $add_note_url ?>" data-relation-id="<?= $relation->id ?>" data-note-id="">
                <i class="bi bi-pencil"></i>
            </button>
            <button type="button" id="modal-relation-note-delete" data-bs-toggle="modal" data-bs-target="#modal-note-delete"
                    class="btn btn-outline-secondary-700 border-0 blue-hover note-block-item-btn-delete" data-confirm-url="<?= $delete_note_url ?>" data-relation-id="<?= $relation->id ?>" data-note-id="">
                <i class="bi bi-trash3"></i>
            </button>
        </div>
    </div>
</template>

<template id="note-block-empty-template">
    <p id="no-notes-provided"><?=t('NO_NOTES_PROVIDED')?></p>
</template>