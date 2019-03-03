<?php

use Bbdgnc\Finder\Enum\ServerEnum;

?>


<div id="div-full">

    <article>
        <h2>Blocks</h2>

        <a href="<?= site_url("block/new") ?>">
            Add New Block
        </a>

        <div class="table t">
            <div class="thead t">
                <div class="tr t">
                    <div class="td">Name</div>
                    <div class="td">Acronym</div>
                    <div class="td">Residue Formula</div>
                    <div class="td">Neutral loss</div>
                    <div class="td">Residue Mass</div>
                    <div class="td">SMILES</div>
                    <div class="td">Reference</div>
                    <div class="td">Editor</div>
                </div>
            </div>
            <div class="tbody">
                <?php foreach ($blocks as $block): ?>
                    <div class='tr'>
                        <div class="td">
                            <?= $block['name']; ?>
                        </div>
                        <div class="td">
                            <a href="<?= site_url("block/detail/" . $block['id']) ?>">
                                <?= $block['acronym']; ?>
                            </a>
                        </div>
                        <div class="td">
                            <?= $block['residue'] ?>
                        </div>
                        <div class="td">
                        </div>
                        <div class="td">
                            <?= $block['mass'] ?>
                        </div>
                        <div class="td">
                            <?= $block['smiles'] ?>
                        </div>
                        <div class="td">
                            <?php if ($block['database'] !== null && !empty($block['identifier'])): ?>
                                <a target="_blank"
                                   href=<?= ServerEnum::getLink($block['database'], $block['identifier']) ?>>
                                    <?= ServerEnum::$allValues[$block['database']]; ?></a>
                            <?php endif; ?>
                        </div>
                        <div class="td">
                            <a href="<?= site_url("block/edit/" . $block['id']) ?>">
                                Edit
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </article>
</div>
