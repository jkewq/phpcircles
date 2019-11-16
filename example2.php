<?php

const IMG_WIDTH = 640;
const IMG_HEIGHT = 480;
const EDGE_PADDING = 20; // distance from the borders where the centre of circle can't be placed
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

function isCentreInEdgeArea(int $pX, int $pY): bool {
	if ($pX < EDGE_PADDING || $pX > IMG_WIDTH-EDGE_PADDING) {
		return true;	
	}
	if ($pY < EDGE_PADDING || $pY > IMG_HEIGHT-EDGE_PADDING) {
		return true;
	}
	return false;
}

function limitRadius(int $r, int $pX, int $pY): int {
	$distLeftBorder = $pX;
	$distRightBorder = IMG_WIDTH - $pX;
	$distTopBorder = $pY;
	$distBottomBorder = IMG_HEIGHT - $pY;
	if ($r > $distLeftBorder || $r > $distRightBorder || $r > $distTopBorder || $r > $distBottomBorder) {
		return min($distLeftBorder, $distRightBorder, $distTopBorder, $distBottomBorder);
	}
	return $r;
}

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
	$pX = rand(0, IMG_WIDTH);
	$pY = rand(0, IMG_HEIGHT);
	if (isCentreInEdgeArea($pX, $pY) === true) {
		$i--;
		continue;
	}
	$radius = limitRadius(rand(RADIUS_MIN, RADIUS_MAX), $pX, $pY);
	$colorIndex = rand(0, $colorBound);
	if (FILLED === true) {
		imagefilledarc($img, $pX, $pY, $radius, $radius, 0, 360, $colorAlloc[$colorIndex], IMG_ARC_PIE);
	} else {
		imagearc($img, $pX, $pY, $radius, $radius, 0, 360, $colorAlloc[$colorIndex]);	
	}
}

header("Content-type: image/png");
imagepng($img);

image_destroy($img);