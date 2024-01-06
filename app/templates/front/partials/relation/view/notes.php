<?php
/*
 * Modal for relation note add:
 * partials/modal/modal-relation-note-add-edit
 * Modal for relation note delete:
 * partials/modal/modal-relation-note-delete
 * Javascript for both modals
 * partials/js/modal-relation-note.js
 */

?>

<div class="block">
    <div class="d-flex align-items-center flex-row justify-content-between mb-3">
        <h5><?=t('NOTES')?></h5>
        <?php
        if (!empty($add_note_url) && !empty($relation)) { ?>
            <button type="button" id="modal-relation-note-add" data-bs-toggle="modal" data-bs-target="#modal-note-add-edit"
                    class="btn btn-sm btn-outline-success" data-confirm-url="<?= $add_note_url ?>" data-relation-id="<?= $relation->id ?>">
                <i class="bi bi-plus"></i> <?=t('NOTE_ADD')?>
            </button>
        <?php
        } ?>
    </div>
    <div class="note-block" >
    <?php
    if (!empty($notes)) {
        foreach ($notes as $note) {
            $app->renderPartial('relation/view/note', [
                'note' => $note,
                'relation' => $relation,
                'add_note_url' => $add_note_url,
                'delete_note_url' => $delete_note_url
            ]);
        }
    } else {
        echo '<p id="no-notes-provided">' . t('NO_NOTES_PROVIDED') . '</p>';
    }
    ?>
    </div>
</div>

<?php
$app->renderPartial('relation/view/noteTemplate', [
    'relation' => $relation,
    'add_note_url' => $add_note_url,
    'delete_note_url' => $delete_note_url
]);
?>

