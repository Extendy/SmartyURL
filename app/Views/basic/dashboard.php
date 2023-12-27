<?= $this->extend(smarty_view('layout')); ?>

<?= $this->section('title') ?><?= smarty_pagetitle(lang('Common.dashboardTitle')); ?> <?= $this->endSection() ?>

<?= $this->section('main') ?>


<!--begin::App Content Header-->
<div class="app-content-header">

</div>
<!--end::App Content Header-->
<!--begin::App Content-->


<div class="container-fluid">

    <?php if (session('error') !== null) : ?>
        <div class="alert alert-danger" role="alert" id="infoMessage" style="">
            <?= session('error') ?>
        </div>
    <?php endif ?>


    <?php if (session('notice') !== null) : ?>
        <div class="alert alert-warning" role="alert" id="infoMessage" style="">
            <?= session('notice') ?>
        </div>
    <?php endif ?>

    <?php if (session('message') !== null) : ?>
        <div class="alert alert-success" role="alert" id="infoMessage" style="">
            <?= session('message') ?>
        </div>
    <?php endif ?>


    <?php if ($show_global_statistics) {
        ?>
        <!-- BEGIN: Url Count boxes -->
        <div class="row">
            <div class="col-12 col-sm-4 col-md-4">
                <div class="info-box">
                                <span class="info-box-icon text-bg-primary shadow-sm">
                                    <i class="bi bi bi-calendar-fill"></i>
                                </span>

                    <div class="info-box-content">
                        <span class="info-box-text"><?= lang('Common.CountsTotalAllUrl'); ?></span>
                        <span class="info-box-number">
                                        <?= $all_urls_count; ?>

                                    </span>
                    </div>
                    <!-- /.info-box-content -->
                </div>
                <!-- /.info-box -->
            </div>
            <!-- /.col -->
            <div class="col-12 col-sm-4 col-md-4">
                <div class="info-box">
                                <span class="info-box-icon text-bg-danger shadow-sm">
                                    <i class="bi bi-calendar-week-fill"></i>
                                </span>

                    <div class="info-box-content">
                        <span class="info-box-text"><?= lang('Common.CountsThisMonthAllUrl'); ?></span>
                        <span class="info-box-number"><?= $all_urls_this_month; ?></span>
                    </div>
                    <!-- /.info-box-content -->
                </div>
                <!-- /.info-box -->
            </div>
            <!-- /.col -->

            <!-- fix for small devices only -->
            <!-- <div class="clearfix hidden-md-up"></div> -->

            <div class="col-12 col-sm-4 col-md-4">
                <div class="info-box">
                                <span class="info-box-icon text-bg-success shadow-sm">
                                    <i class="bi bi-calendar-event-fill"></i>
                                </span>

                    <div class="info-box-content">
                        <span class="info-box-text"><?= lang('Common.CountsTodayAllUrl'); ?></span>
                        <span class="info-box-number"><?= $all_urls_today; ?></span>
                    </div>
                    <!-- /.info-box-content -->
                </div>
                <!-- /.info-box -->
            </div>

        </div>
        <!-- /.row -->
        <!-- END: Url Count boxes -->


        <!-- BEGIN: Url Hits count boxes -->
        <div class="row">
            <div class="col-12 col-sm-4 col-md-4">
                <div class="info-box">
                                <span class="info-box-icon text-bg-primary shadow-sm">
                                    <i class="bi bi-folder-symlink-fill"></i>
                                </span>

                    <div class="info-box-content">
                        <span class="info-box-text"><?= lang('Common.CountsTotalAllHits'); ?></span>
                        <span class="info-box-number">
                                        <?= $all_hits_count; ?>

                                    </span>
                    </div>
                    <!-- /.info-box-content -->
                </div>
                <!-- /.info-box -->
            </div>
            <!-- /.col -->
            <div class="col-12 col-sm-4 col-md-4">
                <div class="info-box">
                                <span class="info-box-icon text-bg-danger shadow-sm">
                                    <i class="bi bi-calendar-event-fill"></i>
                                </span>

                    <div class="info-box-content">
                        <span class="info-box-text"><?= lang('Common.CountsThisMonthAllHits'); ?></span>
                        <span class="info-box-number"><?= $all_hits_this_month; ?></span>
                    </div>
                    <!-- /.info-box-content -->
                </div>
                <!-- /.info-box -->
            </div>
            <!-- /.col -->

            <!-- fix for small devices only -->
            <!-- <div class="clearfix hidden-md-up"></div> -->

            <div class="col-12 col-sm-4 col-md-4">
                <div class="info-box">
                                <span class="info-box-icon text-bg-success shadow-sm">
                                    <i class="bi bi-cart-fill"></i>
                                </span>

                    <div class="info-box-content">
                        <span class="info-box-text"><?= lang('Common.CountsTodayAllHits'); ?></span>
                        <span class="info-box-number"><?= $all_hits_today; ?> </span>
                    </div>
                    <!-- /.info-box-content -->
                </div>
                <!-- /.info-box -->
            </div>

        </div>
        <!-- /.row -->
        <!-- END: Url Hits count boxes -->

        <?php
    }
?>


    <!-- my -->

    <!-- BEGIN: Url Hits count boxes -->
    <div class="row">
        <div class="col-12 col-sm-4 col-md-4">
            <div class="info-box">
                                <span class="info-box-icon text-bg-primary shadow-sm">
                                    <i class="bi bi-folder-symlink-fill"></i>
                                </span>

                <div class="info-box-content">
                    <span class="info-box-text"><?= lang('Common.MyURLsHitCountAllTime'); ?></span>
                    <span class="info-box-number">
                                        <?= $myurl_hits_alltime; ?>

                                    </span>
                </div>
                <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
        </div>
        <!-- /.col -->
        <div class="col-12 col-sm-4 col-md-4">
            <div class="info-box">
                                <span class="info-box-icon text-bg-danger shadow-sm">
                                    <i class="bi bi-calendar-event-fill"></i>
                                </span>

                <div class="info-box-content">
                    <span class="info-box-text"><?= lang('Common.MyURLsHitCountThisMonth'); ?></span>
                    <span class="info-box-number"><?= $myurl_hits_thismonth; ?></span>
                </div>
                <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
        </div>
        <!-- /.col -->

        <!-- fix for small devices only -->
        <!-- <div class="clearfix hidden-md-up"></div> -->

        <div class="col-12 col-sm-4 col-md-4">
            <div class="info-box">
                                <span class="info-box-icon text-bg-success shadow-sm">
                                    <i class="bi bi-cart-fill"></i>
                                </span>

                <div class="info-box-content">
                    <span class="info-box-text"><?= lang('Common.MyURLsHitCountToday'); ?></span>
                    <span class="info-box-number"><?= $myurl_hits_today; ?> </span>
                </div>
                <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
        </div>

    </div>
    <!-- /.row -->
    <!-- END: Url Hits count boxes -->


</div>


<!--end::App Content-->


<?= $this->endSection() ?>
