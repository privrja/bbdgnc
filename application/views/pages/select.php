<?php

use Bbdgnc\Enum\Front;
use Bbdgnc\Finder\Enum\ServerEnum;

?>

<div id="div-canvas">
    <div class="table t">
        <div class="thead t">
            <div class="tr t">
                <div class="td">Name</div>
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
                <div class="td"
                     title=<?= Front::defIndex($molecule, Front::CANVAS_INPUT_NAME) ?>><?= Front::smallerText(Front::defIndex($molecule, Front::CANVAS_INPUT_NAME)) ?></div>
                <div class="td"><?= $molecule[Front::CANVAS_INPUT_FORMULA] ?></div>
                <div class="td"><?= $molecule[Front::CANVAS_INPUT_IDENTIFIER] ?></div>
                <div class="td"><?= $molecule[Front::CANVAS_INPUT_MASS] ?></div>
                <div class="td">
                    <a href=<?= ServerEnum::getLink($molecule[Front::CANVAS_HIDDEN_DATABASE], $molecule[Front::CANVAS_INPUT_IDENTIFIER]) ?>>
                        <?= ServerEnum::$values[$molecule[Front::CANVAS_HIDDEN_DATABASE]] ?></a>
                </div>
                <div class="td"><input type="submit" value="Select"/></div>

                <input type="hidden"
                       name=<?= Front::CANVAS_HIDDEN_DATABASE ?> value="<?= $molecule[Front::CANVAS_HIDDEN_DATABASE] ?>"/>
                <input type="hidden"
                       name=<?= Front::CANVAS_INPUT_NAME ?> value="<?= Front::defIndex($molecule, Front::CANVAS_INPUT_NAME) ?>"/>
                <input type="hidden"
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
        <input type="hidden" name=<?= Front::CANVAS_HIDDEN_DATABASE ?> value="<?= $molecules[0][Front::CANVAS_HIDDEN_DATABASE] ?>"/>
        <input type="hidden" name=<?= Front::CANVAS_HIDDEN_NAME ?> value="<?= $hdName ?>"/>
        <input type="hidden" name=<?= Front::CANVAS_HIDDEN_SMILE ?> value="<?= $hdSmile ?>"/>
        <input type="hidden" name=<?= Front::CANVAS_HIDDEN_FORMULA ?> value="<?= $hdFormula ?>"/>
        <input type="hidden" name=<?= Front::CANVAS_HIDDEN_MASS ?> value="<?= $hdMass ?>"/>
        <input type="hidden" name=<?= Front::CANVAS_HIDDEN_DEFLECTION ?> value="<?= $hdDeflection ?>"/>
        <input type="hidden" name=<?= Front::CANVAS_HIDDEN_IDENTIFIER ?> value="<?= $hdIdentifier ?>"/>
        <input type="submit" value="Next results" />
        </form>
    </div>

</div>
