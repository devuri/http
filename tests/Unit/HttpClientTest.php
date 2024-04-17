<?php

use PHPUnit\Framework\TestCase;
use Urisoft\HttpClient;

class HttpClientTest extends TestCase
{
    private $httpClient;

    protected function setUp(): void
    {
        $this->httpClient = new HttpClient('https://api.example.com/', ['timeout' => 30]);
    }

    public function testGetRequest()
    {
        // Mocking file_get_contents to return a JSON response
        $url = 'https://api.example.com/data/endpoint';
        $response = json_encode(['success' => true, 'data' => 'Test data']);
        
        $this->httpClient = $this->createMock(HttpClient::class);
        $this->httpClient->method('get')
                         ->with($this->equalTo('/data/endpoint'))
                         ->willReturn(['status' => 200, 'body' => $response]);

        $result = $this->httpClient->get('/data/endpoint');

        $this->assertIsArray($result);
        $this->assertEquals(200, $result['status']);
        $this->assertEquals($response, $result['body']);
    }

    public function testPostRequest()
    {
        // Mocking file_get_contents to simulate a POST response
        $url = 'https://api.example.com/data/post';
        $postData = ['key' => 'value'];
        $response = json_encode(['success' => true]);

        $this->httpClient = $this->createMock(HttpClient::class);
        $this->httpClient->method('post')
                         ->with(
                             $this->equalTo('/data/post'),
                             $this->equalTo($postData)
                         )
                         ->willReturn(['status' => 200, 'body' => $response]);

        $result = $this->httpClient->post('/data/post', $postData);

        $this->assertIsArray($result);
        $this->assertEquals(200, $result['status']);
        $this->assertEquals($response, $result['body']);
    }
}
