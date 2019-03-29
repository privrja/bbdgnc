<?php

use Bbdgnc\Enum\Front;
use Bbdgnc\Finder\Enum\ServerEnum;

?>

<?= form_open('land/form', array('id' => 'form-block')); ?>

<div id="div-editor">
    <h2>JSME editor</h2>
    <div class="div-editor-left" id="jsme_container"></div>
    <div id="div-editor-form">

        <label for="sel-block">Select Block</label>
        <?= form_dropdown(Front::BLOCK_SELECT, $blocks, set_value(Front::BLOCK_DATABASE_ID, '0'),
            'id="sel-block" class="select" title="Block"'); ?>

        <label for="txt-block-name">Name</label>
        <input type="text" id="txt-block-name" name="<?= Front::BLOCK_NAME ?>" value="<?= $block->name ?>"/>

        <label for="txt-block-acronym">Acronym</label>
        <input type="text" id="txt-block-acronym" name="<?= Front::BLOCK_ACRONYM ?>" value="<?= $block->acronym ?>"/>

        <label for="txt-block-formula">Residue Formula</label>
        <input type="text" id="txt-block-formula" name="<?= Front::BLOCK_FORMULA ?>" value="<?= $block->formula ?>"/>

        <label for="txt-block-mass">Monoisotopic Residue Mass</label>
        <input type="text" id="txt-block-mass" name="<?= Front::BLOCK_MASS ?>" value="<?= $block->mass ?>"/>

        <label for="txt-block-smiles">SMILES</label>
        <input type="text" id="txt-block-smiles" name="<?= Front::BLOCK_SMILE ?>"
               value="<?= $block->smiles ?>"/>

        <label for="txt-block-losses">Neutral Losses</label>
        <input type="text" id="txt-block-losses" name="<?= Front::BLOCK_NEUTRAL_LOSSES ?>"
               value="<?= $block->losses ?>"/>

        <label for="sel-block-reference-database">Reference Database</label>
        <?= form_dropdown(Front::BLOCK_REFERENCE_SERVER, ServerEnum::$allValues, set_value(Front::BLOCK_REFERENCE_SERVER),
            'id="sel-block-reference-database" class="select" title="Database"'); ?>

        <label for="txt-block-reference">Reference Identifier</label>
        <input type="text" id="txt-block-reference" name="<?= Front::BLOCK_REFERENCE ?>"
               value="<?= $block->identifier ?>"/>

        <button type="button" onclick="saveBb()">Accept changes</button>
    </div>
</div>

<input type="hidden" name="<?= Front::CANVAS_INPUT_DATABASE ?>" value="<?= $database ?>"/>
<input type="hidden" name="<?= Front::CANVAS_INPUT_SEARCH_BY ?>" value="<?= $search ?>"/>
<input type="hidden" name="<?= Front::CANVAS_INPUT_NAME ?>" value="<?= $name ?>"/>
<input type="hidden" name="<?= Front::CANVAS_INPUT_SMILE ?>" value="<?= $smile ?>"/>
<input type="hidden" name="<?= Front::CANVAS_INPUT_FORMULA ?>" value="<?= $formula ?>"/>
<input type="hidden" name="<?= Front::CANVAS_INPUT_MASS ?>" value="<?= $mass ?>"/>
<input type="hidden" name="<?= Front::CANVAS_INPUT_DEFLECTION ?>" value="<?= $deflection ?>"/>
<input type="hidden" name="<?= Front::CANVAS_INPUT_IDENTIFIER ?>" value="<?= $identifier ?>"/>
<input type="hidden" name="<?= Front::BLOCK_COUNT ?>" value="<?= $blockCount ?>"/>
<input type="hidden" name="<?= Front::SEQUENCE ?>" value="<?= $sequence ?>" id="hdn-sequence"/>
<input type="hidden" name="<?= Front::SEQUENCE_TYPE ?>" value="<?= $sequenceType ?>"/>
<input type="hidden" id="hdn-block-decays" name="<?= Front::DECAYS ?>" value="<?= $decays ?>"/>
<input type="hidden" id="hdn-sort" name="<?= Front::SORT ?>" value="<?= $sort ?>"/>
<input type="hidden" name="<?= Front::N_MODIFICATION_SELECT ?>" value="<?= $nSelect ?>"/>
<input type="hidden" name="<?= Front::N_MODIFICATION_NAME ?>" value="<?= $nModification ?>"/>
<input type="hidden" name="<?= Front::N_MODIFICATION_FORMULA ?>" value="<?= $nFormula ?>"/>
<input type="hidden" name="<?= Front::N_MODIFICATION_MASS ?>" value="<?= $nMass ?>"/>
<input type="hidden" name="<?= Front::N_MODIFICATION_TERMINAL_N ?>" value="<?= $nTerminalN ?>"/>
<input type="hidden" name="<?= Front::N_MODIFICATION_TERMINAL_C ?>" value="<?= $nTerminalC ?>"/>
<input type="hidden" name="<?= Front::C_MODIFICATION_SELECT ?>" value="<?= $cSelect ?>"/>
<input type="hidden" name="<?= Front::C_MODIFICATION_NAME ?>" value="<?= $cModification ?>"/>
<input type="hidden" name="<?= Front::C_MODIFICATION_FORMULA ?>" value="<?= $cFormula ?>"/>
<input type="hidden" name="<?= Front::C_MODIFICATION_MASS ?>" value="<?= $cMass ?>"/>
<input type="hidden" name="<?= Front::C_MODIFICATION_TERMINAL_N ?>" value="<?= $cTerminalN ?>"/>
<input type="hidden" name="<?= Front::C_MODIFICATION_TERMINAL_C ?>" value="<?= $cTerminalC ?>"/>
<input type="hidden" name="<?= Front::B_MODIFICATION_SELECT ?>" value="<?= $bSelect ?>"/>
<input type="hidden" name="<?= Front::B_MODIFICATION_NAME ?>" value="<?= $bModification ?>"/>
<input type="hidden" name="<?= Front::B_MODIFICATION_FORMULA ?>" value="<?= $bFormula ?>"/>
<input type="hidden" name="<?= Front::B_MODIFICATION_MASS ?>" value="<?= $bMass ?>"/>
<input type="hidden" name="<?= Front::B_MODIFICATION_TERMINAL_N ?>" value="<?= $bTerminalN ?>"/>
<input type="hidden" name="<?= Front::B_MODIFICATION_TERMINAL_C ?>" value="<?= $bTerminalC ?>"/>

</form>

<script src="<?= AssetHelper::jsJsme() ?>"></script>
<script>

    let structureChanged = false;
    document.getElementById('sel-block').addEventListener('change', blockFromDatabase);
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

    function blockFromDatabase() {
        let blocks = <?= json_encode($blocks); ?>;
        let id = document.getElementById('sel-block').value;
        disable(id != 0);
    }

    function disable(disable = false) {
        document.getElementById('txt-block-name').disabled = disable;
        document.getElementById('txt-block-acronym').disabled = disable;
        document.getElementById('txt-block-formula').disabled = disable;
        document.getElementById('txt-block-mass').disabled = disable;
        document.getElementById('txt-block-smiles').disabled = disable;
        document.getElementById('txt-block-losses').disabled = disable;
        document.getElementById('sel-block-reference-database').disabled = disable;
        document.getElementById('txt-block-reference').disabled = disable;
    }

    function getSmiles() {
        if (!structureChanged) {
            structureChanged = true;
            return;
        }
        let smile = jsmeApplet.nonisomericSmiles();
        if (smile) {
            document.getElementById('txt-block-smiles').value = smile;
        }
    }

    /**
     * This function is called after Accept button is clicked
     * Get SMILES from editor and submit form
     */
    function saveBb() {
        let blockId = '<?= $block->id ?>';
        let lastAcronym = '<?= $block->acronym ?>';
        let acronym;
        if (document.getElementById('txt-block-acronym').disabled) {
            acronym = document.getElementById('sel-block').options[document.getElementById('sel-block').selectedIndex].text;
        } else {
            acronym = document.getElementById('txt-block-acronym').value;
        }
        let sequence = document.getElementById('hdn-sequence').value;
        if ("" === lastAcronym || !sequence.includes(`[${lastAcronym}]`)) {
            sequence = sequenceReplace(blockId, acronym, sequence);
        } else {
            sequence = sequenceReplace(lastAcronym, acronym, sequence);
        }
        let databaseId = '<?= $block->databaseId ?>';
        if (lastAcronym !== acronym) {
            databaseId = null;
        }
        document.getElementById('hdn-sequence').value = sequence;
        redirectWithData('form-block', {blockIdentifier: blockId, blockDatabaseId: databaseId, blocks: 'Blocks'});
    }

    function sequenceReplace(id, acronym, sequence) {
        let length = id.toString().length;
        let index = sequence.indexOf(`[${id}]`);
        if (index === -1) {
            return sequence;
        }
        index++;
        let left = sequence.substr(0, index);
        let right = sequence.substr(index + length);
        return left + acronym + right;
    }

</script>
