<?php

?>


<div id="div-full">

    <article>
        <h2>Blocks</h2>
        <div class="table t">
            <div class="thead t">
                <div class="tr t">
                    <div class="td">Name</div>
                    <div class="td">Summary Formula</div>
                    <div class="td">Monoisotopic Mass</div>
                    <div class="td">N-terminal</div>
                    <div class="td">C-terminal</div>
                    <div class="td">Reference</div>
                    <div class="td">Editor</div>
                </div>
            </div>
            <div class="tbody">
                <?php foreach ($modifications as $modification): ?>
                    <?= form_open('land/block', array('class' => 'tr')); ?>
                    <div class="td">
                        <?= $modification['name']; ?>
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
                    </form>
                <?php endforeach; ?>
            </div>
        </div>
    </article>
</div>

