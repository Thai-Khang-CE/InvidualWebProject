<!DOCTYPE html>
<html lang="en">
<head>
    <?php
    $siteName = 'Seasonal Wardrobe';
    $resolvedPageTitle = isset($pageTitle) && is_string($pageTitle) && trim($pageTitle) !== '' ? trim($pageTitle) : $siteName;
    $resolvedPageDescription = isset($pageDescription) && is_string($pageDescription) && trim($pageDescription) !== ''
        ? trim($pageDescription)
        : 'Seasonal Wardrobe is a seasonal fashion store for Spring, Summer, Autumn, and Winter collections.';
    $fullTitle = $resolvedPageTitle === $siteName ? $siteName : $resolvedPageTitle . ' | ' . $siteName;
    ?>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="<?php echo htmlspecialchars($resolvedPageDescription, ENT_QUOTES, 'UTF-8'); ?>">
    <title><?php echo htmlspecialchars($fullTitle, ENT_QUOTES, 'UTF-8'); ?></title>
    <link rel="sitemap" type="application/xml" title="Sitemap" href="sitemap.xml">
    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="css/responsive.css">
</head>
<body>
