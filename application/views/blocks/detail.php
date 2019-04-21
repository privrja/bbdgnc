<?php

use Bbdgnc\Finder\Enum\ServerEnum;

?>


<div id="div-full">

    <article>
        <h1>Block Detail</h1>

        <div>
            <a href="<?= site_url("block/edit/" . $block['id']) ?>">Edit</a>
        </div>
        <div>Name: <?= $block['name']; ?></div>
        <div>Acronym <?= $block['acronym']; ?></div>
        <div>Residue Formula: <?= $block['residue'] ?></div>
        <div>Neutral loss: <?= $block['losses'] ?></div>
        <div>Residue Mass: <?= $block['mass'] ?></div>
        <div>SMILES: <?= $block['smiles'] ?></div>
        <div>Reference:
            <?php if ($block['database'] !== null && !empty($block['identifier'])): ?>
                <a target="_blank"
                   href=<?= ServerEnum::getLink($block['database'], $block['identifier']) ?>>
                    <?= ServerEnum::$allValues[$block['database']]; ?></a>
            <?php endif; ?>
        </div>
    </article>
</div>

