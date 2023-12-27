<html data-theme="cupcake">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($title) ? htmlspecialchars($title) : 'My Website' ?></title>
    <?php if (isset($description)) : ?>
    <meta name="description" content="<?= htmlspecialchars($description) ?>">
    <?php endif; ?>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.2.1/flowbite.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/daisyui@4.4.24/dist/full.min.css" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="/css/styles-movie.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css?family=Poppins" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        poppins: ['Poppins', 'sans-serif'],
                    },
                    colors: {
                        clifford: '#da373d',
                    }
                }
            }
        }
    </script>
    <!-- Include CSS files -->

    <?php if (isset($cssFiles) && is_array($cssFiles)) : ?>
        <?php foreach ($cssFiles as $cssFile) : ?>
            <link rel="stylesheet" href="<?= htmlspecialchars($cssFile) ?>">
        <?php endforeach; ?>
    <?php endif; ?>

    <!-- Include JavaScript files -->
    <?php if (isset($jsFiles) && is_array($jsFiles)) : ?>
        <?php foreach ($jsFiles as $jsFile) : ?>
            <script src="<?= htmlspecialchars($jsFile) ?>" defer></script>
        <?php endforeach; ?>
    <?php endif; ?>
</head>

<body class="font-poppins sticky top-0 z-50">
    <?php require('nav.php') ?>
    <?php require('footer.php') ?>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.2.0/flowbite.min.js"></script>
</body>

</html>