<?php

use Bbdgnc\Enum\SequenceTypeEnum;

?>

<div id="div-full">

    <article>
        <h2>Sequences</h2>
        <div class="table t">
            <div class="thead t">
                <div class="tr t">
                    <div class="td">Type</div>
                    <div class="td">Name</div>
                    <div class="td">Summary Formula</div>
                    <div class="td">Monoisotopic Mass</div>
                    <div class="td">Sequence</div>
                    <div class="td">SMILES</div>
                    <div class="td">N-terminal</div>
                    <div class="td">C-terminal</div>
                    <div class="td">Branch</div>
                    <div class="td">Reference</div>
                    <div class="td">Editor</div>
                </div>
            </div>
            <div class="tbody">
                <?php foreach ($sequences as $sequence): ?>
                    <?= form_open('land/block', array('class' => 'tr')); ?>
                    <div class="td">
                        <?= SequenceTypeEnum::$values[$sequence['type']]; ?>
                    </div>
                    <div class="td">
                        <?= $sequence['name']; ?>
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
                        <?= $sequence['n_modification_id'] ?>
                    </div>
                    <div class="td">
                        <?= $sequence['c_modification_id'] ?>
                    </div>
                    <div class="td">
                        <?= $sequence['b_modification_id'] ?>
                    </div>
                    </form>
                <?php endforeach; ?>
            </div>
        </div>
    </article>
</div>
