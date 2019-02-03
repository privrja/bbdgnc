<?php

?>


<div id="div-full">

    <article>
        <h2>Blocks</h2>

        <?php foreach ($blocks as $block): ?>

            <?= $block['name']; ?>
            <?= $block['acronym']; ?>
            <br/>

        <?php endforeach; ?>

</div>
