<?php

?>

<div id="div-full">
    <article>
        <h2>Import</h2>

        <?= $error ?>



        <?php echo form_open_multipart('import/upload'); ?>
        <input type="file" name="userfile" size="20"/>
        <br/><br/>
        <input type="submit" value="Upload"/>
        </form>

    </article>
</div>
