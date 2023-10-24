<?= $this->extend(smarty_view('layout')); ?>

<?= $this->section('title') ?><?= smarty_pagetitle(isset($editUrlAction) ? lang('Url.UpdateURLTitle') : lang('Url.urlsList')); ?>  <?= $this->endSection() ?>

<?= $this->section('cssheaderarea') ?>
<link href="https://cdn.datatables.net/v/dt/dt-1.13.6/datatables.min.css" rel="stylesheet">
<?= $this->endSection() ?>


<?= $this->section('main') ?>

<!--BEGIN: listurls main section -->
<style>
    /*hide dataTables_filter by default*/
    .dataTables_filter  { display: none; }
    .dataTables_length { display: none; }

    .listurls-link {
        text-decoration: none;
    }


    /* Change the icon for row details to a Bootstrap 5 "plus" icon */
    .dt-control::before {
        content: "\F79C"; /* The Bootstrap 5 "plus" icon code */
        font-family: 'Bootstrap Icons'; /* Specify the Bootstrap Icons font-family */
        font-size: 16px; /* Adjust the font size as needed */
    }




</style>

<div id="listurls" style="display: ">



    <div class="app-content-header">
        <!--begin::Container-->
        <div class="container-fluid">
            <!--begin::Row-->
            <div class="row">
                <div class="col-sm-6">
                    <h3 class="mb-0"><?= lang('Url.urlsList'); ?> </h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end">
                        <li class="breadcrumb-item"><a
                                href="<?= site_url('dashboard'); ?>">
                                <?= lang('Common.dashboardLnk'); ?>
                            </a></li>
                        <li class="breadcrumb-item active" aria-current="page">
                            <a href="<?= site_url('url'); ?>">
                                <?= lang('Url.urlsLink'); ?>
                            </a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">
                            <?= lang('Url.urlsList'); ?>
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
                    <!--BEGIN: urllist main card -->
                    <div class="card">

                        <div class="card-header">


                            <div class="row">
                                <div class="col-6">
                                    <h3 class="card-title"><?= lang('Url.urlsList'); ?> </h3>
                                </div>
                                <div class="col-6 text-end">

                                    <i class="fas fa-question-circle"></i>
                                </div>
                            </div>


                        </div>

                        <div class="card-body" style="box-sizing: border-box; display: block;">


                            <div id="ListUrlsErrorContainer" class="alert alert-danger alert-dismissible" role="alert" style="display: none;">
                                <?= lang('Url.urlsListErrorAjaxError'); ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>


                            <div class="row">
                                <div class="col-10">
                                    <input type="text" id="searchInput" placeholder="<?= lang('Url.urlsListSearchOnUrls'); ?>" class="form-control">
                                </div>
                                <div class="col-2">
                                    <button id="customSearchButton" class="btn btn-light"><?= lang('Url.urlsListSearchOnUrlsSearchBtn'); ?></button>
                                </div>
                            </div>


                            <table id="urlList" class="display" style="width:100%">
                                <thead>
                                <tr>
                                    <!-- Define your table headers here -->
                                    <th></th>
                                    <th  ><?= lang('Url.UrlId'); ?></th>
                                    <th  ><?= lang('Url.MaskedShortUrl'); ?></th>
                                    <th ><?= lang('Url.UrlTitle'); ?></th>
                                    <th  ><?= lang('Url.UrlHitsNo'); ?></th>
                                    <!-- Add more headers as needed -->
                                </tr>
                                </thead>
                                <tbody>
                                <!-- DataTable will populate the rows here -->
                                </tbody>
                            </table>


                            <?php
                            $defautltUrlListPerPage = setting('Smartyurl.defautltUrlListPerPage');
?>
                            <div class="row">
                                <div class="col-12 d-flex justify-content-end">
                                    <div id="customLengthControl">
                                        <label><?= lang('Url.urlsListEntriesPerPage'); ?></label>
                                        <select id="customLengthSelector">
                                            <option <?= ($defautltUrlListPerPage === 10) ? 'selected' : '' ?> value="10">10</option>
                                            <option <?= ($defautltUrlListPerPage === 25) ? 'selected' : '' ?> value="25">25</option>
                                            <option <?= ($defautltUrlListPerPage === 50) ? 'selected' : '' ?> value="50">50</option>
                                            <option <?= ($defautltUrlListPerPage === 100) ? 'selected' : '' ?> value="100">100</option>
                                        </select>
                                    </div>
                                </div>
                            </div>


                        </div>



                    </div>
                    <!--END: urllist main card -->
                </div>
            </div>
        </div>

    </div>

</div>
<?= csrf_field() ?>
<!--END: listurls main section -->

<?= $this->endSection() ?>





<?= $this->section('jsfooterarea') ?>
<script src="https://cdn.datatables.net/v/bs5/dt-1.13.6/sl-1.7.0/datatables.min.js"></script>

<script src="<?= site_url('assist/listurls') ?>"></script>
<?= $this->endSection() ?>
