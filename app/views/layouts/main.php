<?php
/**
 * Main Layout Template
 * 
 * This is the main layout template used across the application.
 * It provides the HTML structure, head elements, and common assets.
 * 
 * @package RideRentPro\Views\Layouts
 * @author RideRent Pro Team
 * @version 1.0.0
 * 
 * @var string $pageTitle Optional page title to display
 */
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($pageTitle) ? $pageTitle . ' - RideRent Pro' : 'RideRent Pro' ?></title>
    <link rel="stylesheet" href="<?= APP_BASE_URL ?>/assets/css/new-theme.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
<script src="<?= APP_BASE_URL ?>/assets/js/theme.js"></script>

</body>
</html>