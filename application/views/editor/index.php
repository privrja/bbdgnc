<?php

use Bbdgnc\Enum\Front;
use Bbdgnc\Finder\Enum\ServerEnum;

?>

<script src="<?= AssetHelper::jsJsme() ?>"></script>
<script>

    /**
     * This function will be called after the JavaScriptApplet code has been loaded.
     */
    function jsmeOnLoad() {
        jsmeApplet = new JSApplet.JSME("jsme_container", "500px", "500px");
        jsmeApplet.readGenericMolecularInput('<?= $block->smiles ?>');
    }

    /**
     * This function is called after Acept button is clicked
     * Get SMILES from editor and submit form
     */
    function getSmiles() {
        let smile = jsmeApplet.nonisomericSmiles();
        let blockId = '<?= $block->id ?>';
        let lastAcronym = '<?= $block->acronym ?>';
        let acronym = document.getElementById('txt-block-acronym').value;
        let sequence = document.getElementById('hdn-sequence').value;
        if ("" === lastAcronym || !sequence.includes(`[${lastAcronym}]`)) {
            sequence = sequenceReplace(blockId, acronym, sequence);
        } else {
            sequence = sequenceReplace(lastAcronym, acronym, sequence);
        }
        document.getElementById('hdn-sequence').value = sequence;
        redirectWithData({blockIdentifier: blockId, blockSmile: smile, blocks: 'Blocks'});
    }

    function sequenceReplace(id, acronym, sequence) {
        let length = id.toString().length;
        let index = sequence.indexOf(`[${id}]`) + 1;
        let left = sequence.substr(0, index);
        let right = sequence.substr(index + length);
        return left + acronym + right;
    }

    /**
     * This function add data to form as hidden and submit form
     * @param data
     */
    function redirectWithData(data) {
        let form = document.getElementById('form-block');
        for (let name in data) {
            let input = document.createElement('input');
            input.type = 'hidden';
            input.name = name;
            input.value = data[name];
            form.appendChild(input);
        }
        form.submit();
    }
</script>

<?= form_open('land/form', array('id' => 'form-block')); ?>

<div id="div-editor">
    <h2>JSME editor</h2>
    <div class="div-editor-left" id="jsme_container"></div>
    <div id="div-editor-form">
        <label for="txt-block-name">Name</label>
        <input type="text" id="txt-block-name" name="<?= Front::BLOCK_NAME ?>" value="<?= $block->name ?>"/>

        <label for="txt-block-acronym">Acronym</label>
        <input type="text" id="txt-block-acronym" name="<?= Front::BLOCK_ACRONYM ?>" value="<?= $block->acronym ?>"/>

        <label for="txt-block-formula">Residue Formula</label>
        <input type="text" id="txt-block-formula" name="<?= Front::BLOCK_FORMULA ?>" value="<?= $block->formula ?>"/>

        <label for="txt-block-mass">Monoisotopic Residue Mass</label>
        <input type="text" id="txt-block-mass" name="<?= Front::BLOCK_MASS ?>" value="<?= $block->mass ?>"/>

        <label for="txt-block-losses">Neutral Losses</label>
        <input type="text" id="txt-block-losses" name="<?= Front::BLOCK_NEUTRAL_LOSSES ?>"
               value="<?= $block->losses ?>"/>

        <label for="sel-block-reference-database">Reference Database</label>
        <?= form_dropdown(Front::BLOCK_REFERENCE_SERVER, ServerEnum::$allValues, set_value(Front::BLOCK_REFERENCE_SERVER),
            'id="sel-block-reference-database" class="select" title="Database"'); ?>

        <label for="txt-block-reference">Reference Identifier</label>
        <input type="text" id="txt-block-reference" name="<?= Front::BLOCK_REFERENCE ?>"
               value="<?= $block->reference->identifier ?>"/>

        <button onclick="getSmiles()">Accept changes</button>
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

</form>
