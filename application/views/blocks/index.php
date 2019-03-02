<?php

?>


<div id="div-full">

    <article>
        <h2>Blocks</h2>
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
                    <?= form_open('land/block', array('class' => 'tr')); ?>
                    <!--            --><? //= var_dump($block); ?><!--s-->
                    <div class="td">
                        <?= $block['name']; ?>
                    </div>
                    <div class="td">
                        <?= $block['acronym']; ?>
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
                        <?= $block['identifier'] ?>
                    </div>
                    <!--                --><? //= var_dump($block) ?>
                    </form>
                <?php endforeach; ?>
            </div>
        </div>
    </article>
</div>
