<?php

use Bbdgnc\Enum\Front;
use Bbdgnc\Enum\SequenceTypeEnum;
use Bbdgnc\Finder\Enum\ServerEnum;

?>

<script src="<?= AssetHelper::jsJsme() ?>" xmlns:but="http://www.w3.org/1999/html"></script>
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
    <?= form_open('sequence/new', array('id' => 'form-sequence-new')); ?>

    <div id="div-editor">
        <h2>Add New Sequence</h2>
        <div class="div-editor-left" id="jsme_container"></div>
        <div id="div-editor-form">
            <label for="sel-sequence-type">Type</label>
            <?= form_dropdown(Front::SEQUENCE_TYPE, SequenceTypeEnum::$values, set_value(Front::SEQUENCE_TYPE),
                'id="sel-sequence-type" class="select" title="Type" onchange="sequenceTypeChanged()"'); ?>

            <label for="txt-sequence-name">Name</label>
            <input type="text" id="txt-sequence-name" name="<?= Front::CANVAS_INPUT_NAME ?>"
                   value="<?= set_value(Front::CANVAS_INPUT_NAME) ?>"/>

            <label for="txt-formula">Formula</label>
            <input type="text" id="txt-formula" name="<?= Front::CANVAS_INPUT_FORMULA ?>"
                   value="<?= set_value(Front::CANVAS_INPUT_FORMULA) ?>"/>

            <label for="txt-mass">Monoisotopic Mass</label>
            <input type="text" id="txt-mass" name="<?= Front::CANVAS_INPUT_MASS ?>"
                   value="<?= set_value(Front::CANVAS_INPUT_MASS) ?>"/>

            <label for="txt-sequence">Sequence</label>
            <input type="text" id="txt-sequence" name="<?= Front::SEQUENCE ?>"
                   value="<?= set_value(Front::SEQUENCE) ?>"/>

            <label for="txt-block-smiles">SMILES</label>
            <input type="text" id="txt-block-smiles" name="<?= Front::CANVAS_INPUT_SMILE ?>"
                   value="<?= set_value(Front::CANVAS_INPUT_SMILE) ?>"/>

            <label for="sel-block-reference-database">Reference Database</label>
            <?= form_dropdown(Front::CANVAS_INPUT_DATABASE, ServerEnum::$allValues, set_value(Front::CANVAS_INPUT_DATABASE),
                'id="sel-block-reference-database" class="select" title="Database"'); ?>

            <label for="txt-block-reference">Reference Identifier</label>
            <input type="text" id="txt-block-reference" name="<?= Front::CANVAS_INPUT_IDENTIFIER ?>"
                   value="<?= set_value(Front::CANVAS_INPUT_IDENTIFIER) ?>"/>

            <button onclick="getSmiles()">Add</button>

            <?= validation_errors(); ?>
            <?php if (isset($errors)) echo $errors; ?>

        </div>
    </div>
    <?= form_close(); ?>
</div>

