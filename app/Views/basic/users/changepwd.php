<?= $this->extend(smarty_view('layout')); ?>

<?= $this->section('title') ?><?= smarty_pagetitle(lang('Account.changeActpassword')); ?> <?= $this->endSection() ?>

<?= $this->section('main') ?>


<div class="app-content-header">
    <!--begin::Container-->
    <div class="container-fluid">
        <!--begin::Row-->
        <div class="row">
            <div class="col-sm-6">
                <h3 class="mb-0"><?=lang('Account.changeActpassword');?></h3>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                    <li class="breadcrumb-item"><a href="<?= site_url('/dashboard'); ?>"><?= lang('Common.dashboardLnk'); ?></a></li>
                    <li class="breadcrumb-item active" aria-current="page">
                        <a href="<?= site_url('/account/settings'); ?>">
                            <?=lang("Account.accountSettings");?>
                        </a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">
                        <?= lang('Account.changeActpassword'); ?>
                    </li>
                </ol>
            </div>
        </div>
        <!--end::Row-->
    </div>
    <!--end::Container-->
</div>


<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-10 mx-auto">
            <div>
                <div class="col-md-12">
                    <div class="card card-primary card-outline">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-edit"></i>
                                Modal Examples
                            </h3>
                        </div>
                        <div class="card-body">
                            <!-- Error Message (Initially Hidden) -->
                            <?php if (session('error') !== null) : ?>
                            <div class="alert alert-danger" role="alert" id="errorMessage" style="">
                                <?= session('error') ?>
                            </div>
                            <?php elseif (session('errors') !== null) : ?>
                                <div class="alert alert-danger" role="alert" >
                                    <?php if (is_array(session('errors'))) : ?>
                                        <?php foreach (session('errors') as $error) : ?>
                                            <?= $error ?>
                                            <br>
                                        <?php endforeach ?>
                                    <?php else : ?>
                                        <?= session('errors') ?>
                                    <?php endif ?>
                                </div>
                            <?php endif ?>

                            <?php if (session('message') !== null) : ?>
                            <div class="alert alert-success" role="alert" id="infoMessage" style="">
                                <?= session('message') ?>
                            </div>
                            <?php endif ?>


                            <form action="<?= url_to('account/changepwd') ?>" method="post">
                                <?= csrf_field() ?>
                                <div class="mb-3">
                                    <label for="currentPassword" class="form-label"><?=lang('Account.currentPassword');?></label>
                                    <input type="password" class="form-control" id="currentPassword" name="currentPassword" required>
                                </div>
                                <div class="mb-3">
                                    <label for="newPassword" class="form-label"><?=lang('Account.NewPasswprd');?></label>
                                    <input type="password" class="form-control" id="newPassword" name="newPassword" required>
                                </div>
                                <div class="mb-3">
                                    <label for="confirmPassword" class="form-label"><?=lang('Account.NewPasswordConfirm');?></label>
                                    <input type="password" class="form-control" id="confirmPassword" name="confirmPassword" required>
                                </div>
                                <div class="row">
                                    <!-- Add Cancel button to the top-left corner -->
                                    <div class="col-6">
                                        <button type="button" class="btn btn-secondary" onclick="window.location.href='<?= url_to('dashboard') ?>'"> <?=lang("Account.ChangePasswordCancelbtn");?> </button>
                                    </div>
                                    <div class="col-6 text-end">
                                        <button type="submit" class="btn btn-primary"><?=lang('Account.ChangePasswordSubmit');?></button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>




<?= $this->endSection() ?>
