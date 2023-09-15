<?php

namespace App\Controllers;

// use CodeIgniter\Shield\Models\UserModel;
use CodeIgniter\Shield\Models\RememberModel;

/**
 * Class BaseController
 *
 * Url Controller Deal with URL of SmartyURL
 *
 * For security be sure to declare any new methods as protected or private.
 */
class Account extends BaseController
{
    public function index()
    {
        d('this is the index of Account');
    }

    /**
     * @return void
     */
    public function changepwd()
    {
        return view(smarty_view('users/changepwd'));
    }

    public function changepwdAction()
    {
        // security check
        if (! $this->request->is('post')) {
            return $this->response->setStatusCode(405)->setBody('Method Not Allowed');
        }
        /** @THINK does we need to check CSRF manually while we know that ci4 is check it and thru error if not posted */

        // as this controller for the logged in user that he want to change his password

        $user_id            = user_id();
        $currentPassword    = $this->request->getPost('currentPassword');
        $newPassword        = $this->request->getPost('newPassword');
        $newPasswordConfirm = $this->request->getPost('confirmPassword');

        $result = auth()->check([
            'email'    => auth()->user()->email,
            'password' => $currentPassword,
        ]);
        if (! $result->isOK()) {
            return redirect()->to('account/changepwd')->withInput()->with('error', lang('Account.WrongCurrentPassword'));
        }

        if ($newPassword !== $newPasswordConfirm) {
            return redirect()->to('account/changepwd')->withInput()->with('error', lang('Account.NewPasswordNotEqualConfrim'));
        }

        // i will try to change the password
        $users = auth()->getProvider();
        $user  = auth()->user()->fill([
            'password' => $newPassword,
        ]);

        $users->save($user);

        $rememberModel = model(RememberModel::class);
        $user          = auth()->user();
        $rememberModel->purgeRememberTokens($user);

        // if you plan to log out the user after change password then uncomment this
        /*
        auth()->logout();
        return redirect()->to(config('Auth')->logoutRedirect())->with('message', lang('Account.YourAccountPasswordChangedOK'));
        */

        // @TODO I must make sure from the new password is more strong
        return redirect()->to('account/changepwd')->with('message', lang('Account.YourAccountPasswordChangedOK'));
    }
}
