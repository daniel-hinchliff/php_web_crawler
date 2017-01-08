<?php

use Crawler\UrlsMemoryQueue;

class UrlsMemoryQueueTest extends PHPUnit_Framework_TestCase
{
    public function testEmptyQueue()
    {
        $queue = new UrlsMemoryQueue();

        $this->assertNull($queue->getUrl());
    }

    public function testQueueOrder()
    {
        $queue = new UrlsMemoryQueue();

        $queue->addUrl('url 1');
        $queue->addUrl('url 2');
        $queue->addUrl('url 3');

        $this->assertEquals('url 3', $queue->getUrl());
        $this->assertEquals('url 2', $queue->getUrl());
        $this->assertEquals('url 1', $queue->getUrl());
        $this->assertNull($queue->getUrl());
    }
}
