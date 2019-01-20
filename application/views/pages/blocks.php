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
                <!--                <div class="td">Identifier</div>-->
                <div class="td">Editor</div>
            </div>
        </div>
        <div class="tbody">
            <?php foreach ($molecules as $molecule): ?>

                <div class="tr">
                <div class="td">
                    <canvas id="canvas-small-<?= $molecule[Front::CANVAS_INPUT_IDENTIFIER] ?>"
                            data-canvas-small-id="<?= $molecule[Front::CANVAS_INPUT_IDENTIFIER] ?>"
                            class="canvas-small"
                            onclick="drawOrClearLargeSmile(<?= $molecule[Front::CANVAS_INPUT_IDENTIFIER] ?>)"
                            title="<?= $molecule[Front::CANVAS_INPUT_SMILE] ?>"
                    ></canvas>
                </div>

                <div class="td">
                    <input type="text" name="<?= Front::CANVAS_INPUT_SMILE ?>"
                           id="hidden-canvas-small-<?= $molecule[Front::CANVAS_INPUT_IDENTIFIER] ?>"
                           value="<?= $molecule[Front::CANVAS_INPUT_SMILE] ?>"
                           oninput="drawSmallSmile(<?= $molecule[Front::CANVAS_INPUT_IDENTIFIER] ?>)"
                    />
                </div>

                    <div class="td">
                        <button class="" title="SMILES Editor"
                                onclick="readSmiles(<?= $molecule[Front::CANVAS_INPUT_IDENTIFIER] ?>)">Editor
                        </button>
                    </div>
                <input type="hidden"
                       name="<?= Front::CANVAS_INPUT_IDENTIFIER ?>"
                       value="<?= $molecule[Front::CANVAS_INPUT_IDENTIFIER] ?>"/>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <h2 id="h-editor">JSME editor</h2>
    <div class="jsme" code="JME.class" name="JME" archive="JME.jar" width=500 height=500>You have to enable Java and
        JavaScritpt on your machine!
    </div>
    <button class="" onclick="getSmiles()">Accept changes</button>
    <input type="hidden" id="editor-input" value="<?= $molecule[Front::CANVAS_INPUT_IDENTIFIER] ?>"/>

</div>

<canvas id="canvas-large" onclick="clearLargeCanvas()"></canvas>

<script src="<?= AssetHelper::jsJsme() ?>"></script>
