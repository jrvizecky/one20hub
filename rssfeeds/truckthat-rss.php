<?php
/*
Curator.io API
be27d8b8-922f-41f5-a615-c7160fb2e81a
https://api.curator.io/v1
Content-Type: application/json
https://www.w3schools.com/xml/xml_rss.asp
*/

// Send the headers
header('Content-type: text/xml; charset=UTF-8');
header('Pragma: public');
header('Cache-control: private');
header('Expires: -1');

/*
 * Configuration
 */
$apiKey = "be27d8b8-922f-41f5-a615-c7160fb2e81a";
$url = "https://api.curator.io/v1/feeds/26b93eee-b326-4f77-9a56-b3be0b24827e/posts/?api_key=".$apiKey;

$response = file_get_contents($url);
$data = json_decode($response, true);

/*
 * Build feed items
 */

echo '<rss xmlns:atom="http://www.w3.org/2005/Atom" version="2.0">'. "\n";
echo '<channel>'. "\n";
echo '<title>TruckThat Feed</title>'. "\n";
echo '<link>https://one20hubqa.wpengine.com/</link>'. "\n";
echo'<description>This is the TruckThat.com RSS feed</description>'. "\n";

$i = 0;
foreach ($data['posts'] as $post){
    if (++$i == 5) break; // limit for testing
    echo '<item>';
    echo '<title>' . substr($post['text'], 0, strpos($post['text'], ' ', 50)) . '</title>';
    echo '<link>' . $post['url'] . '</link>';
    echo '<description><![CDATA[' . $post['text'] . ']]></description>';
    echo '<enclosure url="' . htmlentities($post['image']) . '" length="0" type="audio/mpeg" />';
    echo '<pubDate>' . date("D, d M Y H:i:s O", strtotime($post['source_created_at'])) . '</pubDate>';
    echo '</item>'. "\n";
}

echo '</channel>';
echo '</rss>';

//echo "<pre>";
//var_dump($data['posts']);
//echo "</pre>";
?>
