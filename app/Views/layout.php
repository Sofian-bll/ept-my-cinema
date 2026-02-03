<!DOCTYPE html>
<html lang='fr'>
<head>
    <meta charset='UTF-8'>
    <title>MVC</title>
</head>
<body>
    <?php require 'partials/_header.php'; ?>
    <main>
        <?php if ($viewPath) {require($viewPath);} ?>
    </main>
    <?php require 'partials/_footer.php'; ?>

</body>
</html>