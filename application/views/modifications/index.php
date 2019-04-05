<?php

use Bbdgnc\Enum\Front; ?>


<div id="div-full">

    <article>
        <h2>Modifications</h2>
        <a href="<?= site_url("modification/new") ?>">Add New Modification</a>
        <div class="table t">
            <div class="thead t">
                <div class="tr t">
                    <div class="td sort" title="Sort"
                         onclick="<?= "sort('" . site_url('modification') . "', 'name', '" . $sort . "')" ?>">
                        Name
                    </div>
                    <div class="td sort" title="Sort"
                         onclick="<?= "sort('" . site_url('modification') . "', 'formula', '" . $sort . "')" ?>">
                        Summary Formula
                    </div>
                    <div class="td sort" title="Sort"
                         onclick="<?= "sort('" . site_url('modification') . "', 'mass', '" . $sort . "')" ?>">
                        Monoisotopic Mass
                    </div>
                    <div class="td sort" title="Sort"
                         onclick="<?= "sort('" . site_url('modification') . "', 'nterminal', '" . $sort . "')" ?>">
                        N-terminal
                    </div>
                    <div class="td sort" title="Sort"
                         onclick="<?= "sort('" . site_url('modification') . "', 'cterminal', '" . $sort . "')" ?>">
                        C-terminal
                    </div>
                    <div class="td">Editor</div>
                </div>
            </div>
            <div class="tbody">
                <div class="td">
                    <input type="text" placeholder="Filter by Name" accesskey="n" id="filter-name"
                           value="<?= Front::setValue('name') ?>"/>
                </div>
                <div class="td">
                    <input type="text" placeholder="Filter by Summary Formula" accesskey="f" id="filter-formula"
                           value="<?= Front::setValue('formula') ?>"/>
                </div>
                <div class="td">
                    <input type="text" placeholder="Filter by Mass From" accesskey="m" id="filter-mass-from"
                           value="<?= Front::setValue('massFrom') ?>"/>
                    <input type="text" placeholder="Filter by Mass To" id="filter-mass-to"
                           value="<?= Front::setValue('massTo') ?>"/>
                </div>
                <div class="td">
                    <input type="text" placeholder="Filter by N-terminal" accesskey="n" id="filter-nterminal"
                           value="<?= Front::setValue('nterminal') ?>"/>
                </div>
                <div class="td">
                    <input type="text" placeholder="Filter by C-terminal" accesskey="c" id="filter-cterminal"
                           value="<?= Front::setValue('cterminal') ?>"/>
                </div>
                <div class="td">
                    <button onclick="window.location.href = '<?= site_url('modification') ?>'">Cancel</button>
                </div>
                <div class="td">
                    <button onclick="<?= "filter('" . site_url('modification') . "')" ?>">Filter</button>
                </div>
                <?php foreach ($modifications as $modification): ?>
                    <div class='tr'>
                        <div class="td">
                            <a href="<?= site_url("modification/detail/" . $modification['id']) ?>">
                                <?= $modification['name']; ?>
                            </a>
                        </div>
                        <div class="td">
                            <?= $modification['formula'] ?>
                        </div>
                        <div class="td">
                            <?= $modification['mass'] ?>
                        </div>
                        <div class="td">
                            <?= $modification['nterminal'] ?>
                        </div>
                        <div class="td">
                            <?= $modification['cterminal'] ?>
                        </div>
                        <div class="td">
                            <a href="<?= site_url("modification/edit/" . $modification['id']) ?>">
                                Edit
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </article>
</div>
