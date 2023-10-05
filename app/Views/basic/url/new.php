<?= $this->extend(smarty_view('layout')); ?>

<?= $this->section('title') ?><?= smarty_pagetitle(lang('Url.addNewURLTitle')); ?> <?= $this->endSection() ?>

<?= $this->section('main') ?>

<!-- Spinner container -->
<div id="spinner" class="d-flex justify-content-center align-items-center" style="height: 100vh;">
    <div class="spinner-grow" role="status">
        <span class="visually-hidden">Loading...</span>
    </div>


</div>

<div id="addnewurlcontent" style="display: none;">
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
        <div id="newurlcontainer" class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <!-- Default box -->
                    <div class="card">
                        <form id="addNewURL" action="<?= url_to('url/new') ?>" method="post">
                            <?= csrf_field() ?>
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

                                <div class="mt-2">
                                    <label class="" for="originalUrl"><?= lang('Url.OriginalUrl'); ?>:</label>
                                    <input dir="ltr" type="url" class="form-control " name="originalUrl"
                                           id="UrlTitle" required
                                           placeholder="https://example.com/somelink?example=1">
                                </div>


                                <div class="mt-2">
                                    <label class="" for="UrlTitle"><?= lang('Url.UrlTitle') . ' ' . lang('Url.UrlTitleDescription') . ' ' . lang('Common.Optional'); ?>:</label>
                                    <input type="text" class="form-control " name="UrlTitle"  id="UrlTitle"  placeholder="<?= lang('Url.UrlTitleDescription'); ?>" >
                                </div>


                                <div class="mt-2">
                                    <label for="basic-url" class="form-label"><?= lang('Url.MaskedShortUrl'); ?>
                                        :</label>
                                    <div class="input-group mb-3" dir="ltr">
                                    <span class="input-group-text"
                                          id="basic-addon3"><?= smarty_detect_site_shortlinker(); ?></span>
                                        <input type="text" dir="ltr" class="form-control" id="basic-url"
                                               aria-describedby="basic-addon3">
                                    </div>
                                </div>


                                <div class="container mt-4">

                                    <div class="dropdown">
                                        <button class="btn btn-dark dropdown-toggle" type="button"
                                                id="choosUrlCondition" data-bs-toggle="dropdown" aria-expanded="false">
                                            <?= lang('Url.AddRedirectConditionOptional'); ?>
                                        </button>
                                        <ul id="newurlconditionmenu" class="dropdown-menu dropdown-menu-dark"
                                            aria-labelledby="choosUrlCondition">
                                            <li>
                                                <a class="smarty-clickable-link dropdown-item" id="addGeoloctionCond" href="#">
                                                    <?= lang('Url.ByvisitorsGeolocation'); ?>
                                                </a>
                                            </li>
                                            <li>
                                                <a class="smarty-clickable-link dropdown-item" id="addDeviceCond" href="#">
                                                    <?= lang('Url.ByvisitorsDevice'); ?>
                                                </a>
                                            </li>

                                        </ul>
                                    </div>
                                </div>


                                <div id="conditions_div" class="mt-4">

                                    <!-- here content fron javascript comes for the redirect condition -->

                                </div>















                                <div class="mt-4">
                                    <label for="urlTags" class="form-label"><?= lang('Url.URLTagsOptional'); ?></label>


                                    <div class="input-group mb-3">
                                        <input name='urlTags' id="urlTags" class='form-control' placeholder='<?= lang('Url.EnterSomeTags'); ?>' value=''>


                                        <div id="tagsContainer" class="mt-2"></div>


                                    </div>
                                </div>

                            </div>


                            <!-- /.card-body -->
                            <div class="card-footer" style="box-sizing: border-box; display: block;">
                                <!-- Use Bootstrap utility classes to align Save button to the right -->
                                <div class="d-flex justify-content-end">
                                    <input type="submit" class="btn btn-primary"
                                           value=" <?= lang('Url.AddNewUrlSubmitbtn'); ?>">
                                </div>
                            </div>
                            <!-- /.card-footer-->
                            <input type="hidden" name="redirectCondition" id="redirectCondition" value="">
                        </form>
                    </div>
                    <!-- /.card -->
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>


<?= $this->section('jsfooterarea') ?>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet"/>
<link href="<?= smarty_cdn() ?>css/select2-bootstrap.css" rel="stylesheet"/>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/@yaireo/tagify"></script>
<script src="https://cdn.jsdelivr.net/npm/@yaireo/tagify/dist/tagify.polyfills.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/@yaireo/tagify/dist/tagify.css" rel="stylesheet" type="text/css" />

<link href="<?= smarty_cdn() ?>css/urltags.css" rel="stylesheet" type="text/css" />

<script src="<?= site_url('assist/newurl.js') ?>"></script>
<?= $this->endSection() ?>

