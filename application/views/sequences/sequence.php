<?php

use Bbdgnc\Enum\Front;
use Bbdgnc\Finder\Enum\FindByEnum;
use Bbdgnc\Finder\Enum\ServerEnum;
use Bbdgnc\TransportObjects\SequenceTO;

?>

<div id="div-right">

    <!--    <div class="form" id="form-main">-->
    <?= form_open('land/form', array('class' => 'form', 'id' => 'form-main')); ?>
    <label for="sel-canvas-database">Database</label>
    <?= form_dropdown(Front::CANVAS_INPUT_DATABASE, ServerEnum::$values, set_value(Front::CANVAS_INPUT_DATABASE),
        'id="sel-canvas-database" class="select" title="Search"'); ?>

    <label for="sel-canvas-search">Search by</label>
    <?= form_dropdown(Front::CANVAS_INPUT_SEARCH_BY, FindByEnum::$values, set_value(Front::CANVAS_INPUT_SEARCH_BY),
        'id="sel-canvas-search" class="select" title="Search"') ?>

    <label for="txt-canvas-name">Name</label>
    <input type="text" id="txt-canvas-name" class="txt-def" name="<?= Front::CANVAS_INPUT_NAME ?>" title="Name"
           value="<?= set_value(Front::CANVAS_INPUT_NAME, $sequence[SequenceTO::NAME]) ?>"/>

    <label for="chk-match" class="chk">Exact match</label>
    <input type="checkbox" id="chk-match" name="<?= Front::CANVAS_INPUT_MATCH ?>"
           value="1" <?= set_checkbox(Front::CANVAS_INPUT_MATCH, '1', true); ?>/>

    <label for="txt-canvas-smile">SMILES</label>
    <textarea id="txt-canvas-smile" class="txt-area" name="<?= Front::CANVAS_INPUT_SMILE ?>"
              title="SMILES"><?= set_value(Front::CANVAS_INPUT_SMILE, $sequence['smiles']) ?></textarea>

    <label for="txt-canvas-fle">Molecular Formula</label>
    <input type="text" id="txt-canvas-fle" class="txt-def" name="<?= Front::CANVAS_INPUT_FORMULA ?>" title="Formula"
           value="<?= set_value(Front::CANVAS_INPUT_FORMULA, $sequence[SequenceTO::FORMULA]) ?>"/>

    <label for="txt-canvas-mass">Monoisotopic Mass</label>
    <input type="number" step="any" id="txt-canvas-mass" class="txt-def" name="<?= Front::CANVAS_INPUT_MASS ?>"
           title="Monoisotopic Mass"
           value="<?= set_value(Front::CANVAS_INPUT_MASS, $sequence[SequenceTO::MASS]) ?>"/>

    <label for="txt-canvas-identifier">Identifier</label>
    <input type=text id="txt-canvas-identifier" class="txt-def" name="<?= Front::CANVAS_INPUT_IDENTIFIER ?>"
           title="Id"
           value="<?= set_value(Front::CANVAS_INPUT_IDENTIFIER, $sequence[SequenceTO::IDENTIFIER]) ?>"/>

    <input type="submit" id="btn-canvas-find" class="btn-same" name="find" value="Find"/>
    <button type="button" id="btn-canvas-update" class="btn-same"
            onclick="window.location.href='<?= ServerEnum::getLink($sequence[SequenceTO::DATABASE], $sequence[SequenceTO::IDENTIFIER]) ?>'">
        Reference
    </button>
    <button type="button" id="button-canvas-easy-smile" class="btn-same" onclick="easy()">Generic SMILES</button>
    <button type="button" id="btn-canvas-disintegrate" class="btn-same" name="blocks" value="Blocks"
            onclick="disintegrate()">Building Blocks
    </button>
    <button type="submit" id="btn-canvas-load" class="btn-same" name="load" value="Load">Unique SMILES</button>
    <button type="button" id="btn-canvas-edit" class="btn-same"
            onclick="window.location.href = '<?= site_url('sequence/edit/' . $sequence['id']) ?>'">Edit
    </button>
    <input type="hidden" id="hdn-decays" name="<?= Front::DECAYS ?>"
           value="<?= set_value(Front::DECAYS, $sequence[SequenceTO::DECAYS]) ?>"/>
</div>
<?php form_close(); ?>

</div>

<!-- Smiles Drawer -->
<script src="<?= AssetHelper::jsSmilesDrawer() ?>"></script>
