<?php namespace Sharenjoy\Cmsharenjoy\Controllers;

use Sharenjoy\Cmsharenjoy\User\UserValidator;
use Sentry, App, View, Redirect, Input, Message;

class DashController extends BaseController {

    /**
     * Let's whitelist all the methods we want to allow guests to visit!
     *
     * @access   protected
     * @var      array
     */
    protected $whitelist = array(
        'getLogin',
        'getLogout',
        'postLogin',
        'getActivate',
        'getResetpassword',
        'postResetpassword',
        'getRemindpassword',
        'postRemindpassword'
    );

    /**
     * Main users page.
     *
     * @access   public
     * @return   View
     */
    public function getIndex()
    {
        return View::make('cmsharenjoy::dashboard');
    }

    /**
     * Log the user out
     * @return Redirect
     */
    public function getLogout()
    {
        Sentry::logout();

        Message::success(trans('cmsharenjoy::app.success_logout'));
        return Redirect::to($this->urlSegment.'/login');
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
            return Redirect::to($this->urlSegment);
        }

        return View::make('cmsharenjoy::login');
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
            return Redirect::to($this->urlSegment.'/login')->withInput();
        }

        $repo = App::make('Sharenjoy\Cmsharenjoy\User\UserInterface');
        $result = $repo->login($input);

        if ( ! $result['status'])
        {
            Message::error($result['message']);
            return Redirect::to($this->urlSegment.'/login')->withInput();
        }

        Message::success($result['message']);
        return Redirect::to($this->urlSegment);
    }

    /**
     * To activate an user via activation code
     * @return void
     */
    public function getActivate($id, $code)
    {
        $repo  = App::make('Sharenjoy\Cmsharenjoy\User\UserInterface');
        $result = $repo->activate($id, $code);

        Message::{$result['status']}($result['message']);
        return Redirect::to($this->urlSegment.'/login');
    }

    /**
     * To reset an user password
     * @param  string $id
     * @param  string $code The code needs to valid
     * @return object Redirect
     */
    public function getResetpassword($code)
    {
        return View::make('cmsharenjoy::reset-password')->with('code', $code);
    }

    public function postResetpassword()
    {
        $input = Input::all();
        $repo  = App::make('Sharenjoy\Cmsharenjoy\User\UserInterface');
        $result = $repo->resetPassword($input);

        if ( ! $result['status'])
        {
            Message::error($result['message']);
            return Redirect::back();
        }

        Message::success($result['message']);
        return Redirect::to($this->urlSegment.'/login');
    }

    public function getRemindpassword()
    {
        return View::make('cmsharenjoy::remind-password');
    }

    public function postRemindpassword()
    {
        $email = Input::get('email');
        $repo  = App::make('Sharenjoy\Cmsharenjoy\User\UserInterface');
        $result = $repo->remindPassword($email);

        if ( ! $result['status'])
        {
            Message::error($result['message']);
            return Redirect::back();
        }

        Message::success($result['message']);
        return Redirect::to($this->urlSegment.'/remindpassword');
    }

}