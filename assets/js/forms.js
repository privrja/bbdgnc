const DISPLAY_BLOCK = "block";
const DISPLAY_NONE = "none";

const CLASS_TXT_DEF = "txt-def";
const CLASS_TXT = "txt";

const CLASS_BTN_SAME = "btn-same";

const CLASS_TXT_AREA_DEF = "txt-area";

const TXT_CANVAS_NAME = "txt-canvas-name";
const TXT_CANVAS_SMILE = "txt-canvas-smile";
const TXT_CANVAS_FLE = "txt-canvas-fle";
const TXT_CANVAS_SUG_NAME = "txt-canvas-sug-name";
const TXT_CANVAS_SUG_SMILE = "txt-canvas-sug-smile";
const TXT_CANVAS_SUG_FLE = "txt-canvas-sug-fle";
const BTN_CANVAS_TRANSLATE_NAME = "btn-canvas-translate-name";
const BTN_CANVAS_TRANSLATE_SMILE = "btn-canvas-translate-smile";
const BTN_CANVAS_TRANSLATE_FLE = "btn-canvas-translate-fle";
const BTN_CANVAS_TRANSLATE_ALL = "btn-canvas-translate-all";

function find() {
    changeCanvasFormView();

}

function changeCanvasFormView() {
    setClass(TXT_CANVAS_NAME, CLASS_TXT);
    setClass(TXT_CANVAS_FLE, CLASS_TXT);
    setClasses(TXT_CANVAS_SUG_NAME, DISPLAY_BLOCK, CLASS_TXT);
    setClasses(TXT_CANVAS_SUG_SMILE, DISPLAY_BLOCK, CLASS_TXT_AREA_DEF);
    setClasses(TXT_CANVAS_SUG_FLE, DISPLAY_BLOCK, CLASS_TXT);
    setClasses(BTN_CANVAS_TRANSLATE_NAME, DISPLAY_BLOCK, CLASS_TXT);
    setClasses(BTN_CANVAS_TRANSLATE_SMILE, DISPLAY_BLOCK, CLASS_TXT);
    setClasses(BTN_CANVAS_TRANSLATE_FLE, DISPLAY_BLOCK, CLASS_TXT);
    setClasses(BTN_CANVAS_TRANSLATE_ALL, DISPLAY_BLOCK, CLASS_BTN_SAME);
}

/**
 *
 * @param id string id of element
 * @param display string css display value
 */
function setClasses(id, display, cls) {
    document.getElementById(id).className = cls;
    document.getElementById(id).className += " " + display;
}

function setClass(id, cls) {
    document.getElementById(id).className = cls;
}

function translateAll() {
    translateName();
    translateSmile();
    translateFle();
    // setClasses(BTN_CANVAS_TRANSLATE_ALL, DISPLAY_NONE, CLASS_BTN_SAME);
}

function translateName() {
    translateCanvasForm(TXT_CANVAS_SUG_NAME, TXT_CANVAS_NAME, BTN_CANVAS_TRANSLATE_NAME);
}

function translateSmile() {
    translateCanvasForm(TXT_CANVAS_SUG_SMILE, TXT_CANVAS_SMILE, BTN_CANVAS_TRANSLATE_SMILE, CLASS_TXT_AREA_DEF);
}

function translateFle() {
    translateCanvasForm(TXT_CANVAS_SUG_FLE, TXT_CANVAS_FLE, BTN_CANVAS_TRANSLATE_FLE);
}

function translateCanvasForm(txtSugId, txtId, btnId, classNext = null) {
    let txtSug = document.getElementById(txtSugId);
    if ("" !== txtSug.value) {
        document.getElementById(txtId).value = txtSug.value;
    }
    txtSug.value = "";
    setClass(txtSugId, DISPLAY_NONE);
    setClass(btnId, DISPLAY_NONE);
    if (classNext == null) {
        setClass(txtId, CLASS_TXT_DEF);
    } else {
        setClasses(txtId, CLASS_TXT_DEF, classNext);
    }
}


