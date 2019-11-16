<?php

const IMG_WIDTH = 640;
const IMG_HEIGHT = 480;
const BACKGROUND = [33, 33, 33];
const NUM_CIRCLES = 30;
const FILLED = true;
const RADIUS_MIN = 30;
const RADIUS_MAX = 200;
const COLOR_DEFS = [
	[255, 0, 0],
	[0, 255, 0],
	[0, 0, 255],
	[255, 255, 0],
	[255, 255, 255]
];

define('RED', 0);
define('GREEN', 1);
define('BLUE', 2);

$img = imagecreatetruecolor(IMG_WIDTH, IMG_HEIGHT);
$bgColor = imagecolorallocate($img, BACKGROUND[RED], BACKGROUND[GREEN], BACKGROUND[BLUE]);
imagefilledrectangle($img, 0, 0, IMG_WIDTH, IMG_HEIGHT, $bgColor);

$colorAlloc = [];
for($i=0;$i<count(COLOR_DEFS);$i++) {
	$colorDef = COLOR_DEFS[$i];
	$colorAlloc[$i] = imagecolorallocate($img, $colorDef[RED], $colorDef[GREEN], $colorDef[BLUE]);
}

$colorBound = count(COLOR_DEFS)-1;

for($i=0;$i<NUM_CIRCLES;$i++) {
	$colorIndex = rand(0, $colorBound);
	$pX = rand(0, IMG_WIDTH);
	$pY = rand(0, IMG_HEIGHT);
	$radius = rand(RADIUS_MIN, RADIUS_MAX);
	if (FILLED === true) {
		imagefilledarc($img, $pX, $pY, $radius, $radius, 0, 360, $colorAlloc[$colorIndex], IMG_ARC_PIE);
	} else {
		imagearc($img, $pX, $pY, $radius, $radius, 0, 360, $colorAlloc[$colorIndex]);	
	}
}

header("Content-type: image/png");
imagepng($img);

image_destroy($img);