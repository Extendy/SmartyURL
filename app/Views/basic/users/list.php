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
                                            <i class="bi bi-person-fill-add"></i>
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


<?= $this->endSection() ?>





<?= $this->section('jsfooterarea') ?>
<script src="https://cdn.datatables.net/v/bs5/dt-1.13.6/sl-1.7.0/datatables.min.js"></script>

<script>
    /* samsam @TODO the order by not working */
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
            "searching": true,
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
                {"data": "user_username_col", "name": "user_username", orderable: false},
                {"data": "user_email_col", "name": "user_email", orderable: false},
                {"data": "user_active_col", "name": "user_active", orderable: false},
                {"data": "user_userroup_col", "name": "user_userroup", orderable: false},
                {"data": "user_lastactive_col", "name": "user_lastactive", orderable: false,"className": "dt-body-center"},
                {"data": "user_urlcounts_col", "name": "user_urlcounts", orderable: false,"className": "dt-body-center"},
                {"data": "user_useractions_col", "name": "user_useractions_col", orderable: false},
            ],
            "initComplete": function () {
                this.api().columns().header().to$().css('text-align', 'center');
            }
        });


    });

</script>

<?= $this->endSection() ?>
