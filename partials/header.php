<?php
// functions.php'yi sayfaya dahil et (Veritabanı, session ve fonksiyonlar)
require_once __DIR__ . '/../config/functions.php';
?>
<!DOCTYPE html>
<html lang="tr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Görev Yönetim Sistemi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        .task-completed {
            text-decoration: line-through;
            opacity: 0.7;
        }
    </style>
</head>

<body class="bg-light">

    <?php
    // Navbar'ı buraya dahil et
    include_once __DIR__ . '/navbar.php';
    ?>

    <div class="container mt-4">