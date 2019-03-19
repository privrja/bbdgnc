<?php

?>


<div id="div-full">

    <article>
        <h2>Modifications</h2>
        <a href="<?= site_url("modification/new") ?>">Add New Modification</a>
        <div class="table t">
            <div class="thead t">
                <div class="tr t">
                    <div class="td">Name</div>
                    <div class="td">Summary Formula</div>
                    <div class="td">Monoisotopic Mass</div>
                    <div class="td">N-terminal</div>
                    <div class="td">C-terminal</div>
                    <div class="td">Editor</div>
                </div>
            </div>
            <diVdv class="tbody">
                <div class="td">
                    <input type="text" placeholder="Filter by Name" accesskey="n" id="filter-name"/>
                </div>
                <div class="td">
                    <input type="text" placeholder="Filter by Summary Formula" accesskey="f" id="filter-formula"/>
                </div>
                <div class="td">
                    <input type="text" placeholder="Filter by Mass From" accesskey="m" id="filter-mass-from"/>
                    <input type="text" placeholder="Filter by Mass To" id="filter-mass-to"/>
                </div>
                <div class="td">
                    <input type="text" placeholder="Filter by N-terminal" accesskey="n" id="filter-nterminal"/>
                </div>
                <div class="td">
                    <input type="text" placeholder="Filter by C-terminal" accesskey="c" id="filter-cterminal"/>
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

