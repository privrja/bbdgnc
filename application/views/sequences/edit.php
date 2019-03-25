<?php

use Bbdgnc\Enum\Front;
use Bbdgnc\Enum\SequenceTypeEnum;
use Bbdgnc\Finder\Enum\ServerEnum;
use Bbdgnc\TransportObjects\ModificationTO;

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
            <input type="text" id="txt-block-smiles" name="<?= Front::CANVAS_INPUT_SMILE ?>"
                   value="<?= set_value(Front::CANVAS_INPUT_SMILE, $sequence['smiles']) ?>"/>

            <label for="sel-block-reference-database">Reference Database</label>
            <?= form_dropdown(Front::CANVAS_INPUT_DATABASE, ServerEnum::$allValues, set_value(Front::CANVAS_INPUT_DATABASE, $sequence['database']),
                'id="sel-block-reference-database" class="select" title="Database"'); ?>

            <label for="txt-block-reference">Reference Identifier</label>
            <input type="text" id="txt-block-reference" name="<?= Front::CANVAS_INPUT_IDENTIFIER ?>"
                   value="<?= set_value(Front::CANVAS_INPUT_IDENTIFIER, $sequence['identifier']) ?>"/>

            <button onclick="getSmiles()">Save</button>

            <?= validation_errors(); ?>
            <?php if (isset($errors)) echo $errors; ?>

        </div>
        <?= form_close(); ?>

        <div id="div-sequence">
            <?= form_open('sequence/modifications/'); ?>
            <div class="div-modification">
                <h4>N-terminal Modification</h4>

                <label for="sel-n-modification">Select Modification</label>
                <?= form_dropdown(Front::N_MODIFICATION_SELECT, $modifications, $nModification['id'],
                    'id="sel-n-modification" class="select" title="Modification"'); ?>

                <div id="div-n-modification">
                    <label for="txt-n-modification">Name</label>
                    <input type="text" id="txt-n-modification" name="nModification"
                           value="<?= set_value('nModification', $nModification['name']) ?>"/>

                    <label for="txt-n-formula">Formula</label>
                    <input type="text" id="txt-n-formula" name="nFormula"
                           value="<?= set_value('nFormula', $nModification[ModificationTO::FORMULA]) ?>"/>

                    <label for="txt-n-mass">Monoisotopic Mass</label>
                    <input type="text" id="txt-n-mass" name="nMass"
                           value="<?= set_value('nMass', $nModification[ModificationTO::MASS]) ?>"/>

                    <label for="chk-n-nterminal" class="chk">N-terminal</label>
                    <input type="checkbox" id="chk-n-nterminal"
                           name="nnTerminal" <?= Front::checked(set_value('nnTerminal', $nModification[ModificationTO::NTERMINAL])) ?> />

                    <label for="chk-n-cterminal" class="chk">C-terminal</label>
                    <input type="checkbox" id="chk-n-cterminal"
                           name="ncTerminal" <?= Front::checked(set_value('ncTerminal', $nModification[ModificationTO::CTERMINAL])) ?> />
                </div>
            </div>

            <div class="div-modification">
                <h4>C-terminal Modification</h4>

                <label for="sel-c-modification">Select Modification</label>
                <?= form_dropdown(Front::C_MODIFICATION_SELECT, $modifications, $cModification['id'],
                    'id="sel-c-modification" class="select" title="Modification"'); ?>

                <div id="div-c-modification">
                    <label for="txt-c-modification">Name</label>
                    <input type="text" id="txt-c-modification" name="cModification"
                           value="<?= set_value('cModification', $cModification[ModificationTO::NAME]) ?>"/>

                    <label for="txt-c-formula">Formula</label>
                    <input type="text" id="txt-c-formula" name="cFormula"
                           value="<?= set_value('cFormula', $cModification[ModificationTO::FORMULA]) ?>"/>

                    <label for="txt-c-mass">Monoisotopic Mass</label>
                    <input type="text" id="txt-c-mass" name="cMass"
                           value="<?= set_value('cMass', $cModification[ModificationTO::MASS]) ?>"/>

                    <label for="chk-c-nterminal" class="chk">N-terminal</label>
                    <input type="checkbox" id="chk-c-nterminal"
                           name="cnTerminal" <?= Front::checked(set_value('cnTerminal', $cModification[ModificationTO::NTERMINAL])) ?>/>

                    <label for="chk-c-cterminal" class="chk">C-terminal</label>
                    <input type="checkbox" id="chk-c-cterminal"
                           name="ccTerminal" <?= Front::checked(set_value('ccTerminal', $cModification[ModificationTO::CTERMINAL])) ?> />
                </div>
            </div>

            <div class="div-modification">
                <h4>Branch Modification</h4>

                <div id="div-b-modification">
                    <label for="sel-b-modification">Select Modification</label>
                    <?= form_dropdown(Front::B_MODIFICATION_SELECT, $modifications, $bModification['id'],
                        'id="sel-b-modification" class="select" title="Modification"'); ?>

                    <label for="txt-b-modification">Name</label>
                    <input type="text" id="txt-b-modification" name="bModification"
                           value="<?= set_value('bModification', $bModification[ModificationTO::NAME]) ?>" disabled/>

                    <label for="txt-b-formula">Formula</label>
                    <input type="text" id="txt-b-formula" name="bFormula"
                           value="<?= set_value('bFormula', $bModification[ModificationTO::FORMULA]) ?>" disabled/>

                    <label for="txt-b-mass">Monoisotopic Mass</label>
                    <input type="text" id="txt-b-mass" name="bMass"
                           value="<?= set_value('bMass', $bModification[ModificationTO::MASS]) ?>" disabled/>

                    <label for="chk-b-nterminal" class="chk">N-terminal</label>
                    <input type="checkbox" id="chk-b-nterminal"
                           name="bnTerminal" <?= Front::checked(set_value('bnTerminal', $bModification[ModificationTO::NTERMINAL])) ?>
                           disabled/>

                    <label for="chk-b-cterminal" class="chk">C-terminal</label>
                    <input type="checkbox" id="chk-b-cterminal"
                           name="bcTerminal" <?= Front::checked(set_value('bcTerminal', $bModification[ModificationTO::CTERMINAL])) ?>
                           disabled/>
                </div>
            </div>
            <input type="hidden" name="sequenceId" value="<?= $sequence['id'] ?>" />
            <input type="submit" value="Save modifications" />
        </div>

    </div>
</div>
