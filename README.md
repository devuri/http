# HttpClient

The `HttpClient` is a PHP component designed to facilitate HTTP requests within PHP applications. It provides a simple, flexible utility for making HTTP requests. It's a basic straightforward way to interact with RESTful APIs from PHP applications.

### Features

- **Flexible HTTP Requests:** Supports GET and POST requests.
- **Customizable User Agent:** Easily configure the user agent from a preset list or set a custom one.
- **API Key Support:** Integrated support for API key authentication.
- **Referrer Setting:** Ability to set the HTTP referrer header.
- **Timeout Configuration:** Set custom timeouts for HTTP requests.
- **Exception Handling:** Catches and handles exceptions during HTTP requests, providing fallbacks and error information.

## Requirements

- PHP 7.4 or higher.
- Composer for dependency management.

### Installation

To install the `HttpClient`, you need to have Composer installed on your machine. Run the following command in your project directory:

```bash
composer require devuri/http
```

### Usage

#### Basic Usage

```php
<?php

require_once 'vendor/autoload.php';

use Urisoft\HttpClient;

// Initialize HttpClient with the base URL of the API
$client = new HttpClient('https://api.example.com');

// Perform a GET request
$response = $client->get('/data');
print_r($response);

// Perform a POST request
$postData = ['key' => 'value'];
$response = $client->post('/submit', $postData);
print_r($response);
```

#### Setting Custom User Agent

```php
$client->set_user_agent('Mozilla/5.0 (compatible; CustomBot/1.0)');
```

#### Setting the Referrer

```php
$client->set_referrer('https://referrer.example.com');
```

### Configuration

The HttpClient constructor accepts an array of options for configuration:

- `user_agent`: Set the default user agent (key names: `'moz'`, `'chrome'`, `'safari'` or any custom string).
- `api_key`: Set the API key for requests that require authentication.
- `timeout`: Set the timeout duration for the HTTP requests.

Example of setting custom configuration:

```php
$options = [
    'user_agent' => 'safari',
    'api_key' => 'your_api_key_here',
    'timeout' => 30, // in seconds
];
$client = new HttpClient('https://api.example.com', $options);
```

### Handling Responses

The `HttpClient` uses PHP's `file_get_contents` function for making HTTP requests and provides detailed response handling, including HTTP status codes and error messages. Response from requests will typically include status code, message, and any headers returned by the server.

### Examples

Below are some concrete examples to demonstrate the usage of the `HttpClient` class for various common tasks such as fetching data from an API, sending data, setting custom headers, and handling the response.

#### Fetching Data from an API

To fetch data using a GET request:

```php
<?php

require_once 'vendor/autoload.php';

use Urisoft\HttpClient;

// Initialize the client with the base URL
$client = new HttpClient('https://jsonplaceholder.typicode.com');

// Fetch posts from an API
$response = $client->get('/posts');
print_r($response);
```

This example retrieves a list of posts from the placeholder API and prints the result.

#### Sending Data with a POST Request

To send data to an API using a POST request:

```php
$client = new HttpClient('https://jsonplaceholder.typicode.com');

// Data to be sent
$data = ['title' => 'foo', 'body' => 'bar', 'userId' => 1];

// Send data
$response = $client->post('/posts', $data);
print_r($response);
```

This code sends data to create a new post on the placeholder API and outputs the API's response.

#### Setting Custom Headers

To set custom headers for a request:

```php
$client = new HttpClient('https://api.example.com');

// Custom headers
$headers = [
    'Content-Type: application/json',
    'Custom-Header: value'
];

// Perform a GET request with custom headers
$response = $client->get('/endpoint', $headers);
print_r($response);
```

This example shows how to include custom headers in a GET request, which is useful for APIs that require specific headers.

#### Handling Responses and Errors

```php
$client = new HttpClient('https://api.example.com');

try {
    $response = $client->get('/data');
    if ($response['status'] !== 200) {
        throw new Exception('Failed to fetch data: ' . $response['message']);
    }
    echo "Data fetched successfully:\n";
    print_r($response);
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
```

This example demonstrates how to handle responses and errors gracefully in your application. It checks the status code and throws an exception if the request was not successful, allowing for robust error handling.

### Combining Parameters and Options

You can also combine various parameters and options like setting referrers, user agents, and API keys together:

```php
$options = [
    'user_agent' => 'safari',
    'api_key' => 'your_secure_api_key',
    'timeout' => 20
];

$client = new HttpClient('https://api.example.com', $options);
$client->set_referrer('https://referrer.example.com');
$response = $client->post('/submit', ['data' => 'value']);
print_r($response);
```

This configuration shows a more complex setup, where multiple options are configured to tailor the HTTP client behavior according to specific needs.

### Setting a Custom User Agent

You can set a custom user agent for your requests:

```php
$client->set_user_agent('Custom User Agent');
```

### Setting a Referrer

Set a referrer URL for your requests:

```php
$client->set_referrer("https://example.com");
$response = $client->get("/data/endpoint");
```

### Error Handling

Exceptions are caught internally, but it is recommended to handle potential errors or exceptions in your application logic, especially for critical operations.

`HttpClient` handles connection errors by returning a status code and error message. Here's how you might handle errors:

```php
if ($response['status'] !== 200) {
    echo "Error: " . $response['message'];
} else {
    echo "Success: " . $response['message'];
}
```

### Security: Handling API Responses

When interacting with external APIs using the `HttpClient` or any similar tool, it's crucial to be aware of the security implications involved in handling the data and responses you receive. Here are some important considerations and best practices to ensure the security of your applications:

#### Validate API Responses

Always assume that any data received from external sources could be potentially malicious. Validate and sanitize all incoming data rigorously before using it in your application. This includes:

- **Checking data types:** Ensure that the data matches expected types (e.g., strings, integers).
- **Sanitizing inputs:** Use library functions to escape hazardous characters and strings, particularly before inserting data into a database or rendering it in a user interface.

#### Handle Sensitive Data Carefully

If the API transmits sensitive data, ensure that it is transmitted securely using TLS (HTTPS). Avoid logging sensitive data such as API keys, user credentials, or personal information, as logs can be a vector for data leaks.

#### Secure Authentication Tokens

If your application uses authentication tokens (e.g., API keys, OAuth tokens):

- **Store tokens securely:** Use secure storage mechanisms to store sensitive tokens and credentials, avoiding hard-coded values in your source code.
- **Regenerate tokens regularly:** Regularly changing API keys and tokens limits the damage in the event of an exposure.

#### Manage Error Handling

Proper error handling can prevent unwanted information disclosure:

- **Avoid detailed error messages:** Detailed error messages can provide attackers with insights into the backend processes, which could be exploited. Use generic error messages for the users and log detailed errors securely.
- **Implement robust logging:** Ensure that error logs are secure and only accessible to authorized personnel.

#### Set Timeout and Size Limits

Configure timeouts and limit the size of the response to avoid denial-of-service attacks that attempt to tie up resources:

- **Timeouts:** Set appropriate timeouts to ensure that slow API responses do not cause your application to hang.
- **Response size limits:** Cap the size of the response that your application will accept to prevent overwhelming your system.

#### Be Cautious with Redirections

Automatically following redirects in HTTP requests can lead to security vulnerabilities:

- **Validate redirects:** Ensure that any redirection is to a trusted URL, especially in scenarios where URLs are constructed dynamically.
- **Limit automatic redirections:** If possible, handle redirections manually after validating the new URL.

#### Regularly Update Dependencies

Keep all dependencies, including your HTTP client libraries, up to date. Regular updates can protect your applications from known vulnerabilities that could be exploited by attackers.

While the `HttpClient` provides a convenient way to make HTTP requests, it is essential to be vigilant and implement robust security practices to protect your application and its data. Always approach external data with caution, and ensure that your application is prepared to handle unexpected or malicious data safely and securely.

## License

This project is licensed under the MIT License - see the [LICENSE.md](LICENSE) file for details.

## Support

If you encounter any issues or have questions about using `HttpClient`, please open an issue.
