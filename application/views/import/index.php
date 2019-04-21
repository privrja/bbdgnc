<?php

use Bbdgnc\CycloBranch\Enum\ImportTypeEnum;

?>

<div id="div-full">
    <article>
        <h1>Import</h1>

        <?= $error ?>

        <?php echo form_open_multipart('import/upload'); ?>
        <label for="sel-import-type">Type</label>
        <?= form_dropdown('importType', ImportTypeEnum::$values, '1',
            'id="sel-import-type" class="select" title="Type"'); ?>
        <input type="file" name="userfile" size="20"/>
        <br/><br/>
        <input type="submit" value="Upload"/>
        </form>

    </article>
</div>
