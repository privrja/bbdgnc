<?php

use Bbdgnc\Finder\Enum\ServerEnum;

?>

<div id="div-full">
    <article>
        <h2>Sequence Detail</h2>

        <div>
            <a href="<?= site_url("sequence/edit") ?>">Edit</a>
        </div>
        <div>Type: <?= $sequence['type']; ?></div>
        <div>Name: <?= $sequence['name']; ?></div>
        <div>Formula: <?= $sequence['formula'] ?></div>
        <div>Mass: <?= $sequence['mass'] ?></div>
        <div>SMILES: <?= $sequence['smiles'] ?></div>
        <div>Reference:
            <?php if ($sequence['database'] !== null && !empty($sequence['identifier'])): ?>
                <a target="_blank"
                   href=<?= ServerEnum::getLink($sequence['database'], $sequence['identifier']) ?>>
                    <?= ServerEnum::$allValues[$sequence['database']]; ?></a>
            <?php endif; ?>
        </div>
    </article>
</div>
