<?php

namespace Potherca\PlantUml\DiagramGenerator;

require dirname(__DIR__) . '/vendor/autoload.php';

$content = '';

$images = glob('../build/plantuml-images/*.png');

$images = array_filter($images, function ($image) {
    return strpos($image, '-old') === false;
});

$images = array_combine($images, $images);

$images = array_map(function ($imageName) {
    $imageName = basename($imageName);
    return str_replace('3o1-', '', $imageName);
},$images);

$images = array_flip($images);

ksort($images);

array_walk($images, function ($imageSource, $imageName) use (&$content, $diagrams) {
    $content .= vsprintf(<<<'HTML'
<li>
    <span>%s</span>
    <img src="%s" />
</li>
HTML
        , [$imageName, $imageSource]
    );
});

echo vsprintf(<<<'HTML'
<!doctype html>
<html>
<link rel="stylesheet" href="style.css" />
<ul>
    %s
</ul>
</html>
HTML
    ,[$content]
);

/*EOF*/
