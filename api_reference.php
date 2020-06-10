<?php
// https://pinboard.in/api/


$params = "";

// https://api.pinboard.in/v1/posts/recent/?auth_token=xxx&format=json

$apiToken = "";

// return all tags
$query = "tags/get";

// delete selected tags
$query = "tags/delete";
$key = "tag=";
$params = "one two";

// rename tag
$query = "tags/rename";
$key = "old=one&new=won"; // crap need more than one key, params
$params = "";

// return secret rss key
$query = "user/secret";

// return a list of suggested tags for url
$query = "posts/suggest";
$key = "url=";
$params = "https://www.w3schools.com";

// return all posts filtere by tag=, start=, results=, more...
$query = "posts/all";
$key = "tag=";
$params = "javascript programming";

// return count of post per day per tag
$query = "posts/dates";
$key = "tag=";
$params = "programming";

// return recent posts filter by tag or count, return default 15, max 100
$query = "posts/recent";
$key = "tag=programming&count=5";
$params = "";

// return one or more based on filter tag, date, url
$query = "posts/get";
$key = "url=";
$params = "https://noahgilmore.com/blog/dark-mode-uicolor-compatibility/";

// delete selected bookmark by url
$query = "posts/delete";
$key = "url=";
$params = "one.html";

// add a new bookmark
// See: https://pinboard.in/api/#posts/add
$query = "posts/add";
$key = "";
$params = "";

// return last date/time a bookmark was added/updated/created
// call before updating all to see if anything changed
$query = "posts/update";
$key = "";
$params = "";

// return list of notes
$query = "notes/list";
$key = "";
$params = "";

// return a specific note based on note id (notice no parm key used)
$query = "notes/49726c69058e34077bdc";
$key = "";
$params = "";

// return all posts filtere by tag=, start=, results=, more...
$query = "posts/all";
$key = "tag=";
$params = "javascript programming";

// return recent posts filter by tag or count, return default 15, max 100
$query = "posts/recent";
$key = "";
$params = "";

$url1 = "https://api.pinboard.in/v1/" . $query . "/?auth_token=" . $apiToken . "&format=json&" . $key;
$url2 = urlencode($params);
$urlenc = $url1 . $url2;
$feeds = file_get_contents($urlenc);

echo $feeds;
