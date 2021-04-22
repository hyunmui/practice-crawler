<?php

use Symfony\Component\DomCrawler\Crawler;

require_once __DIR__ . '/vendor/autoload.php';

$html = file_get_contents('https://chemknock.co.kr/view.do?no=13');
// $html = file_get_contents(__DIR__ . '/test.html');
$crawler = new Crawler($html);

$results = $crawler->filter('div.category-tab-ul > div.li > a');

foreach ($results as $key => $item) {
    var_dump($item->attributes->getNamedItem('href')->textContent);
}
