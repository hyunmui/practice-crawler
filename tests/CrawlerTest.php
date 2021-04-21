<?php

use Illuminate\Support\Collection;
use PHPUnit\Framework\TestCase;
use Symfony\Component\BrowserKit\HttpBrowser;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\DomCrawler\UriResolver;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\String\AbstractString;

use function PHPUnit\Framework\assertEquals;
use function PHPUnit\Framework\assertNotEmpty;
use function PHPUnit\Framework\assertNotNull;
use function Symfony\Component\String\s;

/**
 * 크롤러 테스트
 * 
 * @package 
 */
class CrawlerTest extends TestCase
{
    /**
     * 
     * @var Crawler
     */
    private $crawler;

    protected function setUp(): void
    {
        // setup
        $html = file_get_contents(__DIR__ . './TestDoubles/example.html');
        $this->crawler = new Crawler($html);
    }


    /** @test */
    public function testGetMenuItems()
    {
        // arrange
        $menuLinks = null;

        // action
        $results = $this->crawler->filter('ul#top-menu > li.nav-item > a.nav-link');
        $menuLinks = (new Collection($results->getIterator()))->map(fn (DOMNode $a) => $a->attributes->getNamedItem('href')->textContent);

        // assert
        assertNotEmpty($results);
        $menuLinks->each(fn ($link, $key) => assertEquals('/menu/menu-' . ($key + 1), $link));
    }

    /** @test */
    public function testGetFormData()
    {
        // arrange
        $id = '';
        $password = '';
        $returnUrl = '';

        // action
        $form = $this->crawler->filter('form.form');
        $idDom = $form->filter('input[type=text][name=userId]')->first();
        $pwDom = $form->filter('input[type=password][name=password]')->first();

        $id = $idDom->attr('value');
        $password = $pwDom->attr('value');
        $actionUrl = $form->attr('action');

        $actionParamCollection = new Collection(s($actionUrl)->after('?')->split('&', null));
        $params = $actionParamCollection->mapWithKeys(fn (AbstractString $item) => [$item->before('=')->toString() => $item->after('=')->toString()])->all();
        $returnUrl = $params['ret-url'];

        // assert
        assertEquals('hyunmui', $id, '아이디가 일치하지 않습니다');
        assertEquals('1q2w3e!@', $password, '비밀번호가 일치하지 않습니다');
        assertEquals('/menu/abc-123?name=kkkk', urldecode($returnUrl), '폼 전송 주소가 일치하지 않습니다');
    }

    /** @test */
    public function testLoginThenSuccess()
    {
        // arrange
        $browser = new HttpBrowser(HttpClient::create());
        $crawler = $browser->request('GET', 'https://linkonbiz.com/login');

        // action
        $form = $crawler->filter('form#form_login')->form();
        $form['id'] = '12341234';       // you should enter right information
        $form['password'] = '12341234'; // you should enter right information

        $browser->submit($form);

        $crawler = $browser->request('GET', 'https://linkonbiz.com/account');

        assertNotNull($crawler);
        assertEquals('마이페이지', $crawler->filter('.lnb_title')->children()->first()->text());
    }
}
