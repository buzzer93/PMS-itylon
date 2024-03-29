<?php
/**
 * Start the session and unset the 'connected' session variable.
 * Redirects to the login page after unsetting the variable.
 */
session_start();
session_destroy();
// Redirect to the login page
header('Location: /login.php');
