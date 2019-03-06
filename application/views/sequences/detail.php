<?php

use Bbdgnc\Finder\Enum\ServerEnum;

?>

<div id="div-full">
    <article>
        <h2>Sequence Detail</h2>

        <div>
            <a href="<?= site_url("sequence/edit/" . $sequence['id']) ?>">Edit</a>
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


        <div>
            <a href="<?= site_url("modification/add/" . $sequence['id']) ?>">Add Modification</a>
        </div>
        <?php if (isset($nModification)): ?>
            <article>
                <h2>N - Modification</h2>

                <div>
                    <a href="<?= site_url("modification/edit/" . $nModification['id']) ?>">Edit</a>
                </div>
                <div>Name: <?= $nModification['name']; ?></div>
                <div>Formula: <?= $nModification['formula'] ?></div>
                <div>Mass: <?= $nModification['mass'] ?></div>
                <div>N-terminal: <?= $nModification['nterminal'] ?></div>
                <div>C-terminal: <?= $nModification['cterminal'] ?></div>
            </article>
        <?php endif; ?>

        <?php if (isset($cModification)): ?>
            <article>
                <h2>C - Modification</h2>

                <div>
                    <a href="<?= site_url("modification/edit/" . $cModification['id']) ?>">Edit</a>
                </div>
                <div>Name: <?= $cModification['name']; ?></div>
                <div>Formula: <?= $cModification['formula'] ?></div>
                <div>Mass: <?= $cModification['mass'] ?></div>
                <div>N-terminal: <?= $cModification['nterminal'] ?></div>
                <div>C-terminal: <?= $cModification['cterminal'] ?></div>
            </article>
        <?php endif; ?>

        <?php if (isset($bModification)): ?>
            <article>
                <h2>Branch - Modification</h2>

                <div>
                    <a href="<?= site_url("modification/edit/" . $bModification['id']) ?>">Edit</a>
                </div>
                <div>Name: <?= $bModification['name']; ?></div>
                <div>Formula: <?= $bModification['formula'] ?></div>
                <div>Mass: <?= $bModification['mass'] ?></div>
                <div>N-terminal: <?= $bModification['nterminal'] ?></div>
                <div>C-terminal: <?= $bModification['cterminal'] ?></div>
            </article>
        <?php endif; ?>


        <article>
            <h2>Blocks</h2>
            <?php foreach ($blocks as $block): ?>

                <article>
                    <div>
                        <a href="<?= site_url("block/edit/" . $block['id']) ?>">Edit</a>
                        <a href="<?= site_url("block/detail/" . $block['id']) ?>">Detail</a>
                    </div>
                    <div>Name: <?= $block['name']; ?></div>
                    <div>Acronym <?= $block['acronym']; ?></div>
                    <div>Residue Formula: <?= $block['residue'] ?></div>
                    <div>Neutral loss:</div>
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
            <?php endforeach; ?>

        </article>

    </article>
</div>
