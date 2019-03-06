<?php

use Bbdgnc\Enum\Front;

?>

<div id="div-full">
    <?= form_open('modification/new', array('id' => 'form-modification-new')); ?>

    <div id="div-editor">
        <h2>Add Modification to Sequence</h2>
        <div id="div-editor-form">


            <label for="sel-modification-type">Select Modification Type</label>
            <?= form_dropdown(Front::MODIFICATION_SELECT, $modifications, '0',
                'id="sel-modification" class="select" title="Modification"'); ?>


            <label for="sel-modification">Select Modification</label>
            <?= form_dropdown(Front::MODIFICATION_SELECT, $modifications, '0',
                'id="sel-modification" class="select" title="Modification"'); ?>

            <label for="txt-modification">Name</label>
            <input type="text" id="txt-modification" name="<?= Front::MODIFICATION_NAME ?>"
                   value="<?= set_value(Front::MODIFICATION_NAME) ?>"/>

            <label for="txt-formula">Formula</label>
            <input type="text" id="txt-formula" name="<?= Front::MODIFICATION_FORMULA ?>"
                   value="<?= set_value(Front::MODIFICATION_FORMULA) ?>"/>

            <label for="txt-mass">Monoisotopic Mass</label>
            <input type="text" id="txt-mass" name="<?= Front::MODIFICATION_MASS ?>"
                   value="<?= set_value(Front::MODIFICATION_MASS) ?>"/>

            <label for="chk-nterminal" class="chk">N-terminal</label>
            <input type="checkbox" id="chk-nterminal"
                   name="<?= Front::MODIFICATION_TERMINAL_N ?>" <?= Front::checked(set_value(Front::MODIFICATION_TERMINAL_N)) ?> />

            <label for="chk-cterminal" class="chk">C-terminal</label>
            <input type="checkbox" id="chk-cterminal"
                   name="<?= Front::MODIFICATION_TERMINAL_C ?>" <?= Front::checked(set_value(Front::MODIFICATION_TERMINAL_C)) ?> />
        </div>

        <button>Add</button>

        <?= validation_errors(); ?>
        <?php if (isset($errors)) echo $errors; ?>
    </div>
    <?= form_close(); ?>
</div>

