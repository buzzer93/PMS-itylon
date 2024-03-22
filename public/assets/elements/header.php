<?php
require_once '../functions/fonction.php';
?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link href="./assets/css/style.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet" />
    <title>
        <?php if (isset($title)) : ?>
            <?= $title ?>
        <?php else : ?>
            PMS-Itylon
        <?php endif ?>
    </title>
</head>

<body>
    <div class="header" id="header">
        <nav class="nav container">
            <a href="index.php" class="nav-logo"> PMS-<span>ITYLON</span></a>
            <div class="nav-menu" id="nav-menu">
                <div id="menuContainer"></div>
                <!-- close button -->
                <div class="nav-close" id="nav-close">
                    <i class="ri-close-line"></i>
                </div>
            </div>
            <!-- toggle button -->
            <div class="nav-toggle" id="nav-toggle">
                <i class="ri-menu-line"></i>
            </div>
        </nav>
    </div>