/** constants HTML id */
const CANVAS_ID = "canvas-main";
const TXT_SMILE_ID = "txt-canvas-smile";
const HIDDEN_SMILE_ID = "hidden-canvas-small-";
const CANVAS_SMALL_ID = "canvas-small-";
const CANVAS_LARGE_ID = "canvas-large";

/** constants mode */
const MODE_LIGHT = "light";
const MODE_DARK = "dark";

/** constants colors */
const COLOR_WHITE = "white";
const COLOR_BLACK = "black";

/** Initialize the drawer */

let smilesDrawer = getSmilesDrawer();
let smallSmilesDrawer = getSmallSmilesDrawer();
let largeSmilesDrawer = getLargeSmilesDrawer();

/** Resize event */
window.addEventListener('resize', resize);

function resize() {
    let canvas = document.getElementById(CANVAS_ID);
    canvas.style.width = "100%";
    canvas.style.height = "100%";
    let context = canvas.getContext("2d");
    context.clearRect(0, 0, canvas.width, canvas.height);
    smilesDrawer = getSmilesDrawer();
    drawSmile();
}

window.onload = function () {
    var smallCanvases = document.querySelectorAll('[data-canvas-small-id]');
    for (let i = 0; i < smallCanvases.length; i++) {
        let elem = smallCanvases[i];
        drawSmallSmile(elem.getAttribute("data-canvas-small-id"));
        if (i === 0) {
            document.location.href = "#h-results";
        }
    }
    resize();
};

/** default screen mode (dark/light) def = light */
let DEFAULT_SCREEN_MODE = MODE_LIGHT;

/**
 * Get SMILES Drawer instance with dimension of canvas
 */
function getSmilesDrawer() {
    return new SmilesDrawer.Drawer({width: getCanvasWidth(), height: getCanvasHeight(), padding: 10});
}

function getSmallSmilesDrawer() {
    return new SmilesDrawer.Drawer({width: 300, height: 300});
}

function getLargeSmilesDrawer() {
    return new SmilesDrawer.Drawer({width: 1000, height: 1000});
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
 * Parse Isomeric SMILE to Cannonical SMILE
 */
function easy() {
    /** get smile from txt, parse it and put it back */
    document.getElementById(TXT_SMILE_ID).value = stackToString(smileToEasy(document.getElementById(TXT_SMILE_ID).value));
    /** refresh canvas */
    drawSmile();
}

/**
 * SMILEs to easy
 * TODO form !! [NH3+] not functional !!
 * @param smile string SMILEs
 * @returns {Array} SMILEs
 */
function smileToEasy(smile) {
    var stack = [];
    smile.split('').forEach(c => {
        switch (c) {
            case ']':
                stack = isoText(stack);
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
 * @param {[]} stack
 * @returns stack
 */
function isoText(stack) {
    let text = [];
    let c = ']';
    while (c != '[') {
        switch (c) {
            case '@':
            case 'H':
            case ']':
                break;
            default:
                text.push(c);
                break;
        }
        c = stack.pop();
    }
    stack = stack.concat(text);
    return stack;
}

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

function drawSmile() {
    // Clean the input (remove unrecognized characters, such as spaces and tabs) and parse it
    SmilesDrawer.parse(document.getElementById(TXT_SMILE_ID).value, function (tree) {
        // Draw to the canvas
        activateScreenMode();
        smilesDrawer.draw(tree, CANVAS_ID, DEFAULT_SCREEN_MODE, false);
        document.getElementById(TXT_CANVAS_FLE).value = smilesDrawer.getMolecularFormula();

        // let edge = smilesDrawer.graph.edges[0];
        // let vertexA = smilesDrawer.graph.vertices[edge.sourceId].position;
        // let vertexB = smilesDrawer.graph.vertices[edge.targetId].position;

        // var line = new SmilesDrawer.Line(vertexA, vertexB);
        // console.log(vertexA);
        // console.log(vertexB);
        // console.log(line);
        // line = line.rotate(Math.PI/2);

        // //asi nejdulezitejsi radka kodu :-) :D :P
        // smilesDrawer.canvasWrapper.scale(smilesDrawer.graph.vertices);
        // drawLine2(smilesDrawer.canvasWrapper, line);
        // smilesDrawer.reset();
    });
}

function drawSmallSmile(canvasId) {
    drawSmall(canvasId);
}

function clearLargeCanvas() {
    console.log("clear");
    let canvas = document.getElementById(CANVAS_LARGE_ID);
    canvas.style.display = "none";
}

function drawLargeSmile(canvasId) {
    let canvas = document.getElementById(CANVAS_LARGE_ID);
    canvas.style.display = "block";
    drawLarge(canvasId);
}

function drawSmall(canvasId) {
    SmilesDrawer.parse(document.getElementById(HIDDEN_SMILE_ID + canvasId).value, function (tree) {
        activateScreenMode();
        smallSmilesDrawer.draw(tree, CANVAS_SMALL_ID + canvasId, DEFAULT_SCREEN_MODE, false);
    });
}

function drawLarge(canvasId) {
    console.log("large");
    let canvas = document.getElementById(CANVAS_LARGE_ID);
    let context = canvas.getContext("2d");
    context.clearRect(0, 0, canvas.width, canvas.height);
    SmilesDrawer.parse(document.getElementById(HIDDEN_SMILE_ID + canvasId).value, function (tree) {
        activateScreenMode();
        largeSmilesDrawer.draw(tree, CANVAS_LARGE_ID, DEFAULT_SCREEN_MODE, false);
    });
}

// function drawLine2(wr, line) {
//     let l = line.getLeftVector();
//     let r = line.getRightVector();
//
//     l.x += wr.offsetX;
//     l.y += wr.offsetY;
//
//     r.x += wr.offsetX;
//     r.y += wr.offsetY;
//
//     wr.ctx.save();
//     wr.ctx.beginPath();
//     wr.ctx.moveTo(l.x, l.y);
//     wr.ctx.lineTo(r.x, r.y);
//     wr.ctx.lineCap = 'round';
//     wr.ctx.lineWidth = wr.opts.bondThickness;
//     wr.ctx.strokeStyle = "#FF0000";
//     wr.ctx.stroke();
//     wr.ctx.globalCompositeOperation = 'source-over';
//     wr.ctx.restore();
// }

function disintegrate() {

}