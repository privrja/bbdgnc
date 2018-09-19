<?php

use Bbdgnc\Enum\Constants;
use Bbdgnc\Finder\Enum\ServerEnum;

?>

<div id="div-canvas">
    <?php foreach ($molecules as $molecule): ?>

        <h3><?= $molecule[Constants::CANVAS_INPUT_FORMULA] ?></h3>
        <div class="main">
            <?= $molecule[Constants::CANVAS_INPUT_SMILE] ?>
        </div>
        <a href=<?= ServerEnum::getLink($molecule[Constants::CANVAS_INPUT_DATABASE], $molecule[Constants::CANVAS_INPUT_IDENTIFIER]) ?>>
            <?= ServerEnum::$values[$molecule[Constants::CANVAS_INPUT_DATABASE]] ?></a>

    <?php endforeach; ?>
</div>
