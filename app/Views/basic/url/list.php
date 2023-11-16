<?= $this->extend(smarty_view('layout')); ?>

<?= $this->section('title') ?><?= smarty_pagetitle(isset($editUrlAction) ? lang('Url.UpdateURLTitle') : lang('Url.urlsList')); ?> <?= $filtertext !== null ? ' - ' . $filtertext : '' ?>  <?= $this->endSection() ?>

<?= $this->section('cssheaderarea') ?>
<link href="https://cdn.datatables.net/v/dt/dt-1.13.6/datatables.min.css" rel="stylesheet">
<?= $this->endSection() ?>


<?= $this->section('main') ?>


<!--BEGIN: listurls main section -->
<style>
    /*hide dataTables_filter by default*/
    .dataTables_filter {
        display: none;
    }

    .dataTables_length {
        display: none;
    }

    .listurls-link {
        text-decoration: none;
    }


    /* Change the icon for row details to a Bootstrap 5 "plus" icon */
    .dt-control::before {
        content: "\F79C"; /* The Bootstrap 5 "plus" icon code */
        font-family: 'Bootstrap Icons'; /* Specify the Bootstrap Icons font-family */
        font-size: 16px; /* Adjust the font size as needed */
    }

    .copy-button {
        cursor: pointer;
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
                                    <h3 class="card-title"><?= lang('Url.urlsList'); ?> <?= $filtertext !== null ? ' - ' . $filtertext : '' ?></h3>
                                </div>
                                <div class="col-6 text-end">

                                    <i class="fas fa-question-circle"></i>
                                </div>
                            </div>


                        </div>

                        <div class="card-body" style="box-sizing: border-box; display: block;">


                            <div id="ListUrlsErrorContainer" class="alert alert-danger alert-dismissible" role="alert"
                                 style="display: none;">
                                <?= lang('Url.urlsListErrorAjaxError'); ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                        aria-label="Close"></button>
                            </div>


                            <div class="row pb-3">
                                <div class="col-10">
                                    <input type="text" id="searchInput"
                                           placeholder="<?= lang('Url.urlsListSearchOnUrls'); ?>" class="form-control">
                                </div>
                                <div class="col-2">
                                    <button id="customSearchButton"
                                            class="btn btn-light"><?= lang('Url.urlsListSearchOnUrlsSearchBtn'); ?></button>
                                </div>
                            </div>


                            <div class="table-responsive">
                                <table id="urlList"
                                       class="display table table-bordered table-striped table-hover dt-responsive"
                                       style="width:100%">
                                    <thead>
                                    <tr>
                                        <!-- Define your table headers here -->
                                        <th></th>
                                        <th><?= lang('Url.UrlId'); ?></th>
                                        <th><?= lang('Url.MaskedShortUrl'); ?></th>
                                        <th><?= lang('Url.UrlTitle'); ?></th>
                                        <th><?= lang('Url.UrlHitsNo'); ?></th>
                                        <!-- Add more headers as needed -->
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <!-- DataTable will populate the rows here -->
                                    </tbody>
                                </table>
                            </div>


                            <?php
                            $defautltUrlListPerPage = setting('Smartyurl.defautltUrlListPerPage');
?>
                            <div class="row">
                                <div class="col-12 d-flex justify-content-end">
                                    <div id="customLengthControl">
                                        <label><?= lang('Url.urlsListEntriesPerPage'); ?></label>
                                        <select id="customLengthSelector">
                                            <option <?= ($defautltUrlListPerPage === 10) ? 'selected' : '' ?>
                                                value="10">10
                                            </option>
                                            <option <?= ($defautltUrlListPerPage === 25) ? 'selected' : '' ?>
                                                value="25">25
                                            </option>
                                            <option <?= ($defautltUrlListPerPage === 50) ? 'selected' : '' ?>
                                                value="50">50
                                            </option>
                                            <option <?= ($defautltUrlListPerPage === 100) ? 'selected' : '' ?>
                                                value="100">100
                                            </option>
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
<?php
$listurls_query_string = '';
if (isset($filterrule)) {
    $listurls_query_string = "?filterrule={$filterrule}&filtervalue={$filtervalue}"
    ?>
    <input type="hidden" id="filterrule" value="<?= $filterrule; ?>">
    <input type="hidden" id="filtervalue" value="<?= $filtervalue; ?>">
    <?php
}
?>


<!--END: listurls main section -->

<?= $this->endSection() ?>





<?= $this->section('jsfooterarea') ?>
<script src="https://cdn.datatables.net/v/bs5/dt-1.13.6/sl-1.7.0/datatables.min.js"></script>

<script src="<?= site_url('assist/listurls') . $listurls_query_string ?>"></script>

<script type="application/javascript">

    $(document).ready(function () {


        document.getElementById('listurls').addEventListener('mouseover', function (event) {
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

        document.getElementById('listurls').addEventListener('mouseout', function (event) {
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


        $("#listurls").on("click", ".copy-button", function () {
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

    /* delete url */
    function delUrl(urlId) {

    }


    /* delete url button */
    $("#listurls").on("click", "#deleteurlButton", function () {
        /* Store the reference to the button element*/

        var deleteButton = this;
        var urlId = this.dataset.urlId;
        var urlGo = this.dataset.urlGo;

        var csrfToken = $("input[name='csrf_smarty']").val();
        /* Show SweetAlert2 confirmation dialog */
        Swal.fire({
            title: '<?= lang('Url.urlDelConfrim'); ?>',
            text: urlGo,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#073600',
            confirmButtonText: '<?= lang('Url.urlDelYes'); ?>',
            cancelButtonText: '<?= lang('Common.btnNo'); ?>',
        }).then((result) => {
            if (result.isConfirmed) {
                /* If user clicks "Yes", make AJAX request with CSRF token*/
                $.ajax({
                    url: '/url/del/'+urlId, /* Replace with your actual server endpoint*/
                    type: 'POST',
                    data: {
                        /* Any data you want to send for deletion*/
                        /* Include CSRF token in the data*/

                        /* ... other data ...*/
                    },
                    headers: {
                        /*/ Include CSRF token in the request headers */
                        'X-CSRF-Token': csrfToken,
                    },
                    dataType: 'json',
                    success: function (response) {
                        /* Check if the server response indicates success*/
                        if (response.status === 'deleted') {
                            /*delete the tr which contains the delete button and the previous ta lso becuase it is a parent*/
                            var closestTr = $(deleteButton).closest('tr');
                            var prevTr = closestTr.prev('tr');
                            closestTr.remove();
                            prevTr.remove();
                            Swal.fire({
                                title: '<?= lang('Url.urlDelOK'); ?>',
                                icon: 'success'
                            });
                            /* You can also perform additional actions based on the response*/

                        } else {
                            Swal.fire('Error', response.error, 'error');
                        }
                    },
                    error: function (xhr, status, error) {
                        /*/ Handle errors*/
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            /* json error */
                            Swal.fire({
                                title: '<?= lang('Common.ajaxErrorTitle'); ?>',
                                text: xhr.responseJSON.message,
                                icon: 'error',
                                confirmButtonText: '<?= lang('Common.btnOK'); ?>',
                            });
                        } else {
                            /* not json error may be network error*/
                            Swal.fire({
                                title: '<?= lang('Common.ajaxErrorTitle'); ?>',
                                text: '<?= lang('Common.ajaxCallError1'); ?>',
                                icon: 'error',
                                confirmButtonText: '<?= lang('Common.btnOK'); ?>',
                            });
                        }
                    }
                });
            }
        });

    });




</script>

<?= $this->endSection() ?>
