<?php
/**
 * ImageResizerExample.
 *
 *  How to resize an image from a URL
 *
 */

require_once "DotMaui.php";

$APIKEY = "YOUR_API_KEY";

$client  = new DotMaui\Client($APIKEY);
$img_req = new DotMaui\ImgResizerRequest();

$img_req->Url    = "https://dotmaui.com/android-chrome-192x192.png";
$img_req->Height = 50;
$img_req->Width  = 100;

if ($client->saveImgResizedFromUrl($img_req, "C:\\Users\\dm\\Pictures\\dotmaui.png")) {
    echo "Success.";
}
else {
    echo "Failed.";

}