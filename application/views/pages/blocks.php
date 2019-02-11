<?php

use Bbdgnc\Enum\Front;
use Bbdgnc\Enum\SequenceTypeEnum;
use Bbdgnc\Finder\Enum\ServerEnum;

?>

<div id="div-blocks">
    <h2 id="h-results">Building blocks</h2>

    <div id="div-sequence">
        <div id="div-top-sequence">
            <h3>Sequence</h3>
            <p>Number of blocks: <?= $blockCount ?></p>

            <label for="sel-sequence-type">Type</label>
            <?= form_dropdown(Front::CANVAS_INPUT_DATABASE, SequenceTypeEnum::$values, set_value(Front::SEQUENCE_TYPE),
                'id="sel-sequence-type" class="select" title="Type" onchange="sequenceTypeChanged()"'); ?>

            <label for="txt-sequence">Sequence</label>
            <input type="text" id="txt-sequence" name="sequence" value=""/>
        </div>

        <div class="div-modification">
            <h4>N-terminal Modification</h4>

            <label for="txt-n-modification">Name</label>
            <input type="text" id="txt-n-modification" name="nModification" value=""/>

            <label for="txt-n-formula">Formula</label>
            <input type="text" id="txt-n-formula" name="nFormula" value=""/>

            <label for="txt-n-mass">Monoisotopic Mass</label>
            <input type="text" id="txt-n-mass" name="nMass" value=""/>

            <label for="chk-n-nterminal" class="chk">N-terminal</label>
            <input type="checkbox" id="chk-n-nterminal" name="nnTerminal" value=""/>

            <label for="chk-n-cterminal" class="chk">C-terminal</label>
            <input type="checkbox" id="chk-n-cterminal" name="ncTerminal" value=""/>
        </div>

        <div class="div-modification">
            <h4>C-terminal Modification</h4>

            <label for="txt-c-modification">Name</label>
            <input type="text" id="txt-c-modification" name="cModification" value=""/>

            <label for="txt-c-formula">Formula</label>
            <input type="text" id="txt-c-formula" name="cFormula" value=""/>

            <label for="txt-c-mass">Monoisotopic Mass</label>
            <input type="text" id="txt-c-mass" name="cMass" value=""/>

            <label for="chk-c-nterminal" class="chk">N-terminal</label>
            <input type="checkbox" id="chk-c-nterminal" name="cnTerminal" value=""/>

            <label for="chk-c-cterminal" class="chk">C-terminal</label>
            <input type="checkbox" id="chk-c-cterminal" name="ccTerminal" value=""/>
        </div>

        <div class="div-modification">
            <h4>Branch Modification</h4>
            <label for="txt-b-modification">Name</label>
            <input type="text" id="txt-b-modification" name="bModification" value=""/>

            <label for="txt-b-formula">Formula</label>
            <input type="text" id="txt-b-formula" name="bFormula" value=""/>

            <label for="txt-b-mass">Monoisotopic Mass</label>
            <input type="text" id="txt-b-mass" name="bMass" value=""/>

            <label for="chk-b-nterminal" class="chk">N-terminal</label>
            <input type="checkbox" id="chk-b-nterminal" name="bnTerminal" value=""/>

            <label for="chk-b-cterminal" class="chk">C-terminal</label>
            <input type="checkbox" id="chk-b-cterminal" name="bcTerminal" value=""/>
        </div>
    </div>

    <div class="table t">
        <div class="thead t">
            <div class="tr t">
                <div class="td"></div>
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
                <div class="td">
                    <canvas id="canvas-small-<?= $block->id; ?>"
                            data-canvas-small-id="<?= $block->id ?>"
                            class="canvas-small"
                            onclick="drawOrClearLargeSmile(<?= $block->id ?>)"
                            title="<?= $block->smiles ?>">
                    </canvas>
                </div>

                <div class="td">
                    <p><?= $block->name ?></p>
                </div>

                <div class="td">
                    <p><?= $block->acronym ?></p>
                </div>

                <div class="td">
                    <p><?= $block->formula ?></p>
                </div>

                <div class="td">
                    <p><?= $block->losses ?></p>
                </div>

                <div class="td">
                    <p><?= $block->mass ?></p>
                </div>

                <div class="td">
                    <p><?= $block->smiles ?></p>
                </div>

                <div class="td">
                    <a target="_blank"
                       href=<?= ServerEnum::getLink($block->reference->server, $block->reference->identifier) ?>>
                        <?= ServerEnum::$allValues[$block->reference->server]; ?></a>
                </div>

                <input type="hidden" name="<?= Front::BLOCK_SMILE ?>"
                       id="hidden-canvas-small-<?= $block->id ?>"
                       value="<?= $block->smiles ?>"/>

                <input type="hidden" name="<?= Front::BLOCK_IDENTIFIER ?>" value="<?= $block->id ?>"/>
                <input type="hidden" name="<?= Front::BLOCK_NAME ?>" value="<?= $block->name ?>"/>
                <input type="hidden" name="<?= Front::BLOCK_ACRONYM ?>" value="<?= $block->acronym ?>"/>
                <input type="hidden" name="<?= Front::BLOCK_FORMULA ?>" value="<?= $block->formula ?>"/>
                <input type="hidden" name="<?= Front::BLOCK_MASS ?>" value="<?= $block->mass ?>"/>
                <input type="hidden" name="<?= Front::BLOCK_NEUTRAL_LOSSES ?>" value="<?= $block->losses ?>"/>
                <input type="hidden" name="<?= Front::BLOCK_REFERENCE ?>" value="<?= $block->reference->identifier ?>"/>
                <input type="hidden" name="<?= Front::BLOCK_REFERENCE_SERVER ?>"
                       value="<?= $block->reference->server ?>"/>
                <input type="hidden" name="<?= Front::BLOCK_COUNT ?>" value="<?= $blockCount ?>"/>

                <div class="td">
                    <input type="submit" title="SMILES Editor" name="editor" value="Edit"/>
                </div>

                <input type="hidden" name="<?= Front::CANVAS_INPUT_DATABASE ?>" value="<?= $database ?>"/>
                <input type="hidden" name="<?= Front::CANVAS_INPUT_SEARCH_BY ?>" value="<?= $search ?>"/>
                <input type="hidden" name="<?= Front::CANVAS_INPUT_NAME ?>" value="<?= $name ?>"/>
                <input type="hidden" name="<?= Front::CANVAS_INPUT_SMILE ?>" value="<?= $smile ?>"/>
                <input type="hidden" name="<?= Front::CANVAS_INPUT_FORMULA ?>" value="<?= $formula ?>"/>
                <input type="hidden" name="<?= Front::CANVAS_INPUT_MASS ?>" value="<?= $mass ?>"/>
                <input type="hidden" name="<?= Front::CANVAS_INPUT_DEFLECTION ?>" value="<?= $deflection ?>"/>
                <input type="hidden" name="<?= Front::CANVAS_INPUT_IDENTIFIER ?>" value="<?= $identifier ?>"/>
                </form>
            <?php endforeach; ?>
        </div>
    </div>
</div>

</div>

<canvas id="canvas-large" onclick="clearLargeCanvas()"></canvas>

