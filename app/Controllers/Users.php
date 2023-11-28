<?php

namespace App\Controllers;

use App\Models\UrlModel;
use App\Models\UserModel;

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
            // get the user usergroups
            $userGroups = $user->getGroups();
            $user_group = '';

            $delete_user_button = "
            <button id='deleteUserButton' data-user-id='{$user->id}' type='button' class=' btn btn-outline-danger'>
                        <i class='bi bi-trash'></i>
            </button>
            ";

            $user_useractions_col = 'edit - ' . $delete_user_button;

            if ($user->active) {
                $userActive = '' . lang('Users.ListUsersEmailVerifiedStatusActiveYes') . " <a class='btn btn-sm btn-outline-danger' href='#deactivate{$user->id}'>" . lang('Users.ListUsersEmailVerifiedStatusDeActivate') . '</a>';
            } else {
                $userActive = '' . lang('Users.ListUsersEmailVerifiedStatusActiveNo') . " <a class='btn btn-sm btn-outline-success' href='#activate{$user->id}'>" . lang('Users.ListUsersEmailVerifiedStatusActivate') . '</a>';
            }

            foreach ($userGroups as $group) {
                $user_group .= ' ' . $group;
            }
            // get the user url counts

            $user_url_count = "<a class='text-secondary' href='url/user/{$user->id}'>" . $this->urlmodel->getUrlsForUser($user->id, null, null, null, 'url_id', 'asc', 'count') . '</a>';

            $users_data[] = [
                'user_id_col'          => $user->id,
                'user_username_col'    => $user->username,
                'user_lastactive_col'  => $user->last_active->format('Y-m-d H:i:s'),
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
        echo 'Add new user procedures';
        dd('Add New Users form here in future'); // TODO Need work @FIXME
    }

    public function delUser(int $UserId)
    {
        $response = [];

        if (! auth()->user()->can('users.manage', 'super.admin')) {
            $response['error'] = lang('Common.permissionsNoenoughpermissions');

            return $this->response->setStatusCode(403)->setJSON($response);
        }
        $user_id = (int) esc(smarty_remove_whitespace_from_url_identifier($UrlId));
        // samsam @TODO iam here
    }
}
