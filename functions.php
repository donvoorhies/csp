<?php
// File: functions.php

/**
 * Set Content Security Policy, HTTP Strict Transport Security, CORS, and Alt-Svc headers.
 *
 * This function defines and sets the Content-Security-Policy,
 * Strict-Transport-Security, Cross-Origin Resource Sharing (CORS),
 * and Alternative Services (Alt-Svc) HTTP headers to enhance the security,
 * interoperability, and performance of the WordPress site.
 */
function custom_security_headers() {
    // --- Security Headers Applied to Most Responses ---

    // Define the Content Security Policy.
    // default-src 'self': Allows loading resources only from the same origin.
    // script-src 'self' 'unsafe-inline' 'unsafe-eval': Allows scripts from the same origin, inline scripts, and dynamic script evaluation (e.g., via eval()).
    // style-src 'self' 'unsafe-inline': Allows stylesheets from the same origin and inline styles.
    // img-src 'self' data:: Allows images from the same origin and data URIs.
    $csp_policy = "default-src 'self'; script-src 'self' 'unsafe-inline' 'unsafe-eval'; style-src 'self' 'unsafe-inline'; img-src 'self' data:;";
    header("Content-Security-Policy: " . $csp_policy);

    // Define the HTTP Strict Transport Security (HSTS) policy.
    // max-age=31536000: Tells browsers to remember to enforce HTTPS for one year.
    // includeSubDomains: Applies HSTS to all subdomains.
    // preload: Allows the site to be submitted to HSTS preload lists.
    $hsts_policy = "max-age=31536000; includeSubDomains; preload";
    // Note: HSTS headers are only effective over an HTTPS connection.
    header("Strict-Transport-Security: " . $hsts_policy);

    // --- CORS and Preflight Request Handling ---

    // Handle preflight OPTIONS requests.
    // Preflight requests are sent by browsers to check CORS permissions before sending the actual request.
    if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
        // Access-Control-Allow-Origin: *: Allows requests from any origin for preflight.
        header("Access-Control-Allow-Origin: *");
        // Access-Control-Allow-Methods: Specifies the methods allowed when accessing the resource.
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
        // Access-Control-Allow-Headers: Specifies the headers that can be used during the actual request.
        header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept, Authorization");
        // Access-Control-Allow-Credentials: false: Explicitly state that credentials are not allowed.
        // When using a wildcard origin ('*'), credentials must typically be false.
        header("Access-Control-Allow-Credentials: false");
        
        // Set HTTP status to 204 No Content, which is appropriate for preflight responses.
        status_header(204); 
        // Terminate script execution for preflight requests as no further processing is needed.
        exit;
    }

    // --- Headers for Non-Preflight Requests ---

    // CORS Headers for actual requests
    // Access-Control-Allow-Origin: *: Allows requests from any origin.
    // While '*' is permissive, it's often used for public APIs. For tighter security, replace '*' with specific domain(s).
    header("Access-Control-Allow-Origin: *");
    // Access-Control-Allow-Credentials: false: Explicitly state that credentials are not allowed for actual requests as well,
    // consistent with the preflight and the use of '*' origin.
    header("Access-Control-Allow-Credentials: false");

    // Alternative Services (Alt-Svc) Header.
    // Informs the client that the service is also available via other means, e.g., HTTP/3 on specific ports.
    // h3=":443"; ma=86400: Advertise HTTP/3 support on UDP port 443 for 24 hours (86400 seconds).
    // h3-29=":443": Advertise support for an older draft version of HTTP/3 (draft 29) for broader compatibility.
    // This header is generally not needed for OPTIONS responses.
    $alt_svc_policy = 'h3=":443"; ma=86400, h3-29=":443"; ma=86400'; // Added ma for h3-29 as well
    header("alt-svc: " . $alt_svc_policy);
}

// Hook the custom_security_headers function into the send_headers action.
// This ensures that the headers are sent with every page request.
add_action('send_headers', 'custom_security_headers');

?>
