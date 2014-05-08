<?php namespace Sharenjoy\Cmsharenjoy\Controllers;

use Sharenjoy\Cmsharenjoy\Validator\Login as LoginValidator;
use Sharenjoy\Cmsharenjoy\Validator\Resetpassword as ResetpasswordValidator;
use Sentry, App, View, Redirect, Input, Config, Message, Mail, Str;

class DashController extends BaseController {

    protected $appName = 'dashboard';

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
        'getForgotpassword',
        'postForgotpassword'
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

        Message::merge(array('success' => trans('cmsharenjoy::admin.success_logout')))->flash();
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
        try
        {
            $input = Input::all();
            $validator = new LoginValidator(App::make('validator'));

            if ( ! $validator->with($input)->passes())
            {
                if ($validator->getErrorsToArray())
                {
                    foreach ($validator->getErrorsToArray() as $message)
                    {
                        Message::merge(array('errors' => $message))->flash();
                    }
                }
                return Redirect::to($this->urlSegment.'/login')->withInput();
            }

            $credentials = array(
                'email'    => Input::get('email'),
                'password' => Input::get('password'),
            );

            // authenticate user
            Sentry::authenticate($credentials, Input::get('remember'));
        }
        catch(\Cartalyst\Sentry\Throttling\UserBannedException $e)
        {
            Message::merge(array('errors' => trans('cmsharenjoy::admin.invalid_email_password')))->flash();
            return Redirect::to($this->urlSegment.'/login')->withInput();
        }
        catch (\RuntimeException $e)
        {
            Message::merge(array('errors' => trans('cmsharenjoy::admin.invalid_email_password')))->flash();
            return Redirect::to($this->urlSegment.'/login')->withInput();
        }

        Message::merge(array('success' => trans('cmsharenjoy::admin.success_login')))->flash();
        return Redirect::to( $this->urlSegment );
    }

    /**
     * To activate an user via activation code
     * @return void
     */
    public function getActivate($id, $code)
    {
        try
        {
            // Find the user using the user id
            $user = Sentry::findUserById($id);

            // Attempt to activate the user
            if ($user->attemptActivation($code))
            {
                Message::merge(array('success' => trans('cmsharenjoy::admin.user_actived')))->flash();
                return Redirect::to($this->urlSegment.'/login');
            }
            else
            {
                Message::merge(array('errors' => trans('cmsharenjoy::admin.error_active')))->flash();
                return Redirect::to($this->urlSegment.'/login');
            }
        }
        catch (\Cartalyst\Sentry\Users\UserNotFoundException $e)
        {
            Message::merge(array('errors' => trans('cmsharenjoy::admin.user_not_found')))->flash();
            return Redirect::to($this->urlSegment.'/login');
        }
        catch (\Cartalyst\Sentry\Users\UserAlreadyActivatedException $e)
        {
            Message::merge(array('info' => trans('cmsharenjoy::admin.user_already_actived')))->flash();
            return Redirect::to($this->urlSegment.'/login');
        }
    }

    /**
     * To reset an user password
     * @param  string $id
     * @param  string $code The code needs to valid
     * @return object Redirect
     */
    public function getResetpassword($id, $code)
    {
        return View::make('cmsharenjoy::reset-password')->with('id', $id)->with('code', $code);
    }

    public function postResetpassword()
    {
        try
        {
            $input = Input::all();

            $validator = new ResetpasswordValidator(App::make('validator'));

            if ( ! $validator->with($input)->passes())
            {
                if ($validator->getErrorsToArray())
                {
                    foreach ($validator->getErrorsToArray() as $message)
                    {
                        Message::merge(array('errors' => $message))->flash();
                    }
                }
                return Redirect::to($this->urlSegment.'/resetpassword/'.$input['id'].'/'.$input['code'])->withInput();
            }

            // Find the user using the user id
            $user = Sentry::findUserById($input['id']);
            
            if ( ! $user->checkPassword($input['old_password']))
            {
                Message::merge(array('errors' => trans('cmsharenjoy::admin.old_password_incorrect')))->flash();
                return Redirect::back();
            }

            if ($input['password'] !== $input['password_confirmation'])
            {
                Message::merge(array('errors' => trans('cmsharenjoy::admin.password_no_match')))->flash();
                return Redirect::back();
            }

            // Check if the reset password code is valid
            if ($user->checkResetPasswordCode($input['code']))
            {
                // Attempt to reset the user password
                if ($user->attemptResetPassword($input['code'], $input['password']))
                {
                    Sentry::logout();
                    Message::merge(array('success' => trans('cmsharenjoy::admin.password_reset_success')))->flash();
                    return Redirect::to($this->urlSegment.'/login');
                }
                else
                {
                    Message::merge(array('errors' => trans('cmsharenjoy::admin.password_reset_failed')))->flash();
                    return Redirect::back();
                }
            }
            else
            {
                Message::merge(array('errors' => trans('cmsharenjoy::admin.password_reset_code_invalid')))->flash();
                return Redirect::back();
            }
        }
        catch (\Cartalyst\Sentry\Users\UserNotFoundException $e)
        {
            Message::merge(array('errors' => trans('cmsharenjoy::admin.user_not_found')))->flash();
            return Redirect::back();
        }
    }

    public function getForgotpassword()
    {
        return View::make('cmsharenjoy::forgot-password');
    }

    public function postForgotpassword()
    {
        try
        {
            $email = Input::get('email');
            $user = Sentry::findUserByLogin($email);

            $password = Str::random(8);
            $user->password = $password;

            if ( ! $user->save())
            {
                Message::merge(array('errors' => trans('cmsharenjoy::admin.some_wrong')))->flash();
                return Redirect::to($this->urlSegment.'/forgotpassword');
            }

            // Get the password reset code
            $resetCode = $user->getResetPasswordCode();

            $userName = $user->account->first_name.' '.$user->account->last_name;
            $datas = array(
                'id'        => $user->id,
                'username'  => $userName,
                'code'      => $resetCode,
                'password'  => $password
            );

            // send email
            Mail::queue('cmsharenjoy::mail.user-reset-password', $datas, function($message) use ($user)
            {
                $message->from(Config::get('mail.from.address'), Config::get('mail.from.name'))
                        ->subject(trans('cmsharenjoy::admin.reset_password'));
                $message->to($user->email);
            });
        }
        catch (\Cartalyst\Sentry\Users\UserNotFoundException $e)
        {
            Message::merge(array('errors' => trans('cmsharenjoy::admin.user_not_found')))->flash();
            return Redirect::to($this->urlSegment.'/forgotpassword');
        }

        Message::merge(array('success' => trans('cmsharenjoy::admin.sent_reset_code')))->flash();
        return Redirect::to($this->urlSegment.'/forgotpassword');
    }

}