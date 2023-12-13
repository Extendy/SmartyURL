<?php

namespace App\Controllers;

use App\Models\UrlModel;
use App\Models\UserModel;
use CodeIgniter\Shield\Entities\User;
use CodeIgniter\Shield\Models\UserIdentityModel;

class Users extends BaseController
{
    public function __construct()
    {
        $usermodel       = new UserModel();
        $this->usermodel = $usermodel;
        $urlmodel        = new UrlModel();
        $this->urlmodel  = $urlmodel;
    }

    // list users
    public function index()
    {
        // make sure from permissions
        // users.list
        if (! auth()->user()->can('users.list', 'super.admin')) {
            return smarty_permission_error(lang('Common.permissionsNoenoughpermissions'), false);
        }
        $data         = [];
        $data['lang'] = session('lang');

        return view(smarty_view('users/list'), $data);
    }

    public function listUsersData()
    {
        if (! auth()->user()->can('users.list', 'super.admin')) {
            return smarty_permission_error(lang('Common.permissionsNoenoughpermissions'), true);
        }

        $draw        = $this->request->getGet('draw');
        $start       = $this->request->getGet('start');
        $length      = $this->request->getGet('length');
        $columnOrder = $this->request->getGet('order');

        if ($columnOrder !== null) {
            $ajax_column_index = $columnOrder['0']['column'];
            $order_by_dir      = $columnOrder['0']['dir'];

            // Do not think that the data that comes from the client is always correct
            // so switch it to use defaults
            switch ($order_by_dir) {
                case 'asc':
                    $order_by_rule = 'asc';
                    break;

                case 'desc':
                    $order_by_rule = 'desc';
                    break;

                default:
                    $order_by_rule = 'desc';
                    break;
            }

            // i will know the column name from get
            $ajax_columns              = $this->request->getGet('columns');
            $order_by_ajax_column_name = $ajax_columns[$ajax_column_index]['name'];

            // echo $order_by_ajax_column_name;
            // echo $order_by_rule;

            switch ($order_by_ajax_column_name) {
                case 'user_id':
                    $order_by = 'id';
                    break;

                case 'user_username':
                    $order_by = 'username';
                    break;

                case 'user_actstatus':
                    $order_by = 'status';
                    break;

                case 'user_lastactive':
                    $order_by = 'last_active';
                    break;

                case 'user_active':
                    $order_by = 'active';
                    break;

                default:
                    $order_by = 'id';
                    break;
            }
        } else {
            $order_by      = 'id';
            $order_by_rule = 'desc';
        }

        $data = [];

        // i will try to get the basic information about users

        $conditions = [
            // 'id'     => 1,
        ];

        $all_users_count = $this->usermodel
            ->where($conditions)
            ->orderBy($order_by, $order_by_dir)
            ->countAllResults();

        $users = $this->usermodel
            ->where($conditions)
            ->limit($length, $start)
            ->orderBy($order_by, $order_by_dir)
            ->find();

        // $users is CodeIgniter\Shield\Entities\User
        // now i will deal with each user yo define its record
        $users_data      = [];
        $recordsFiltered = $all_users_count; // no need fot counder while not used filter count($users);

        $user_useractions_col = 'edit - delete';

        foreach ($users as $user) {
            $user->email    = esc($user->email);
            $user->username = esc($user->username);

            // account status
            if ($user->status === 'banned') {
                $userActStatus = '<span>' . lang('Users.ListUsersAccountStatusBanned') . "</span> <button id='btnUserUnBan' data-user-id='{$user->id}' data-user-name='{$user->username}' class='btn btn-sm btn-outline-success'>" . lang('Users.ListUsersAccountUnBanUser') . '</button>';
            } else {
                $userActStatus = '<span>' . lang('Users.ListUsersAccountStatusNormal') . "</span> <button id='btnUserBan' data-user-id='{$user->id}' data-user-name='{$user->username}' class='btn btn-sm btn-outline-danger'>" . lang('Users.ListUsersAccountBanUser') . '</button>';
            }

            // get the user usergroups
            $userGroups = $user->getGroups();
            $user_group = '';

            $delete_user_button = "
            <button id='deleteUserButton' data-user-id='{$user->id}' data-user-name='{$user->username}' type='button' class=' btn btn-outline-danger'>
                        <i class='bi bi-trash'></i>
            </button>
            ";

            $user_useractions_col = 'edit - ' . $delete_user_button;

            if ($user->active) {
                $userActive = '<span>' . lang('Users.ListUsersEmailVerifiedStatusActiveYes') . "</span> <button id='deactivateUserButton' data-user-email='{$user->email}' data-user-id='{$user->id}' class='btn btn-sm btn-outline-danger' href='#deactivate{$user->id}'>" . lang('Users.ListUsersEmailVerifiedStatusDeActivate') . '</button>';
            } else {
                $userActive = '<span>' . lang('Users.ListUsersEmailVerifiedStatusActiveNo') . " </span> <button id='activateUserButton' data-user-email='{$user->email}' data-user-id='{$user->id}' class='btn btn-sm btn-outline-success' href='#activate{$user->id}'>" . lang('Users.ListUsersEmailVerifiedStatusActivate') . '</button>';
            }

            foreach ($userGroups as $group) {
                if ($group === 'superadmin') {
                    $btn_class = 'btn-outline-dark';
                } else {
                    $btn_class = 'btn-outline-dark';
                }
                $user_group .= ' <a href="#" class="btn btn-sm  ' . $btn_class . '">' . esc($group) . '</a>';
            }
            // get the user url counts

            $user_url_count = "<a class='text-secondary' href='url/user/{$user->id}'>" . $this->urlmodel->getUrlsForUser($user->id, null, null, null, 'url_id', 'asc', 'count') . '</a>';

            $users_data[] = [
                'user_id_col'          => $user->id,
                'user_username_col'    => $user->username,
                'user_actstatus_col'   => $userActStatus,
                'user_lastactive_col'  => $user->last_active ? $user->last_active->format('Y-m-d H:i:s') : '-',
                'user_email_col'       => $user->email,
                'user_active_col'      => $userActive,
                'user_userroup_col'    => $user_group,
                'user_urlcounts_col'   => $user_url_count,
                'user_useractions_col' => $user_useractions_col,
            ];
        }

        $data = [
            'draw'            => $draw,
            'recordsTotal'    => $all_users_count, // $urlAllCount
            'recordsFiltered' => $recordsFiltered, // $filterAllnumRows
            'data'            => $users_data,
        ];

        return $this->response->setJSON($data);
    }

    public function addNew()
    {
        if (! auth()->user()->can('users.manage', 'super.admin')) {
            return smarty_permission_error();
        }
        // get usergroups

        $usergruops = setting('AuthGroups.groups');

        $data               = [];
        $data['userGroups'] = $usergruops;

        return view(smarty_view('users/new'), $data);
    }

    public function addNewAction()
    {
        if (! auth()->user()->can('users.manage', 'super.admin')) {
            return smarty_permission_error();
        }

        // @TODO samsam here
        // username and email cannot be used for another user

        $validation = \Config\Services::validation();
        $postData   = $this->request->getPost();

        $validation->setRule('username', lang('Users.ListUsersColUsername'), 'required|min_length[3]|max_length[30]');
        $validation->setRule('email', lang('Users.ListUsersColEmail'), 'required|valid_email');
        $validation->setRule('password', lang('Users.UsersAddNewUserPassword'), 'required|min_length[8]');

        // Validate the data
        if ($validation->withRequest($this->request)->run()) {
            // Data is valid, proceed with add new user

            // Get the User Provider (UserModel by default)
            $users = auth()->getProvider();

            $user = new User([
                'username' => $postData['username'],
                'email'    => $postData['email'],
                'password' => $postData['password'],
            ]);
            $users->save($user);

            // To get the complete user object with ID, we need to get from the database
            $user = $users->findById($users->getInsertID());

            // set email status
            switch ($postData['email_status']) {
                case '1':
                    // email acive
                    $user->activate();
                    break;

                case '0':
                    // email not active
                    $user->deactivate();
                    break;
            }

            // set account status
            switch ($postData['account_status']) {
                case 'active':
                    $user->unBan();
                    break;

                case 'banned':
                    // check to see if ban essage set
                    if ($postData['ban_reason'] === '') {
                        $ban_message = null;
                    } else {
                        $ban_message = $postData['ban_reason'];
                    }
                    $user->ban($ban_message);
                    break;
            }

            // now I will add user to the usergroup
            if (is_array($postData['usergroup'])) {
                foreach ($postData['usergroup'] as $group) {
                    $user->addGroup($group);
                }
            } else {
                // how come this filed is required but not array?? . but i will add the user go default system group
                $users->addToDefaultGroup($user);
            }

            dd($user);
        } else {
            // Error validating form
            // Data is not valid, show validation errors

            /*
             * //Not working because it will set error even they are valid
            $validation->setError('username', 'Custom error message for username');
            $validation->setError('email', 'Custom error message for email');
            $validation->setError('password', 'Custom error message for password');*/

            $validationErrors = $validation->getErrors();

            return redirect()->to('/users/addnew')->withInput()->with('validationErrors', $validationErrors);
        }
    }

    public function delUser(int $UserId)
    {
        $response = [];

        if (! auth()->user()->can('users.manage', 'super.admin')) {
            $response['error'] = lang('Common.permissionsNoenoughpermissions');

            return $this->response->setStatusCode(403)->setJSON($response);
        }
        $user_id = (int) $UserId;
        // i will try to find the user
        $conditions = [
            'id' => $user_id,
        ];
        $users = $this->usermodel
            ->where($conditions)
            ->find();

        if (count($users) !== 1) {
            // that mean user not exists
            $response['error'] = lang('Users.UserNotFound');

            return $this->response->setStatusCode(200)->setJSON($response);
        }

        foreach ($users as $user) {
            // Get the User Provider (UserModel by default)

            $my_user_id = user_id();
            if ($my_user_id === $user->id) {
                // you cannot ban your own account
                $response['error'] = lang('Users.UserDelUserErrorYourSelf');
            } else {
                // not superadmin cannot delete superadmin
                // now the current logged user usergroup
                $auth           = service('auth');
                $my_user        = $auth->user();
                $my_user_groups = $my_user->getGroups();
                // know the needed to ban user usergroup
                $user_groups = $user->getGroups();
                if (! in_array('superadmin', $my_user_groups, true) && in_array('superadmin', $user_groups, true)) {
                    // user is not super admin and try to delete superadmin . and this is not allowed
                    // when you need to del superadmin you need to be superadmin
                    $response['error'] = lang('Users.UserDelUserErrorSuperadmin');

                    return $this->response->setStatusCode(200)->setJSON($response);
                }

                $usersprovider = auth()->getProvider();
                $deluser       = $usersprovider->delete($user->id, true);

                if ($deluser) {
                    // user deleted
                    $response['status'] = 'deleted';
                } else {
                    $response['error'] = lang('Users.UserDelUserErrorDel');
                }
            }
        }

        return $this->response->setStatusCode(200)->setJSON($response);
    }

    /**
     * This function Activate User Email Account.
     * called using ajax to activate user account and  it suppose that user confirm activation
     *
     * @return \CodeIgniter\HTTP\ResponseInterface
     */
    public function activateUser(int $UserId)
    {
        $response = [];
        if (! auth()->user()->can('users.manage', 'super.admin')) {
            $response['error'] = lang('Common.permissionsNoenoughpermissions');

            return $this->response->setStatusCode(403)->setJSON($response);
        }
        $user_id    = (int) $UserId;
        $conditions = [
            'id' => $user_id,
        ];
        $users = $this->usermodel
            ->where($conditions)
            ->find();
        if (count($users) !== 1) {
            // that mean user not exists
            $response['error'] = lang('Users.UserNotFound');

            return $this->response->setStatusCode(200)->setJSON($response);
        }

        // user is exists i will try to activate it
        foreach ($users as $user) {
            if (! $user->isActivated()) {
                $user->activate();
                // remove any email_activate Identity when activate email
                $UserIdentityModel = new UserIdentityModel();
                $UserIdentityModel->deleteIdentitiesByType($user, 'email_activate');
                $response['status'] = 'activated';
            } else {
                $response['error'] = lang('Users.UserIsAlreadyActivated');
            }
        }

        return $this->response->setStatusCode(200)->setJSON($response);
    }

    public function deactivateUser(int $UserId)
    {
        $response = [];
        if (! auth()->user()->can('users.manage', 'super.admin')) {
            $response['error'] = lang('Common.permissionsNoenoughpermissions');

            return $this->response->setStatusCode(403)->setJSON($response);
        }
        $user_id    = (int) $UserId;
        $conditions = [
            'id' => $user_id,
        ];
        $users = $this->usermodel
            ->where($conditions)
            ->find();
        if (count($users) !== 1) {
            // that mean user not exists
            $response['error'] = lang('Users.UserNotFound');

            return $this->response->setStatusCode(200)->setJSON($response);
        }

        // user is exists i will try to deactivate it
        foreach ($users as $user) {
            if ($user->isActivated()) {
                $my_user_id = user_id();
                if ($my_user_id === $user->id) {
                    // you cannot ban your own account
                    $response['error'] = lang('Users.UserDeActivatedErrorYourSelf');
                } else {
                    $user->deactivate();
                    $response['status'] = 'deactivated';
                }
            } else {
                $response['error'] = lang('Users.UserIsAlreadyDeActivated');
            }
        }

        return $this->response->setStatusCode(200)->setJSON($response);
    }

    public function banUser(int $UserId)
    {
        $response = [];
        if (! auth()->user()->can('users.manage', 'super.admin')) {
            $response['error'] = lang('Common.permissionsNoenoughpermissions');

            return $this->response->setStatusCode(403)->setJSON($response);
        }
        $user_id    = (int) $UserId;
        $conditions = [
            'id' => $user_id,
        ];
        $users = $this->usermodel
            ->where($conditions)
            ->find();
        if (count($users) !== 1) {
            // that mean user not exists
            $response['error'] = lang('Users.UserNotFound');

            return $this->response->setStatusCode(200)->setJSON($response);
        }

        // user is exists i will try to ban it
        foreach ($users as $user) {
            if (! $user->isBanned()) {
                // user cannot ban his account
                $my_user_id = user_id();
                if ($my_user_id === $user->id) {
                    // you cannot ban your own account
                    $response['error'] = lang('Users.ListUsersAccountBannedErrorYourSelf');
                } else {
                    // not superadmin cannot ban superadmin
                    // now the current logged user usergroup
                    $auth           = service('auth');
                    $my_user        = $auth->user();
                    $my_user_groups = $my_user->getGroups();
                    // know the needed to ban user usergroup
                    $user_groups = $user->getGroups();
                    if (! in_array('superadmin', $my_user_groups, true) && in_array('superadmin', $user_groups, true)) {
                        // user is not super admin and try to ban superadmin . and this is nit allowed
                        // when you need to ban superadmin you need to be superadmin
                        $response['error'] = lang('Users.ListUsersAccountBannedErrorSuperadmin');

                        return $this->response->setStatusCode(200)->setJSON($response);
                    }

                    $user->ban();
                    $response['status'] = 'banned';
                }
            } else {
                $response['error'] = lang('Users.ListUsersAccountBannedAlready');
            }
        }

        return $this->response->setStatusCode(200)->setJSON($response);
    }

    public function unbanUser(int $UserId)
    {
        $response = [];
        if (! auth()->user()->can('users.manage', 'super.admin')) {
            $response['error'] = lang('Common.permissionsNoenoughpermissions');

            return $this->response->setStatusCode(403)->setJSON($response);
        }
        $user_id    = (int) $UserId;
        $conditions = [
            'id' => $user_id,
        ];
        $users = $this->usermodel
            ->where($conditions)
            ->find();
        if (count($users) !== 1) {
            // that mean user not exists
            $response['error'] = lang('Users.UserNotFound');

            return $this->response->setStatusCode(200)->setJSON($response);
        }

        // user is exists i will try to unban it
        foreach ($users as $user) {
            if ($user->isBanned()) {
                $user->unBan();
                $response['status'] = 'unbanned';
            } else {
                $response['error'] = lang('Users.ListUsersAccountUnBannedAlready');
            }
        }

        return $this->response->setStatusCode(200)->setJSON($response);
    }
}
