<?php
/**
 * Checks if the user is currently connected.
 *
 * @return bool Returns true if the user is connected, false otherwise.
 */
function isConnected(): bool
{
    // Start session if not already started
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    return !empty($_SESSION['connected']);
}

/**
 * Redirects to the login page if the user is not connected.
 * Exits the script after redirection.
 */
function login()
{
    // If not connected, redirect to login page
    if (!isConnected()) {
        header('Location: ./login.php');
        exit();
    }
}
