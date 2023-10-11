<?= $this->extend(smarty_view('layout')); ?>

<?= $this->section('title') ?><?= smarty_pagetitle(lang('Common.dashboardTitle')); ?> <?= $this->endSection() ?>

<?= $this->section('main') ?>



<!--begin::App Content Header-->
<div class="app-content-header">
    <!--begin::Container-->
    <div class="container-fluid">
        <!--begin::Row-->
        <div class="row">
            <div class="col-sm-6">
                <h3 class="mb-0">Fixed Layout</h3>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">
                        Fixed Layout
                    </li>
                </ol>
            </div>

        </div>
        <!--end::Row-->
    </div>
    <!--end::Container-->
</div>
<!--end::App Content Header-->
<!--begin::App Content-->
<div class="app-content">
    <!--begin::Container-->
    <div class="container-fluid">
        <!--begin::Row-->
        <div class="row">
            <div class="col-12">

                <?php if (session('error') !== null) : ?>
                    <div class="alert alert-danger" role="alert" id="infoMessage" style="">
                        <?= session('error') ?>
                    </div>
                <?php endif ?>

                <?php if (session('message') !== null) : ?>
                    <div class="alert alert-success" role="alert" id="infoMessage" style="">
                        <?= session('message') ?>
                    </div>
                <?php endif ?>

                <?php if (session('notice') !== null) : ?>
                    <div class="alert alert-warning" role="alert" id="infoMessage" style="">
                        <?= session('notice') ?>
                    </div>
                <?php endif ?>

                <!-- Default box -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Title</h3>

                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-lte-toggle="card-collapse" title="Collapse">
                                <i data-lte-icon="expand" class="bi bi-plus-lg"></i>
                                <i data-lte-icon="collapse" class="bi bi-dash-lg"></i>
                            </button>
                            <button type="button" class="btn btn-tool" data-lte-toggle="card-remove" title="Remove">
                                <i class="bi bi-x-lg"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        Start creating your amazing application!
                        Fixed Layout
                        <br /><br /><br /><br /><br /><br /><br /><br />
                        <br /><br /><br /><br /><br /><br /><br />
                        <br /><br /><br /><br /><br /><br />
                        <br /><br /><br /><br /><br /><br /><br />

                    </div>
                    <!-- /.card-body -->
                    <div class="card-footer">Footer</div>
                    <!-- /.card-footer-->
                </div>
                <!-- /.card -->
            </div>
        </div>
        <!--end::Row-->
    </div>
    <!--end::Container-->
</div>
<!--end::App Content-->


<?= $this->endSection() ?>
