<?php
/**
 * @var Exception $error
 */
?>
<!doctype html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>Pamutlabor Teszt Feladat</title>
</head>
<body>
<h1 class="text-3xl font-bold underline">
    Error!
</h1>
<p>
    <?php echo $error->getMessage(); ?>
</p>
</body>
</html>