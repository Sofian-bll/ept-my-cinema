<!DOCTYPE html>
<html lang='fr'>
<head>
    <meta charset='UTF-8'>
    <link
            rel='stylesheet'
            href='https://cdn.jsdelivr.net/npm/@picocss/pico@2/css/pico.classless.min.css'
    >
    <title>MVC</title>
</head>
<body>
    <?php require 'partials/_header.php'; ?>
    <main>
        <?php if ($viewPath) {
            require($viewPath);
        } ?>
    </main>
    <?php require 'partials/_footer.php'; ?>

</body>
</html>