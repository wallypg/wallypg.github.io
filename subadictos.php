<?php header('Content-Type: text/xml');

$topInfo = '<?xml version="1.0" encoding="UTF-8"?>
            <rss version="2.0" xmlns:dc="http://purl.org/dc/elements/1.1/" xmlns:content="http://purl.org/rss/1.0/modules/content/">
              <channel>
                <title>RSS feed de tusubtitulo.com para SubAdictos</title>
                <link>http://www.webscenario.com.ar/rss_subadictos.xml</link>
                <description>Últimos subtítulos de series realizados por tusubtitulo.com</description>
                <language>es</language>
                <image>
                  <title>SubAdictos</title>
                  <url>http://www.subadictos.net/foros/images/misc/vbulletin3_logo_white.png</url>
                  <link>http://www.subadictos.net/</link>
                  <description>SubAdictos</description>
                </image>';

$bottomInfo = '</channel>
              </rss>';


$curlResource=curl_init();
curl_setopt_array($curlResource, array(
  CURLOPT_RETURNTRANSFER => 1,
  CURLOPT_URL => 'http://www.tusubtitulo.com/ajax_tabs.php?mode=translated&page=1&max=30'
));
$curlResult = curl_exec($curlResource);
if(!curl_exec($curlResource)){
  die('Error: "' . curl_error($curlResource) . '" - Code: ' . curl_errno($curlResource));
}
curl_close($curlResource);

// a new dom object
$dom = new domDocument; 
// load the html into the object
libxml_use_internal_errors(true);
$dom->loadHTML($curlResult); 
// discard white space
$dom->preserveWhiteSpace = false;

$completeXml = $topInfo;

$arrayItems = array();

foreach ($dom->getElementsByTagName('a') as $node) {

  $completeXml .= "<item>
                    <title>".$node->nodeValue."</title>
                    <link>http://www.tusubtitulo.com/".$node->getAttribute( 'href' )."</link>
                    <description>".$node->nodeValue."</description>
                  </item>";
}
// <pubDate>".date("r")."</pubDate> no creo que sirva incluirlo


$completeXml .= $bottomInfo;
// echo $completeXml;

// $rss = new SimpleXMLElement($completeXml);
// echo $rss->asXML();

// Crea archivo .xml en el mismo directorio
file_put_contents('rss_subadictos.xml',$completeXml);
header ('Location: rss_subadictos.xml');
?>