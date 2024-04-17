<?php

namespace Urisoft;

use Exception;

class HttpClient
{
    private ?string $base_url;
    private ?string $api_key;
    private array $http_response;
    private DotAccess $context;
    private ?string $user_agent;
    private array $agents = [
        'moz'    => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.110 Safari/537.36',
        'chrome' => 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/54.0.2840.99 Safari/537.36',
        'safari' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_11_6) AppleWebKit/602.1.50 (KHTML, like Gecko) Version/10.0 Safari/602.1.50',
    ];

    public function __construct( string $base_url, array $context = [] )
    {
        $this->base_url = $base_url;
        $this->context  = new DotAccess(
            array_merge(
                [
					'user_agent' => 'chrome',
					'api_key'    => null,
					'timeout'    => 10,
				],
                $context
            )
        );
        $this->api_key  = $this->context->get( 'api_key' );
        $agent_key      = $this->context->get( 'user_agent' );

        if ( \array_key_exists( $agent_key, $this->agents ) ) {
            $this->user_agent = $this->agents[ $agent_key ];
        }
    }

    public function set_user_agent( string $user_agent ): void
    {
        $this->user_agent = $user_agent;
    }

    public function context(): DotAccess
    {
        return $this->context;
    }

    public function get( string $endpoint, array $headers = [] ): array
    {
        return $this->request( $endpoint, 'GET', [], $headers );
    }

    public function post( string $endpoint, array $data = [], array $headers = [] ): array
    {
        return $this->request( $endpoint, 'POST', $data, $headers );
    }

    protected function set_http_response( array $http_response_header ): array
    {
        if ( ! empty( $http_response_header ) ) {
            $this->http_response['response'] = $http_response_header;
            $this->http_response['http']     = explode( ' ', $http_response_header[0], 3 );
            $this->http_response['code']     = (int) $this->http_response['http'][1];
            $this->http_response['message']  = $this->http_response['http'][2];
            $this->http_response['status']   = $this->http_response['code'];
        }

        return [];
    }

    private function request( string $endpoint, string $method, array $data = [], array $headers = [] ): array
    {
        if ( ! $this->user_agent ) {
            $this->user_agent = $this->agents['chrome'];
        }

        $url             = $this->base_url . $endpoint;
        $default_headers = $this->api_key ? [ 'Authorization: Bearer ' . $this->api_key ] : [];
        $headers         = array_merge( $default_headers, $headers, [ 'User-Agent' => $this->user_agent ] );

        $opts = [
            'http' => [
                'method'  => $method,
                'header'  => implode( "\r\n", $headers ),
                'content' => 'POST' === $method ? http_build_query( $data ) : null,
                'timeout' => $this->context->get( 'timeout' ),
            ],
        ];

        $context = stream_context_create( $opts );

        try {
            $response = file_get_contents( $url, false, $context );
            if ( false === $response ) {
                throw new Exception( "Unable to reach the endpoint: $url" );
            }
        } catch ( Exception $e ) {
            // error_log( $e->getMessage() );
            if ( ! isset( $http_response_header ) ) {
                return [
					'status'  => 0,
					'message' => 'unknown error',
				];
            }

            $this->set_http_response( $http_response_header );

            return [
				'status'  => $this->http_response['code'],
				'message' => $this->http_response['message'],
			];
        }//end try

        // https://www.php.net/manual/en/reserved.variables.httpresponseheader.php
        $this->set_http_response( $http_response_header );

        return $this->http_response;
    }

    private function parse_http_status( array $http_response_header ): int
    {
        if ( ! empty( $http_response_header ) ) {
            $status_line = explode( ' ', $http_response_header[0], 3 );

            return (int) $status_line[1];
        }

        return 0;
    }
}
