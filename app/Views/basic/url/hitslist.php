<?= $this->extend(smarty_view('layout')); ?>

<?= $this->section('title') ?><?= smarty_pagetitle(lang('Url.urlInfoSeeAllHitsforURL')); ?>  <?= $this->endSection() ?>

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
                    <h3 class="mb-0"><?= lang('Url.urlInfoSeeAllHitsforURL'); ?>  </h3>
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
                            <a href="<?= site_url("url/view/{$url_id}"); ?>">
                                <?= esc($url_identifier); ?>
                            </a>
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
        <div id="urlhitscontainer" class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <!--BEGIN: urllist main card -->
                    <div class="card">

                        <div class="card-header">

                            <div class="row">
                                <div class="col-6">
                                    <h3 class="card-title">
                                        <?= $go_url; ?>

                                        <i data-bs-toggle="tooltip" data-bs-placement="top"
                                           title='<?= lang('Url.CopyURL'); ?>' class='bi bi-clipboard copy-button'
                                           data-content='<?= $go_url; ?>' data-target='link2'></i>

                                    </h3>
                                </div>
                                <div class="col-6 text-end">

                                    <a data-bs-toggle="tooltip" data-bs-placement="top"
                                       title='<?= lang('Url.UpdateUrlSubmitbtn'); ?>'
                                       href='<?= site_url("url/edit/{$url_id}"); ?>' class='link-dark edit-link'><i
                                            class='bi bi-pencil edit-link-btn'></i></a>


                                </div>
                            </div>


                        </div> <!-- card-header -->

                        <div class="card-body" style="box-sizing: border-box; display: block;">

                            <div id="UrlHitsErrorContainer" class="alert alert-danger alert-dismissible" role="alert"
                                 style="display: none;">
                                <?= lang('Url.urlsListErrorAjaxError'); ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                        aria-label="Close"></button>
                            </div>

                            <div>

                                <div class="table-responsive">
                                    <table id="urlHits"
                                           class="display table table-bordered table-striped table-hover dt-responsive"
                                           style="width:100%">
                                        <thead>
                                        <tr>
                                            <!-- Define your table headers here -->
                                            <th><?= lang('Url.urlInfoVisitDate'); ?></th>
                                            <th><?= lang('Url.urlInfoVisitorIP'); ?></th>
                                            <th><?= lang('Url.urlInfoVisitorCountry'); ?></th>
                                            <th><?= lang('Url.urlInfoVisitorDevice'); ?></th>
                                            <th><?= lang('Url.urlInfoVisitorUserAgent'); ?></th>
                                            <th><?= lang('Url.urlInfoFinalTarget'); ?></th>
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


</div>


<?= $this->endSection() ?>




<?= $this->section('jsfooterarea') ?>
<script src="https://cdn.datatables.net/v/bs5/dt-1.13.6/sl-1.7.0/datatables.min.js"></script>

<script>

    $(document).ready(function () {
        const table = $('#urlHits').DataTable({
            "language": {
                "url": "https://cdn.datatables.net/plug-ins/1.13.6/i18n/<?= $lang; ?>.json",
            },
            "ajax": {
                "url": "/url/hitslistdata/<?= $url_id; ?>",
                "dataSrc": "data",
                "type": "get",
                "error": function (xhr, error, thrown) {
                    var errorContainer = $('#UrlHitsErrorContainer');
                    try {
                        var response = JSON.parse(xhr.responseText);
                        if (response && response.error) {
                            errorContainer.text("<?= lang('Url.urlsListErrorAjaxError'); ?> "+response.error);
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
                {"data": "hit_date_col", "name": "hit_date", orderable: true},
                {"data": "hit_ip_col", "name": "hit_ip", orderable: true},
                {"data": "hit_country_col", "name": "hit_country", orderable: false},
                {"data": "hit_device_col", "name": "hit_device", orderable: false},
                {"data": "hit_useragent_col", "name": "hit_useragent", orderable: false},
                {"data": "hit_finalurl_col", "name": "hit_finalurl", orderable: false},
            ],
            "initComplete": function () {
                this.api().columns().header().to$().css('text-align', 'center');
            }
        });

    });


    $(document).ready(function () {


        document.getElementById('urlhitscontainer').addEventListener('mouseover', function (event) {
            const copyButton = event.target;
            const editLinkBtn = event.target;

            if (copyButton.classList.contains('copy-button')) {

                copyButton.classList.add('bi-clipboard-fill');
                copyButton.classList.remove('bi-clipboard');
            }

            if (editLinkBtn.classList.contains('edit-link-btn')) {

                editLinkBtn.classList.add('bi-pencil-fill');
                editLinkBtn.classList.remove('bi-pencil');
            }


        });

        document.getElementById('urlhitscontainer').addEventListener('mouseout', function (event) {
            const copyButton = event.target;
            const editLinkBtn = event.target;

            if (copyButton.classList.contains('copy-button')) {

                copyButton.classList.remove('bi-clipboard-fill');
                copyButton.classList.add('bi-clipboard');
            }

            if (editLinkBtn.classList.contains('edit-link-btn')) {

                editLinkBtn.classList.add('bi-pencil');
                editLinkBtn.classList.remove('bi-pencil-fill');
            }

        });


        $("#urlhitscontainer").on("click", ".copy-button", function () {
            var content = $(this).data("content");


            var textArea = document.createElement("textarea");
            textArea.value = content;
            document.body.appendChild(textArea);
            textArea.select();
            document.execCommand("copy");
            document.body.removeChild(textArea);


            $(this).removeClass('bi-clipboard-fill');
            $(this).addClass('bi-clipboard-check-fill');

            Swal.fire({
                text: "<?= lang('Url.urlCopiedtoClipboard'); ?>",
                icon: "success",
                timer: 1000,
                timerProgressBar: true,
                position: "top-start",
                allowEscapeKey: true,
                showConfirmButton: false,
                toast: true,

            });


            setTimeout(function () {
                $(".copy-button").removeClass('bi-clipboard-check-fill');
            }, 500);
        });


    });

</script>
<?= $this->endSection() ?>
