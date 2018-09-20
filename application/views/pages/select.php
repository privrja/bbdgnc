<?php

use Bbdgnc\Enum\Constants;
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
                <div class="td" title=<?= Constants::defIndex($molecule, Constants::CANVAS_INPUT_NAME) ?>><?= Constants::smallerText(Constants::defIndex($molecule, Constants::CANVAS_INPUT_NAME)) ?></div>
                <div class="td"><?= $molecule[Constants::CANVAS_INPUT_FORMULA] ?></div>
                <div class="td"><?= $molecule[Constants::CANVAS_INPUT_IDENTIFIER] ?></div>
                <div class="td"><?= $molecule[Constants::CANVAS_INPUT_MASS] ?></div>
                <div class="td">
                    <a href=<?= ServerEnum::getLink($molecule[Constants::CANVAS_HIDDEN_DATABASE], $molecule[Constants::CANVAS_INPUT_IDENTIFIER]) ?>>
                        <?= ServerEnum::$values[$molecule[Constants::CANVAS_HIDDEN_DATABASE]] ?></a>
                </div>
                <span class="td"><input type="submit" value="Select"/></span>

                <input type="hidden"
                       name=<?= Constants::CANVAS_HIDDEN_DATABASE ?> value="<?= $molecule[Constants::CANVAS_HIDDEN_DATABASE] ?>"/>
                <input type="hidden"
                       name=<?= Constants::CANVAS_INPUT_NAME ?> value="<?= Constants::defIndex($molecule, Constants::CANVAS_INPUT_NAME) ?>"/>
                <input type="hidden"
                       name=<?= Constants::CANVAS_INPUT_SMILE ?> value="<?= $molecule[Constants::CANVAS_INPUT_SMILE] ?>"/>
                <input type="hidden"
                       name=<?= Constants::CANVAS_INPUT_FORMULA ?> value="<?= $molecule[Constants::CANVAS_INPUT_FORMULA] ?>"/>
                <input type="hidden"
                       name=<?= Constants::CANVAS_INPUT_MASS ?> value="<?= $molecule[Constants::CANVAS_INPUT_MASS] ?>"/>
                <input type="hidden"
                       name=<?= Constants::CANVAS_INPUT_IDENTIFIER ?> value="<?= $molecule[Constants::CANVAS_INPUT_IDENTIFIER] ?>"/>
                <input type="hidden" name=<?= Constants::CANVAS_HIDDEN_NAME ?> value="<?= $hdname ?>"/>
                </form>

            <?php endforeach; ?>
        </div>
    </div>

</div>
