<?php

namespace SmsSender\Tests\HttpAdapter;

use SmsSender\Tests\TestCase;

use SmsSender\HttpAdapter\CurlHttpAdapter;

/**
 * @author Kévin Gomez <kevin_gomez@carpe-hora.com>
 */
class CurlHttpAdapterTest extends TestCase
{
    protected function setUp()
    {
        if (!function_exists('curl_init')) {
            $this->markTestSkipped('cURL has to be enabled.');
        }
    }

    public function testGetNullContent()
    {
        $curl = new CurlHttpAdapter();
        $this->assertNull($curl->getContent(null));
    }
}
