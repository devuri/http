<?php

namespace Urisoft;

use Urisoft\DotAccess;
use Exception;

class HttpClient
{
    private ?string $base_url;
    private ?string $api_key;
    private DotAccess $context;

    public function __construct(string $base_url, array $context = ['timeout' => 20])
    {
        $this->base_url = $base_url;
        $this->context = new DotAccess($context);
        $this->api_key = $this->context->get('api_key');
    }

    public function get(string $endpoint, array $headers = []): array
    {
        return $this->request($endpoint, 'GET', [], $headers);
    }

    public function post(string $endpoint, array $data = [], array $headers = []): array
    {
        return $this->request($endpoint, 'POST', $data, $headers);
    }

    private function request(string $endpoint, string $method, array $data = [], array $headers = []): array
    {
        $url = $this->base_url . $endpoint;
        $default_headers = $this->api_key ? ['Authorization: Bearer ' . $this->api_key] : [];
        $headers = array_merge($default_headers, $headers);

        $opts = [
            'http' => [
                'method' => $method,
                'header' => implode("\r\n", $headers),
                'content' => $method === 'POST' ? http_build_query($data) : null,
                'timeout' => $this->context->get('timeout'),
            ],
        ];

        $context = stream_context_create($opts);

		try {
	        $response = file_get_contents($url, false, $context);
	        if ($response === false) {
	            throw new \Exception("Unable to reach the endpoint: $url");
	        }
	    } catch (\Exception $e) {
			error_log( 'request error' . $e->getMessage() );
	        return ['status' => 0, 'body' => 'error'];
	    }

        $status = $response !== false ? $this->parse_http_status($http_response_header) : 0;

        return [
            'status' => $status,
            'body' => $response !== false ? $response : "Request failed with status code: $status",
        ];
    }

    private function parse_http_status(array $http_response_header): int
    {
        if (!empty($http_response_header)) {
            $status_line = explode(' ', $http_response_header[0], 3);
            return (int)$status_line[1];
        }
        return 0;
    }
}

