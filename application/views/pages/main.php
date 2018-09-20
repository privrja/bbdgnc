<?php

use Bbdgnc\Finder\Enum\ServerEnum;
use Bbdgnc\Finder\Enum\FindByEnum;
use Bbdgnc\Enum\Front;

?>

<div id="div-right">

    <?= form_open('land/form', array('class' => 'form')); ?>

    <label for="database">Database</label>
    <select name="database" class="select" title="Search">
        <option value=<?= ServerEnum::PUBCHEM ?>>PubChem</option>
        <option value=<?= ServerEnum::CHEMSPIDER ?>>ChemSpider</option>
        <option value=<?= ServerEnum::NORINE ?>>Norine</option>
        <option value=<?= ServerEnum::PDB ?>>PDB</option>
    </select>

    <label for="search">Search by</label>
    <select name="search" class="select" title="Search">
        <option value=<?= FindByEnum::NAME ?>>Name</option>
        <option value=<?= FindByEnum::SMILE ?>>SMILES</option>
        <option value=<?= FindByEnum::FORMULA ?>>Molecular Formula</option>
        <option value=<?= FindByEnum::MASS ?>>Monoisotopic Mass</option>
        <option value=<?= FindByEnum::IDENTIFIER ?>>Identifier</option>
    </select>

    <label for="name">Name</label>
    <input type="text" id="txt-canvas-name" class="txt-def" name="name" title="Name" value="<?= $name ?>"/>

    <label for="chk-match" class="chk">Exact match</label>
    <input type="checkbox" id="chk-match" name=<?= Front::CANVAS_INPUT_MATCH ?> value="1" checked="checked" />

    <label for="smile">SMILES</label>
    <textarea id="txt-canvas-smile" class="txt-area" name="smile" title="SMILES"><?= $smile ?></textarea>

    <label for="formula">Molecular Formula</label>
    <input type="text" id="txt-canvas-fle" class="txt-def" name="formula" title="Formula" value="<?= $formula ?>"/>

    <label for="mass">Monoisotopic Mass</label>
    <input type="number" id="txt-canvas-mass" class="txt-def" name="mass" title="Monoisotopic Mass"
           value="<?= $mass ?>"/>
    <label for="deflection" class="lbl-block">+/-</label>
    <input type="number" id="txt-canvas-mass-deflection" class="txt-def" name="deflection" title="Deflection"/>

    <label for="identifier">Identifier</label>
    <input type=text class="txt-def" name="identifier" title="Id" value="<?= $identifier ?>"/>

    <input type="hidden" name=<?= Front::CANVAS_HIDDEN_DATABASE ?> value="<?= $hddatabase ?>"/>

    <input type="submit" id="btn-canvas-find" class="btn-same" name="find" value="Find"/>
    <button type="button" id="btn-canvas-load" class="btn-same" name="load">Load</button>
    <button type="button" id="button-canvas-easy-smile" class="btn-same" onclick="easy()">Canonical SMILES</button>
    <button type="button" id="btn-canvas-disintegrate" class="btn-same">Building Blocks</button>
    <button type="button" id="btn-canvas-update" class="btn-same" onclick="drawSmile()">Update</button>
    <input type="submit" class="btn-same" value="Save"/>

    </form>

    <?= validation_errors(); ?>

</div>

<!-- Smiles Drawer -->
<script src="https://unpkg.com/smiles-drawer@1.0.10/dist/smiles-drawer.min.js"></script>
<!--<script src="--><? //= AssetHelper::jsUrl() . "smiles-drawer.js" ?><!--"></script>-->

<script src="<?= AssetHelper::jsUrl() . "canvas.js" ?>"></script>
