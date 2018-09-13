<div id="div-full">

    <article>
        <h1>Settings</h1>

        <h2>Colors</h2>

<!--        --><?//= validation_errors(); ?>
<!--        --><?//= form_open('settings/colors', array('class' => 'form')); ?>

        <label for="background">Background color</label>
        <input type="color" class="txt-def" name="background" title="Background color"/>
        <br />

        <label for="menu">Menu color</label>
        <input type="color" class="txt-def" name="menu" title="Menu color"/>
        <br />

        <label for="font">Font color</label>
        <input type="color" class="txt-def" name="font" title="Font color"/>
        <br />

        <input type="submit" name="color" value="Change colors" onclick="changeColors()"/>
<!--        </form>-->
    </article>

</div>


