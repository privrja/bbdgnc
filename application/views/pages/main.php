<?php

use Bbdgnc\Finder\Enum\ServerEnum;
use Bbdgnc\Finder\Enum\FindByEnum;
use Bbdgnc\Enum\Front;

?>

<div id="div-right">

    <?= form_open('land/form', array('class' => 'form')); ?>

    <label for="sel-canvas-database">Database</label>
    <?= form_dropdown(Front::CANVAS_INPUT_DATABASE, ServerEnum::$values, set_value(Front::CANVAS_INPUT_DATABASE),
        'id="sel-canvas-database" class="select" title="Search"'); ?>

    <label for="sel-canvas-search">Search by</label>
    <?= form_dropdown(Front::CANVAS_INPUT_SEARCH_BY, FindByEnum::$values, set_value(Front::CANVAS_INPUT_SEARCH_BY),
        'id="sel-canvas-search" class="select" title="Search"') ?>

    <label for="txt-canvas-name">Name</label>
    <input type="text" id="txt-canvas-name" class="txt-def" name="<?= Front::CANVAS_INPUT_NAME ?>" title="Name"
           value="<?= $name ?>"/>

    <label for="chk-match" class="chk">Exact match</label>
    <input type="checkbox" id="chk-match" name="<?= Front::CANVAS_INPUT_MATCH ?>" value="1" checked="checked"/>

    <label for="txt-canvas-smile">SMILES</label>
    <textarea id="txt-canvas-smile" class="txt-area" name="<?= Front::CANVAS_INPUT_SMILE ?>"
              title="SMILES"><?= $smile ?></textarea>

    <label for="txt-canvas-fle">Molecular Formula</label>
    <input type="text" id="txt-canvas-fle" class="txt-def" name="<?= Front::CANVAS_INPUT_FORMULA ?>" title="Formula"
           value="<?= $formula ?>"/>

    <label for="txt-canvas-mass">Monoisotopic Mass</label>
    <input type="number" step="any" id="txt-canvas-mass" class="txt-def" name="<?= Front::CANVAS_INPUT_MASS ?>"
           title="Monoisotopic Mass" value="<?= $mass ?>"/>
    <label for="txt-canvas-mass-deflection" class="lbl-block">+/-</label>
    <input type="number" step="any" id="txt-canvas-mass-deflection" class="txt-def" name="<?= Front::CANVAS_INPUT_DEFLECTION ?>"
           title="Deflection" value="<?= $deflection ?>"/>

    <label for="txt-canvas-identifier">Identifier</label>
    <input type=text id="txt-canvas-identifier" class="txt-def" name="<?= Front::CANVAS_INPUT_IDENTIFIER ?>" title="Id"
           value="<?= $identifier ?>"/>

    <input type="hidden" name="<?= Front::CANVAS_HIDDEN_DATABASE ?>" value="<?= $hdDatabase ?>"/>

    <input type="submit" id="btn-canvas-find" class="btn-same" name="find" value="Find"/>
    <button type="button" id="btn-canvas-update" class="btn-same" onclick="resize()">Update</button>
    <button type="button" id="button-canvas-easy-smile" class="btn-same" onclick="easy()">Canonical SMILES</button>
    <button type="button" id="btn-canvas-disintegrate" class="btn-same">Building Blocks</button>
    <button type="button" id="btn-canvas-load" class="btn-same" name="load">Load</button>
    <input type="submit" class="btn-same" value="Save"/>

    </form>

    <?= validation_errors(); ?>
    <?php if (isset($errors)) echo $errors; ?>

</div>

<!-- Smiles Drawer -->
<script src="https://unpkg.com/smiles-drawer@1.0.10/dist/smiles-drawer.min.js"></script>
<!--<script src="--><? //= AssetHelper::jsUrl() . "smiles-drawer.js" ?><!--"></script>-->

<script src="<?= AssetHelper::jsUrl() . "canvas.js" ?>"></script>
