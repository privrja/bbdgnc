<?php

use Bbdgnc\Enum\Front;

?>

<div id="div-blocks">
    <h2 id="h-results">Building blocks</h2>
    <div class="table t">
        <div class="thead t">
            <div class="tr t">
                <div class="td"></div>
                <div class="td">SMILES</div>
                <div class="td">Editor</div>
            </div>
        </div>
        <div class="tbody">
            <?php foreach ($molecules as $molecule): ?>

                <?= form_open('editor', array('class' => 'tr')); ?>
                <div class="td">
                    <canvas id="canvas-small-<?= $molecule[Front::CANVAS_INPUT_IDENTIFIER] ?>"
                            data-canvas-small-id="<?= $molecule[Front::CANVAS_INPUT_IDENTIFIER] ?>"
                            class="canvas-small"
                            onclick="drawOrClearLargeSmile(<?= $molecule[Front::CANVAS_INPUT_IDENTIFIER] ?>)"
                            title="<?= $molecule[Front::CANVAS_INPUT_SMILE] ?>">
                    </canvas>
                </div>

                <div class="td">
                    <input type="text" name="<?= Front::CANVAS_INPUT_SMILE ?>"
                           id="hidden-canvas-small-<?= $molecule[Front::CANVAS_INPUT_IDENTIFIER] ?>"
                           value="<?= $molecule[Front::CANVAS_INPUT_SMILE] ?>"
                           oninput="drawSmallSmile(<?= $molecule[Front::CANVAS_INPUT_IDENTIFIER] ?>)"/>
                </div>

                <input type="hidden" name="<?= Front::EDITOR_INPUT ?>"
                       value="<?= $molecule[Front::CANVAS_INPUT_IDENTIFIER] ?>"/>
                <input type="hidden" name="<?= Front::BLOCKS_BLOCK_SMILES ?>" value="<?= $smiles ?>"/>

                <div class="td">
                    <input type="submit" title="SMILES Editor" value="Editor"/>
                </div>

                </form>
            <?php endforeach; ?>
        </div>
    </div>
</div>


</div>

<canvas id="canvas-large" onclick="clearLargeCanvas()"></canvas>

