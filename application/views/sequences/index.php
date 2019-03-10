<?php

use Bbdgnc\Enum\SequenceTypeEnum;
use Bbdgnc\Finder\Enum\ServerEnum;

?>

<div id="div-full">

    <article>
        <h2>Sequences</h2>
        <a href="<?= site_url("sequence/new") ?>">Add New Sequence</a>
        <div class="table t">
            <div class="thead t">
                <div class="tr t">
                    <div class="td">Type</div>
                    <div class="td">Name</div>
                    <div class="td">Summary Formula</div>
                    <div class="td">Monoisotopic Mass</div>
                    <div class="td">Sequence</div>
                    <div class="td">N-terminal</div>
                    <div class="td">C-terminal</div>
                    <div class="td">Branch</div>
                    <div class="td">Reference</div>
                    <div class="td">Editor</div>
                </div>
            </div>
            <div class="tbody">
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
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </article>
</div>
