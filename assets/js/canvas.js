/** constants HTML id */
const CANVAS_ID = "canvas-main";
const TXT_SMILE_ID = "txt-canvas-smile";
const HIDDEN_SMILE_ID = "hidden-canvas-small-";
const CANVAS_SMALL_ID = "canvas-small-";
const CANVAS_LARGE_ID = "canvas-large";
const FORM_MAIN = "form-main";
const SEQUENCE_TYPE = "sel-sequence-type";
const SEL_N_MODIFICATION = "sel-n-modification";
const TXT_N_MODIFICATION = "txt-n-modification";
const TXT_N_FORMULA = "txt-n-formula";
const TXT_N_MASS = "txt-n-mass";
const CHK_N_NTERMINAL = "chk-n-nterminal";
const CHK_N_CTERMINAL = "chk-n-cterminal";
const SEL_C_MODIFICATION = "sel-c-modification";
const TXT_C_MODIFICATION = "txt-c-modification";
const TXT_C_FORMULA = "txt-c-formula";
const TXT_C_MASS = "txt-c-mass";
const CHK_C_NTERMINAL = "chk-c-nterminal";
const CHK_C_CTERMINAL = "chk-c-cterminal";
const SEL_B_MODIFICATION = "sel-b-modification";
const TXT_BRANCH_MODIFICATION = "txt-b-modification";
const TXT_BRANCH_FORMULA = "txt-b-formula";
const TXT_BRANCH_MASS = "txt-b-mass";
const CHK_BRANCH_NTERMINAL = "chk-b-nterminal";
const CHK_BRANCH_CTERMINAL = "chk-b-cterminal";
const TXT_CANVAS_FLE_ID = 'txt-canvas-fle';
const AMPERSAND = '&';

const CAPTION_RESULTS = "#h-results";

const WINDOW_MIN_WIDTH = 850;
const WINDOW_MIN_HEIGHT = 575;
const CANVAS_SMALL_SQUARE = 300;

const PIXEL_TWO = 2;
const PROCENT_SIXTY = 0.6;

/** constants mode */
const MODE_LIGHT = "light";
const MODE_DARK = "dark";

/** constants colors */
const COLOR_WHITE = "white";
const COLOR_BLACK = "black";

/** boolean is mobile version? */
let mobile = false;

/** int last show last preview of SMILES */
let lastLargeSmilesId;

let decaysNoRedraw = false;

/** default options for main drawer */
let options = {
    width: getCanvasWidth(),
    height: getCanvasHeight(),
    themes: {light: {O: '#e67e22', DECAY: '#ff0000'}},
    drawDecayPoints: 1,
    compactDrawing: false
};

/** Initialize the drawers */
let smilesDrawer = getSmilesDrawer();
let smallSmilesDrawer = getSmallSmilesDrawer();
let largeSmilesDrawer = getLargeSmilesDrawer();
let canvasRef = document.getElementById(CANVAS_ID);
if (canvasRef) {
    var offsetX = canvasRef.offsetLeft;
    var offsetY = canvasRef.offsetTop;
}

function setupDecaySource() {
    let source = JSON.parse('[' + document.getElementById('hdn-decays').value + ']');
    if (typeof source !== 'undefined' && source.length > 0) {
        options.drawDecayPoints = 2;
        options.decaySource = source;
        updateOptions();
    }
}

function updateOptions() {
    smilesDrawer = new SmilesDrawer.Drawer(options);
    drawSmile();
}

/** events */
document.addEventListener('DOMContentLoaded', function () {
    window.addEventListener('resize', resize);
    window.addEventListener('load', finder);
    if (document.getElementById(TXT_SMILE_ID)) {
        window.addEventListener('load', setupDecaySource);
        document.getElementById(TXT_SMILE_ID).addEventListener('input', changeSmilesInput);
    }

    if (canvasRef) {
        canvasRef.addEventListener('click', function (e) {
            smilesDrawer.handleMouseClick(e, offsetX, offsetY);
        });
    }

    if (document.getElementById(SEQUENCE_TYPE)) {
        if (document.getElementById('h-results')) {
            window.addEventListener('load', sequenceTypeChanged);
        }
    }

    if (document.getElementById(SEL_N_MODIFICATION)) {
        document.getElementById(SEL_N_MODIFICATION).addEventListener('change', modificationSelect);
    }

    if (document.getElementById(SEL_C_MODIFICATION)) {
        document.getElementById(SEL_C_MODIFICATION).addEventListener('change', modificationSelect);
    }

    if (document.getElementById(SEL_B_MODIFICATION)) {
        document.getElementById(SEL_B_MODIFICATION).addEventListener('change', modificationSelect);
    }

    if (document.getElementsByTagName('i.fa-sort')) {
        window.addEventListener('load', changeSortArrows);
    }
});

/**
 * Prepare small previews for results find by query
 */
function finder() {
    let smallCanvases = document.querySelectorAll('[data-canvas-small-id]');
    for (let i = 0; i < smallCanvases.length; i++) {
        let elem = smallCanvases[i];
        drawSmallSmile(elem.getAttribute("data-canvas-small-id"));
        if (i === 0) {
            document.location.href = CAPTION_RESULTS;
        }
    }
    mobileVersion();
    resize();
}

/**
 * Resize canvas on window moves, etc
 */
function resize() {
    let canvas = document.getElementById(CANVAS_ID);
    if (canvas) {
        canvas.style.width = "100%";
        canvas.style.height = "100%";
        let context = canvas.getContext("2d");
        context.clearRect(0, 0, canvas.width, canvas.height);
        smilesDrawer = getSmilesDrawer();
        drawSmile();
    }
}

function sequenceTypeNumber($sequenceType) {
    switch ($sequenceType) {
        case "linear":
            return 0;
        case "cyclic":
            return 1;
        case "branched":
            return 2;
        case "branch-cyclic":
            return 3;
        case "linear-polyketide":
            return 4;
        case "cyclic-polyketide":
            return 5;
        case "other":
            return 6;
    }
}

function disintegrate() {
    let smilesAndSequence = smilesDrawer.buildBlockSmiles();
    let data = {
        blockSmiles: smilesAndSequence[0],
        blocks: 'Blocks',
        first: true,
        sequence: smilesAndSequence[1],
        sequenceType: sequenceTypeNumber(smilesAndSequence[2]),
        decays: smilesAndSequence[3]
    };
    redirectWithData(FORM_MAIN, data);
}

/** default screen mode (dark/light) def = light */
let DEFAULT_SCREEN_MODE = MODE_LIGHT;

/**
 * Get SMILES Drawer instance with dimension of canvas
 */
function getSmilesDrawer() {
    try {
        return new SmilesDrawer.Drawer(options);
    } catch (e) {
        console.log(e);
    }
}

/** Get SMILES Drawer instance for small preview */
function getSmallSmilesDrawer() {
    try {
        return new SmilesDrawer.Drawer({
            width: CANVAS_SMALL_SQUARE,
            height: CANVAS_SMALL_SQUARE,
            compactDrawing: false
        });
    } catch (e) {
        console.log(e);
    }
}

/** Get SMILES Drawer instance for large preview */
function getLargeSmilesDrawer() {
    try {
        return new SmilesDrawer.Drawer({
            width: getWindowWidth() * PROCENT_SIXTY,
            height: getWindowHeight() + PIXEL_TWO,
            compactDrawing: false
        });
    } catch (e) {
        console.log(e);
    }
}

/**
 * Mobile version?
 * set mobile to true if window is too small
 */
function mobileVersion() {
    mobile = getWindowWidth() <= WINDOW_MIN_WIDTH || getWindowHeight() <= WINDOW_MIN_HEIGHT;
}

/**
 * Get window width
 * @returns {number}
 */
function getWindowWidth() {
    return window.innerWidth;
}

/**
 * Get window height
 * @returns {number}
 */
function getWindowHeight() {
    return window.innerHeight;
}

/**
 * Get current width of canvas
 */
function getCanvasWidth() {
    if (document.getElementById(CANVAS_ID)) {
        return document.getElementById(CANVAS_ID).offsetWidth;
    } else {
        return 0;
    }
}

/**
 * Get current height of canvas
 */
function getCanvasHeight() {
    if (document.getElementById(CANVAS_ID)) {
        return document.getElementById(CANVAS_ID).offsetHeight;
    } else {
        return 0;
    }
}

/**
 * enable/disable input for modification on type of sequence selected
 */
function sequenceTypeChanged() {
    switch (document.getElementById(SEQUENCE_TYPE).value) {
        case "0":
        case "4":
            enableModificationN();
            enableModificationC();
            disableModificationBranch();
            break;
        case "1":
        case "5":
            disableModificationN();
            disableModificationC();
            disableModificationBranch();
            break;
        case "3":
            disableModificationN();
            disableModificationC();
            enableModificationBranch();
            break;
        case "2":
        case "6":
        default:
            enableModificationN();
            enableModificationC();
            enableModificationBranch();
            break;
    }
}

function disableModificationN() {
    enableOrDisableModificationN(true);
}

function enableModificationN() {
    enableOrDisableModificationN(false);
}

function disableModificationC() {
    enableOrDisableModificationC(true);
}

function enableModificationC() {
    enableOrDisableModificationC(false);
}

function disableModificationBranch() {
    enableOrDisableModificationBranch(true);
}

function enableModificationBranch() {
    enableOrDisableModificationBranch(false);
}

function enableOrDisableModificationN(disable) {
    disableOrEnableElement(SEL_N_MODIFICATION, disable);
    disableOrEnableElement(TXT_N_MODIFICATION, disable);
    disableOrEnableElement(TXT_N_FORMULA, disable);
    disableOrEnableElement(TXT_N_MASS, disable);
    disableOrEnableElement(CHK_N_NTERMINAL, disable);
    disableOrEnableElement(CHK_N_CTERMINAL, disable);
    if (document.getElementById(SEL_N_MODIFICATION) && document.getElementById(SEL_N_MODIFICATION).value != '0' && !disable) {
        displayModification(SEL_N_MODIFICATION, true);
    }
}

function enableOrDisableModificationC(disable) {
    disableOrEnableElement(SEL_C_MODIFICATION, disable);
    disableOrEnableElement(TXT_C_MODIFICATION, disable);
    disableOrEnableElement(TXT_C_FORMULA, disable);
    disableOrEnableElement(TXT_C_MASS, disable);
    disableOrEnableElement(CHK_C_NTERMINAL, disable);
    disableOrEnableElement(CHK_C_CTERMINAL, disable);
    if (document.getElementById(SEL_C_MODIFICATION) && document.getElementById(SEL_C_MODIFICATION).value != '0' && !disable) {
        displayModification(SEL_C_MODIFICATION, true);
    }
}

function enableOrDisableModificationBranch(disable) {
    disableOrEnableElement(SEL_B_MODIFICATION, disable);
    disableOrEnableElement(TXT_BRANCH_MODIFICATION, disable);
    disableOrEnableElement(TXT_BRANCH_FORMULA, disable);
    disableOrEnableElement(TXT_BRANCH_MASS, disable);
    disableOrEnableElement(CHK_BRANCH_NTERMINAL, disable);
    disableOrEnableElement(CHK_BRANCH_CTERMINAL, disable);
    if (document.getElementById(SEL_B_MODIFICATION) && document.getElementById(SEL_B_MODIFICATION).value != '0' && !disable) {
        displayModification(SEL_B_MODIFICATION, true);
    }
}

function disableOrEnableElement(elementId, disable) {
    if (document.getElementById(elementId)) {
        document.getElementById(elementId).disabled = disable;
    }
}

/**
 * Parse Isomeric SMILE to Canonical SMILES
 *
 * get smile from txt
 * parse it and put it back
 * refresh canvas
 */
function easy() {
    document.getElementById(TXT_SMILE_ID).value = stackToString(smileToEasy(document.getElementById(TXT_SMILE_ID).value));
    drawSmile();
}

/**
 * SMILEs to easy form
 * @param smile string SMILEs
 * @returns {Array} SMILEs
 */
function smileToEasy(smile) {
    let stack = [];
    smile.split('').forEach(c => {
        switch (c) {
            case ']':
                stack = isoText(stack);
                break;
            case '/':
            case '\\':
                break;
            case ')':
                let index = stack.length - 1;
                if (stack[index] === '(') {
                    stack.pop();
                } else {
                    stack.push(c);
                }
                break;
            default:
                stack.push(c);
                break;
        }
    });
    return stack;
}

/**
 * Go back in stack and solve [C@@H] -> C
 * @param stack
 * @returns {char[] | string}
 */
function isoText(stack) {
    let text = [];
    let c = ']';
    let last = '';
    while (c != '[') {
        switch (c) {
            case '@':
                break;
            case 'H':
                if (last !== '@') {
                    text.unshift(c);
                }
                break;
            default:
                text.unshift(c);
                break;
        }
        last = c;
        c = stack.pop();
    }
    text.unshift('[');

    if (text.length === 3 && text[1] === 'H') {
        text = [];
    }
    if (text.length === 3) {
        text = text[1];
    }
    if (text.length === 4) {
        text = text[1];
    }

    stack = stack.concat(text);
    return stack;
}

/**
 * Return string from stack
 * @param stack
 * @returns {string}
 */
function stackToString(stack) {
    var text = "";
    stack.forEach(e => {
        text += e;
    });
    return text;
}

/** activate screen mode (light/dark) */
function activateScreenMode() {
    switch (DEFAULT_SCREEN_MODE) {
        case MODE_LIGHT:
        default:
            lightMode();
            break;
        case MODE_DARK:
            darkMode();
            break;
    }
}

/** activate dark mode */
function darkMode() {
    document.getElementById(CANVAS_ID).style.backgroundColor = COLOR_BLACK;
}

/** activate light mode */
function lightMode() {
    document.getElementById(CANVAS_ID).style.backgroundColor = COLOR_WHITE;
}

function changeSmilesInput() {
    options.drawDecayPoints = 1;
    options.decaySource = [];
    updateOptions();
}

/** draw smiles to main canvas */
function drawSmile() {
    if (!decaysNoRedraw) {
        decaysNoRedraw = true;
    } else {
        document.getElementById('hdn-decays').value = '';
    }
    // Clean the input (remove unrecognized characters, such as spaces and tabs) and parse it
    let strSmiles = document.getElementById(TXT_SMILE_ID).value;
    strSmiles = strSmiles.replace(/\r?\n|\r/g, '');
    strSmiles = strSmiles.trim();
    SmilesDrawer.parse(strSmiles, function (tree) {
        // Draw to the canvas
        activateScreenMode();
        smilesDrawer.draw(tree, CANVAS_ID, DEFAULT_SCREEN_MODE, false);
        document.getElementById(TXT_CANVAS_FLE_ID).value = smilesDrawer.getMolecularFormula();
        canvasRef.style.width = '100%';
        canvasRef.style.height = '100%';
    });
}

/**
 * Draw SMILES to small preview
 * @param canvasId - where to draw SMILES
 */
function drawSmallSmile(canvasId) {
    drawSmall(canvasId);
}

/**
 * Clear large preview
 */
function clearLargeCanvas() {
    document.getElementById(CANVAS_LARGE_ID).style.display = "none";
}

function drawOrClearLargeSmile(canvasId) {
    if (mobile) return;
    if (lastLargeSmilesId === canvasId) {
        clearLargeCanvas();
        lastLargeSmilesId = null;
    } else {
        let canvas = document.getElementById(CANVAS_LARGE_ID);
        canvas.style.display = "block";
        drawLarge(canvasId);
        lastLargeSmilesId = canvasId;
    }
}

function drawSmall(canvasId) {
    SmilesDrawer.parse(document.getElementById(HIDDEN_SMILE_ID + canvasId).value, function (tree) {
        activateScreenMode();
        smallSmilesDrawer.draw(tree, CANVAS_SMALL_ID + canvasId, DEFAULT_SCREEN_MODE, false);
    });
}

function drawLarge(canvasId) {
    let canvas = document.getElementById(CANVAS_LARGE_ID);
    let context = canvas.getContext("2d");
    context.clearRect(0, 0, canvas.width, canvas.height);
    SmilesDrawer.parse(document.getElementById(HIDDEN_SMILE_ID + canvasId).value, function (tree) {
        activateScreenMode();
        largeSmilesDrawer.draw(tree, CANVAS_LARGE_ID, DEFAULT_SCREEN_MODE, false);
    });
}


function save() {
    let sequence = document.getElementById("txt-sequence").value;
    let data = {sequence: sequence, save: 'Save'};
    data.sequenceType = document.getElementById("sel-sequence-type").value;
    data.blockCount = document.getElementsByClassName("block-count")[0].value;
    data.decays = document.getElementById("hdn-block-decays").value;
    data.nSelect = document.getElementById("sel-n-modification").value;
    data.nModification = document.getElementById("txt-n-modification").value;
    data.nFormula = document.getElementById("txt-n-formula").value;
    data.nMass = document.getElementById("txt-n-mass").value;
    data.nTerminalN = document.getElementById("chk-n-nterminal").checked;
    data.nTerminalC = document.getElementById("chk-n-cterminal").checked;
    data.cSelect = document.getElementById("sel-c-modification").value;
    data.cModification = document.getElementById("txt-c-modification").value;
    data.cFormula = document.getElementById("txt-c-formula").value;
    data.cMass = document.getElementById("txt-c-mass").value;
    data.cTerminalN = document.getElementById("chk-c-nterminal").checked;
    data.cTerminalC = document.getElementById("chk-c-cterminal").checked;
    data.bSelect = document.getElementById("sel-b-modification").value;
    data.bModification = document.getElementById("txt-b-modification").value;
    data.bFormula = document.getElementById("txt-b-formula").value;
    data.bMass = document.getElementById("txt-b-mass").value;
    data.bTerminalN = document.getElementById("chk-b-nterminal").checked;
    data.bTerminalC = document.getElementById("chk-b-cterminal").checked;
    redirectWithData(FORM_MAIN, data);
}

function editorBlock(identifier) {
    let data = {editor: 'Edit'};
    data.database = document.getElementById("sel-canvas-database").value;
    data.search = document.getElementById("sel-canvas-search").value;
    data.name = document.getElementById("txt-canvas-name").value;
    data.smile = document.getElementById("txt-canvas-smile").value;
    data.formula = document.getElementById("txt-canvas-fle").value;
    data.mass = document.getElementById("txt-canvas-mass").value;
    data.identifier = document.getElementById("txt-canvas-identifier").value;
    data.sequence = document.getElementById("txt-sequence").value;
    data.sequenceType = document.getElementById("sel-sequence-type").value;
    data.decays = document.getElementById("hdn-block-decays").value;
    data.nSelect = document.getElementById("sel-n-modification").value;
    data.nModification = document.getElementById("txt-n-modification").value;
    data.nFormula = document.getElementById("txt-n-formula").value;
    data.nMass = document.getElementById("txt-n-mass").value;
    data.nTerminalN = document.getElementById("chk-n-nterminal").checked;
    data.nTerminalC = document.getElementById("chk-n-cterminal").checked;
    data.cSelect = document.getElementById("sel-c-modification").value;
    data.cModification = document.getElementById("txt-c-modification").value;
    data.cFormula = document.getElementById("txt-c-formula").value;
    data.cMass = document.getElementById("txt-c-mass").value;
    data.cTerminalN = document.getElementById("chk-c-nterminal").checked;
    data.cTerminalC = document.getElementById("chk-c-cterminal").checked;
    data.bSelect = document.getElementById("sel-b-modification").value;
    data.bModification = document.getElementById("txt-b-modification").value;
    data.bFormula = document.getElementById("txt-b-formula").value;
    data.bMass = document.getElementById("txt-b-mass").value;
    data.bTerminalN = document.getElementById("chk-b-nterminal").checked;
    data.bTerminalC = document.getElementById("chk-b-cterminal").checked;
    redirectWithData("form-block-edit" + identifier, data);
}

function editSequenceSmiles(url) {
    let data = {};
    try {
        data.database = document.getElementById("sel-canvas-database").value;
        data.search = document.getElementById("sel-canvas-search").value;
        data.name = document.getElementById("txt-canvas-name").value;
        data.smile = document.getElementById("txt-canvas-smile").value;
        data.formula = document.getElementById("txt-canvas-fle").value;
        data.mass = document.getElementById("txt-canvas-mass").value;
        data.identifier = document.getElementById("txt-canvas-identifier").value;
    } catch (e) {
        console.log(e);
    } finally {
        redirectOnlyWithData(url, data);
    }
}

function modificationSelect(event) {
    displayModification(event.target.id, event.target.value != 0);
}

function displayModification(id, display) {
    switch (id) {
        case SEL_N_MODIFICATION:
            document.getElementById('txt-n-modification').disabled = display;
            document.getElementById('txt-n-formula').disabled = display;
            document.getElementById('txt-n-mass').disabled = display;
            document.getElementById('chk-n-nterminal').disabled = display;
            document.getElementById('chk-n-cterminal').disabled = display;
            break;
        case SEL_C_MODIFICATION:
            document.getElementById('txt-c-modification').disabled = display;
            document.getElementById('txt-c-formula').disabled = display;
            document.getElementById('txt-c-mass').disabled = display;
            document.getElementById('chk-c-nterminal').disabled = display;
            document.getElementById('chk-c-cterminal').disabled = display;
            break;
        case SEL_B_MODIFICATION:
            document.getElementById('txt-b-modification').disabled = display;
            document.getElementById('txt-b-formula').disabled = display;
            document.getElementById('txt-b-mass').disabled = display;
            document.getElementById('chk-b-nterminal').disabled = display;
            document.getElementById('chk-b-cterminal').disabled = display;
            break;
    }
}

function redirectWithData(formId, data) {
    let form = document.getElementById(formId);
    form.method = 'post';
    for (let name in data) {
        let input = document.createElement('input');
        input.type = 'hidden';
        input.name = name;
        input.value = data[name];
        form.appendChild(input);
    }
    form.submit();
}

function redirectOnlyWithData(url, data) {
    let form = document.createElement('form');
    form.method = 'post';
    form.action = url;
    document.getElementsByTagName('BODY')[0].appendChild(form);
    for (let name in data) {
        let input = document.createElement('input');
        input.type = 'hidden';
        input.name = name;
        input.value = data[name];
        form.appendChild(input);
    }
    form.submit();
}

function changeSortArrows() {
   let sortArrow = getGetValue();
   if (sortArrow !== {}) {
       let is2 = document.getElementsByClassName(sortArrow.key)[0];
       is2.className = "fa fa-sort-" + translateSortOrder(sortArrow.value) + ' ' + sortArrow.key;
   }
}

let sortArray = ['type', 'name', 'acronym', 'residue', 'formula', 'losses', 'mass', 'smiles', 'sequence', 'nterminal', 'cterminal'];

function translateSortOrder(order) {
    return order === 'desc' ? 'down' : 'up';
}

function getGetValue() {
    let parameters = getGetParameters();

    for (let index = 0; index <= sortArray.length; ++index) {
        let value = findGetParameterValue(sortArray[index] + 'Sort', parameters);
        if (value != null) {
            return {key: sortArray[index], value: value};
        }
    }
    return {};
}

function getGetParameters() {
    return location.search.substr(1).split("&");
}

function findGetParameterValue(parameterName, params) {
    var result = null, tmp = [];
    params.forEach(function (item) {
            tmp = item.split("=");
            if (tmp[0] === parameterName) result = decodeURIComponent(tmp[1]);
        });
    return result;
}

function findGetParameter(parameterName) {
    var result = null,
        tmp = [];
    location.search
        .substr(1)
        .split("&")
        .forEach(function (item) {
            tmp = item.split("=");
            if (tmp[0] === parameterName) result = decodeURIComponent(tmp[1]);
        });
    return result;
}

function sort(param, sort, direction = 'asc') {
    let sortQuery = AMPERSAND + sort + 'Sort=' + direction;
    filter(param, sortQuery);
}

function filter(param, sort = '') {
    let query = '?' + sort +
        filterValue('filter-name', 'name') +
        filterValue('filter-acronym', 'acronym') +
        filterValue('filter-residue', 'residue') +
        filterValue('filter-losses', 'losses') +
        filterValue('filter-mass-from', 'massFrom') +
        filterValue('filter-mass-to', 'massTo') +
        filterValue('filter-smiles', 'smiles') +
        filterValue('filter-type', 'type') +
        filterValue('filter-formula', 'formula') +
        filterValue('filter-sequence', 'sequence') +
        filterValue('filter-nterminal', 'nterminal') +
        filterValue('filter-cterminal', 'cterminal');
    window.location.href = param + query;
}

function getInputData(id) {
    return document.getElementById(id).value;
}

function filterValue(id, key) {
    try {
        let name = getInputData(id);
        return AMPERSAND + key + '=' + name;
    } catch (e) {
        console.log(e);
        return '';
    }
}
