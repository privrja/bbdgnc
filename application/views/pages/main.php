<div id="div-canvas">
    <canvas id="canvas-main"></canvas>
</div>
<div id="div-right">

    <?= validation_errors(); ?>

    <?= form_open('land/index', array('class' => 'form')); ?>

    <label for="search">Search in</label>
    <select name="search" class="select" title="Search">
        <option value="pubchem">PubChem</option>
        <option value="chemspider">ChemSpider</option>
        <option value="norine">Norine</option>
        <option value="pdb">PDB</option>
    </select>

    <label for="name">Name</label>
    <input type="text" id="txt-canvas-name" class="txt-def" name="name" title="Name"/>
    <input type="submit" class="btn-find" name="findByName" value="Find"/>

    <label for="smile">SMILE</label>
    <textarea id="txt-canvas-smile" class="txt-area" name="smile" title="Smile"></textarea>
    <input type="submit" class="btn-find" name="findBySmile" value="Find"/>

    <label for="fle">Formula</label>
    <input type="text" id="txt-canvas-fle" class="txt-def" name="fle" title="Formula"/>
    <input type="submit" class="btn-find" name="findByFle" value="Find"/>

    <label for="id">Id</label>
    <input type=text class="txt-def" name="id" title="Id"/>
    <input type="submit" class="btn-find" name="findById" value="Find"/>

    <label>&nbsp;</label>
    <button id="button-canvas-easy-smile" class="btn-same" onclick="easy()">Canonical SMILES</button>
    <br />

    <label>&nbsp;</label>
    <button id="btn-canvas-disintegrate" class="btn-same">Generate Building Blocks</button>
    <br />

    <label>&nbsp;</label>
    <input type="submit" class="btn-same" value="Save to DB"/>

    </form>

</div>

<!-- Smiles Drawer -->
<script src="https://unpkg.com/smiles-drawer@1.0.10/dist/smiles-drawer.min.js"></script>
<!--<script src="--><?//= AssetHelper::jsUrl() . "smiles-drawer.js" ?><!--"></script>-->

<script src="<?= AssetHelper::jsUrl() . "canvas.js" ?>"></script>
