/** constants HTML id */
const CANVAS_ID = "canvas-main";
const TXT_SMILE_ID = "txt-canvas-smile";
const HIDDEN_SMILE_ID = "hidden-canvas-small-";
const CANVAS_SMALL_ID = "canvas-small-";
const CANVAS_LARGE_ID = "canvas-large";
const FORM_MAIN = "form-main";
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

/** default options for main drawer */
let options = {
    width: getCanvasWidth(),
    height: getCanvasHeight(),
    themes: {light: {O: '#e67e22', DECAY: '#ff0000'}},
    drawDecayPoints: true,
    compactDrawing: false
};

/** Initialize the drawers */
let smilesDrawer = getSmilesDrawer();
let smallSmilesDrawer = getSmallSmilesDrawer();
let largeSmilesDrawer = getLargeSmilesDrawer();
let canvasRef = document.getElementById(CANVAS_ID);
let offsetX = canvasRef.offsetLeft;
let offsetY = canvasRef.offsetTop;

/** events */
document.addEventListener('DOMContentLoaded', function () {
    window.addEventListener('resize', resize);
    window.addEventListener('load', finder);
    document.getElementById(TXT_SMILE_ID).addEventListener('input', drawSmile);
    canvasRef.addEventListener('click', function (e) {
        smilesDrawer.handleMouseClick(e, offsetX, offsetY);
    });
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
    canvas.style.width = "100%";
    canvas.style.height = "100%";
    let context = canvas.getContext("2d");
    context.clearRect(0, 0, canvas.width, canvas.height);
    smilesDrawer = getSmilesDrawer();
    drawSmile();
}

function disintegrate() {
    let smiles = smilesDrawer.buildBlockSmiles();
    let data = {blockSmiles: smiles, blocks: 'Blocks', first: true};
    redirectWithData(FORM_MAIN, data);
}

/** default screen mode (dark/light) def = light */
let DEFAULT_SCREEN_MODE = MODE_LIGHT;

/**
 * Get SMILES Drawer instance with dimension of canvas
 */
function getSmilesDrawer() {
    return new SmilesDrawer.Drawer(options);
}

/** Get SMILES Drawer instance for small preview */
function getSmallSmilesDrawer() {
    return new SmilesDrawer.Drawer({width: CANVAS_SMALL_SQUARE, height: CANVAS_SMALL_SQUARE });
}

/** Get SMILES Drawer instance for large preview */
function getLargeSmilesDrawer() {
    return new SmilesDrawer.Drawer({width: getWindowWidth() * PROCENT_SIXTY, height: getWindowHeight() + PIXEL_TWO });
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
    return document.getElementById(CANVAS_ID).offsetWidth;
}

/**
 * Get current height of canvas
 */
function getCanvasHeight() {
    return document.getElementById(CANVAS_ID).offsetHeight;
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
    while (c != '[') {
        switch (c) {
            case '@':
            case 'H':
                break;
            default:
                text.unshift(c);
                break;
        }
        c = stack.pop();
    }
    text.unshift('[');

    if (text.length === 3) {
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

/** draw smiles to main canvas */
function drawSmile() {
    // Clean the input (remove unrecognized characters, such as spaces and tabs) and parse it
    let strSmiles = document.getElementById(TXT_SMILE_ID).value;
    strSmiles = strSmiles.replace(/\r?\n|\r/g, '');
    strSmiles = strSmiles.trim();
    SmilesDrawer.parse(strSmiles, function (tree) {
        // Draw to the canvas
        activateScreenMode();
        smilesDrawer.draw(tree, CANVAS_ID, DEFAULT_SCREEN_MODE, false);
        // document.getElementById(TXT_CANVAS_FLE).value = smilesDrawer.getMolecularFormula();
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
