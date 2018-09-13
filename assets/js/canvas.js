/** constants HTML id */
const CANVAS_ID = "canvas-main";
const TXT_SMILE_ID = "txt-canvas-smile";
const LBL_FORMULA = "label-formula";

/** constants mode */
const MODE_LIGHT = "light";
const MODE_DARK = "dark";

/** constants colors */
const COLOR_WHITE = "white";
const COLOR_BLACK = "black";

/** Initialize the drawer */

let smilesDrawer = new SmilesDrawer.Drawer({width: getCanvasWidth(), height: getCanvasHeight()});
/** Add event listener on input on change text*/
let input = document.getElementById(TXT_SMILE_ID);
input.addEventListener('input', drawSmile);

/** default screen mode (dark/light) def = light */
let DEFAULT_SCREEN_MODE = MODE_LIGHT;

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
 * SMILEs to easy form
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
    SmilesDrawer.parse(input.value, function(tree) {
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