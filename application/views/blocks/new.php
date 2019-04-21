<?php

use Bbdgnc\Enum\Front;
use Bbdgnc\Finder\Enum\ServerEnum;

?>


<script src="<?= AssetHelper::jsJsme() ?>"></script>
<script>

    document.addEventListener('input', readSmiles);

    /**
     * This function will be called after the JavaScriptApplet code has been loaded.
     */
    function jsmeOnLoad() {
        jsmeApplet = new JSApplet.JSME("jsme_container", "500px", "500px");
        readSmiles();
        jsmeApplet.setCallBack("AfterStructureModified", getSmiles);
    }

    function readSmiles() {
        jsmeApplet.readGenericMolecularInput(document.getElementById('txt-block-smiles').value);
    }

    /**
     * This function is called after Acept button is clicked
     * Get SMILES from editor and submit form
     */
    function getSmiles() {
        let smile = jsmeApplet.nonisomericSmiles();
        if (smile) {
            document.getElementById('txt-block-smiles').value = smile;
        }
    }

</script>

<div id="div-full">
    <?= form_open('block/new', array('id' => 'form-block-new')); ?>

    <div id="div-editor">
        <h2>Add New Block</h2>
        <div class="div-editor-left" id="jsme_container"></div>
        <div id="div-editor-form">
            <label for="txt-block-name">Name</label>
            <input type="text" id="txt-block-name" name="<?= Front::BLOCK_NAME ?>"
                   value="<?= set_value(Front::BLOCK_NAME) ?>"/>

            <label for="txt-block-acronym">Acronym</label>
            <input type="text" id="txt-block-acronym" name="<?= Front::BLOCK_ACRONYM ?>"
                   value="<?= set_value(Front::BLOCK_ACRONYM) ?>"/>

            <label for="txt-block-formula">Residue Formula</label>
            <input type="text" id="txt-block-formula" name="<?= Front::BLOCK_FORMULA ?>"
                   value="<?= set_value(Front::BLOCK_FORMULA) ?>"/>

            <label for="txt-block-mass">Monoisotopic Residue Mass</label>
            <input type="number" step="any" id="txt-block-mass" name="<?= Front::BLOCK_MASS ?>"
                   value="<?= set_value(Front::BLOCK_MASS) ?>"/>

            <label for="txt-block-mass">SMILES</label>
            <input type="text" id="txt-block-smiles" name="<?= Front::BLOCK_SMILES ?>"
                   value="<?= set_value(Front::BLOCK_SMILES) ?>"/>

            <label for="txt-block-losses">Neutral Losses</label>
            <input type="text" id="txt-block-losses" name="<?= Front::BLOCK_NEUTRAL_LOSSES ?>"
                   value="<?= set_value(Front::BLOCK_NEUTRAL_LOSSES) ?>"/>

            <label for="sel-block-reference-database">Reference Database</label>
            <?= form_dropdown(Front::BLOCK_REFERENCE_SERVER, ServerEnum::$allValues, set_value(Front::BLOCK_REFERENCE_SERVER),
                'id="sel-block-reference-database" class="select" title="Database"'); ?>

            <label for="txt-block-reference">Reference Identifier</label>
            <input type="text" id="txt-block-reference" name="<?= Front::BLOCK_REFERENCE ?>"
                   value="<?= set_value(Front::BLOCK_REFERENCE) ?>"/>

        </div>
        <div id="div-editor-form-block">
            <button onclick="getSmiles()">Add</button>

            <button type="button" onclick="window.location.href = '<?= site_url('block') ?>'">Back to list</button>

            <div>
                <?= validation_errors(); ?>
                <?php if (isset($errors)) echo $errors; ?>
            </div>
        </div>
    </div>
    <?= form_close(); ?>
</div>
