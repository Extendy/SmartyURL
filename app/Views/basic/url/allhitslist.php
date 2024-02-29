<?= $this->extend(smarty_view('layout')); ?>

<?= $this->section('title') ?>
<?= smarty_pagetitle(lang('Url.SeeAllURLsHits')); ?>
<?= $this->endSection() ?>

<?= $this->section('cssheaderarea') ?>
<link href="https://cdn.datatables.net/v/dt/dt-1.13.6/datatables.min.css" rel="stylesheet">
<?= $this->endSection() ?>


<?= $this->section('main') ?>

<div id="urlhits" style="display: ">

    <div class="app-content-header">
        <!--begin::Container-->
        <div class="container-fluid">
            <!--begin::Row-->
            <div class="row">
                <div class="col-sm-6">
                    <h3 class="mb-0">
                        <?= lang('Url.SeeAllURLsHits'); ?> - <?= $title_segment; ?>
                    </h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end">
                        <li class="breadcrumb-item"><a href="<?= site_url('dashboard'); ?>">
                                <?= lang('Common.dashboardLnk'); ?>
                            </a></li>
                        <li class="breadcrumb-item active" aria-current="page">
                            <a href="<?= site_url('url'); ?>">
                                <?= lang('Url.urlsLink'); ?>
                            </a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">
                            <?= lang('Url.SeeAllURLsHits'); ?>
                        </li>



                    </ol>
                </div>
            </div>
            <!--end::Row-->
        </div>
        <!--end::Container-->
    </div>

</div> <!-- end of urlhits -->

<div class="app-content-header">
    <!--begin::Container-->
    <div id="urlhitscontainer" class="container-fluid">
        <div class="row">
            <div class="col-12">
                <!--BEGIN: urllist main card -->
                <div class="card">

                    <div class="card-header">

                        <div class="row">
                            <div class="col-6">
                                <h3 class="card-title">
                                    <?= lang('Url.SeeAllURLsHits'); ?>
                                </h3>
                            </div>
                            <div class="col-6 text-end">




                            </div>
                        </div>


                    </div> <!-- card-header -->

                    <div class="card-body" style="box-sizing: border-box; display: block;">

                        <div id="UrlHitsErrorContainer" class="alert alert-danger alert-dismissible" role="alert"
                            style="display: none;">
                            <?= lang('Url.urlsListErrorAjaxError'); ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>

                        <div>

                            <div class="table-responsive">
                                <table id="urlHits"
                                    class="display table table-bordered table-striped table-hover dt-responsive"
                                    style="width:100%">
                                    <thead>
                                        <tr>
                                            <!-- Define your table headers here -->
                                            <th>
                                                <?= lang('Url.urlInfoVisitDate'); ?>
                                            </th>
                                            <th>
                                                <?= lang('Url.MaskedShortUrl'); ?>
                                            </th>
                                            <th>
                                                <?= lang('Url.urlInfoVisitorIP'); ?>
                                            </th>
                                            <th>
                                                <?= lang('Url.urlInfoVisitorCountry'); ?>
                                            </th>
                                            <th>
                                                <?= lang('Url.urlInfoVisitorDevice'); ?>
                                            </th>
                                            <th>
                                                <?= lang('Url.urlInfoVisitorUserAgent'); ?>
                                            </th>
                                            <th>
                                                <?= lang('Url.urlInfoFinalTarget'); ?>
                                            </th>
                                            <!-- Add more headers as needed -->
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!-- DataTable will populate the rows here -->
                                    </tbody>
                                </table>
                            </div>


                        </div>

                    </div> <!-- card-body -->


                </div> <!-- card -->
            </div>
        </div>
    </div>

</div>


<?= $this->endSection() ?>



<?= $this->section('jsfooterarea') ?>
<script src="https://cdn.datatables.net/v/bs5/dt-1.13.6/sl-1.7.0/datatables.min.js"></script>

</script>

<script>

    $(document).ready(function () {
        const table = $('#urlHits').DataTable({
            "language": {
                "url": "https://cdn.datatables.net/plug-ins/1.13.6/i18n/<?= $lang; ?>.json",
            },
            "ajax": {
                "url": "/url/urlshitslistdata/?p=<?= $period; ?>&who=<?= $who; ?>",
                "dataSrc": "data",
                "type": "get",
                "error": function (xhr, error, thrown) {
                    var errorContainer = $('#UrlHitsErrorContainer');
                    try {
                        var response = JSON.parse(xhr.responseText);
                        if (response && response.error) {
                            errorContainer.text("<?= lang('Url.urlsListErrorAjaxError'); ?> " + response.error);
                        } else {
                            errorContainer.text("<?= lang('Url.urlsListErrorAjaxError'); ?>");
                        }
                    } catch (parseError) {
                        errorContainer.text("<?= lang('Url.urlsListErrorAjaxError'); ?>");
                    }


                    errorContainer.show();
                },
            },
            "searching": false,
            "dom": 'lfrtipB',
            "lengthChange": false,
            "processing": true,
            "serverSide": true,
            "responsive": true,
            "deferRender": true,
            pageLength: 25,
            order: [[0, 'desc']],
            "columns": [
                { "data": "hit_date_col", "name": "hit_date", orderable: true },
                { "data": "hit_url_col", "name": "hit_url", orderable: true },
                { "data": "hit_ip_col", "name": "hit_ip", orderable: true },
                { "data": "hit_country_col", "name": "hit_country", orderable: false },
                { "data": "hit_device_col", "name": "hit_device", orderable: false },
                { "data": "hit_useragent_col", "name": "hit_useragent", orderable: false },
                { "data": "hit_finalurl_col", "name": "hit_finalurl", orderable: false },
            ],
            "initComplete": function () {
                this.api().columns().header().to$().css('text-align', 'center');
            }
        });

    });
</script>
<?= $this->endSection() ?>