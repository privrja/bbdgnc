<?php

use Bbdgnc\Enum\Front;

?>

<?= form_open('land/form', array('id' => 'form-block')); ?>

<div id="div-editor">
    <h2>JSME editor</h2>
    <div class="div-editor-left" id="jsme_container"></div>
    <div id="div-editor-form">


        <button type="button" onclick="returnBack()">Accept to SMILES</button>
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
<input type="hidden" name="<?= Front::DECAYS ?>" value="<?= $decays ?>"/>
<input type="hidden" name="<?= Front::SORT ?>" value="<?= $sort ?>"/>
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

    /**
     * This function will be called after the JavaScriptApplet code has been loaded.
     */
    function jsmeOnLoad() {
        jsmeApplet = new JSApplet.JSME("jsme_container", "500px", "500px", {
            options: "nocanonize"
        });
        readSmiles();
        // jsmeApplet.setCallBack("AfterStructureModified", getSmiles);
    }

    function readSmiles() {
        jsmeApplet.readGenericMolecularInput('<?= $smile ?>');
    }

    function getSmiles() {
        return jsmeApplet.nonisomericSmiles();
    }

    /**
     * This function is called after Accept button is clicked
     * Get SMILES from editor and submit form
     */
    function returnBack() {
        redirectWithData('form-block', {smile: getSmiles()});
    }

</script>
