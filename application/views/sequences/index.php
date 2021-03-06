<?php

use Bbdgnc\Enum\Front;
use Bbdgnc\Enum\SequenceTypeEnum;
use Bbdgnc\Finder\Enum\ServerEnum;

?>

<div id="div-full">

    <article>
        <h1>Sequences</h1>
        <a href="<?= site_url("sequence/new") ?>">Add New Sequence</a>
        <div class="table t">
            <div class="thead t">
                <div class="tr t">
                    <div class="td sort" title="Sort" onclick="<?= "sort('" . site_url('sequence') . "', 'type', '" . $sort . "')" ?>">Type <i class="fa fa-sort type"></i></div>
                    <div class="td sort" title="Sort" onclick="<?= "sort('" . site_url('sequence') . "', 'name', '" . $sort . "')" ?>">Name <i class="fa fa-sort name"></i></div>
                    <div class="td sort" title="Sort" onclick="<?= "sort('" . site_url('sequence') . "', 'formula', '" . $sort . "')" ?>">Summary Formula <i class="fa fa-sort formula"></i></div>
                    <div class="td sort" title="Sort" onclick="<?= "sort('" . site_url('sequence') . "', 'mass', '" . $sort . "')" ?>">Monoisotopic Mass <i class="fa fa-sort mass"></i></div>
                    <div class="td sort" title="Sort" onclick="<?= "sort('" . site_url('sequence') . "', 'sequence', '" . $sort . "')" ?>">Sequence <i class="fa fa-sort sequence"></i></div>
                    <div class="td">N-terminal</div>
                    <div class="td">C-terminal</div>
                    <div class="td">Branch</div>
                    <div class="td">Reference</div>
                    <div class="td">Editor</div>
                    <div class="td">Delete</div>
                </div>
            </div>
            <div class="tbody">
                <div class='tr'>
                    <div class="td">
                        <input type="text" placeholder="Filter by Type" accesskey="t" id="filter-type" value="<?= Front::setValue('type') ?>"/>
                    </div>
                    <div class="td">
                        <input type="text" placeholder="Filter by Name" accesskey="n" id="filter-name" value="<?= Front::setValue('name') ?>"/>
                    </div>
                    <div class="td">
                        <input type="text" placeholder="Filter by Summary Formula" accesskey="f" id="filter-formula" value="<?= Front::setValue('formula') ?>"/>
                    </div>
                    <div class="td">
                        <input type="number" step="any" placeholder="Filter by Mass From" accesskey="m" id="filter-mass-from" value="<?= Front::setValue('massFrom') ?>"/>
                        <input type="number" step="any" placeholder="Filter by Mass To" id="filter-mass-to" value="<?= Front::setValue('massTo') ?>"/>
                    </div>
                    <div class="td">
                        <input type="text" placeholder="Filter by Sequence" accesskey="s" id="filter-sequence" value="<?= Front::setValue('sequence') ?>"/>
                    </div>
                    <div class="td">
                    </div>
                    <div class="td">
                    </div>
                    <div class="td">
                    </div>
                    <div class="td">
                        <button onclick="window.location.href = '<?= site_url('sequence') ?>'">Cancel</button>
                    </div>
                    <div class="td">
                        <button onclick="<?= "filter('" . site_url('sequence') . "')" ?>">Filter</button>
                    </div>
                </div>
                <?php foreach ($sequences as $sequence): ?>
                    <div class='tr'>
                        <div class="td">
                            <?= SequenceTypeEnum::$values[$sequence['type']]; ?>
                        </div>
                        <div class="td">
                            <a href="<?= site_url("sequence/detail/" . $sequence['id']) ?>">
                                <?= $sequence['name']; ?>
                            </a>
                        </div>
                        <div class="td">
                            <?= $sequence['formula'] ?>
                        </div>
                        <div class="td">
                            <?= $sequence['mass'] ?>
                        </div>
                        <div class="td">
                            <?= $sequence['sequence'] ?>
                        </div>
                        <div class="td">
                            <?= $sequence['nname'] ?>
                        </div>
                        <div class="td">
                            <?= $sequence['cname'] ?>
                        </div>
                        <div class="td">
                            <?= $sequence['bname'] ?>
                        </div>
                        <div class="td">
                            <?php if ($sequence['database'] !== null && !empty($sequence['identifier'])): ?>
                                <a target="_blank"
                                   href=<?= ServerEnum::getLink($sequence['database'], $sequence['identifier']) ?>>
                                    <?= ServerEnum::$allValues[$sequence['database']]; ?></a>
                            <?php endif; ?>
                        </div>
                        <div class="td">
                            <a href="<?= site_url("sequence/edit/" . $sequence['id']) ?>">
                                Edit
                            </a>
                        </div>
                        <div class="td">
                            <a href="<?= site_url("sequence/delete/" . $sequence['id']) ?>">
                                Delete
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </article>
</div>
