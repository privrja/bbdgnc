<?php

use Bbdgnc\Enum\Front;
use Bbdgnc\Enum\SequenceTypeEnum;

?>

<div id="div-blocks">
    <h2 id="h-results">Building blocks</h2>

    <div id="div-sequence">
        <p>Number of blocks: <?= $blockCount ?></p>

        <label for="sel-sequence-type">Type</label>
        <?= form_dropdown(Front::CANVAS_INPUT_DATABASE, SequenceTypeEnum::$values, set_value(Front::SEQUENCE_TYPE),
            'id="sel-sequence-type" class="select" title="Type"'); ?>

        <label for="txt-sequence">Sequence</label>
        <input type="text" id="txt-sequence" name="sequence" value="" />

        <label for="txt-n-modification">N-terminal Modification</label>
        <input type="text" id="txt-n-modification" name="nModification" value="" />

        <label for="txt-c-modification">C-terminal Modification</label>
        <input type="text" id="txt-c-modification" name="cModification" value="" />

        <label for="txt-branch-modification">Branch Modification</label>
        <input type="text" id="txt-branch-modification" name="branchModification" value="" />

    </div>

    <div class="table t">
        <div class="thead t">
            <div class="tr t">
                <div class="td"></div>
                <div class="td">Name</div>
                <div class="td">Acronym</div>
                <div class="td">Formula</div>
                <div class="td">Neutral loss</div>
                <div class="td">Mass</div>
                <div class="td">SMILES</div>
                <div class="td">Reference</div>
                <div class="td">Editor</div>
            </div>
        </div>
        <div class="tbody">
            <?php foreach ($blocks as $block): ?>

                <?= form_open('land/block', array('class' => 'tr')); ?>
                <div class="td">
                    <canvas id="canvas-small-<?= $block->id; ?>"
                            data-canvas-small-id="<?= $block->id ?>"
                            class="canvas-small"
                            onclick="drawOrClearLargeSmile(<?= $block->id ?>)"
                            title="<?= $block->smiles ?>">
                    </canvas>
                </div>

                <div class="td">
                    <input type="text" size="20" name="<?= Front::BLOCK_NAME ?>" value="<?= $block->name ?>" />
                </div>

                <div class="td">
                    <input type="text" size="20" name="<?= Front::BLOCK_ACRONYM ?>" value="<?= $block->acronym ?>" />
                </div>

                <div class="td">
                    <input type="text" size="20" name="<?= Front::BLOCK_FORMULA ?>" value="<?= $block->formula ?>" />
                </div>

                <div class="td">
                    <input type="text" name="<?= Front::BLOCK_SMILE ?>"
                           id="hidden-canvas-small-<?= $block->id ?>"
                           value="<?= $block->smiles ?>"
                           oninput="drawSmallSmile(<?= $block->id ?>)"/>
                </div>

                <input type="hidden" name="<?= Front::BLOCK_IDENTIFIER ?>" value="<?= $block->id ?>" />
                <input type="hidden" name="<?= Front::BLOCK_COUNT ?>" value="<?= $blockCount ?>" />

                <div class="td">
                    <input type="submit" title="SMILES Editor" name="editor" value="Edit" />
                </div>

                <input type="hidden" name="<?= Front::CANVAS_INPUT_DATABASE ?>" value="<?= $database ?>" />
                <input type="hidden" name="<?= Front::CANVAS_INPUT_SEARCH_BY ?>" value="<?= $search ?>" />
                <input type="hidden" name="<?= Front::CANVAS_INPUT_NAME ?>" value="<?= $name ?>" />
                <input type="hidden" name="<?= Front::CANVAS_INPUT_SMILE ?>" value="<?= $smile ?>" />
                <input type="hidden" name="<?= Front::CANVAS_INPUT_FORMULA ?>" value="<?= $formula ?>" />
                <input type="hidden" name="<?= Front::CANVAS_INPUT_MASS ?>" value="<?= $mass ?>" />
                <input type="hidden" name="<?= Front::CANVAS_INPUT_DEFLECTION ?>" value="<?= $deflection ?>" />
                <input type="hidden" name="<?= Front::CANVAS_INPUT_IDENTIFIER ?>" value="<?= $identifier ?>" />
                </form>
            <?php endforeach; ?>
        </div>
    </div>
</div>

</div>

<canvas id="canvas-large" onclick="clearLargeCanvas()"></canvas>

