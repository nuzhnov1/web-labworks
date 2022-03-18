<?php
include '../../Lab2/Materials/global.php';
include 'error.php';

global $g_products;

const IM_WIDTH = 1920;
const IM_HEIGHT = 1080;
const TEXT_HEIGHT = 40;
const VALUE_HEIGHT = 40;
const COLUMN_OFFSET_PERCENT = 10;

try {
    putenv("GDFONTPATH=" . realpath("."));

    $field = $_GET['field'];
    if ($field == 'manufacturer')
        $table = $g_products->get_avg_price_for_each_manufacturer();
    else
        $table = $g_products->get_avg_price_for_each_vendor();

    $im = imagecreate(IM_WIDTH, IM_HEIGHT);

    $BACKGROUND_COLOR = imagecolorallocate($im, 255, 255, 255);
    $TEXT_COLOR = imagecolorallocate($im, 0, 0, 0);

    $ROWS_COUNT = count($table);
    $HEIGHT = IM_HEIGHT - (TEXT_HEIGHT + VALUE_HEIGHT);
    $COLUMN_WIDTH_PLUS_OFFSET = floor(IM_WIDTH / $ROWS_COUNT);
    $COLUMN_WIDTH = round($COLUMN_WIDTH_PLUS_OFFSET * (1 - COLUMN_OFFSET_PERCENT / 100));
    $OFFSET = $COLUMN_WIDTH_PLUS_OFFSET - $COLUMN_WIDTH;
    $MAX_PRICE = $table[0]['avg_price'];

    imagefill($im, 0, 0, $BACKGROUND_COLOR);

    for ($i = 0, $x1 = -($COLUMN_WIDTH + $OFFSET); $i < $ROWS_COUNT; $i++) {
        $row = $table[$i];
        $name = $row['name'];
        $price = round($row['avg_price'], 2);

        $red = rand(0, 200);
        $green = rand(0, 200);
        $blue = rand(0, 200);
        $column_color = imagecolorallocate($im, $red, $green, $blue);

        $x1 += $COLUMN_WIDTH + $OFFSET;
        $y1 = (IM_HEIGHT - 1) - TEXT_HEIGHT;
        $x2 = $x1 + $COLUMN_WIDTH;
        $y2 = $y1 - floor(($price / $MAX_PRICE) * ($HEIGHT));

        $name_box = imagettfbbox(18, 0, "TimesNewRoman.ttf", $name);
        $name_offset = round((($x2 - $x1) - ($name_box[2] - $name_box[0])) / 2);
        $name_height = $name_box[7] - $name_box[1];
        $price_box = imagettfbbox(18, 0, "TimesNewRoman.ttf", $price);
        $price_offset = round((($x2 - $x1) - ($price_box[2] - $price_box[0])) / 2);

        imagefilledrectangle($im, $x1, $y1, $x2, $y2, $column_color);
        imagettftext($im, 18, 0, $x1 + $name_offset, $y1 - $name_height, $TEXT_COLOR, "TimesNewRoman.ttf", $name);
        imagettftext($im, 18, 0, $x1 + $price_offset, $y2, $TEXT_COLOR, "TimesNewRoman.ttf", $price);
    }

    imagepng($im);
}
catch (RuntimeException $e) {
    echo generate_error_page($e->getMessage());
}
