<?php

use Bbdgnc\Enum\Front;

?>

<div id="div-full">
    <?= form_open('modification/edit/' . $modification['id'], array('id' => 'form-modification-edit')); ?>

    <div id="div-editor">
        <h2>Edit Modification</h2>
        <div id="div-editor-form">
            <label for="txt-modification">Name</label>
            <input type="text" id="txt-modification" name="<?= Front::MODIFICATION_NAME ?>"
                   value="<?= set_value(Front::MODIFICATION_NAME, $modification['name']) ?>"/>

            <label for="txt-formula">Formula</label>
            <input type="text" id="txt-formula" name="<?= Front::MODIFICATION_FORMULA ?>"
                   value="<?= set_value(Front::MODIFICATION_FORMULA, $modification['formula']) ?>"/>

            <label for="txt-mass">Monoisotopic Mass</label>
            <input type="number" step="any" id="txt-mass" name="<?= Front::MODIFICATION_MASS ?>"
                   value="<?= set_value(Front::MODIFICATION_MASS, $modification['mass']) ?>"/>

            <label for="chk-nterminal" class="chk">N-terminal</label>
            <input type="checkbox" id="chk-nterminal"
                   name="<?= Front::MODIFICATION_TERMINAL_N ?>" <?= Front::checked(set_value(Front::MODIFICATION_TERMINAL_N, $modification['nterminal'])) ?> />

            <label for="chk-cterminal" class="chk">C-terminal</label>
            <input type="checkbox" id="chk-cterminal"
                   name="<?= Front::MODIFICATION_TERMINAL_C ?>" <?= Front::checked(set_value(Front::MODIFICATION_TERMINAL_C, $modification['cterminal'])) ?> />
        </div>

        <button>Save</button>

        <button type="button" onclick="window.location.href = '<?= site_url('modification') ?>'">Back to list</button>

        <?= validation_errors(); ?>
        <?php if (isset($errors)) echo $errors; ?>
    </div>
    <?= form_close(); ?>
</div>
