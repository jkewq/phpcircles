<?php

// RESTRICTION: the circles can't intersect each other
// KNOWN ISSUES: it don't work on too many circles (timeout); the average distance between the circles increase;
//               the average radius decreases

const IMG_WIDTH = 640;
const IMG_HEIGHT = 480;
const EDGE_PADDING = 20; 
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
define('X', 0);
define('Y', 1);
define('R', 2);

$circlesList = [];

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

function isIntersectingOtherCircles(int $r, int $pX, int $pY): bool {
	global $circlesList;
	if (is_iterable($circlesList) === true && count($circlesList) > 0) {
		foreach($circlesList as $circle) {
			$dist = floor(sqrt(($pX-$circle[X])**2 + ($pY-$circle[Y])**2));
			if ($dist <= $r+$circle[R]) {
				return true;	
			}
		}
	}
	return false;
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
	if (isIntersectingOtherCircles($radius, $pX, $pY) === true) {
		$i--;
		continue;
	}
	$colorIndex = rand(0, $colorBound);
	if (FILLED === true) {
		imagefilledarc($img, $pX, $pY, $radius, $radius, 0, 360, $colorAlloc[$colorIndex], IMG_ARC_PIE);
	} else {
		imagearc($img, $pX, $pY, $radius, $radius, 0, 360, $colorAlloc[$colorIndex]);	
	}
	array_push($circlesList, [$pX, $pY, $radius]);
}

header("Content-type: image/png");
imagepng($img);

image_destroy($img);