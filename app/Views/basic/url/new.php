<?= $this->extend(smarty_view('layout')); ?>

<?= $this->section('title') ?><?= smarty_pagetitle(isset($editUrlAction) ? lang('Url.UpdateURLTitle') : lang('Url.addNewURLTitle')); ?>  <?= $this->endSection() ?>

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
                    <h3 class="mb-0"><?= isset($editUrlAction) ? lang('Url.UpdateURLTitle') : lang('Url.addNewURLTitle'); ?> <?= $UrlId ?? ''; ?></h3>
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
                            <?= isset($editUrlAction) ? lang('Url.UpdateURLTitle') : lang('Url.addNewURLTitle'); ?>
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
                        <form id="addNewURL" action="<?= $editUrlAction ?? url_to('url/new') ?>" method="post">
                            <?= csrf_field() ?>
                            <input type="hidden" name="redirectCondition" id="redirectCondition" value="">
                            <div class="card-header">
                                <!-- Use Bootstrap grid for two columns -->
                                <div class="row">
                                    <div class="col-6">
                                        <h3 class="card-title"><?= isset($editUrlAction) ? lang('Url.UpdateURLTitle') : lang('Url.addNewURLTitle'); ?></h3>
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

                                <div class="mt-2">
                                    <label class="" for="originalUrl"><?= lang('Url.OriginalUrl'); ?>:</label>
                                    <input dir="ltr" type="url" class="form-control " name="originalUrl"
                                           id="UrlTitle" required
                                           placeholder="" value="<?=  old('originalUrl') ?? $originalUrl ?? ''; ?>">
                                </div>


                                <div class="mt-2">
                                    <label class="" for="UrlTitle"><?= lang('Url.UrlTitle') . ' ' . lang('Url.UrlTitleDescription') . ' ' . lang('Common.Optional'); ?>:</label>
                                    <input type="text" class="form-control " name="UrlTitle"  id="UrlTitle"  placeholder="" value="<?= old('UrlTitle') ?? $UrlTitle ?? ''; ?>" >
                                </div>


                                <div class="mt-2">
                                    <label for="basic-url" class="form-label"><?= lang('Url.MaskedShortUrl'); ?>
                                        :</label>
                                    <div class="input-group mb-3" dir="ltr">
                                    <span class="input-group-text"
                                          id="basic-addon3"><?= smarty_detect_site_shortlinker(); ?></span>
                                        <input type="text" dir="ltr" class="form-control" name="UrlIdentifier"  id="UrlIdentifier"
                                               aria-describedby="basic-addon3" value="<?= old('UrlIdentifier') ?? $UrlIdentifier ?? ''; ?>" required>
                                    </div>
                                </div>


                                <div class="container mt-4">

                                    <div class="dropdown">
                                        <button class="btn btn-dark dropdown-toggle " type="button"
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
                                    <?php
                                    if (old('redirectCondition') !== null || isset($redirectCondition)) {
                                        $segment_form_data                      = [];
                                        $segment_form_data['redirectCondition'] = $redirectCondition ?? old('redirectCondition');
                                        // geo

                                        $segment_form_data['geocountry']  = old('geocountry') ?? $geocountry ?? '';
                                        $segment_form_data['geofinalurl'] = old('geofinalurl') ?? $geofinalurl ?? '';
                                        // device
                                        $segment_form_data['device']         = old('device') ?? $device ?? '';
                                        $segment_form_data['devicefinalurl'] = old('devicefinalurl') ?? $devicefinalurl ?? '';

                                        echo view(smarty_view('url/segments/url_conditions'), $segment_form_data);
                                    }
?>

                                </div>















                                <div class="mt-4">
                                    <label for="urlTags" class="form-label"><?= lang('Url.URLTagsOptional'); ?></label>


                                    <div class="input-group mb-3">
                                        <input name='urlTags' id="urlTags" class='form-control' placeholder='<?= lang('Url.EnterSomeTags'); ?>' value='<?= old('urlTags') ?? $urlTags ?? ''; ?>'>


                                        <div id="tagsContainer" class="mt-2"></div>


                                    </div>
                                </div>

                            </div>


                            <!-- /.card-body -->
                            <div class="card-footer" style="box-sizing: border-box; display: block;">
                                <!-- Use Bootstrap utility classes to align Save button to the right -->
                                <div class="d-flex justify-content-end">
                                    <input type="submit" class="btn btn-primary"
                                           value=" <?= isset($editUrlAction) ? lang('Url.UpdateUrlSubmitbtn') : lang('Url.AddNewUrlSubmitbtn'); ?>">
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

<script src="<?= site_url('assist/newurl') ?>"></script>
<?= $this->endSection() ?>

