<?php

namespace Sharenjoy\Cmsharenjoy\Http\Controllers;

use Message;
use Illuminate\Http\Request;
use Sharenjoy\Cmsharenjoy\User\UserInterface;
use Sharenjoy\Cmsharenjoy\Http\Controllers\BaseController;

class DashController extends BaseController
{
    public function __construct()
    {
        $this->middleware('admin.auth',  ['only'=>['getIndex', 'getLogout']]);
        $this->middleware('admin.guest', ['except'=>['getIndex', 'getLogout']]);

        parent::__construct();
    }

    /**
     * Main users page.
     *
     * @access   public
     * @return   View
     */
    public function getIndex()
    {
        return view('admin.unity.dashboard');
    }

    /**
     * Log the user out
     * 
     * @return Redirect
     */
    public function getLogout()
    {
        auth()->guard('admin')->logout();

        session()->flush();

        Message::success(pick_trans('success_logout'));

        return redirect($this->accessUrl.'/login');
    }

    /**
     * Login form page.
     *
     * @access   public
     * @return   View
     */
    public function getLogin()
    {
        // If logged in, redirect to admin area
        if (auth()->guard('admin')->check()) {
            return redirect($this->accessUrl);
        }

        return view('admin.unity.login');
    }

    /**
     * Login form processing.
     *
     * @access   public
     * @return   Redirect
     */
    public function postLogin(UserInterface $user, Request $request)
    {
        if ( ! $user->setInput($request->all())->validate('', 'loginRules', 'flash')) {
            return redirect($this->accessUrl.'/login')->withInput();
        }

        $result = $user->login($request->all());

        if ( ! $result['status']) {
            Message::error($result['message']);

            return redirect($this->accessUrl.'/login')->withInput();
        }

        Message::success($result['message']);

        return redirect($this->accessUrl);
    }

    /**
     * To activate an user via activation code
     * @return void
     */
    public function getActivate(UserInterface $user, $id, $code)
    {
        $result = $user->activate($id, $code);

        Message::{$result['status']}($result['message']);

        return redirect($this->accessUrl.'/login');
    }

    /**
     * To reset an user password
     * @param  string $id
     * @param  string $code The code needs to valid
     * @return object Redirect
     */
    public function getResetPassword($code)
    {
        return view('admin.unity.reset-password')->with('code', $code);
    }

    public function postResetPassword(UserInterface $user, Request $request)
    {
        $result = $user->resetPassword($request->all());

        if ( ! $result['status']) {
            Message::error($result['message']);

            return redirect()->back();
        }

        Message::success($result['message']);

        return redirect($this->accessUrl.'/login');
    }

    public function getRemindPassword()
    {
        return view('admin.unity.remind-password');
    }

    public function postRemindPassword(UserInterface $user, Request $request)
    {
        $result = $user->remindPassword($request->input('email'));

        if ( ! $result['status']) {
            Message::error($result['message']);

            return redirect()->back();
        }

        Message::success($result['message']);

        return redirect($this->accessUrl.'/remind-password');
    }

}