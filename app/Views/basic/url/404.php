<!DOCTYPE html>
<html lang="<?= smarty_current_lang(); ?>" dir="<?= smarty_current_lang_direction(); ?>">
<!--begin::Head-->

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title><?= setting('Smartyurl.siteName') . ' - ' . lang('Url.urlNotFoundShort') ?></title>
    <meta name="title" content="<?= setting('Smartyurl.siteName') . ' - ' . lang('Url.urlNotFoundShort') ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">


    <!-- Include Bootstrap 5 CSS (You may need to adjust the path) -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css">
    <!-- Include your custom CSS for styling -->
    <!--begin::Third Party Plugin(Bootstrap Icons)-->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.min.css" integrity="sha256-BicZsQAhkGHIoR//IB2amPN5SrRb3fHB8tFsnqRAwnk=" crossorigin="anonymous">

    <style>
        @charset "utf-8";
        /* CSS Document */
        body {
            margin: 0;
        }
        h1 {
            font-family: Baskerville, "Palatino Linotype", Palatino,
            "Century Schoolbook L", "Times New Roman", "serif";
            font-size: 5em;

        }
        p {
            font-family: Baskerville, "Palatino Linotype", Palatino,
            "Century Schoolbook L", "Times New Roman", "serif";
            font-size: 2em;

        }
        canvas {
            display: block;
            vertical-align: top;
            top: 0;
        }
        #tsparticles {
            margin-right: auto;
            margin-left: auto;
            width: 95%;
            background-color: rgb(255, 255, 255);
            text-align: center;
        }
    </style>


</head>
<body style="background-color: #f8f9fa;">

<div class="container text-center py-5">
    <h1 class="display-4 text-primary">
        <i class="bi bi-emoji-dizzy"></i>

</div>

<section id="tsparticles">
    <h1>404 Not Found</h1>

    <p>
        <?= lang('Url.urlNotFoundShort'); ?>
        <br />
        <?= lang('Url.urlNotFoundLong'); ?>
    </p>
</section>

<footer class="d-flex justify-content-center align-items-center">
    <a href="<?= setting('Smartyurl.mainpagefor404notfound'); ?>" class="btn btn-outline-primary btn-lg">Go to Website</a>
</footer>

<!-- Include Bootstrap 5 JavaScript (You may need to adjust the path) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/js/bootstrap.bundle.min.js"></script>
<!-- Include your custom JavaScript (if any) -->
</body>
</html>
