<?php

use Bbdgnc\Enum\Front;
use Bbdgnc\Enum\SequenceTypeEnum;
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
    <?= form_open('sequence/edit/' . $sequence['id'], array('id' => 'form-sequence-edit')); ?>

    <div id="div-editor">
        <h2>Edit Sequence</h2>
        <div class="div-editor-left" id="jsme_container"></div>
        <div id="div-editor-form">
            <label for="sel-sequence-type">Type</label>
           <?= form_dropdown(Front::SEQUENCE_TYPE, SequenceTypeEnum::$values, set_value(Front::SEQUENCE_TYPE, $sequence['type']),
                'id="sel-sequence-type" class="select" title="Type" onchange="sequenceTypeChanged()"'); ?>

            <label for="txt-sequence-name">Name</label>
            <input type="text" id="txt-sequence-name" name="<?= Front::CANVAS_INPUT_NAME ?>"
                   value="<?= set_value(Front::CANVAS_INPUT_NAME, $sequence['name']) ?>"/>

            <label for="txt-formula">Formula</label>
            <input type="text" id="txt-formula" name="<?= Front::CANVAS_INPUT_FORMULA ?>"
                   value="<?= set_value(Front::CANVAS_INPUT_FORMULA, $sequence['formula']) ?>"/>

            <label for="txt-mass">Monoisotopic Mass</label>
            <input type="text" id="txt-mass" name="<?= Front::CANVAS_INPUT_MASS ?>"
                   value="<?= set_value(Front::CANVAS_INPUT_MASS, $sequence['mass']) ?>"/>

            <label for="txt-sequence">Sequence</label>
            <input type="text" id="txt-sequence" name="<?= Front::SEQUENCE ?>"
                   value="<?= set_value(Front::SEQUENCE, $sequence['sequence']) ?>"/>

            <label for="txt-block-smiles">SMILES</label>
            <input type="text" id="txt-block-smiles" name="<?= Front::BLOCK_SMILES ?>"
                   value="<?= set_value(Front::BLOCK_SMILES, $sequence['smiles']) ?>"/>

            <label for="sel-block-reference-database">Reference Database</label>
            <?= form_dropdown(Front::CANVAS_INPUT_DATABASE, ServerEnum::$allValues, set_value(Front::CANVAS_INPUT_DATABASE, $sequence['database']),
                'id="sel-block-reference-database" class="select" title="Database"'); ?>

            <label for="txt-block-reference">Reference Identifier</label>
            <input type="text" id="txt-block-reference" name="<?= Front::CANVAS_INPUT_IDENTIFIER ?>"
                   value="<?= set_value(Front::CANVAS_INPUT_IDENTIFIER, $sequence['identifier']) ?>"/>

            <button onclick="getSmiles()">Edit</button>

            <?= validation_errors(); ?>
            <?php if (isset($errors)) echo $errors; ?>

        </div>
    </div>
    <?= form_close(); ?>
</div>
