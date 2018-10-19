<div id="div-full">

    <article>
        <h1>Settings</h1>

        <h2>Source</h2>

        <?= validation_errors(); ?>
        <?= form_open('settings/colors', array('class' => 'form')); ?>


        <label for="source">Default source</label>
        <select name="source" class="select" title="Default source to search">
            <option value="pubchem">PubChem</option>
            <option value="chemspider">ChemSpider</option>
            <option value="norine">Norine</option>
            <option value="pdb">PDB</option>
        </select>

        <input type="submit" name="btnSource" value="Change source" />
        </form>
    </article>

</div>


