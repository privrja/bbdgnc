<?php

use Bbdgnc\Enum\Front;
use Bbdgnc\Finder\Enum\ServerEnum;

?>

<div id="div-select">
    <h2 id="h-results">Results</h2>
    <div class="table t">
        <div class="thead t">
            <div class="tr t">
                <div class="td"></div>
                <div class="td">Formula</div>
                <div class="td">Identifier</div>
                <div class="td">Mass</div>
                <div class="td">Database</div>
                <div class="td">Action</div>
            </div>
        </div>
        <div class="tbody">
            <?php foreach ($molecules as $molecule): ?>

                <?= form_open('land/select', array('class' => 'tr')); ?>
                <div class="td">
                    <canvas id="canvas-small-<?= $molecule[Front::CANVAS_INPUT_IDENTIFIER] ?>"
                            data-canvas-small-id="<?= $molecule[Front::CANVAS_INPUT_IDENTIFIER] ?>"
                            class="canvas-small"
                            onclick="drawOrClearLargeSmile(<?= $molecule[Front::CANVAS_INPUT_IDENTIFIER] ?>)"
                            title="<?= $molecule[Front::CANVAS_INPUT_FORMULA] ?>"
                    ></canvas>
                </div>
                <div class="td"><?= Front::formula($molecule[Front::CANVAS_INPUT_FORMULA]) ?></div>
                <div class="td"><?= $molecule[Front::CANVAS_INPUT_IDENTIFIER] ?></div>
                <div class="td"><?= $molecule[Front::CANVAS_INPUT_MASS] ?></div>
                <div class="td">
                    <a target="_blank" href=<?= ServerEnum::getLink($molecule[Front::CANVAS_HIDDEN_DATABASE], $molecule[Front::CANVAS_INPUT_IDENTIFIER]) ?>>
                        <?= ServerEnum::$values[$molecule[Front::CANVAS_HIDDEN_DATABASE]] ?></a>
                </div>
                <div class="td"><input type="submit" value="Select"/></div>

                <input type="hidden"
                       name=<?= Front::CANVAS_HIDDEN_DATABASE ?> value="<?= $molecule[Front::CANVAS_HIDDEN_DATABASE] ?>"/>
                <input type="hidden"
                       name=<?= Front::CANVAS_INPUT_NAME ?> value="<?= Front::defIndex($molecule, Front::CANVAS_INPUT_NAME) ?>"/>
                <input type="hidden" id="hidden-canvas-small-<?= $molecule[Front::CANVAS_INPUT_IDENTIFIER] ?>"
                       name=<?= Front::CANVAS_INPUT_SMILE ?> value="<?= $molecule[Front::CANVAS_INPUT_SMILE] ?>"/>
                <input type="hidden"
                       name=<?= Front::CANVAS_INPUT_FORMULA ?> value="<?= $molecule[Front::CANVAS_INPUT_FORMULA] ?>"/>
                <input type="hidden"
                       name=<?= Front::CANVAS_INPUT_MASS ?> value="<?= $molecule[Front::CANVAS_INPUT_MASS] ?>"/>
                <input type="hidden"
                       name=<?= Front::CANVAS_INPUT_IDENTIFIER ?> value="<?= $molecule[Front::CANVAS_INPUT_IDENTIFIER] ?>"/>
                </form>

            <?php endforeach; ?>
        </div>
    </div>

    <div>
        <?= form_open('land/next', array('class' => 'form')); ?>
        <input type="hidden"
               name=<?= Front::CANVAS_HIDDEN_DATABASE ?> value="<?= $molecules[0][Front::CANVAS_HIDDEN_DATABASE] ?>"/>
        <input type="hidden" name=<?= Front::CANVAS_HIDDEN_NAME ?> value="<?= $hdName ?>"/>
        <input type="hidden" name=<?= Front::CANVAS_HIDDEN_SMILE ?> value="<?= $hdSmile ?>"/>
        <input type="hidden" name=<?= Front::CANVAS_HIDDEN_FORMULA ?> value="<?= $hdFormula ?>"/>
        <input type="hidden" name=<?= Front::CANVAS_HIDDEN_MASS ?> value="<?= $hdMass ?>"/>
        <input type="hidden" name=<?= Front::CANVAS_HIDDEN_DEFLECTION ?> value="<?= $hdDeflection ?>"/>
        <input type="hidden" name=<?= Front::CANVAS_HIDDEN_IDENTIFIER ?> value="<?= $hdIdentifier ?>"/>
        <input type="submit" value="Next results"/>
        </form>
    </div>

</div>

<canvas id="canvas-large" onclick="clearLargeCanvas()"></canvas>
