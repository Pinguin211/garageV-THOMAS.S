import $ from "jquery";

export function getSvgCross(attr = {}) {
    const cross = $('<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 297 297">\n' +
        '    <g>\n' +
        '        <path d="M108.388,297c-2.616,0.002-5.126-1.038-6.977-2.888c-1.849-1.851-2.889-4.359-2.889-6.977l0.014-88.653l-88.621,0.012' +
        '            c-2.616,0-5.125-1.038-6.976-2.888c-1.851-1.85-2.889-4.358-2.889-6.977l0.014-80.222c0.001-5.445,4.415-9.86,9.861-9.86' +
        '            l88.626-0.014l0.016-88.662c0.001-5.445,4.415-9.858,9.86-9.86L188.616,0c2.617,0,5.126,1.038,6.977,2.888' +
        '            c1.85,1.851,2.888,4.359,2.888,6.977l-0.013,88.655l88.617-0.012c2.616,0,5.125,1.038,6.976,2.887' +
        '            c1.851,1.852,2.889,4.359,2.889,6.976l-0.012,80.22c-0.001,5.445-4.415,9.86-9.86,9.86l-88.629,0.016l-0.014,88.658' +
        '            c-0.001,5.445-4.415,9.86-9.86,9.86L108.388,297z M108.399,178.756c2.616,0,5.125,1.038,6.976,2.888' +
        '            c1.85,1.85,2.889,4.359,2.889,6.977l-0.014,88.653l60.462-0.012l0.014-88.659c0-5.445,4.414-9.86,9.86-9.86l88.627-0.016' +
        '            l0.009-60.494l-88.618,0.014c-2.615,0-5.124-1.04-6.975-2.89c-1.851-1.849-2.889-4.359-2.889-6.975l0.014-88.655l-60.464,0.008' +
        '            l-0.016,88.664c0,5.446-4.414,9.859-9.861,9.861l-88.625,0.012l-0.01,60.498L108.399,178.756z"/>' +
        '    </g>\n' +
        '</svg>')
    cross.attr(attr)
    return cross
}


export function getSvgPhoto(attr = {}) {
    const photo = $('<svg version="1.0" xmlns="http://www.w3.org/2000/svg" width="1280.000000pt" height="896.000000pt" viewBox="0 0 1280.000000 896.000000" preserveAspectRatio="xMidYMid meet" data-arp-injected="true">\n' +
        '<g transform="translate(0.000000,896.000000) scale(0.100000,-0.100000)">' +
        '<path d="M3964 8653 c-87 -170 -235 -459 -330 -643 l-172 -335 -1731 -3 -1731 -2 0 -3780 0 -3780 2068 0 c1138 0 2072 3 2075 7 4 3 -23 29 -60 57 -401 305 -746 696 -1007 1143 l-48 83 -874 0 -874 0 0 2495 0 2495 1483 0 1482 0 330 645 330 645 1495 0 1495 0 330 -645 330 -645 1483 0 1482 0 0 -2495 0 -2495 -874 0 -874 0 -48 -82 c-256 -440 -582 -813 -976 -1120 -53 -42 -94 -78 -91 -82 3 -3 937 -6 2075 -6 l2068 0 0 3780 0 3780 -1731 2 -1731 3 -329 640 -328 640 -2280 3 -2280 2 -157 -307z"/>' +
        '<path d="M6140 6434 c-19 -2 -78 -9 -130 -15 -132 -14 -362 -61 -507 -103 -394 -114 -756 -300 -1101 -568 -132 -103 -402 -369 -510 -505 -324 -403 -548 -878 -647 -1368 -52 -256 -59 -333 -60 -645 0 -292 5 -368 41 -570 232 -1298 1247 -2338 2534 -2594 249 -50 358 -60 640 -60 282 0 391 10 640 60 956 190 1783 819 2230 1695 172 338 276 676 332 1079 20 147 17 654 -5 800 -93 617 -298 1100 -669 1578 -104 134 -372 405 -503 508 -479 378 -971 593 -1570 686 -101 15 -191 21 -405 23 -151 2 -291 1 -310 -1z m514 -1289 c1125 -148 1886 -1234 1640 -2344 -147 -663 -644 -1211 -1288 -1420 -702 -229 -1456 -49 -1975 470 -387 387 -588 897 -568 1439 11 295 66 516 193 777 283 584 841 990 1481 1078 135 18 378 18 517 0z"/>' +
        '</g>' +
        '</svg>')
    photo.attr(attr)
    return photo
}