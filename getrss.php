<?php
//get the q parameter from URL
// q = u:pinboarduser/
// q = t:javascript/
// q = secret:xxxxxxx/u:pinboarduser/

//https://feeds.pinboard.in/rss/secret:1655b6cd9dfd61147582/u:parkdn/t:javascript/

$q = $_GET["q"];
if ($q == '') {
    $q = "t:javascript/";
}
$url = ("https://feeds.pinboard.in/rss/" . $q);


$feeds = simplexml_load_file($url);
//print_r($feeds);

$i = 0;
if (!empty($feeds)) {

    $site = $feeds->channel->title;
    $sitelink = $feeds->channel->link;

    echo "<h3 class='text-center text-white-50'>" . $site . "</h3>";
    //echo "<h2>" . $sitelink . "</h2>";
?>
    <table>

        <?php
        foreach ($feeds->item as $item) {

            $title = $item->title;
            $link = $item->link;
            $modifyUrl = parse_url($link);
            $host = $modifyUrl['host'];
            $description = $item->description;

            if ($title == 'Twitter') {
                $title = 'Twitter: ' . $description;
            }
            //$title = substr($title, 0, 45);

            // crazy namespace logic goes here
            // https://www.sitepoint.com/simplexml-and-namespaces/
            $ns_dc = $item->children('http://purl.org/dc/elements/1.1/');
            $postDate = $ns_dc->date;

            // namespace didn't work
            // https://stackoverflow.com/questions/1186107/simple-xml-dealing-with-colons-in-nodes
            //$postDate = $item->{'dc:date'};

            $pubDate = date('D, d M y', strtotime($postDate));

            //if ($i >= 50) break;
        ?>
            <tr>
                <td class="text-nowrap p-1">
                    <a class="feed_title" target="_blank" href="javascript:openWin('<?php echo $link; ?>'); ">

                        <div class="desc">
                            <span class="small">
                                <?php echo $host . ' [' . $pubDate . ']' ?>
                            </span>
                        </div>
                        <div class="descs">
                            <span class=""><?php echo $title; ?></span>
                        </div>
                    </a>

                </td>
                <!-- <td><?php echo $description; ?></td> -->
            </tr>

    <?php
            $i++;
        }
    }
    ?>
    </table>