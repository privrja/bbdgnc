<?php

use Bbdgnc\Enum\Front;
use Bbdgnc\Enum\SequenceTypeEnum;
use Bbdgnc\Finder\Enum\ServerEnum;
use Bbdgnc\TransportObjects\SequenceTO;

?>

<script src="<?= AssetHelper::jsJsme() ?>"></script>

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
            <input type="number" step="any" id="txt-mass" name="<?= Front::CANVAS_INPUT_MASS ?>"
                   value="<?= set_value(Front::CANVAS_INPUT_MASS, $sequence['mass']) ?>"/>

            <label for="txt-sequence">Sequence</label>
            <input type="text" id="txt-sequence" name="<?= Front::SEQUENCE ?>"
                   value="<?= set_value(Front::SEQUENCE, $sequence['sequence']) ?>"/>

            <label for="txt-block-smiles">SMILES</label>
            <input type="text" id="txt-block-smiles" name="<?= Front::CANVAS_INPUT_SMILE ?>"
                   value="<?= set_value(Front::CANVAS_INPUT_SMILE, $sequence['smiles']) ?>"/>

            <label for="sel-block-reference-database">Reference Database</label>
            <?= form_dropdown(Front::CANVAS_INPUT_DATABASE, ServerEnum::$allValues, set_value(Front::CANVAS_INPUT_DATABASE, $sequence['database']),
                'id="sel-block-reference-database" class="select" title="Database"'); ?>

            <label for="txt-block-reference">Reference Identifier</label>
            <input type="text" id="txt-block-reference" name="<?= Front::CANVAS_INPUT_IDENTIFIER ?>"
                   value="<?= set_value(Front::CANVAS_INPUT_IDENTIFIER, $sequence['identifier']) ?>"/>

            <input type="hidden" id="hdn-decays" name="<?= Front::DECAYS ?>" value="<?= $sequence[SequenceTO::DECAYS] ?>" />
            <button>Save</button>

            <?= validation_errors(); ?>
            <?php if (isset($errors)) echo $errors; ?>

        </div>
        <?= form_close(); ?>

        <div id="div-sequence">
            <?= form_open('sequence/modifications/'); ?>
            <div class="div-modification">
                <h4>N-terminal Modification</h4>

                <label for="sel-n-modification">Select Modification</label>
                <?= form_dropdown(Front::N_MODIFICATION_SELECT, $modifications, set_value(Front::N_MODIFICATION_SELECT, $nModification['id']),
                    'id="sel-n-modification" class="select" title="Modification"'); ?>
            </div>

            <div class="div-modification">
                <h4>C-terminal Modification</h4>

                <label for="sel-c-modification">Select Modification</label>
                <?= form_dropdown(Front::C_MODIFICATION_SELECT, $modifications, set_value(Front::C_MODIFICATION_SELECT, $cModification['id']),
                    'id="sel-c-modification" class="select" title="Modification"'); ?>
            </div>

            <div class="div-modification">
                <h4>Branch Modification</h4>

                <label for="sel-b-modification">Select Modification</label>
                <?= form_dropdown(Front::B_MODIFICATION_SELECT, $modifications, set_value(Front::B_MODIFICATION_SELECT, $bModification['id']),
                    'id="sel-b-modification" class="select" title="Modification"'); ?>
            </div>
            <input type="hidden" name="sequenceId" value="<?= $sequence['id'] ?>"/>
            <input type="submit" value="Save modifications"/>
        </div>

    </div>
</div>

<script>

    let structureChanged = false;
    document.getElementById('txt-block-smiles').addEventListener('input', readSmiles);

    /**
     * This function will be called after the JavaScriptApplet code has been loaded.
     */
    function jsmeOnLoad() {
        jsmeApplet = new JSApplet.JSME("jsme_container", "500px", "500px", {
            options: "nocanonize"
        });
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
        if (!structureChanged) {
            structureChanged = true;
            return;
        }
        let smile = jsmeApplet.nonisomericSmiles();
        if (smile) {
            document.getElementById('txt-block-smiles').value = smile;
            document.getElementById('hdn-decays').value = '';
        }
    }

</script>
