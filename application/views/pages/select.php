<?php

use Bbdgnc\Enum\Constants;
use Bbdgnc\Finder\Enum\ServerEnum;

?>

<div id="div-canvas">
    <?php foreach ($molecules as $molecule): ?>

        <?= form_open('land/select', array('class' => 'form')); ?>

        <h3><?= $molecule[Constants::CANVAS_INPUT_NAME] ?></h3>
        <p><?= $molecule[Constants::CANVAS_INPUT_MASS] ?></p>
        <p><?= $molecule[Constants::CANVAS_INPUT_FORMULA] ?></p>
        <a href=<?= ServerEnum::getLink($molecule[Constants::CANVAS_HIDDEN_DATABASE], $molecule[Constants::CANVAS_INPUT_IDENTIFIER]) ?>>
            <?= ServerEnum::$values[$molecule[Constants::CANVAS_HIDDEN_DATABASE]] ?></a>

        <input type="hidden" name=<?= Constants::CANVAS_HIDDEN_DATABASE ?> value="<?= $molecule[Constants::CANVAS_HIDDEN_DATABASE] ?>" />
        <input type="hidden" name=<?= Constants::CANVAS_INPUT_NAME ?> value="<?= $molecule[Constants::CANVAS_INPUT_NAME] ?>" />
        <input type="hidden" name=<?= Constants::CANVAS_INPUT_SMILE ?> value="<?= $molecule[Constants::CANVAS_INPUT_SMILE] ?>" />
        <input type="hidden" name=<?= Constants::CANVAS_INPUT_FORMULA ?> value="<?= $molecule[Constants::CANVAS_INPUT_FORMULA] ?>" />
        <input type="hidden" name=<?= Constants::CANVAS_INPUT_MASS ?> value="<?= $molecule[Constants::CANVAS_INPUT_MASS] ?>" />
        <input type="hidden" name=<?= Constants::CANVAS_INPUT_IDENTIFIER ?> value="<?= $molecule[Constants::CANVAS_INPUT_MASS] ?>" />
        <input type="submit" value="Select"/>

        </form>
    <?php endforeach; ?>

</div>
