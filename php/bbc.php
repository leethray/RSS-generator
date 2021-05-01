<?php
include "gethtml.php";
$regex_link = '/(?<=<a href="\/zhongwen\/simp\/world-).+?(?=")/';
$regex_tit = '/(?<=<title data-react-helmet="true">).+?(?= -)/';
$regex_con = '/(?<=<main role="main">).*?(?=<\/main>)/s';
$header = '<?xml version="1.0" encoding="utf-8"?><rss version="2.0"><channel><title>BBC</title>';
$footer = '</channel></rss>';


$html = gethtml('http://www.bbc.com/zhongwen/simp');
if (preg_match_all($regex_link, $html, $links)) {   //generate article link from main html
    foreach($links[0] as $link) {
        $link = preg_replace('/(.+)/','http://www.bbc.com/zhongwen/simp/world-$1', $link);
        $content = gethtml($link);   //get content html page from article links
        preg_match($regex_con, $content, $article);  //generate article from content html
        preg_match($regex_tit, $content, $title);  //generate title form content html
        $rss.= '<item><title>'.$title[0].'</title><link><![CDATA['.$link. ']]></link><description><![CDATA['.$article[0].']]></description></item>'; //output title, link, article as an rss item
    }
    //echo $rss;//调试用
    file_put_contents('bbc.xml',$header.$rss.$footer);  // output all rss items to xml file
}
?>