<?php namespace Sharenjoy\Cmsharenjoy\Controllers;

use Illuminate\Support\MessageBag;
use Sharenjoy\Cmsharenjoy\Validators\Login;
use View, Auth, Redirect, Validator, Session, Input, Config, Message;

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
        'postLogin'
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
        Auth::logout();
        Session::flush();
        Message::merge(array('success' => 'Succesfully logged out.'))->flash();
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
        if (Auth::check())
        {
            return Redirect::to( $this->urlSegment );
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
        $loginValidator = new Login( Input::all() );

        // Check if the form validates with success.
        if ( $loginValidator->passes() )
        {

            $loginDetails = array(
                'email' => Input::get('email'),
                'password' => Input::get('password')
            );

            // Try to log the user in.
            if ( Auth::attempt( $loginDetails ) )
            {
                $user = Auth::user();
                $user->last_login = date('Y-m-d H:i:s');
                $user->save();

                // Redirect to the users page.
                Message::merge(array('success' => 'You have logged in successfully !'))->flash();
                return Redirect::to( $this->urlSegment );
            }else{
                // Redirect to the login page.
                Message::merge(array('errors' => 'Invalid Email &amp; Password !'))->flash();
                return Redirect::to($this->urlSegment.'/login');
            }
        }

        // Something went wrong.
        return Redirect::to($this->urlSegment.'/login')
                ->withErrors( $loginValidator->messages() )->withInput();
    }

}