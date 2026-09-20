<?php
declare(strict_types=1);

$size = 512;
$image = imagecreatetruecolor($size, $size);
imagealphablending($image, false);
imagesavealpha($image, true);
$transparent = imagecolorallocatealpha($image, 0, 0, 0, 127);
imagefill($image, 0, 0, $transparent);
imageantialias($image, true);
$red = imagecolorallocate($image, 245, 9, 20);
$darkRed = imagecolorallocate($image, 145, 0, 8);
$points = [75, 108, 147, 108, 172, 120, 237, 300, 256, 270, 275, 300, 340, 120, 365, 108, 437, 108, 365, 378, 330, 378, 256, 270, 182, 378, 147, 378];
imagefilledpolygon($image, $points, $red);
imagefilledpolygon($image, [75, 108, 147, 108, 182, 378, 147, 378], $darkRed);
imagefilledpolygon($image, [365, 108, 437, 108, 365, 378, 330, 378], $darkRed);
imagepng($image, __DIR__ . '/../public/assets/logo.png', 6);
imagepng($image, __DIR__ . '/../public/assets/favicon.png', 6);
imagedestroy($image);
echo "logo.png et favicon.png créés\n";
