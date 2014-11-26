<?php namespace Sharenjoy\Cmsharenjoy\Controllers;

use Sharenjoy\Cmsharenjoy\User\UserValidator;
use Sentry, App, View, Redirect, Input, Message, Session;

class DashController extends BaseController {

    /**
     * Let's whitelist all the methods
     * we want to allow guests to visit!
     *
     * @access   protected
     * @var      array
     */
    protected $whitelist = [
        'getLogin',
        'getLogout',
        'postLogin',
        'getActivate',
        'getResetpassword',
        'postResetpassword',
        'getRemindpassword',
        'postRemindpassword'
    ];

    /**
     * Main users page.
     *
     * @access   public
     * @return   View
     */
    public function getIndex()
    {
        return View::make('cmsharenjoy::unity.dashboard');
    }

    /**
     * Log the user out
     * 
     * @return Redirect
     */
    public function getLogout()
    {
        Sentry::logout();

        Session::flush();

        Message::success(trans('cmsharenjoy::app.success_logout'));

        return Redirect::to($this->accessUrl.'/login');
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
        if (Sentry::check())
        {
            return Redirect::to($this->accessUrl);
        }

        return View::make('cmsharenjoy::unity.login');
    }

    /**
     * Login form processing.
     *
     * @access   public
     * @return   Redirect
     */
    public function postLogin()
    {
        $input = Input::all();
            
        $validator = new UserValidator;

        if ( ! $validator->setRule('loginRules')->valid($input, 'flash'))
        {
            return Redirect::to($this->accessUrl.'/login')->withInput();
        }

        $handler = App::make('Sharenjoy\Cmsharenjoy\User\UserInterface');
        $result = $handler->login($input);

        if ( ! $result['status'])
        {
            Message::error($result['message']);

            return Redirect::to($this->accessUrl.'/login')->withInput();
        }

        Message::success($result['message']);

        return Redirect::to($this->accessUrl);
    }

    /**
     * To activate an user via activation code
     * @return void
     */
    public function getActivate($id, $code)
    {
        $handler = App::make('Sharenjoy\Cmsharenjoy\User\UserInterface');
        $result = $handler->activate($id, $code);

        Message::{$result['status']}($result['message']);

        return Redirect::to($this->accessUrl.'/login');
    }

    /**
     * To reset an user password
     * @param  string $id
     * @param  string $code The code needs to valid
     * @return object Redirect
     */
    public function getResetpassword($code)
    {
        return View::make('cmsharenjoy::unity.reset-password')->with('code', $code);
    }

    public function postResetpassword()
    {
        $input = Input::all();
        $handler = App::make('Sharenjoy\Cmsharenjoy\User\UserInterface');
        $result = $handler->resetPassword($input);

        if ( ! $result['status'])
        {
            Message::error($result['message']);

            return Redirect::back();
        }

        Message::success($result['message']);

        return Redirect::to($this->accessUrl.'/login');
    }

    public function getRemindpassword()
    {
        return View::make('cmsharenjoy::unity.remind-password');
    }

    public function postRemindpassword()
    {
        $email = Input::get('email');
        $handler = App::make('Sharenjoy\Cmsharenjoy\User\UserInterface');
        $result = $handler->remindPassword($email);

        if ( ! $result['status'])
        {
            Message::error($result['message']);

            return Redirect::back();
        }

        Message::success($result['message']);

        return Redirect::to($this->accessUrl.'/remindpassword');
    }

}