<?php
session_start();
include '../connection.php'; // Include your database connection

/**
 * Check user privileges based on their role.
 *
 * @param string $userRole
 * @return array
 */
function checkUserPrivileges($userRole) {
    switch ($userRole) {
        case 'Admin':
            return ['home.php', 'alumni.php', 'courses.php']; // Admin can access all
        case 'Registrar':
            return ['home.php', 'alumni.php']; // Registrar access
        case 'Dean':
            return ['alumni.php']; // Dean access
        case 'Program Chair':
            return ['alumni.php']; // Program Chair access
        case 'Alumni':
            return ['alumni.php']; // Alumni access
        default:
            return []; // No access
    }
}

/**
 * Set user session after login.
 *
 * @param array $userData
 */
function setUserSession($userData) {
    $_SESSION['user_id'] = $userData['id'];
    $_SESSION['user_role'] = $userData['role'];
    $_SESSION['user_college'] = $userData['college'] ?? null; // For Dean
    $_SESSION['user_department'] = $userData['department'] ?? null; // For Program Chair
}

/**
 * Render sidebar based on user role.
 *
 * @param string $role
 */
function renderSidebar($role) {
    $accessiblePages = checkUserPrivileges($role);
    echo '<ul class="sidebar-menu">';
    foreach ($accessiblePages as $page) {
        echo '<li><a href="' . htmlspecialchars($page) . '">' . ucfirst(basename($page, '.php')) . '</a></li>';
    }
    echo '</ul>';
}

/**
 * Check if the user is logged in.
 */
function checkLogin() {
    if (!isset($_SESSION['user_id'])) {
        header('Location: login.php'); // Redirect to login if not logged in
        exit();
    }
}
?>
