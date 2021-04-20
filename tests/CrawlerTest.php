<?php

use PHPUnit\Framework\TestCase;
use Symfony\Component\DomCrawler\Crawler;

use function PHPUnit\Framework\assertNotEmpty;
use function PHPUnit\Framework\assertTrue;

/**
 * 크롤러 테스트
 * 
 * @package 
 */
class CrawlerTest extends TestCase
{
    /** @test */
    public function test_function()
    {
        // arrange
        $html = file_get_contents(__DIR__ . './TestDoubles/category.html');
        $crawler = new Crawler($html);

        // action
        $results = $crawler->filter('div.category-tab-ul > div.li > a');

        // assert
        assertNotEmpty($results);
    }
}
