<?php
include "gethtml.php";
$regex_link = '/(?<=<a href="\/story\/).+?(?=")/';
$regex_tit1 = '/(?<=<p class="DailyHeader-title").+?(?=\/p>)/s';
$regex_tit2 = '/(?<=>).+?(?=<)/s';
$regex_con = '/<header class="DailyHeader">.*?(?=<div class="view-more">)/s';
$header = '<?xml version="1.0" encoding="utf-8"?><rss version="2.0"><channel><title>知乎日报</title>';
$footer = '</channel></rss>';
$html = gethtml('http://daily.zhihu.com/');
if (preg_match_all($regex_link, $html, $links)) {   //generate article link from main html
    foreach($links[0] as $link) {
        $link = preg_replace('/(.+)/','http://daily.zhihu.com/story/$1', $link);
        $content = gethtml($link);   //get content html page from article links
        preg_match($regex_con, $content, $article);  //generate article from content html
        preg_match($regex_tit1, $content, $title1);  //generate title form content html
        preg_match($regex_tit2, $title1[0], $title2);  //generate title form content html
        $rss.= '<item><title>'.$title2[0].'</title><link><![CDATA['.$link. ']]></link><description><![CDATA['.$article[0].']]></description></item>'; //output title, link, article as an rss item
    }
    echo $rss;//调试用
    file_put_contents('zhihu.xml',$header.$rss.$footer);  // output all rss items to xml file
}
?>