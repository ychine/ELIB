<?php
if (class_exists('Imagick')) {
    echo "Imagick is WORKING!<br>";
    $im = new Imagick();
    $im->newImage(1, 1, new ImagickPixel('#ffffff'));
    $im->setImageFormat('png');
    $pngData = $im->getImagesBlob();
    echo strpos($pngData, "\x89PNG\r\n\x1a\n") === 0 ? 'Ok' : 'Failed';
} else {
    echo "Imagick NOT loaded";
}
phpinfo();
?>