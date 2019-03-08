<?php

use Bbdgnc\Finder\Enum\ServerEnum;

?>

<?php foreach ($results as $data): ?>
    <?= $data[0]['residue'] ?>
    <br/>
    <?php foreach ($data as $block): ?>
        <?= $block['name'] ?>
        <br/>
    <?php endforeach; ?>
<?php endforeach; ?>

<p><?= $links; ?></p>

