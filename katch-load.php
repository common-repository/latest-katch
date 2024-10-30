<?php
$html = "";
$katchusername = get_option('katchuser');
$url = "https://katch.me/".$katchusername."/rss";
if ($katchusername == '') {$url = "https://katch.me/KatchHQ/rss";}
$xml = simplexml_load_file($url);
for($i = 0; $i < 1; $i++){
	$title = $xml->channel->item[$i]->title;
	$link = $xml->channel->item[$i]->link;
	$description = $xml->channel->item[$i]->description;
	$pubDate = $xml->channel->item[$i]->pubDate;
	
        $html .= "<a href='$link'><h3>$title</h3></a>";
	$html .= "$description";
	$html .= "<br />$pubDate<hr />";
$pieces = explode(":", $description);
$linkfix = explode('"', $pieces[1]);
$linksegment = $linkfix[0];
$katchlink = "https:" . $linksegment;
}
$katchsource = file_get_contents($katchlink);
function getMetaTags($str)
{
  $pattern = '
  ~<\s*meta\s

  # using lookahead to capture type to $1
    (?=[^>]*?
    \b(?:name|property|http-equiv)\s*=\s*
    (?|"\s*([^"]*?)\s*"|\'\s*([^\']*?)\s*\'|
    ([^"\'>]*?)(?=\s*/?\s*>|\s\w+\s*=))
  )

  # capture content to $2
  [^>]*?\bcontent\s*=\s*
    (?|"\s*([^"]*?)\s*"|\'\s*([^\']*?)\s*\'|
    ([^"\'>]*?)(?=\s*/?\s*>|\s\w+\s*=))
  [^>]*>

  ~ix';
  
  if(preg_match_all($pattern, $str, $out))
    return array_combine($out[1], $out[2]);
  return array();
}
$meta_tags = getMetaTags($katchsource);
$katchurl = $meta_tags["og:url"];
$katchimage = $meta_tags["og:image"];
$pieces = explode(":", $description);
$katchwidth = get_option('katchwidth');
if ($katchwidth == '') {$katchwidth = '282';}
$katchheight = get_option('katchheight');
if ($katchheight == '') {$katchheight = '500';}
$link1 = '<iframe width="';
$link2 = '" height="';
$link3 = '" src="https:';
$link4 = '"></iframe>';
$embedcode = $link1.$katchwidth.$link2.$katchheight.$link3.$linksegment.$link4;
if ($katchusername == '') {$embedcode = 'Unable to display latest Katch. Please save a username in Settings > Latest Katch in your WordPress Dashboard.';}
?>