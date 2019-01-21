<?php

use Bbdgnc\Enum\Front;

?>

<script src="<?= AssetHelper::jsJsme() ?>"></script>

<script>
    /**
     * This function will be called after the JavaScriptApplet code has been loaded.
     */
    function jsmeOnLoad() {
        jsmeApplet = new JSApplet.JSME("jsme_container", "600px", "600px");
        jsmeApplet.readGenericMolecularInput('<?= $blockSmile ?>');
    }

    function getSmiles() {
        let smile = jsmeApplet.nonisomericSmiles();
        redirectWithData({blockIdentifier: <?= $blockIdentifier ?>, blockSmile: smile, blocks: 'Blocks'});
    }

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

<div id="div-editor">
    <h2 class="">JSME editor</h2>
    <div class="table-left">
        <div class="td" id="jsme_container"></div>
        <button onclick="getSmiles()">Accept changes</button>
    </div>
</div>


<?= form_open('land/form', array('class' => 'tr', 'id' => 'form-block')); ?>

<input type="hidden" name="<?= Front::CANVAS_INPUT_DATABASE ?>" value="<?= $database ?>" />
<input type="hidden" name="<?= Front::CANVAS_INPUT_SEARCH_BY ?>" value="<?= $search ?>" />
<input type="hidden" name="<?= Front::CANVAS_INPUT_NAME ?>" value="<?= $name ?>" />
<input type="hidden" name="<?= Front::CANVAS_INPUT_SMILE ?>" value="<?= $smile ?>" />
<input type="hidden" name="<?= Front::CANVAS_INPUT_FORMULA ?>" value="<?= $formula ?>" />
<input type="hidden" name="<?= Front::CANVAS_INPUT_MASS ?>" value="<?= $mass ?>" />
<input type="hidden" name="<?= Front::CANVAS_INPUT_DEFLECTION ?>" value="<?= $deflection ?>" />
<input type="hidden" name="<?= Front::CANVAS_INPUT_IDENTIFIER ?>" value="<?= $identifier ?>" />
<input type="hidden" name="<?= Front::BLOCK_ACRONYM ?>" value="<?= $blockAcronym ?>" />
<input type="hidden" name="<?= Front::BLOCK_NAME ?>" value="<?= $blockName ?>" />
<input type="hidden" name="<?= Front::BLOCK_COUNT ?>" value="<?= $blockCount ?>" />

</form>
