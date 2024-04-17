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

To start making HTTP requests, instantiate the `HttpClient` with the base URL of the API and optional configuration settings:

```php
use Urisoft\HttpClient;

$baseUrl = "https://api.example.com";
$context = [
    'user_agent' => 'moz', // Use 'moz', 'chrome', or 'safari'
    'api_key'    => 'your_api_key',
    'timeout'    => 20
];

$client = new HttpClient($baseUrl, $context);
```

### Making Requests

#### GET Request

Here's how to make a GET request to retrieve data:

```php
$endpoint = "/data/endpoint";
$response = $client->get($endpoint);

echo "<pre>";
print_r($response);
echo "</pre>";
```

#### POST Request

To send data via a POST request:

```php
$endpoint = "/data/post";
$data = [
    'key' => 'value',
    'another_key' => 'another_value'
];
$response = $client->post($endpoint, $data);

echo "<pre>";
print_r($response);
echo "</pre>";
```

### Setting a Custom User Agent

You can set a custom user agent for your requests:

```php
$client->set_user_agent('Custom User Agent');
```

### Error Handling

`HttpClient` handles connection errors by returning a status code and error message. Here's how you might handle errors:

```php
if ($response['status'] !== 200) {
    echo "Error: " . $response['message'];
} else {
    echo "Success: " . $response['message'];
}
```

## License

This project is licensed under the MIT License - see the [LICENSE.md](LICENSE) file for details.

## Support

If you encounter any issues or have questions about using `HttpClient`, please open an issue.
