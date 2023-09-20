<!DOCTYPE html>
<html lang="<?= smarty_current_lang(); ?>" dir="<?= smarty_current_lang_direction(); ?>">
<!--begin::Head-->

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title><?= setting('Smartyurl.siteName') . ' - ' . lang('URl.urlNotFoundShort') ?></title>
    <meta name="title" content="<?= setting('Smartyurl.siteName') . ' - ' . lang('URl.urlNotFoundShort') ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">


    <!-- Include Bootstrap 5 CSS (You may need to adjust the path) -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css">
    <!-- Include your custom CSS for styling -->

</head>
<body style="background-color: #f8f9fa;">

<div class="container text-center py-5">
    <h1 class="display-4 text-primary">404 Not Found - <?= lang('URl.urlNotFoundShort'); ?></h1>
    <p class="lead"><?= lang('URl.urlNotFoundLong'); ?></p>
</div>

<!-- Include Bootstrap 5 JavaScript (You may need to adjust the path) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/js/bootstrap.bundle.min.js"></script>
<!-- Include your custom JavaScript (if any) -->
</body>
</html>
