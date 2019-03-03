<?php

?>


<div id="div-full">

    <article>
        <h2>Modification Detail</h2>

        <div>
            <a href="<?= site_url("modification/edit") ?>">Edit</a>
        </div>
        <div>Name: <?= $modification['name']; ?></div>
        <div>Formula: <?= $modification['formula'] ?></div>
        <div>Mass: <?= $modification['mass'] ?></div>
        <div>N-terminal: <?= $modification['nterminal'] ?></div>
        <div>C-terminal: <?= $modification['cterminal'] ?></div>
    </article>
</div>

