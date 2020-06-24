<?php
session_start();
// main api class
// uses pinboard-api.php
// source: https://github.com/kijin/pinboard-api

include 'pinboard-api.php';

// get configuration defaults
$configStr = file_get_contents("config.json");
$config = json_decode($configStr, false);

// get post params
$userid = (isset($_POST['userid']) ? $_POST['userid'] : null);
//$userid = $_POST['userid'];
if ($userid == '') {
    $userid = $config->userid;
}
$token = (isset($_POST['token']) ? $_POST['token'] : null);
//$token = $_POST['token'];
if ($token == '') {
    $token = $config->token;
}
$action = (isset($_POST['action']) ? $_POST['action'] : null);
//$action = $_POST['action'];
if ($action == '') {
    $action = $config->action;
}

$pref = (object) (isset($_POST['pref']) ? $_POST['pref'] : null);

// init Pinboard api
$pinboard = new PinboardAPI($userid, $token);

//$pinboard->enable_logging(function ($str) {
//     echo "$str\n";
// });

// take action
switch ($action) {
    case 'getRecent':
        $out = getRecent();
        break;
    case 'getByDate':
        $out = getByDate();
        break;
    case 'getByTag':
        $out = getByTag();
        break;
    case 'getByHost':
        $out = getByHost();
        break;
    case 'getTagCloud':
        $out = getTagCloud();
        break;
    default:
        $out = getRecent();
}

// all done return results to page
echo $out;


function getRecent($count = 100)
{
    global $pinboard, $pref;

    $bookmarks = $pinboard->get_recent($count);

    $output = formatBookmarks($bookmarks, "Recents");

    return $output;
}

function getTagCloud()
{
    global $pinboard, $pref;

    $tags = $pinboard->get_tags();
    $output = formatTagCloud($tags, 'Tags');
    return $output;
}

function getByDate()
{
    global $pinboard, $pref;

    $date = $_POST['date'];
    $bookmarks = $pinboard->search_by_date($date);
    $pubDate = date('D M d, Y', $date);
    $output = formatBookmarks($bookmarks, $pubDate);
    return $output;
}

function getByHost()
{
    global $pinboard, $pref;

    $host = $_POST['host'];
    $bookmarks = $pinboard->search_by_url($host);
    $output = formatBookmarks($bookmarks, $host);
    return $output;
}

function getByTag()
{
    global $pinboard, $pref;

    $pref = (object) $_POST['pref'];
    // var_dump($pref->priTag);

    $tag = $_POST['tag'];
    $bookmarks = $pinboard->search_by_tag($tag);
    $output = formatBookmarks($bookmarks, $tag);
    return $output;
}

function formatBookmarks($bookmarks, $heading)
{

    $l = sizeof($bookmarks);
    $output =  "<div class='text-center'><span class='h1 text-white'>$heading</span>";
    $output .= "<span class='h9 text-white '>$l</span></div>";
    $output .= "<div class='text-secondary text-center'><input type='text' class='text-secondary form-control-sm' id='filter'>";
    $output .= "</div> <br>";
    foreach ($bookmarks as $bookmark) {


        $url = $bookmark->url;

        $description = $bookmark->description;
        if (strlen($description) > 100) {
            $s = substr($description, 0, 100);
            $description = substr($s, 0, strrpos($s, ' ')) . '...';
        }

        $title = $bookmark->title;
        if ($title == 'Twitter') {
            $title = 'Twitter: ' . $description;
        }

        // cool way to trim a string at a word break
        if (strlen($title) > 100) {
            $s = substr($title, 0, 100);
            $title = substr($s, 0, strrpos($s, ' ')) . '...';
        }

        $dateLink = formatDate($bookmark);
        $hostLink = formatHost($bookmark);
        $tagLink = formatTags($bookmark);

        $link = 'javascript:openWin("' .  $url . '");';

        // $output .= "<tr>";
        // $output .= " <td class='text-nowrap p-1'>";
        $output .= "<div class='border-bottom' >";
        $output .= "  <a class='feed_title' target='_blank' href='" . $link . " '>";

        $output .= "   <div class='title'>";
        $output .= "    <span>" . $title . "</span>";
        $output .= "   </div>";

        $output .= "   <div class='desc'>";
        $output .= "    <span class='small'>" . $description . "</span>";
        $output .= "   </div>";
        $output .= "  </a>";

        $output .= "   <div class='desc align-right'>";
        $output .= $dateLink;
        $output .= $hostLink;
        $output .= $tagLink;
        $output .= "   </div>";
        $output .= "</div>";

        // $output .= " </td>";
        // $output .= "</tr>";
    }

    //$output .= "</table>";

    return $output;
}

function formatTagCloud($tags, $heading)
{
    global $pref;

    $l = sizeof($tags);
    $output =  "<div class='text-center'><span class='h1 text-white'>$heading</span>";
    $output .= "<span class='h9 text-white'>$l</span></div><br>";

    // $output =  "<h3 class='text-center text-white'>" . $heading . "</h3>";
    // $output .= "";

    foreach ($tags as $tag) {

        if ($tag->count >= $pref->priTag) {
            $ntag = "'" . trim($tag->tag) . "'";
            $taglink = "javascript:getByTag($ntag);";

            $output .= "  <a class='m-1 btn btn-dark' href=$taglink role='button'>";

            $output .= "   <span class='title'>";
            $output .= "    <span>" . $tag->tag . "</span>";
            $output .= "    <span class='badge badge-danger mb-2'>" . $tag->count . "</span>";
            $output .= "   </span>";

            $output .= "  </a>";
        }
    }
    $output .= "<hr>";

    foreach ($tags as $tag) {

        if ($tag->count >= 1 && $tag->count < $pref->priTag) {

            if ($tag->count <= $pref->lowTag) {
                $color = "badge-info";
            } else {
                $color = "badge-danger";
            }
            $ntag = "'" . trim($tag->tag) . "'";
            $taglink = "javascript:getByTag($ntag);";

            $output .= "  <a class='m-1 btn btn-dark' href=$taglink role='button'>";

            $output .= "   <span class='title'>";
            $output .= "    <span>" . $tag->tag . "</span>";
            $output .= "    <span class='badge $color mb-2'>" . $tag->count . "</span>";
            $output .= "   </span>";

            $output .= "  </a>";
        }
    }





    return $output;
}

function formatDate($bookmark)
{
    $output = "<span>";
    $pubDate = date('D M d, y', $bookmark->timestamp);
    $datelink = "javascript:getByDate('" . $bookmark->timestamp . "');";
    $output .= "    <a href=" . $datelink . " >";
    $output .= "      <span class='formatDate'>" . $pubDate . "</span>";
    $output .= "    </a>";
    $output .= "</span>";
    return $output;
}

function formatHost($bookmark)
{
    $output = "<span>";
    $host = parse_url($bookmark->url)['host'];
    $hostlink = "javascript:getByHost('" . $host . "');";
    $output .= "    <a href=" . $hostlink . " >";
    $output .= "      <span class='formatHost'>" . $host . "</span>";
    $output .= "    </a>";
    $output .= "</span>";
    return $output;
}

function formatTags($bookmark)
{
    $output = "<span class='float-right'>";
    foreach ($bookmark->tags as $tag) {
        $taglink = "javascript:getByTag('" . $tag . "');";
        $output .= "    <a href=" . $taglink . " >";
        $output .= "      <span class='formatTags '>" . $tag . " </span>";
        $output .= "    </a>";
    }
    $output .= "</span>";
    return $output;
}
