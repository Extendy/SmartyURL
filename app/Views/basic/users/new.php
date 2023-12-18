<?= $this->extend(smarty_view('layout')); ?>

<?= $this->section('title') ?><?= smarty_pagetitle(lang('Users.UsersAddNewUser')); ?>  <?= $this->endSection() ?>


<?= $this->section('cssheaderarea') ?>

<?= $this->endSection() ?>

<?= $this->section('main') ?>

<div id="addusermain" style="display: ">

    <div class="app-content-header">
        <!--begin::Container-->
        <div class="container-fluid">
            <!--begin::Row-->
            <div class="row">
                <div class="col-sm-6">
                    <h3 class="mb-0"><?= lang('Users.UsersAddNewUser') ?>  </h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end">
                        <li class="breadcrumb-item"><a
                                href="<?= site_url('dashboard'); ?>">
                                <?= lang('Common.dashboardLnk'); ?>
                            </a></li>
                        <li class="breadcrumb-item active" aria-current="page">
                            <a href="<?= site_url('users'); ?>">
                                <?= lang('Users.SystemUserMenu'); ?>
                            </a>
                        </li>

                        <li class="breadcrumb-item active" aria-current="page">
                            <?= lang('Users.UsersAddNewUser'); ?>
                        </li>


                    </ol>
                </div>
            </div>
            <!--end::Row-->
        </div>
        <!--end::Container-->
    </div> <!-- app-content-header -->


    <div class="app-content-header">
        <div id="" class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-6">
                                    <h3 class="card-title">
                                        <?= lang('Users.UsersAddNewUser'); ?>


                                    </h3>
                                </div>
                                <div class="col-6 text-end">
                                    <!-- nothing come here in add new user page -->
                                </div>
                            </div>
                        </div>
                        <div class="card-body" style="box-sizing: border-box; display: block;">

                            <?php if (session()->has('validationErrors')): ?>
                            <div id="AddNewUserErrorContainer" class="alert alert-danger alert-dismissible" role="alert"
                                 style="display: ;">
                                <?= lang('Users.UsersAddNewValidatingErrorHappen'); ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                        aria-label="Close"></button>

                                <?php foreach (session('validationErrors') as $error): ?>
                                    <li><?= esc($error) ?></li>
                                <?php endforeach; ?>

                            </div>
                            <div>
                                <?php endif; ?>


                                <!-- begin: add new user form -->

                                <form action="<?= site_url('users/addnew') ?>" method="post">
                                    <?= csrf_field() ?>
                                    <div class="form-row ">
                                        <div class="form-group col-md-6 pt-2">
                                            <label for="username"><?= lang('Users.ListUsersColUsername'); ?>:</label>
                                            <input type="text" class="form-control pt-2" name="username" id="username"
                                                   required value="<?= old('username'); ?>">
                                        </div>
                                        <div class="form-group col-md-6 pt-2">
                                            <label for="email"><?= lang('Users.ListUsersColEmail'); ?>:</label>
                                            <input type="email" class="form-control pt-2" name="email" id="email"
                                                   value="<?= old('email'); ?>" required>
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="form-group col-md-6 pt-2">
                                            <label><?= lang('Users.ListUsersEmailStatus'); ?>:</label>
                                            <div class="form-check">
                                                <input type="radio" class="form-check-input pt-2" name="email_status"
                                                       value="1" id="verified" required
                                                    <?= (old('email_status') === '1') ? 'checked' : ''; ?>
                                                >
                                                <label for="verified"
                                                       class="form-check-label"><?= lang('Users.ListUsersEmailVerifiedStatusActiveYes'); ?></label>
                                            </div>
                                            <div class="form-check">
                                                <input type="radio" class="form-check-input pt-2" name="email_status"
                                                       value="0" id="not_verified"
                                                    <?= (old('email_status') === '0') ? 'checked' : ''; ?>
                                                       required>
                                                <label for="not_verified"
                                                       class="form-check-label"><?= lang('Users.ListUsersEmailVerifiedStatusActiveNo'); ?>
                                                    - <?= lang('Users.UsersAddNewUserUsersEmailVerifiedStatusActiveNoWillSendActivateCode'); ?></label>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-md-6 pt-2">
                                                <label for="password"><?= lang('Users.UsersAddNewUserPassword'); ?>:</label>
                                                <div class="input-group">
                                                    <input type="password" class="form-control pt-2" name="password" id="password" required>
                                                    <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                                        <i class="bi bi-eye"></i>
                                                    </button>
                                                </div>
                                            </div>
                                            <div class="col-md-6 pt-2">
                                                <label for="password" class="d-none d-md-block">&nbsp;</label>
                                                <!-- Hidden label for spacing on smaller screens -->
                                                <p class="form-text text-muted align-middle">
                                                   <!-- hint for later -->
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="form-group col-md-6 pt-2">
                                            <label><?= lang('Users.UsersAddNewUserAccountStatus'); ?>:</label>
                                            <div class="form-check">
                                                <input type="radio" class="form-check-input pt-2" name="account_status"
                                                       value="active" id="active"
                                                    <?= (old('account_status') === 'active') ? 'checked' : ''; ?>
                                                       required>
                                                <label for="active"
                                                       class="form-check-label"><?= lang('Users.ListUsersAccountStatusNormal'); ?></label>
                                            </div>
                                            <div class="form-check">
                                                <input type="radio" class="form-check-input pt-2" name="account_status"
                                                       value="banned" id="banned"
                                                    <?= (old('account_status') === 'banned') ? 'checked' : ''; ?>
                                                       required>
                                                <label for="banned"
                                                       class="form-check-label"><?= lang('Users.ListUsersAccountStatusBanned'); ?></label>
                                            </div>
                                        </div>
                                        <div class="form-group row" id="banReasonGroup" style="display: none;">
                                            <div class="form-group col-md-6 pt-2">
                                                <label for="ban_reason"><?= lang('Users.ListUsersAccountBanStatus'); ?>
                                                    :</label>
                                                <input class="form-control pt-2" name="ban_reason" type="text"
                                                       id="ban_reason"></input>
                                            </div>

                                            <div class="col-md-6 pt-2">
                                                <label for="ban_reason" class="d-none d-md-block">&nbsp;</label>
                                                <!-- Hidden label for spacing on smaller screens -->
                                                <p class="form-text text-muted align-middle"><?= lang('Users.UsersAddNewUserKeepbanreasonEmptyToShowDefault'); ?></p>
                                            </div>

                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="form-group col-md-6 pt-2">
                                            <label for="usergroup"><?= lang('Users.UsersAddNewUserUserGroups'); ?>
                                                :</label>
                                            <select class="form-control pt-2" name="usergroup[]" id="usergroup"
                                                    multiple required>
                                                <?php foreach ($userGroups as $groupKey => $group): ?>
                                                    <option value="<?= $groupKey; ?>"
                                                        <?= (in_array($groupKey, old('usergroup', []), true)) ? 'selected' : ''; ?>
                                                    ><?= esc($groupKey); ?>
                                                        - <?= esc($group['title']); ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="col-md-6 pt-3">
                                            <button type="submit"
                                                    class="btn btn-primary"><?= lang('Users.UsersAddNewUserAddBtn'); ?></button>
                                            <a href="<?= site_url('users') ?>"
                                               class="btn btn-secondary"><?= lang('Common.btnCancel'); ?></a>
                                        </div>
                                    </div>
                                </form>


                                <!-- end: add new user form -->


                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


    </div> <!-- div id:daddusermain -->

    <?= $this->endSection() ?>

    <?= $this->section('jsfooterarea') ?>

    <script>

        $(document).ready(function () {
            /* Listen for changes in the radio button */
            $('input[name="account_status"]').change(function () {
                /* Check if the selected value is 'banned' */
                if ($(this).val() === 'banned') {
                    /* Show the ban_reason input field */
                    $('#banReasonGroup').show();
                    $('#ban_reason').focus();
                } else {
                    /* Hide the ban_reason input field */
                    $('#banReasonGroup').hide();
                }
            });


            /* Store the initial usergroup value */
            var initialUsergroup = $('#usergroup').val();

            /* Listen for changes in the usergroup select */
            $('#usergroup').change(function () {

                var selectedUsergroup = $(this).val();

                /* Check if the usergroup has changed to 'superadmin' */
                if (selectedUsergroup == 'superadmin' && initialUsergroup != 'superadmin') {
                    /* Show SweetAlert2 confirmation dialog */
                    Swal.fire({
                        title: '<?=lang('Common.titleConfirm'); ?>',
                        text: '<?=lang('Users.UsersAddNewSuperAdminConfirm'); ?>',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#073600',
                        confirmButtonText: 'Yes',
                        cancelButtonText: 'No',
                    }).then((result) => {
                        if (result.isConfirmed) {
                            /* User clicked "Yes", keep the change */
                            /* Update the initial usergroup value */
                            initialUsergroup = 'superadmin';
                        } else {
                            /* User clicked "No", revert the change */
                            /* Set the usergroup back to the initial value */
                            $(this).val(initialUsergroup);
                        }
                    });
                } else {
                    /* Usergroup is not 'superadmin', update the initial value */
                    initialUsergroup = selectedUsergroup;
                }
            });


            //toggle password
            document.getElementById('togglePassword').addEventListener('click', function () {
                const passwordInput = document.getElementById('password');
                const toggleButton = document.getElementById('togglePassword');

                // Toggle the password visibility
                const type = passwordInput.type === 'password' ? 'text' : 'password';
                passwordInput.type = type;

                // Change button color based on password visibility
                const isPasswordVisible = type === 'text';
                toggleButton.classList.toggle('btn-danger', isPasswordVisible);
                toggleButton.classList.toggle('btn-outline-secondary', !isPasswordVisible);
            });

        });



    </script>


    <?= $this->endSection() ?>
