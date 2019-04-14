<?php

use Bbdgnc\Enum\Front;

?>

<?= form_open('land', array('id' => 'form-smiles')); ?>

<div id="div-editor">
    <h2>JSME editor</h2>
    <div class="div-editor-left" id="jsme_container"></div>
    <div id="div-editor-form">
        <button type="button" onclick="returnBack()">Accept to SMILES</button>
        <button type="button" onclick="cancel()">Cancel</button>
    </div>
</div>

<input type="hidden" name="<?= Front::CANVAS_INPUT_DATABASE ?>" value="<?= $database ?>"/>
<input type="hidden" name="<?= Front::CANVAS_INPUT_SEARCH_BY ?>" value="<?= $search ?>"/>
<input type="hidden" name="<?= Front::CANVAS_INPUT_NAME ?>" value="<?= $name ?>"/>
<input type="hidden" name="<?= Front::CANVAS_INPUT_SMILE ?>" value="<?= $smile ?>"/>
<input type="hidden" name="<?= Front::CANVAS_INPUT_FORMULA ?>" value="<?= $formula ?>"/>
<input type="hidden" name="<?= Front::CANVAS_INPUT_MASS ?>" value="<?= $mass ?>"/>
<input type="hidden" name="<?= Front::CANVAS_INPUT_IDENTIFIER ?>" value="<?= $identifier ?>"/>

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
        redirectWithData('form-smiles', {smile: getSmiles()});
    }

    function cancel() {
        redirectWithData('form-smiles', {smile: '<?= $smile?>'});
    }

</script>
