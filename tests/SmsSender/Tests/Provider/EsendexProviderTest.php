<?php

namespace SmsSender\Tests\Provider;

use SmsSender\Provider\EsendexProvider;
use SmsSender\Result\ResultInterface;
use SmsSender\Tests\TestCase;

class EsendexProviderTest extends TestCase
{
    /**
     * @expectedException \RuntimeException
     */
    public function testSendWithNullApiCredentials()
    {
        $adapter = $this->getMock('\SmsSender\HttpAdapter\HttpAdapterInterface');
        $provider = new EsendexProvider($adapter, null, null, null);
        $provider->send('0642424242', 'foo!');
    }

    /**
     * @expectedException \RuntimeException
     */
    public function testGetStatusWithNullApiCredentials()
    {
        $adapter = $this->getMock('\SmsSender\HttpAdapter\HttpAdapterInterface');
        $provider = new EsendexProvider($adapter, null, null, null);
        $provider->getStatus('dummyMessageId');
    }

    public function testSend()
    {
        $this->provider = new EsendexProvider($this->getMockAdapter(), 'username', 'pass', 'account');
        $result = $this->provider->send('0642424242', 'foo');

        $this->assertNull($result['id']);
        $this->assertEquals(ResultInterface::STATUS_FAILED, $result['status']);
        $this->assertEquals('0642424242', $result['recipient']);
        $this->assertEquals('foo', $result['body']);
        $this->assertEmpty($result['originator']);
    }

    public function testSendWithMockData()
    {
        $data = <<<EOF
Result=OK
MessageIDs=3c13bbba-a9c2-460c-961b-4d6772960af0
EOF;
        $this->provider = new EsendexProvider($this->getMockAdapter(null, $data), 'username', 'pass', 'account');
        $result = $this->provider->send('0642424242', 'foo');

        $this->assertEquals('3c13bbba-a9c2-460c-961b-4d6772960af0', $result['id']);
        $this->assertEquals(ResultInterface::STATUS_SENT, $result['status']);
        $this->assertEquals('0642424242', $result['recipient']);
        $this->assertEquals('foo', $result['body']);
        $this->assertEmpty($result['originator']);
    }

    public function testSendWithMockDataAndOriginator()
    {
        $data = <<<EOF
Result=OK
MessageIDs=3c13bbba-a9c2-460c-961b-4d6772960af0
EOF;
        $this->provider = new EsendexProvider($this->getMockAdapter(null, $data), 'username', 'pass', 'account');
        $result = $this->provider->send('0642424242', 'foo', 'Superman');

        $this->assertEquals('3c13bbba-a9c2-460c-961b-4d6772960af0', $result['id']);
        $this->assertEquals(ResultInterface::STATUS_SENT, $result['status']);
        $this->assertEquals('0642424242', $result['recipient']);
        $this->assertEquals('foo', $result['body']);
        $this->assertEquals('Superman', $result['originator']);
    }

    public function testSendWithNullPhone()
    {
        $this->provider = new EsendexProvider($this->getMockAdapter(), 'username', 'pass', 'account');
        $result = $this->provider->send(null, 'foo');

        $this->assertNull($result['id']);
        $this->assertEquals(ResultInterface::STATUS_FAILED, $result['status']);
        $this->assertNull($result['recipient']);
        $this->assertEquals('foo', $result['body']);
        $this->assertEmpty($result['originator']);
    }

    public function testSendWithNullMessage()
    {
        $this->provider = new EsendexProvider($this->getMockAdapter(), 'username', 'pass', 'account');
        $result = $this->provider->send('0642424242', null);

        $this->assertNull($result['id']);
        $this->assertEquals(ResultInterface::STATUS_FAILED, $result['status']);
        $this->assertEquals('0642424242', $result['recipient']);
        $this->assertNull($result['body']);
        $this->assertEmpty($result['originator']);
    }

    public function testSendWithEmptyPhone()
    {
        $this->provider = new EsendexProvider($this->getMockAdapter(), 'username', 'pass', 'account');
        $result = $this->provider->send('', 'foo');

        $this->assertNull($result['id']);
        $this->assertEquals(ResultInterface::STATUS_FAILED, $result['status']);
        $this->assertEmpty($result['recipient']);
        $this->assertEquals('foo', $result['body']);
        $this->assertEmpty($result['originator']);
    }

    public function testSendWithEmptyMessage()
    {
        $this->provider = new EsendexProvider($this->getMockAdapter(), 'username', 'pass', 'account');
        $result = $this->provider->send('0642424242', '');

        $this->assertNull($result['id']);
        $this->assertEquals(ResultInterface::STATUS_FAILED, $result['status']);
        $this->assertEquals('0642424242', $result['recipient']);
        $this->assertEmpty($result['body']);
        $this->assertEmpty($result['originator']);
    }
/*
    public function testSendForReal()
    {
        if (!isset($_SERVER['ESENDEX_API_USER']) || !isset($_SERVER['ESENDEX_API_PASS']) || !isset($_SERVER['ESENDEX_API_ACCOUNT'])) {
            $this->markTestSkipped('You need to configure the ESENDEX_API_USER, ESENDEX_API_PASS, ESENDEX_API_ACCOUNT values in phpunit.xml');
        }

        $this->provider = new EsendexProvider($this->getMockAdapter(), $_SERVER['ESENDEX_API_USER'], $_SERVER['ESENDEX_API_PASS'], $_SERVER['ESENDEX_API_ACCOUNT']);
        $result = $this->provider->send('0642424242', ''); // @todo: get a fake number

        $this->assertEquals('foo', $result['id']);
        $this->assertTrue($result['sent']);
    }
*/
}
