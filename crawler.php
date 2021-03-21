<?php

use MesseEsang\Crawler\Domain\MiddleCategory;
use Symfony\Component\DomCrawler\Crawler;

require_once __DIR__ . '/vendor/autoload.php';

$html = file_get_contents('https://chemknock.co.kr/view.do?no=13');

$crawler = new Crawler($html);
