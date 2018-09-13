<div id="div-canvas">
    <canvas id="canvas-main"></canvas>
</div>
<div id="div-right">

    <fieldset>
        <label for="search"><span>Search in</span><select name="search" class="select">
                <option value="pubchem">PubChem</option>
                <option value="chemspider">ChemSpider</option>
                <option value="norine">Norine</option>
                <option value="pdb">PDB</option>
            </select></label>
    </fieldset>

    <fieldset>
        <label for="name"><span>Name</span><input type="text" id="txt-canvas-name" class="txt-def" name="name"/></label>
        <button onclick="find()">FIND</button>
        <input type="text" id="txt-canvas-sug-name" class="txt-def none" title="Suggested name"/>
        <button id="btn-canvas-translate-name" class="none" onclick="translateName()"><i class="fa fa-arrow-left"></i> <i class="fa fa-arrow-left"></i></button>
    </fieldset>

    <fieldset>
        <label for="smile">
            <span>SMILE</span>
            <textarea id="txt-canvas-smile" class="txt-area" name="smile"></textarea>
        </label>
        <button onclick="find()">FIND</button>
    </fieldset>

    <fieldset>
        <label><span>&nbsp;</span></label>
        <textarea id="txt-canvas-sug-smile" class="txt-area none" title="Suggested SMILE"></textarea>
        <button id="btn-canvas-translate-smile" class="none" onclick="translateSmile()"><i class="fa fa-arrow-up"></i> <i class="fa fa-arrow-up"></i></button>
    </fieldset>

    <fieldset>
        <label for="fle"><span>Formula</span><input type="text" id="txt-canvas-fle" class="txt-def" name="fle"/></label>
        <button onclick="find()">FIND</button>
        <input type="text" id="txt-canvas-sug-fle" class="txt-def none" title="Suggested formula"/>
        <button id="btn-canvas-translate-fle" class="none" onclick="translateFle()"><i class="fa fa-arrow-left"></i> <i class="fa fa-arrow-left"></i></button>
    </fieldset>

    <fieldset>
        <label for="id"><span>ID</span><input type=text class="txt-def" name="id"/></label>
        <button onclick="find()">FIND</button>
    </fieldset>

    <fieldset>
        <label><span>&nbsp;</span></label>
        <button id="button-canvas-easy-smile" class="btn-same" onclick="easy()">Easy SMILE</button>
        <button id="btn-canvas-disintegrate" class="btn-same">Disintegrate</button>
    </fieldset>

    <fieldset>
        <label><span>&nbsp;</span></label>
        <button id="btn-canvas-translate-all" class="btn-same" onclick="translateAll()"><i class="fa fa-arrow-left"></i> <i class="fa fa-arrow-left"></i> <i class="fa fa-arrow-left"></i></button>
    </fieldset>

    <fieldset>
        <label><span>&nbsp;</span></label>
        <input type="submit" class="btn-same" value="Save to DB"/>
    </fieldset>

</div>
