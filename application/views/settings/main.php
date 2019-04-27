<?php

use Bbdgnc\CycloBranch\Enum\ResetTypeEnum;

?>
<div id="div-full">

    <article>
        <h1>Settings</h1>
        <br/>
        <?= $errors ?>

        <h2>Reset database</h2>
        <?= form_open('settings/reset', array('class' => 'form')); ?>
        <input type="hidden" value="delx" name="delete"/>
        <div id="">
            <?= form_dropdown('resetType', ResetTypeEnum::$values, '2') ?>
        </div>
        <div>
            <input type="submit" value="Reset" name="btnReset"/>
        </div>
        <?= form_close(); ?>

        <h2>Remove uploads & database</h2>
        <?= form_open('settings/remove', array('class' => 'form')); ?>
        <input type="hidden" value="remx" name="remove"/>
        <div>
            <input type="submit" value="Remove" name="btnRemove"/>
        </div>
        <?= form_close(); ?>

    </article>

</div>
