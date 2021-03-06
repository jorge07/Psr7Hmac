<?php

namespace UMA\Tests\Psr\Http\Message\Serializer;

use Asika\Http\Test\Stub\StubMessage;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use UMA\Psr\Http\Message\Serializer\MessageSerializer;
use UMA\Tests\Psr\Http\Message\RequestsProvider;
use UMA\Tests\Psr\Http\Message\ResponsesProvider;

class MessageSerializerTest extends \PHPUnit_Framework_TestCase
{
    use RequestsProvider;
    use ResponsesProvider;

    public function testSerializeNeitherRequestNorResponse()
    {
        $this->setExpectedException(\InvalidArgumentException::class);

        MessageSerializer::serialize(new StubMessage());
    }

    /**
     * @dataProvider simplestRequestProvider
     *
     * @param RequestInterface $request
     */
    public function testSimplestRequest(RequestInterface $request)
    {
        $expectedSerialization = "GET /index.html HTTP/1.1\r\nHost: www.example.com\r\n\r\n";

        $this->assertSame($expectedSerialization, MessageSerializer::serialize($request));
    }

    /**
     * @dataProvider simplestResponseProvider
     *
     * @param ResponseInterface $response
     */
    public function testSimplestResponse(ResponseInterface $response)
    {
        $expectedSerialization = "HTTP/1.1 200 OK\r\n\r\n";

        $this->assertSame($expectedSerialization, MessageSerializer::serialize($response));
    }

    /**
     * @dataProvider emptyRequestWithHeadersProvider
     *
     * @param RequestInterface $request
     */
    public function testEmptyRequestWithHeaders(RequestInterface $request)
    {
        $expectedSerialization = "GET /index.html HTTP/1.1\r\nHost: www.example.com\r\nAccept: */*\r\nAccept-Encoding: gzip, deflate\r\nConnection: keep-alive\r\nUser-Agent: PHP/5.6.21\r\n\r\n";

        $this->assertSame($expectedSerialization, MessageSerializer::serialize($request));
    }

    /**
     * @dataProvider emptyResponseWithHeadersProvider
     *
     * @param ResponseInterface $response
     */
    public function testEmptyResponseWithHeaders(ResponseInterface $response)
    {
        $expectedSerialization = "HTTP/1.1 200 OK\r\nAccept-Ranges: bytes\r\nContent-Encoding: gzip\r\nContent-Length: 606\r\nContent-Type: text/html\r\n\r\n";

        $this->assertSame($expectedSerialization, MessageSerializer::serialize($response));
    }

    /**
     * @dataProvider bodiedRequestProvider
     *
     * @param RequestInterface $request
     */
    public function testBodiedRequest(RequestInterface $request)
    {
        $fh = fopen(__DIR__.'/../fixtures/avatar.png', 'r');

        $expectedSerialization = "POST /avatar/upload.php HTTP/1.1\r\nHost: www.example.com\r\nContent-Length: 13360\r\nContent-Type: image/png\r\n\r\n".stream_get_contents($fh);

        $this->assertSame($expectedSerialization, MessageSerializer::serialize($request));
    }

    /**
     * @dataProvider bodiedResponseProvider
     *
     * @param ResponseInterface $response
     */
    public function testBodiedResponse(ResponseInterface $response)
    {
        $fh = fopen(__DIR__.'/../fixtures/avatar.png', 'r');

        $expectedSerialization = "HTTP/1.1 200 OK\r\nContent-Length: 13360\r\nContent-Type: image/png\r\n\r\n".stream_get_contents($fh);

        $this->assertSame($expectedSerialization, MessageSerializer::serialize($response));
    }
}
