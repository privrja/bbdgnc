<div id="div-full">

    <article>
        <h1>Settings</h1>

        <h2>Reset database</h2>
        <?= form_open('settings/reset', array('class' => 'form')); ?>

        <input type="submit" value="Reset" name="btnReset" />
        <input type="hidden" value="delx" name="delete" />

        <?= form_close(); ?>

    </article>

</div>


