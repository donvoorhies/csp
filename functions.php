<?php
// File: functions.php

/**
 * Set Content Security Policy and HTTP Strict Transport Security headers.
 *
 * This function defines and sets the Content-Security-Policy and
 * Strict-Transport-Security HTTP headers to enhance the security of the
 * WordPress site.
 */
function custom_csp_headers() {
    // Define the Content Security Policy.
    // default-src 'self': Allows loading resources only from the same origin.
    // script-src 'self' 'unsafe-inline' 'unsafe-eval': Allows scripts from the same origin, inline scripts, and dynamic script evaluation (e.g., via eval()).
    // style-src 'self' 'unsafe-inline': Allows stylesheets from the same origin and inline styles.
    // img-src 'self' data:: Allows images from the same origin and data URIs.
    $csp_policy = "default-src 'self'; script-src 'self' 'unsafe-inline' 'unsafe-eval'; style-src 'self' 'unsafe-inline'; img-src 'self' data:;";

    // Set the Content-Security-Policy header.
    header("Content-Security-Policy: " . $csp_policy);

    // Define the HTTP Strict Transport Security (HSTS) policy.
    // max-age=31536000: Tells browsers to remember to enforce HTTPS for one year.
    //                   It's recommended to start with a lower value (e.g., max-age=300 for 5 minutes) for testing
    //                   and gradually increase it.
    // includeSubDomains: Applies HSTS to all subdomains. Ensure all subdomains support HTTPS before enabling this.
    // preload: Allows the site to be submitted to HSTS preload lists maintained by browser vendors.
    //          This is a powerful directive; ensure your site and all subdomains are fully HTTPS capable
    //          and that you understand the implications before using 'preload'.
    //          Submission to preload lists is a separate process (e.g., hstspreload.org).
    $hsts_policy = "max-age=31536000; includeSubDomains; preload";

    // Set the Strict-Transport-Security header.
    // Note: HSTS headers are only effective over an HTTPS connection.
    // WordPress should be configured to enforce HTTPS for this to have an effect.
    header("Strict-Transport-Security: " . $hsts_policy);
}

// Hook the custom_csp_headers function into the send_headers action.
// This ensures that the CSP and HSTS headers are sent with every page request.
add_action('send_headers', 'custom_csp_headers');

?>
