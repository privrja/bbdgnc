<?php use Bbdgnc\Enum\Constants; ?>

<div id="div-canvas">
    <?php foreach ($molecules as $molecule): ?>

        <h3><?= $molecule[Constants::CANVAS_INPUT_FORMULA] ?></h3>
        <div class="main">
            <?= $molecule[Constants::CANVAS_INPUT_SMILE] ?>
        </div>
        <a href=""><?= $molecule[Constants::CANVAS_INPUT_DATABASE] ?></a>

    <?php endforeach; ?>
</div>
