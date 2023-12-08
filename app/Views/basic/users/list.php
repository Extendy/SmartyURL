<?= $this->extend(smarty_view('layout')); ?>

<?= $this->section('title') ?><?= smarty_pagetitle(lang('Users.SystemUsersList')); ?>  <?= $this->endSection() ?>

<?= $this->section('cssheaderarea') ?>
<link href="https://cdn.datatables.net/v/dt/dt-1.13.6/datatables.min.css" rel="stylesheet">
<style>
    .dt-body-center { text-align: center; }
</style>


<?= $this->endSection() ?>


<?= $this->section('main') ?>

<div id="userslistmain" style="display: ">

    <div class="app-content-header">
        <!--begin::Container-->
        <div class="container-fluid">
            <!--begin::Row-->
            <div class="row">
                <div class="col-sm-6">
                    <h3 class="mb-0"><?= lang('Users.SystemUsersList') ?>  </h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end">
                        <li class="breadcrumb-item"><a
                                href="<?= site_url('dashboard'); ?>">
                                <?= lang('Common.dashboardLnk'); ?>
                            </a></li>
                        <li class="breadcrumb-item active" aria-current="page">
                            <a href="<?= site_url('users'); ?>">
                                <?= lang('Users.SystemUsersList'); ?>
                            </a>
                        </li>


                    </ol>
                </div>
            </div>
            <!--end::Row-->
        </div>
        <!--end::Container-->
    </div> <!-- app-content-header -->

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
                                        <?= lang('Users.SystemUsersList'); ?>


                                    </h3>
                                </div>


                                <div class="col-6 text-end">
                                    <?php
                                    if (auth()->user()->can('users.manage', 'super.admin')) {
                                        ?>
                                        <a href="<?= site_url('users/addnew'); ?>"
                                           class="btn btn-sm btn-outline-primary">
                                            <i class="bi bi-person-plus"></i>
                                            <?= lang('Users.UsersAddNewUser'); ?>

                                        </a>
                                        <?php
                                    }
?>
                                </div>

                            </div>


                        </div> <!-- class card-header -->
                        <div class="card-body" style="box-sizing: border-box; display: block;">


                            <div id="UserslistErrorContainer" class="alert alert-danger alert-dismissible" role="alert"
                                 style="display: none;">
                                <?= lang('Common.ajaxCallErrorAjaxError'); ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                        aria-label="Close"></button>
                            </div>
                            <div>
                                <div class="table-responsive">
                                    <table id="usersList"
                                           class="display table table-bordered table-striped table-hover dt-responsive"
                                           style="width:100%">
                                        <thead>
                                        <tr>
                                            <!-- Define your table headers here -->

                                            <th><?= lang('Users.ListUsersColId'); ?></th>
                                            <th><?= lang('Users.ListUsersColUsername'); ?></th>
                                            <th><?= lang('Users.ListUsersColEmail'); ?></th>
                                            <th><?= lang('Users.ListUsersColEmailVerifiedStatus'); ?></th>
                                            <th><?= lang('Users.ListUsersColUserGroup'); ?></th>
                                            <th><?= lang('Users.ListUsersColUserLastActive'); ?></th>
                                            <th><?= lang('Users.ListUsersColUserUrlCount'); ?></th>
                                            <th><?= lang('Users.ListUsersColUserActions'); ?></th>
                                            <!-- Add more headers as needed -->
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <!-- DataTable will populate the rows here -->
                                        </tbody>
                                    </table>
                                </div>
                            </div>


                        </div> <!-- class card-body -->

                    </div>
                </div>
            </div>
        </div>
    </div> <!-- app-content-header -->


</div> <!-- dev id userslist -->
<?= csrf_field() ?>

<?= $this->endSection() ?>





<?= $this->section('jsfooterarea') ?>
<script src="https://cdn.datatables.net/v/bs5/dt-1.13.6/sl-1.7.0/datatables.min.js"></script>

<script>

    $(document).ready(function () {

        const table = $('#usersList').DataTable({
            "language": {
                "url": "https://cdn.datatables.net/plug-ins/1.13.6/i18n/<?= $lang; ?>.json",
            },
            "ajax": {
                "url": "<?= site_url('/users/listusers'); ?>",
                "dataSrc": "data",
                "type": "get",
                "error": function (xhr, error, thrown) {
                    var errorContainer = $('#UserslistErrorContainer');
                    try {
                        var response = JSON.parse(xhr.responseText);
                        if (response && response.error) {
                            errorContainer.text("<?= lang('Url.ajaxCallErrorAjaxError'); ?> " + response.error);
                        } else {
                            errorContainer.text("<?= lang('Url.ajaxCallErrorAjaxError'); ?>");
                        }
                    } catch (parseError) {
                        errorContainer.text("<?= lang('Url.ajaxCallErrorAjaxError'); ?>");
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
                {"data": "user_id_col", "name": "user_id", orderable: true,"className": "dt-body-center"},
                {"data": "user_username_col", "name": "user_username", orderable: true},
                {"data": "user_email_col", "name": "user_email"},
                {"data": "user_active_col", "name": "user_active", orderable: true},
                {"data": "user_userroup_col", "name": "user_userroup", orderable: false},
                {"data": "user_lastactive_col", "name": "user_lastactive", orderable: true,"className": "dt-body-center"},
                {"data": "user_urlcounts_col", "name": "user_urlcounts", orderable: false,"className": "dt-body-center"},
                {"data": "user_useractions_col", "name": "user_useractions_col", orderable: false},
            ],
            "initComplete": function () {
                this.api().columns().header().to$().css('text-align', 'center');
            }
        });


    });


    $(document).ready(function () {

        var csrfToken = $("input[name='csrf_smarty']").val();

        /* delete user button */
        $("#usersList").on("click", "#deleteUserButton", function () {
            /* Store the reference to the button element*/

            var deleteButton = this;
            var userId = this.dataset.userId;
            var userAccount =  this.dataset.userName;

            Swal.fire({
                title: '<?= lang('Users.UserDelConfirmTitle'); ?>',
                text: '<?= lang('Users.UserDelConfirm', ["' + userAccount + '"]); ?>',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#073600',
                confirmButtonText: '<?= lang('Common.btnYes'); ?>',
                cancelButtonText: '<?= lang('Common.btnNo'); ?>',
            }).then((result) => {
                if (result.isConfirmed) {
                    /* If user clicks "Yes", make AJAX request with CSRF token*/
                    $.ajax({
                        url: '/users/del/'+userId,
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

                                /* delete the user html row  */
                                var row = deleteButton.closest('tr');
                                if (row) {
                                    row.remove();
                                }

                                Swal.fire({
                                    title: '<?= lang('Users.UserDelUserDeleted'); ?>',
                                    icon: 'success'
                                });
                                /* You can also perform additional actions based on the response*/

                            } else {
                                Swal.fire('Error', response.error, 'error');
                            }
                        },
                        error: function (xhr, status, error) {
                            /*/ Handle errors*/
                            if (xhr.responseJSON && xhr.responseJSON.error) {
                                /* json error */
                                Swal.fire({
                                    title: '<?= lang('Common.ajaxErrorTitle'); ?>',
                                    text: xhr.responseJSON.error,
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

        /* activate user button */
        $("#usersList").on("click", "#activateUserButton", function () {
            /* Store the reference to the button element*/

            var activateButton = this;
            var userId = this.dataset.userId;
            var userEmail =  this.dataset.userEmail;


            Swal.fire({
                title: '<?= lang('Users.UserActivateConfrimTitle'); ?>',
                text: '<?= lang('Users.UserActivateConfrimText', ["' + userEmail + '"]); ?>',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#073600',
                confirmButtonText: '<?= lang('Common.btnYes'); ?>',
                cancelButtonText: '<?= lang('Common.btnNo'); ?>',
            }).then((result) => {
                if (result.isConfirmed) {
                    /* If user clicks "Yes", make AJAX request with CSRF token*/
                    $.ajax({
                        url: '/users/activate/'+userId,
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
                            if (response.status === 'activated') {

                                /* change the button and the status */
                                $(activateButton).attr('id', 'deactivateUserButton');
                                $(activateButton).text('<?= lang('Users.ListUsersEmailVerifiedStatusDeActivate'); ?>');
                                $(activateButton).removeClass('btn-outline-success').addClass('btn-outline-danger');
                                $(activateButton).prev('span').text('<?= lang('Users.ListUsersEmailVerifiedStatusActiveYes'); ?>');

                                Swal.fire({
                                    title: '<?= lang('Users.UserActivatedOk'); ?>',
                                    icon: 'success'
                                });
                                /* You can also perform additional actions based on the response*/

                            } else {
                                Swal.fire('Error', response.error, 'error');
                            }
                        },
                        error: function (xhr, status, error) {
                            /*/ Handle errors*/
                            if (xhr.responseJSON && xhr.responseJSON.error) {
                                /* json error */
                                Swal.fire({
                                    title: '<?= lang('Common.ajaxErrorTitle'); ?>',
                                    text: xhr.responseJSON.error,
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


        /* deactivate user button */
        $("#usersList").on("click", "#deactivateUserButton", function () {
            /* Store the reference to the button element*/

            var deactivateButton = this;
            var userId = this.dataset.userId;
            var userEmail =  this.dataset.userEmail;


            Swal.fire({
                title: '<?= lang('Users.UserActivateConfrimTitle'); ?>',
                text: '<?= lang('Users.UserDeActivateConfrimText', ["' + userEmail + '"]); ?>',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#073600',
                confirmButtonText: '<?= lang('Common.btnYes'); ?>',
                cancelButtonText: '<?= lang('Common.btnNo'); ?>',
            }).then((result) => {
                if (result.isConfirmed) {
                    /* If user clicks "Yes", make AJAX request with CSRF token*/
                    $.ajax({
                        url: '/users/deactivate/'+userId,
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
                            if (response.status === 'deactivated') {

                                /* change the button and the status */
                                $(deactivateButton).attr('id', 'activateUserButton');
                                $(deactivateButton).text('<?= lang('Users.ListUsersEmailVerifiedStatusActivate'); ?>');
                                $(deactivateButton).removeClass('btn-outline-danger').addClass('btn-outline-success');
                                $(deactivateButton).prev('span').text('<?= lang('Users.ListUsersEmailVerifiedStatusActiveNo'); ?>');

                                Swal.fire({
                                    title: '<?= lang('Users.UserDeActivatedOk'); ?>',
                                    icon: 'success'
                                });
                                /* You can also perform additional actions based on the response*/

                            } else {
                                Swal.fire('Error', response.error, 'error');
                            }
                        },
                        error: function (xhr, status, error) {
                            /*/ Handle errors*/
                            if (xhr.responseJSON && xhr.responseJSON.error) {
                                /* json error */
                                Swal.fire({
                                    title: '<?= lang('Common.ajaxErrorTitle'); ?>',
                                    text: xhr.responseJSON.error,
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


    });

</script>

<?= $this->endSection() ?>
