<?= $this->extend(smarty_view('layout')); ?>

<?= $this->section('title') ?><?= smarty_pagetitle(lang('Url.addNewURLTitle')); ?> <?= $this->endSection() ?>

<?= $this->section('main') ?>

<div class="app-content-header">
    <!--begin::Container-->
    <div class="container-fluid">
        <!--begin::Row-->
        <div class="row">
            <div class="col-sm-6">
                <h3 class="mb-0"><?= lang('Url.addNewURLTitle'); ?></h3>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                    <li class="breadcrumb-item"><a
                            href="<?= site_url('/dashboard'); ?>"><?= lang('Common.dashboardLnk'); ?></a></li>
                    <li class="breadcrumb-item active" aria-current="page">
                        <a href="<?= site_url('/url'); ?>">
                            <?= lang('Url.urlsLink'); ?>
                        </a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">
                        <?= lang('Url.addNewURLTitle'); ?>
                    </li>
                </ol>
            </div>
        </div>
        <!--end::Row-->
    </div>
    <!--end::Container-->
</div>




<div class="app-content-header">
    <!--begin::Container-->
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <!-- Default box -->
                <div class="card">
                    <form action="your-action-url" method="post">
                        <div class="card-header">
                            <!-- Use Bootstrap grid for two columns -->
                            <div class="row">
                                <div class="col-6">
                                    <h3 class="card-title"><?= lang('Url.addNewURLTitle'); ?></h3>
                                </div>
                                <div class="col-6 text-end">
                                    <!-- Replace with Font Awesome help icon -->
                                    <i class="fas fa-question-circle"></i>
                                </div>
                            </div>
                            <!-- Remove minimize and close buttons -->
                            <!-- Remove the button elements -->
                        </div>
                        <div class="card-body" style="box-sizing: border-box; display: block;">
                            <!-- Input group for the input field with a help icon -->
                            <div class="input-group">
                                <input type="text" class="form-control form-control-lg" placeholder="https://example.com/some">
                                <span class="input-group-text">
                                    <i class="bi bi-link-45deg"></i>
                                </span>
                            </div>
                        </div>
                        <!-- /.card-body -->
                        <div class="card-footer" style="box-sizing: border-box; display: block;">
                            <!-- Use Bootstrap utility classes to align Save button to the right -->
                            <div class="d-flex justify-content-end">
                                <input type="submit" class="btn btn-primary" value="Save">
                            </div>
                        </div>
                        <!-- /.card-footer-->
                    </form>
                </div>
                <!-- /.card -->
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>


<?= $this->section('jsfooterarea') ?>
<script src="<?= smarty_cdn() ?>js/url/newurl.js"></script>
<?= $this->endSection() ?>

