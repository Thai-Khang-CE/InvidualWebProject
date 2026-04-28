<?php

require_once __DIR__ . '/../config/database.php';

$message = 'Database connection failed';

try {
    $database = new Database();
    $database->getConnection();
    $message = 'Database connected successfully';
} catch (Throwable $exception) {
    $message = 'Database connection failed';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Seasonal Wardrobe</title>
    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="css/responsive.css">
</head>
<body>
    <div class="container">
        <h1><?php echo htmlspecialchars($message, ENT_QUOTES, 'UTF-8'); ?></h1>
    </div>

    <script src="js/main.js"></script>
</body>
</html>
