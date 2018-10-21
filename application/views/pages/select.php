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
                    <a target="_blank" href=<?= ServerEnum::getLink($database, $molecule[Front::CANVAS_INPUT_IDENTIFIER]) ?>>
                        <?= ServerEnum::$values[$database]; ?></a>
                </div>
                <div class="td"><input type="submit" value="Select"/></div>

                <input type="hidden"
                       name=<?= Front::CANVAS_INPUT_DATABASE ?> value="<?= $database ?>"/>
                <input type="hidden" name="<?= Front::CANVAS_INPUT_SEARCH_BY ?>" value="<?= $search ?>" />
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
               name=<?= Front::CANVAS_INPUT_DATABASE ?> value="<?= $database ?>"/>
        <input type="hidden" name="<?= Front::CANVAS_INPUT_SEARCH_BY ?>" value="<?= $search ?>" />
        <input type="hidden" name=<?= Front::CANVAS_INPUT_NAME ?> value="<?= $name ?>"/>
        <input type="hidden" name=<?= Front::CANVAS_INPUT_SMILE ?> value="<?= $smile ?>"/>
        <input type="hidden" name=<?= Front::CANVAS_INPUT_FORMULA ?> value="<?= $formula ?>"/>
        <input type="hidden" name=<?= Front::CANVAS_INPUT_MASS ?> value="<?= $mass ?>"/>
        <input type="hidden" name=<?= Front::CANVAS_INPUT_DEFLECTION ?> value="<?= $deflection ?>"/>
        <input type="hidden" name=<?= Front::CANVAS_INPUT_IDENTIFIER ?> value="<?= $identifier ?>"/>
        <input type="submit" value="Next results"/>
        </form>
    </div>

</div>

<canvas id="canvas-large" onclick="clearLargeCanvas()"></canvas>
