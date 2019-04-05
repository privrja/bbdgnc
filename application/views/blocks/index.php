<?php

use Bbdgnc\Enum\Front;
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
                    <div class="td sort" title="Sort" onclick="<?= "sort('" . site_url('block') . "', 'name', '" . $sort . "')" ?>">Name</div>
                    <div class="td sort" title="Sort" onclick="<?= "sort('" . site_url('block') . "', 'acronym', '". $sort . "')" ?>">Acronym</div>
                    <div class="td sort" title="Sort" onclick="<?= "sort('" . site_url('block') . "', 'residue', '" . $sort . "')" ?>">Residue Formula</div>
                    <div class="td sort" title="Sort" onclick="<?= "sort('" . site_url('block') . "', 'losses', '" . $sort . "')" ?>">Neutral loss</div>
                    <div class="td sort" title="Sort" onclick="<?= "sort('" . site_url('block') . "', 'mass', '" . $sort . "')" ?>">Residue Mass</div>
                    <div class="td sort" title="Sort" onclick="<?= "sort('" . site_url('block') . "', 'smiles', '" . $sort . "')" ?>">SMILES</div>
                    <div class="td">Reference</div>
                    <div class="td">Editor</div>
                </div>
            </div>
            <div class="tbody">
                <div class='tr'>
                    <div class="td">
                        <input type="text" placeholder="Filter by Name" accesskey="n" id="filter-name" value="<?= Front::setValue('name') ?>"/>
                    </div>
                    <div class="td">
                        <input type="text" placeholder="Filter by Acronym" accesskey="a" id="filter-acronym" value="<?= Front::setValue('acronym') ?>"/>
                    </div>
                    <div class="td">
                        <input type="text" placeholder="Filter by Residue Formula" accesskey="f" id="filter-residue" value="<?= Front::setValue('residue') ?>"/>
                    </div>
                    <div class="td">
                        <input type="text" placeholder="Filter by Neutral Losses" accesskey="l" id="filter-losses" value="<?= Front::setValue('losses') ?>"/>
                    </div>
                    <div class="td">
                        <input type="number" step="any" placeholder="Filter by Mass From" accesskey="m" id="filter-mass-from" value="<?= Front::setValue('massFrom') ?>"/>
                        <input type="number" step="any" placeholder="Filter by Mass To" id="filter-mass-to" value="<?= Front::setValue('massTo') ?>"/>
                    </div>
                    <div class="td">
                        <input type="text" placeholder="Filter by SMILES" accesskey="s" id="filter-smiles" value="<?= Front::setValue('smiles') ?>"/>
                    </div>
                    <div class="td">
                        <button onclick="window.location.href = '<?= site_url('block') ?>'">Cancel</button>
                    </div>
                    <div class="td">
                        <button onclick="<?= "filter('" . site_url('block') . "')" ?>">Filter</button>
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
