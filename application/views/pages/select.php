<div id="div-canvas">
    <?php foreach ($molecules as $molecule): ?>
        <h3><?php echo $molecule['name']; ?></h3>
        <div class="main">
            <?php echo $molecule['smile']; ?>
        </div>

    <?php endforeach; ?>
</div>
