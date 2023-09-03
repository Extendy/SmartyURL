<!DOCTYPE html>
<html lang="<?= smarty_current_lang(); ?>" dir="<?= smarty_current_lang_direction(); ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= lang('Common.permissionsError'); ?></title>
    <!-- Include AdminLTE CSS -->
    <link rel="stylesheet" href="<?= smarty_cdn()?>css/adminlte<?= smarty_current_lang_dotdirection(); ?>.min.css">
    <link rel="stylesheet" href="<?= smarty_cdn()?>css/smarty<?= smarty_current_lang_dotdirection(); ?>.css">
    <style>
        /* Custom styles for the error page */
        body {
            background-color: #f8f9fa;
        }
        .error-container {
            text-align: center;
            padding: 100px 0;
        }
        .error-heading {
            font-size: 2rem;
            color: #dc3545;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.2);
        }
        .error-box {
            background-color: #fff;
            border: 1px solid #dcdcdc;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .error-message {
            font-size: 1.5rem;
            margin-top: 20px;
            color: #343a40;
        }
        .home-button {
            margin-top: 30px;
            background-color: #007bff;
            border-color: #007bff;
            color: #fff;
        }
        .home-button:hover {
            background-color: #0056b3;
            border-color: #0056b3;
        }
    </style>
</head>
<body>


<div class="container error-container">
    <div class="row">
        <div class="col-md-12">

            <div class="error-box">
                <h1 class="error-heading"><?= lang('Common.permissionsError'); ?></h1>
                <p class="error-message"><?= lang('Common.permissionsNoenoughpermissions'); ?></p>
                <p>

                        <?= $errorMsg; ?>
                </p>
                <a href="<?= site_url('/dashboard'); ?>" class="btn btn-primary home-button"><?= lang('Common.dashboardLnk'); ?></a>
            </div>

        </div>
    </div>
</div>


<!-- Include Bootstrap 5 JavaScript and Popper.js for Bootstrap's JavaScript plugins -->
<script src="<?= smarty_cdn()?>js/adminlte.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
</body>
</html>
