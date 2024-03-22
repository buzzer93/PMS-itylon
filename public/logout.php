<?php
/**
 * Start the session and unset the 'connected' session variable.
 * Redirects to the login page after unsetting the variable.
 */
session_start();

// Unset the 'connected' session variable
unset($_SESSION['connected']);

// Redirect to the login page
header('Location: /login.php');
