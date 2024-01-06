<?php
/*
 * Aanpassingen die hieronder gedaan worden moeten ook toegepast worden in de template in noteTemplate.php
 */
?>
<div class="note-block-item justify-content-between d-flex flex-row" data-hook="note-block-item-<?= $note->id; ?>">
    <div class="d-flex flex-column">
        <strong><div class="note-block-item-created mb-2"><?= 'Toegevoegd op '.date('d-m-Y', strtotime($note->dateTimeCreated)).' door Gebruiker '.$note->getUser(); ?></div></strong>
        <div class="note-block-item-description" data-hook="note-description-<?= $note->id; ?>"><?= $note->description; ?></div>
        <?php if (!empty($note->dateTimeUpdated) && !empty($note->updatedById)) { ?>
            <div class="note-block-item-updated"><?= 'Bewerkt op '.date('d-m-Y', strtotime($note->dateTimeUpdated)).' door Gebruiker '.$note->getUserUpdated(); ?></div>
        <?php } ?>
    </div>
    <div class="flex-nowrap d-flex align-self-start">
        <button type="button" id="modal-relation-note-edit" data-bs-toggle="modal" data-bs-target="#modal-note-add-edit"
                class="btn blue-hover btn-outline-secondary-700 border-0 note-block-item-btn-edit" data-confirm-url="<?= $add_note_url ?>" data-relation-id="<?= $relation->id ?>" data-note-id="<?= $note->id; ?>">
            <i class="bi bi-pencil"></i>
        </button>
        <button type="button" id="modal-relation-note-delete" data-bs-toggle="modal" data-bs-target="#modal-note-delete"
                class="btn blue-hover btn-outline-secondary-700 border-0 note-block-item-btn-delete" data-confirm-url="<?= $delete_note_url ?>" data-relation-id="<?= $relation->id ?>" data-note-id="<?= $note->id; ?>">
            <i class="bi bi-trash3"></i>
        </button>
    </div>
</div>