<?php

namespace UMA\Tests\Psr\Http\Message\Serializer;

use UMA\Psr\Http\Message\Serializer\MessageSerializer;
use UMA\Tests\Psr\Http\Message\BaseTestCase;

class MessageSerializerTest extends BaseTestCase
{
    public function testSimpleRequests()
    {
        $expectedSerialization = "GET /index.html HTTP/1.1\r\nHost: www.example.com\r\n\r\n";

        foreach ($this->psr7RequestShotgun('GET', 'http://www.example.com/index.html') as $request) {
            $actualSerialization = MessageSerializer::serialize($request);

            $this->assertSame($expectedSerialization, $actualSerialization);
        }
    }

    public function testSimpleResponses()
    {
        $expectedSerialization = "HTTP/1.1 200 OK\r\n\r\n";

        foreach ($this->psr7ResponseShotgun(200) as $response) {
            $actualSerialization = MessageSerializer::serialize($response);

            $this->assertSame($expectedSerialization, $actualSerialization);
        }
    }

    public function testHeadedRequests()
    {
        $expectedSerialization = "GET /index.html HTTP/1.1\r\nHost: www.example.com\r\nAccept: */*\r\nAccept-Encoding: gzip, deflate\r\nConnection: keep-alive\r\nUser-Agent: PHP/5.6.21\r\n\r\n";

        $requests = $this->psr7RequestShotgun('GET', 'http://www.example.com/index.html', [
            'User-Agent' => 'PHP/5.6.21',
            'Accept' => '*/*',
            'Connection' => 'keep-alive',
            'Accept-Encoding' => 'gzip, deflate',
        ]);

        foreach ($requests as $request) {
            $actualSerialization = MessageSerializer::serialize($request);

            $this->assertSame($expectedSerialization, $actualSerialization);
        }
    }

    public function testSerializeNeitherRequestNorResponse()
    {
        $this->setExpectedException(\InvalidArgumentException::class);

        MessageSerializer::serialize(new \Asika\Http\Test\Stub\StubMessage());
    }
}
