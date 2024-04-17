# HttpClient

The `HttpClient` provides a simple, flexible utility for making HTTP requests. This package supports GET and POST methods and is designed to facilitate API requests requiring API key authentication. It's a basic straightforward way to interact with RESTful APIs from PHP applications.

## Features

- Supports GET and POST HTTP methods.
- Simple methods for GET and POST requests.
- Handles API key authentication via headers.
- Automatic handling of API keys and headers.
- Configurable timeouts for requests.
- Parses HTTP status codes from responses.

## Requirements

- PHP 7.4 or higher.
- Composer for dependency management.

## Installation

To install `HttpClient`, run the following Composer command in your project directory:

```bash
composer require devuri/http
```

This command adds `HttpClient` to your project and handles autoloading.

## Usage

### Setup

Ensure your project includes Composer's autoloader:

```php
require 'vendor/autoload.php';

use Urisoft\HttpClient;
```

### Initialize HttpClient

Create an instance of `HttpClient`. You can specify the base URL and optional parameters like API key and timeout:

```php
$baseUrl = 'https://api.example.com/';
$client = new HttpClient($baseUrl, ['api_key' => 'your_api_key_here', 'timeout' => 30]);
```

### Making a GET Request

To fetch data via a GET request:

```php
$endpoint = '/data/endpoint';
$response = $client->get($endpoint);

echo "<pre>";
print_r($response);
echo "</pre>";
```

### Making a POST Request

To send data via a POST request:

```php
$endpoint = '/data/post';
$data = [
    'key' => 'value',
    'another_key' => 'another_value'
];
$response = $client->post($endpoint, $data);

echo "<pre>";
print_r($response);
echo "</pre>";
```

### Error Handling

The `HttpClient` class handles errors internally and returns a structured array. Here's how to interpret the responses:

```php
if ($response['status'] == 0) {
    echo "Error: " . $response['body'];
} else {
    echo "Received response: " . $response['body'];
}
```

## License

This project is licensed under the MIT License - see the [LICENSE.md](LICENSE) file for details.

## Support

If you encounter any issues or have questions about using `HttpClient`, please open an issue.
