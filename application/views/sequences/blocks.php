<?php

use Bbdgnc\Enum\Front;
use Bbdgnc\Enum\SequenceTypeEnum;
use Bbdgnc\Finder\Enum\ServerEnum;
use Bbdgnc\TransportObjects\BlockTO;
use Bbdgnc\TransportObjects\ModificationTO;
use Bbdgnc\TransportObjects\SequenceTO;

?>

<div id="div-blocks">
    <h2>Building blocks</h2>

    <div id="div-sequence">
        <div id="div-top-sequence">
            <h3>Sequence</h3>
            <p>Number of blocks: <?= sizeof($blocks) ?></p>

            <label for="sel-sequence-type">Type</label>
            <?= form_dropdown(Front::SEQUENCE_TYPE, SequenceTypeEnum::$values, $sequence[SequenceTO::TYPE],
                'id="sel-sequence-type" class="select" title="Type" onchange="sequenceTypeChanged()"'); ?>

            <label for="txt-sequence">Sequence</label>
            <input type="text" id="txt-sequence" name="sequence" value="<?= $sequence[SequenceTO::SEQUENCE] ?>"/>
        </div>

        <div class="div-modification">
            <h4>N-terminal Modification</h4>

            <?php if(isset($nModification['id'])): ?>
            <button onclick="window.location.href = '<?= site_url('modification/edit/' . $nModification['id']) ?>'">Edit</button>
            <?php endif; ?>

            <div id="div-n-modification">
                <label for="txt-n-modification">Name</label>
                <input type="text" id="txt-n-modification" name="nModification"
                       value="<?= set_value('nModification', $nModification['name']) ?>" disabled />

                <label for="txt-n-formula">Formula</label>
                <input type="text" id="txt-n-formula" name="nFormula"
                       value="<?= set_value('nFormula', $nModification[ModificationTO::FORMULA]) ?>" disabled />

                <label for="txt-n-mass">Monoisotopic Mass</label>
                <input type="text" id="txt-n-mass" name="nMass"
                       value="<?= set_value('nMass', $nModification[ModificationTO::MASS]) ?>" disabled />

                <label for="chk-n-nterminal" class="chk">N-terminal</label>
                <input type="checkbox" id="chk-n-nterminal"
                       name="nnTerminal" <?= Front::checked(set_value('nnTerminal', $nModification[ModificationTO::NTERMINAL])) ?> disabled />

                <label for="chk-n-cterminal" class="chk">C-terminal</label>
                <input type="checkbox" id="chk-n-cterminal"
                       name="ncTerminal" <?= Front::checked(set_value('ncTerminal', $nModification[ModificationTO::CTERMINAL])) ?> disabled />
            </div>
        </div>

        <div class="div-modification">
            <h4>C-terminal Modification</h4>

            <?php if(isset($cModification['id'])): ?>
            <button onclick="window.location.href = '<?= site_url('modification/edit/' . $cModification['id']) ?>'">Edit</button>
            <?php endif; ?>

            <div id="div-c-modification">
                <label for="txt-c-modification">Name</label>
                <input type="text" id="txt-c-modification" name="cModification"
                       value="<?= set_value('cModification', $cModification[ModificationTO::NAME]) ?>" disabled />

                <label for="txt-c-formula">Formula</label>
                <input type="text" id="txt-c-formula" name="cFormula"
                       value="<?= set_value('cFormula', $cModification[ModificationTO::FORMULA]) ?>" disabled />

                <label for="txt-c-mass">Monoisotopic Mass</label>
                <input type="text" id="txt-c-mass" name="cMass"
                       value="<?= set_value('cMass', $cModification[ModificationTO::MASS]) ?>" disabled />

                <label for="chk-c-nterminal" class="chk">N-terminal</label>
                <input type="checkbox" id="chk-c-nterminal"
                       name="cnTerminal" <?= Front::checked(set_value('cnTerminal', $cModification[ModificationTO::NTERMINAL])) ?> disabled />

                <label for="chk-c-cterminal" class="chk">C-terminal</label>
                <input type="checkbox" id="chk-c-cterminal"
                       name="ccTerminal" <?= Front::checked(set_value('ccTerminal', $cModification[ModificationTO::CTERMINAL])) ?> disabled />
            </div>
        </div>

        <div class="div-modification">
            <h4>Branch Modification</h4>

            <?php if(isset($bModification['id'])): ?>
            <button onclick="window.location.href = '<?= site_url('modification/edit/' . $bModification['id']) ?>'">Edit</button>
            <?php endif; ?>

            <div id="div-b-modification">
                <label for="txt-b-modification">Name</label>
                <input type="text" id="txt-b-modification" name="bModification"
                       value="<?= set_value('bModification', $bModification[ModificationTO::NAME]) ?>" disabled/>

                <label for="txt-b-formula">Formula</label>
                <input type="text" id="txt-b-formula" name="bFormula"
                       value="<?= set_value('bFormula', $bModification[ModificationTO::FORMULA]) ?>" disabled/>

                <label for="txt-b-mass">Monoisotopic Mass</label>
                <input type="text" id="txt-b-mass" name="bMass"
                       value="<?= set_value('bMass', $bModification[ModificationTO::MASS]) ?>" disabled/>

                <label for="chk-b-nterminal" class="chk">N-terminal</label>
                <input type="checkbox" id="chk-b-nterminal"
                       name="bnTerminal" <?= Front::checked(set_value('bnTerminal', $bModification[ModificationTO::NTERMINAL])) ?>
                       disabled/>

                <label for="chk-b-cterminal" class="chk">C-terminal</label>
                <input type="checkbox" id="chk-b-cterminal"
                       name="bcTerminal" <?= Front::checked(set_value('bcTerminal', $bModification[ModificationTO::CTERMINAL])) ?>
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

                <div class="tr" id="<?= 'form-block-edit' . $block['id'] ?>">
                    <div class="td">
                        <canvas id="canvas-small-<?= $block['id']; ?>"
                                data-canvas-small-id="<?= $block['id'] ?>"
                                class="canvas-small"
                                onclick="drawOrClearLargeSmile(<?= $block['id'] ?>)"
                                title="<?= $block[BlockTO::SMILES] ?>">
                        </canvas>
                    </div>

                    <div class="td">
                        <p><?= $block[BlockTO::NAME] ?></p>
                    </div>

                    <div class="td">
                        <p><?= $block[BlockTO::ACRONYM] ?></p>
                    </div>

                    <div class="td">
                        <p><?= $block[BlockTO::RESIDUE] ?></p>
                    </div>

                    <div class="td">
                        <p><?= $block[BlockTO::LOSSES] ?></p>
                    </div>

                    <div class="td">
                        <p><?= $block[BlockTO::MASS] ?></p>
                    </div>

                    <div class="td">
                        <p><?= $block[BlockTO::SMILES] ?></p>
                    </div>

                    <div class="td">
                        <?php if ($block['database'] !== null && !empty($block['identifier'])): ?>
                            <a target="_blank"
                               href=<?= ServerEnum::getLink($block[BlockTO::DATABASE], $block[BlockTO::IDENTIFIER]) ?>>
                                <?= ServerEnum::$allValues[$block[BlockTO::DATABASE]]; ?></a>
                        <?php endif; ?>
                    </div>

                    <input type="hidden" name="<?= Front::BLOCK_SMILE ?>"
                           id="hidden-canvas-small-<?= $block['id'] ?>"
                           value="<?= $block[BlockTO::SMILES] ?>"/>

                    <div class="td">
                        <button onclick="window.location.href = '<?= site_url('block/edit/' . $block['id']) ?>'">Edit
                        </button>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

</div>

<canvas id="canvas-large" onclick="clearLargeCanvas()"></canvas>

