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

## Usage

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

## Examples

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

## Handling Responses

The `HttpClient` uses PHP's `file_get_contents` function for making HTTP requests and provides detailed response handling, including HTTP status codes and error messages. Response from requests will typically include status code, message, and any headers returned by the server.

### Response Array Structure

When using the `HttpClient` class to make HTTP requests, the response returned by methods such as `get` or `post` will be an associative array containing various pieces of information related to the HTTP response. Here's an explanation of what might be included in this response array, followed by a detailed example:

#### Common Components of the Response Array

- **status**: The HTTP status code returned by the server. This is an integer (e.g., 200 for successful requests, 404 for not found errors, etc.).
- **message**: The status message associated with the HTTP status code (e.g., "OK" for 200, "Not Found" for 404).
- **response**: The full response headers as an array, received from the server.
- **code**: A duplicate of the status, included for convenience.
- **referrer**: The referrer URL, if set during the request.
- **http**: An array containing the protocol version, status code, and message extracted from the first line of the response headers.

### Example of a Response Array

Here is an example to illustrate how the response array might look after a GET request to an API endpoint:

```php
$response = $client->get('/api/data');

print_r($response);
```

Assuming the request was successful, the output might look something like this:

```plaintext
Array
(
    [status] => 200
    [message] => OK
    [response] => Array
        (
            [0] => HTTP/1.1 200 OK
            [Content-Type] => application/json; charset=utf-8
            [Content-Length] => 1234
            [Connection] => keep-alive
            [Date] => Wed, 12 Apr 2024 08:23:17 GMT
            [Server] => Apache/2.4.41 (Unix)
        )
    [code] => 200
    [referrer] => https://referrer.example.com
    [http] => Array
        (
            [0] => HTTP/1.1
            [1] => 200
            [2] => OK
        )
)
```

#### Detailed Breakdown:

- **status** (200): Indicates a successful HTTP request.
- **message** ("OK"): Describes the HTTP status code in text.
- **response**: Contains detailed headers returned by the server, which include information like content type, content length, connection status, server type, etc.
- **code** (200): Redundant information about the HTTP status code, provided for convenience.
- **referrer** ("https://referrer.example.com"): Indicates the URL that referred the request, if any.
- **http**: A breakdown of the initial response line, providing protocol, status code, and the textual status message.

### Handling and Utilizing the Response

You can use this structured response to check the status of the HTTP request, log information, parse headers for session or caching purposes, and use the body's content (not shown in this array but typically handled separately as a string or JSON object). This structured approach allows for robust error handling and integration in more complex application logic, ensuring that your application can react appropriately to different responses from external services.









## Security: Handling API Responses

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

## Scope and Limitations

The `HttpClient` is designed as a simple and straightforward tool for making HTTP requests in PHP applications.
It is suitable for small to medium-sized projects where basic HTTP functionality is required without the overhead of more complex libraries.

#### When to Use HttpClient

- **Small Projects:** Ideal for small applications or scripts that need to interact with APIs without complex requirements.
- **Learning and Prototyping:** Excellent choice for educational purposes, tutorials, or prototyping where simplicity and ease of use are paramount.
- **Limited Dependencies:** Useful in environments where minimizing dependencies is a priority, or where the installation of larger libraries might be restricted.

#### Limitations

While `HttpClient` is efficient for straightforward tasks, it lacks some of the advanced features and optimizations found in more comprehensive HTTP client libraries. Some of these limitations include:

- **Concurrency:** Does not support asynchronous requests or handle concurrent API calls natively.
- **Middleware and Plugins:** Does not support middleware or plugins for extending functionality with hooks or interceptors.

#### Considerations for More Complex Needs

For more feature-rich and robust HTTP client capabilities, consider using a more comprehensive library such as **GuzzleHTTP**. Guzzle provides a vast array of features suitable for complex applications, including:

- **Asynchronous Requests:** Support for async and parallel requests to improve performance in applications that make numerous external calls.
- **Middleware Support:** Allows for middleware that can modify requests and responses on the fly, which is useful for tasks like logging, authentication, and caching.
- **Full HTTP Protocol Support:** Better compliance with the latest HTTP standards, including HTTP/2, and more sophisticated SSL/TLS options.

`HttpClient` serves as a practical solution for a lightweight and uncomplicated way to make HTTP requests. However, for applications requiring more extensive functionality or handling high volumes of traffic, a more advanced solution like GuzzleHTTP is recommended. Always evaluate the specific needs and complexity of your project to choose the most appropriate tool for the job.

## License

This project is licensed under the MIT License - see the [LICENSE.md](LICENSE) file for details.

## Support

If you encounter any issues or have questions about using `HttpClient`, please open an issue.
