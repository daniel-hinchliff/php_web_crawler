<?php

use Crawler\Crawler;
use Prophecy\Prophet;
use PHPUnit\Framework\TestCase;

/**
 * @property Prophet $prophet
 */

class CrawlerNavigationTest extends TestCase
{
    private $prophet;

    const url = 'http://url.com';
    const urlPass = 'http://url.com/pass';
    const urlFail = 'http://url.com/fail';

    public function testAddToQueueInvokedIfUrlNotFiltered()
    {
        $queue = $this->prophet->prophesize('Crawler\UrlsMemoryQueue');
        $navigator = $this->prophet->prophesize('Crawler\Navigator');
        $fetcher = $this->prophet->prophesize('Crawler\Fetcher');

        $fetcher->fetch(self::url)->willReturn('<a href="pass">link</a>');
        $navigator->filter(self::urlPass, self::url)->willReturn(true);
        $queue->processedUrl(self::url)->shouldBeCalled();
        $queue->addUrl(self::urlPass)->shouldBeCalled();
        $queue->getUrl()->willReturn(self::url, null);

        $crawler = new Crawler();
        $crawler->setFetcher($fetcher->reveal());
        $crawler->setQueue($queue->reveal());
        $crawler->crawl(0);
    }

    public function testAddToQueueNotInvokedIfUrlFiltered()
    {
        $queue = $this->prophet->prophesize('Crawler\UrlsMemoryQueue');
        $navigator = $this->prophet->prophesize('Crawler\Navigator');
        $fetcher = $this->prophet->prophesize('Crawler\Fetcher');

        $fetcher->fetch(self::url)->willReturn('<a href="fail">link</a>');
        $navigator->filter(self::urlFail, self::url)->willReturn(false);
        $queue->addUrl(self::urlFail)->shouldNotBeCalled();
        $queue->processedUrl(self::url)->shouldBeCalled();
        $queue->getUrl()->willReturn(self::url, null);

        $crawler = new Crawler();
        $crawler->setNavigator($navigator->reveal());
        $crawler->setFetcher($fetcher->reveal());
        $crawler->setQueue($queue->reveal());
        $crawler->crawl(0);
    }

    protected function setUp(): void
    {
        $this->prophet = new Prophet;
    }

    protected function tearDown(): void
    {
        $this->prophet->checkPredictions();
        $this->assertTrue(true);
    }
}
