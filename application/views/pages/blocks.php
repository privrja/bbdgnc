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
            <?= form_dropdown(Front::SEQUENCE_TYPE, SequenceTypeEnum::$values, $sequenceType,
                'id="sel-sequence-type" class="select" title="Type" onchange="sequenceTypeChanged()"'); ?>

            <label for="txt-sequence">Sequence</label>
            <input type="text" id="txt-sequence" name="sequence" value="<?= $sequence ?>"/>
        </div>

        <div class="div-modification">
            <h4>N-terminal Modification</h4>

            <label for="sel-n-modification">Select Modification</label>
            <?= form_dropdown(Front::N_MODIFICATION_SELECT, $modifications, set_value(Front::N_MODIFICATION_SELECT, '0'),
                'id="sel-n-modification" class="select" title="Modification"'); ?>

            <div id="div-n-modification">
                <label for="txt-n-modification">Name</label>
                <input type="text" id="txt-n-modification" name="nModification" value="<?= $nModification ?>"/>

                <label for="txt-n-formula">Formula</label>
                <input type="text" id="txt-n-formula" name="nFormula" value="<?= $nFormula ?>"/>

                <label for="txt-n-mass">Monoisotopic Mass</label>
                <input type="text" id="txt-n-mass" name="nMass" value="<?= $nMass ?>"/>

                <label for="chk-n-nterminal" class="chk">N-terminal</label>
                <input type="checkbox" id="chk-n-nterminal" name="nnTerminal" <?= Front::checked($nTerminalN) ?> />

                <label for="chk-n-cterminal" class="chk">C-terminal</label>
                <input type="checkbox" id="chk-n-cterminal" name="ncTerminal" <?= Front::checked($nTerminalC) ?> />
            </div>
        </div>

        <div class="div-modification">
            <h4>C-terminal Modification</h4>

            <label for="sel-c-modification">Select Modification</label>
            <?= form_dropdown(Front::C_MODIFICATION_SELECT, $modifications, set_value(Front::C_MODIFICATION_SELECT, '0'),
                'id="sel-c-modification" class="select" title="Modification"'); ?>

            <div id="div-c-modification">
                <label for="txt-c-modification">Name</label>
                <input type="text" id="txt-c-modification" name="cModification" value="<?= $cModification ?>"/>

                <label for="txt-c-formula">Formula</label>
                <input type="text" id="txt-c-formula" name="cFormula" value="<?= $cFormula ?>"/>

                <label for="txt-c-mass">Monoisotopic Mass</label>
                <input type="text" id="txt-c-mass" name="cMass" value="<?= $cMass ?>"/>

                <label for="chk-c-nterminal" class="chk">N-terminal</label>
                <input type="checkbox" id="chk-c-nterminal" name="cnTerminal" <?= Front::checked($cTerminalN) ?>/>

                <label for="chk-c-cterminal" class="chk">C-terminal</label>
                <input type="checkbox" id="chk-c-cterminal" name="ccTerminal" <?= Front::checked($cTerminalC) ?> />
            </div>
        </div>

        <div class="div-modification">
            <h4>Branch Modification</h4>

            <label for="sel-b-modification">Select Modification</label>
            <?= form_dropdown(Front::B_MODIFICATION_SELECT, $modifications, set_value(Front::B_MODIFICATION_SELECT, '0'),
                'id="sel-b-modification" class="select" title="Modification"'); ?>

            <div id="div-b-modification">
                <label for="txt-b-modification">Name</label>
                <input type="text" id="txt-b-modification" name="bModification" value="<?= $bModification ?>" disabled/>

                <label for="txt-b-formula">Formula</label>
                <input type="text" id="txt-b-formula" name="bFormula" value="<?= $bFormula ?>" disabled/>

                <label for="txt-b-mass">Monoisotopic Mass</label>
                <input type="text" id="txt-b-mass" name="bMass" value="<?= $bMass ?>" disabled/>

                <label for="chk-b-nterminal" class="chk">N-terminal</label>
                <input type="checkbox" id="chk-b-nterminal" name="bnTerminal" <?= Front::checked($bTerminalN) ?>
                       disabled/>

                <label for="chk-b-cterminal" class="chk">C-terminal</label>
                <input type="checkbox" id="chk-b-cterminal" name="bcTerminal" <?= Front::checked($bTerminalC) ?>
                       disabled/>
            </div>
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

                <?= form_open('land/block', array('class' => 'tr', 'id' => 'form-block-edit' . $block->id)); ?>
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
                    <?php if ($block->database !== null && !empty($block->identifier)): ?>
                        <a target="_blank"
                           href=<?= ServerEnum::getLink($block->database, $block->identifier) ?>>
                            <?= ServerEnum::$allValues[$block->database]; ?></a>
                    <?php endif; ?>
                </div>

                <input type="hidden" name="<?= Front::BLOCK_SMILE ?>"
                       id="hidden-canvas-small-<?= $block->id ?>"
                       value="<?= $block->smiles ?>"/>

                <div class="td">
                    <input type="submit" title="SMILES Editor" value="Edit" onclick="editorBlock('<?= $block->id ?>')"/>
                </div>

                <input type="hidden" name="<?= Front::BLOCK_IDENTIFIER ?>" value="<?= $block->id ?>"/>
                <input type="hidden" name="<?= Front::BLOCK_DATABASE_ID ?>" value="<?= $block->databaseId ?>"/>
                <input type="hidden" name="<?= Front::BLOCK_NAME ?>" value="<?= $block->name ?>"/>
                <input type="hidden" name="<?= Front::BLOCK_ACRONYM ?>" value="<?= $block->acronym ?>"/>
                <input type="hidden" name="<?= Front::BLOCK_FORMULA ?>" value="<?= $block->formula ?>"/>
                <input type="hidden" name="<?= Front::BLOCK_MASS ?>" value="<?= $block->mass ?>"/>
                <input type="hidden" name="<?= Front::BLOCK_NEUTRAL_LOSSES ?>" value="<?= $block->losses ?>"/>
                <input type="hidden" name="<?= Front::BLOCK_REFERENCE ?>" value="<?= $block->identifier ?>"/>
                <input type="hidden" name="<?= Front::BLOCK_REFERENCE_SERVER ?>"
                       value="<?= $block->database ?>"/>
                <input type="hidden" name="<?= Front::BLOCK_COUNT ?>" value="<?= $blockCount ?>" class="block-count"/>

                <input type="hidden" name="<?= Front::CANVAS_INPUT_DATABASE ?>" value="<?= $database ?>"/>
                <input type="hidden" name="<?= Front::CANVAS_INPUT_SEARCH_BY ?>" value="<?= $search ?>"/>
                <input type="hidden" name="<?= Front::CANVAS_INPUT_NAME ?>" value="<?= $name ?>"/>
                <input type="hidden" name="<?= Front::CANVAS_INPUT_SMILE ?>" value="<?= $smile ?>"/>
                <input type="hidden" name="<?= Front::CANVAS_INPUT_FORMULA ?>" value="<?= $formula ?>"/>
                <input type="hidden" name="<?= Front::CANVAS_INPUT_MASS ?>" value="<?= $mass ?>"/>
                <input type="hidden" name="<?= Front::CANVAS_INPUT_DEFLECTION ?>" value="<?= $deflection ?>"/>
                <input type="hidden" name="<?= Front::CANVAS_INPUT_IDENTIFIER ?>" value="<?= $identifier ?>"/>
                <input type="hidden" name="<?= Front::SEQUENCE ?>" value="<?= $sequence ?>"/>
                <input type="hidden" name="<?= Front::SEQUENCE_TYPE ?>" value="<?= $sequenceType ?>"/>
                <input type="hidden" id="hdn-block-decays" name="<?= Front::DECAYS ?>" value="<?= set_value(Front::DECAYS, $decays) ?>"/>

                <?= form_close(); ?>
            <?php endforeach; ?>
        </div>
    </div>
</div>

</div>

<canvas id="canvas-large" onclick="clearLargeCanvas()"></canvas>

