<div id="div-canvas">
    <canvas id="canvas-main"></canvas>
</div>
<div id="div-right">

    <?php echo validation_errors(); ?>

    <?php echo form_open('land/index', array('class' => 'form')); ?>

    <label for="search">Search in</label>
    <select name="search" class="select">
        <option value="pubchem">PubChem</option>
        <option value="chemspider">ChemSpider</option>
        <option value="norine">Norine</option>
        <option value="pdb">PDB</option>
    </select>

    <label for="name">Name</label>
    <input type="text" id="txt-canvas-name" class="txt-def" name="name"/>
    <button class="btn-find" onclick="find()">Find</button>

    <label for="smile">SMILE</label>
    <textarea id="txt-canvas-smile" class="txt-area" name="smile"></textarea>
    <button class="btn-find" onclick="find()">Find</button>

    <label for="fle">Formula</label>
    <input type="text" id="txt-canvas-fle" class="txt-def" name="fle"/>
    <button class="btn-find" onclick="find()">Find</button>

    <label for="id">ID</label>
    <input type=text class="txt-def" name="id"/>
    <button class="btn-find" onclick="find()">Find</button>

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
