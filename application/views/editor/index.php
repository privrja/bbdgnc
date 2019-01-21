<script src="<?= AssetHelper::jsJsme() ?>"></script>

<script>
    /**
     * This function will be called after the JavaScriptApplet code has been loaded.
     */
    function jsmeOnLoad() {
        jsmeApplet = new JSApplet.JSME("jsme_container", "600px", "600px");
        jsmeApplet.readGenericMolecularInput('<?= $smile ?>');
    }

    function getSmiles() {
        let smile = jsmeApplet.nonisomericSmiles();
        let smiles = '<?= $smiles ?>';
        let acSmiles = smiles.split(',');
        let index = '<?= $editorInput ?>';
        acSmiles[index] = smile;
        redirectWithData({smiles: acSmiles, blocks: 'Blocks'});
    }

    function redirectWithData(data) {
        let form = document.createElement('form');
        document.body.appendChild(form);
        form.method = 'post';
        form.action = 'land/form';
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
