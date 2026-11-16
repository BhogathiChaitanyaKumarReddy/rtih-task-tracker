<?php
/**
 * RTIH Task Tracker - Main Application Entrypoint
 * Ratan Tata Innovation Hub (RTIH) Amaravati Foundation
 */

require_once __DIR__ . '/includes/auth.php';

if (isLoggedIn()) {
    header('Location: dashboard.php');
} else {
    header('Location: auth/login.php');
}
exit;

