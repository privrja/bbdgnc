<?php

use Bbdgnc\Finder\Enum\ServerEnum;

?>


<div id="div-full">

    <article>
        <h2>Blocks</h2>

        <a href="<?= site_url("block/new") ?>">Add New Block</a>
        <br/>
        <a href="<?= site_url("block/merge") ?>">Merge by Formula</a>

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
                <div class='tr'>
                    <div class="td">
                        <input type="text" placeholder="Filter by Name" accesskey="n"/>
                    </div>
                    <div class="td">
                        <input type="text" placeholder="Filter by Acronym" accesskey="a"/>
                    </div>
                    <div class="td">
                        <input type="text" placeholder="Filter by Residue Formula" accesskey="f"/>
                    </div>
                    <div class="td">
                        <input type="text" placeholder="Filter by Neutral Losses" accesskey="l"/>
                    </div>
                    <div class="td">
                        <input type="text" placeholder="Filter by Mass From" accesskey="m"/>
                        <input type="text" placeholder="Filter by Mass To"/>
                    </div>
                    <div class="td">
                        <input type="text" placeholder="Filter by SMILES" accesskey="s"/>
                    </div>
                    <div class="td">
                        <button onclick="cancelFilterBlock()">Cancel</button>
                    </div>
                    <div class="td">
                        <button onclick="filterBlock()">Filter</button>
                    </div>
                </div>
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
                            <?= $block['losses'] ?>
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
        <p><?= $links; ?></p>
    </article>
</div>
