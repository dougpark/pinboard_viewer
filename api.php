<?php
// main api class
// uses pinboard-api.php
// source: https://github.com/kijin/pinboard-api

include 'pinboard-api.php';

$pinboard = new PinboardAPI('parkdn', 'parkdn:25B74F40CD4358B19F5A');

$bookmarks = $pinboard->get_recent();

$output = "";

foreach ($bookmarks as $bookmark) {
    $pubDate = date('D, M d y', $bookmark->timestamp);
    $output .= $bookmark->url . "<br>";
    $output .= $bookmark->title . "<br>";
    $output .= $bookmark->description . "<br>";
    $output .= $pubDate . "<br><br>";
}

echo $output;
