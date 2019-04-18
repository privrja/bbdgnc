<?php

use Bbdgnc\CycloBranch\Enum\ResetTypeEnum;

?>
<div id="div-full">

    <article>
        <h1>Settings</h1>

        <h2>Reset database</h2>
        <?= form_open('settings/reset', array('class' => 'form')); ?>
        <input type="hidden" value="delx" name="delete"/>
        <div id="">
            <?= form_dropdown('resetType', ResetTypeEnum::$values, '1') ?>
        </div>
        <div>
            <input type="submit" value="Reset" name="btnReset"/>
        </div>
        <?= form_close(); ?>

    </article>

</div>
