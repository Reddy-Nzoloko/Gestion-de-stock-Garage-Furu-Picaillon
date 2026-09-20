<?php
declare(strict_types=1);

$size = 64;
$image = imagecreatetruecolor($size, $size);
imagealphablending($image, false);
imagesavealpha($image, true);
$transparent = imagecolorallocatealpha($image, 0, 0, 0, 127);
imagefill($image, 0, 0, $transparent);
$red = imagecolorallocate($image, 237, 7, 17);
$darkRed = imagecolorallocate($image, 142, 0, 8);
$points = [10, 14, 18, 14, 26, 41, 32, 26, 38, 41, 46, 14, 54, 14, 43, 52, 36, 52, 32, 42, 28, 52, 21, 52];
imagefilledpolygon($image, $points, $red);
imagefilledpolygon($image, [10, 14, 18, 14, 27, 52, 21, 52], $darkRed);
imagefilledpolygon($image, [46, 14, 54, 14, 43, 52, 37, 52], $darkRed);
ob_start();
imagepng($image);
$png = ob_get_clean();
imagedestroy($image);
$ico = pack('vvv', 0, 1, 1);
$ico .= pack('CCCCvvVV', $size, $size, 0, 0, 1, 32, strlen($png), 22);
$ico .= $png;
file_put_contents(__DIR__ . '/../public/assets/favicon.ico', $ico);
echo "favicon.ico créé\n";
