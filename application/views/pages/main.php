<div id="div-canvas">
    <canvas id="canvas-main"></canvas>
</div>
<div id="div-right">

    <?= validation_errors(); ?>

    <?= form_open('land/index', array('class' => 'form')); ?>

    <label for="search">Database</label>
    <select name="search" class="select" title="Search">
        <option value="pubchem">PubChem</option>
        <option value="chemspider">ChemSpider</option>
        <option value="norine">Norine</option>
        <option value="pdb">PDB</option>
    </select>

    <label for="search">Search by</label>
    <select name="search" class="select" title="Search">
        <option value="name">Name</option>
        <option value="name">SMILES</option>
        <option value="fle">Molecular Formula</option>
        <option value="mass">Monoisotopic Mass</option>
        <option value="id">Identifier</option>
    </select>

    <label for="name">Name</label>
    <input type="text" id="txt-canvas-name" class="txt-def" name="name" title="Name"/>

    <label for="smile">SMILES</label>
    <textarea id="txt-canvas-smile" class="txt-area" name="smile" title="SMILES"></textarea>

    <label for="fle">Molecular Formula</label>
    <input type="text" id="txt-canvas-fle" class="txt-def" name="fle" title="Formula"/>

    <label for="mass">Monoisotopic Mass</label>
    <input type="number" id="txt-canvas-mass" class="txt-def" name="mass" title="Monoisotopic Mass"/>
    <label for="deflection" class="lbl-block">+/-</label>
    <input type="number" id="txt-canvas-mass-deflection" class="txt-def" name="deflection" title="Deflection"/>

    <label for="id">Identifier</label>
    <input type=text class="txt-def" name="id" title="Id"/>

    <button type="button" id="button-canvas-find" class="btn-same" name="find">Find</button>
    <br/>
    <button type="button" id="button-canvas-easy-smile" class="btn-same" onclick="easy()">Canonical SMILES</button>
    <button type="button" id="btn-canvas-disintegrate" class="btn-same">Building Blocks</button>
    <button type="button" id="btn-canvas-update" class="btn-same" onclick="drawSmile()">Update</button>
    <input type="submit" class="btn-same" value="Save"/>

    </form>

</div>

<!-- Smiles Drawer -->
<script src="https://unpkg.com/smiles-drawer@1.0.10/dist/smiles-drawer.min.js"></script>
<!--<script src="--><? //= AssetHelper::jsUrl() . "smiles-drawer.js" ?><!--"></script>-->

<script src="<?= AssetHelper::jsUrl() . "canvas.js" ?>"></script>
