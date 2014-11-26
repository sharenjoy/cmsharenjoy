<?php namespace Sharenjoy\Cmsharenjoy\User;

use Sharenjoy\Cmsharenjoy\Core\EloquentBaseHandler;
use Sharenjoy\Cmsharenjoy\Service\Validation\ValidableInterface;
use Sentry, Mail, Config, Message;

class UserHandler extends EloquentBaseHandler implements UserInterface {

    public function __construct(User $model, ValidableInterface $validator)
    {
        $this->validator = $validator;
        $this->model     = $model;

        parent::__construct();
    }

    public function create()
    {   
        try
        {
            $input = $this->getInput();

            $user = Sentry::createUser(array(
                'email'       => $input['email'],
                'password'    => $input['password'],
                'name'        => $input['name'],
                'phone'       => $input['phone'],
                'avatar'      => $input['avatar'],
                'description' => $input['description'],
                // 'permissions' => $permissions
            ));

            // activate user
            $activationCode = $user->getActivationCode();

            if (true)
            {
                $user->attemptActivation($activationCode);
            }
            else
            {
                $data = array(
                    'id'       => $user->id,
                    'code'     => $activationCode,
                    'username' => $user->name,
                );

                // send email
                Mail::queue('cmsharenjoy::emails.auth.user-activation', $data, function($message) use ($user)
                {
                    $message->from(Config::get('mail.from.address'), Config::get('mail.from.name'))
                            ->subject('Account activation');
                    $message->to($user->getLogin());
                });
            }
        }
        catch (\Cartalyst\Sentry\Users\LoginRequiredException $e)
        {
            Message::error('Login field is required.');
            return false;
        }
        catch (\Cartalyst\Sentry\Users\PasswordRequiredException $e)
        {
            Message::error('Password field is required.');
            return false;
        }
        catch (\Cartalyst\Sentry\Users\UserExistsException $e)
        {
            Message::error('User with this login already exists.');
            return false;
        }
        catch (\Cartalyst\Sentry\Groups\GroupNotFoundException $e)
        {
            Message::error('Group was not found.');
            return false;
        }

        return Message::result(true, trans('cmsharenjoy::app.success_created'), $user);
    }

    public function update($id)
    {
        try
        {
            $input = $this->getInput();

            // Find the user using the user id
            $user = Sentry::findUserById($id);

            // Update the user details
            $user->email       = $input['email'];
            $user->name        = $input['name'];
            $user->phone       = $input['phone'];
            $user->avatar      = $input['avatar'];
            $user->description = $input['description'];

            // Update the user
            if ( ! $user->save())
            {
                Message::error('User information was not updated');
                return false;
            }            
        }
        catch (Cartalyst\Sentry\Users\UserExistsException $e)
        {
            Message::error('User with this login already exists.');
            return false;
        }
        catch (Cartalyst\Sentry\Users\UserNotFoundException $e)
        {
            Message::error('User was not found.');
            return false;
        }

        return Message::result(true, trans('cmsharenjoy::app.success_updated'), $user);
    }

    public function activate($id, $code)
    {
        try
        {
            // Find the user using the user id
            $user = Sentry::findUserById($id);

            // Attempt to activate the user
            if ($user->attemptActivation($code))
            {
                return Message::result('success', trans('cmsharenjoy::app.user_actived'));
            }
            else
            {
                return Message::result('error', trans('cmsharenjoy::app.error_active'));
            }
        }
        catch (\Cartalyst\Sentry\Users\UserNotFoundException $e)
        {
            return Message::result('error', trans('cmsharenjoy::app.user_not_found'));
        }
        catch (\Cartalyst\Sentry\Users\UserAlreadyActivatedException $e)
        {
            return Message::result('warning', trans('cmsharenjoy::app.user_already_actived'));
        }
    }

    public function remindPassword($var)
    {
        try
        {
            if (is_numeric($var))
            {
                $user = Sentry::findUserById($var);
            }
            elseif (is_string($var))
            {
                $user = Sentry::findUserByLogin($var);
            }

            // Get the password reset code
            $resetCode = $user->getResetPasswordCode();

            $data = array(
                'id'        => $user->id,
                'username'  => $user->name,
                'code'      => $resetCode
            );

            // send email
            Mail::queue('cmsharenjoy::emails.auth.user-reset-password', $data, function($message) use ($user)
            {
                $message->from(Config::get('mail.from.address'), Config::get('mail.from.name'))
                        ->subject(trans('cmsharenjoy::app.reset_password'));
                $message->to($user->email);
            });
        }
        catch (\Cartalyst\Sentry\Users\UserNotFoundException $e)
        {
            return Message::result(false, trans('cmsharenjoy::app.user_not_found'));
        }

        return Message::result(true, trans('cmsharenjoy::app.sent_reset_code'));
    }

    public function resetPassword($input)
    {
        try
        {
            // Find the user using the user id
            $user = Sentry::findUserByResetPasswordCode($input['code']);

            if ($input['email'] !== $user->email)
            {
                return Message::result(false, trans('cmsharenjoy::app.error_active'));
            }

            if ($input['password'] !== $input['password_confirmation'])
            {
                return Message::result(false, trans('cmsharenjoy::app.password_no_match'));
            }

            // Check if the reset password code is valid
            if ($user->checkResetPasswordCode($input['code']))
            {
                // Attempt to reset the user password
                if ($user->attemptResetPassword($input['code'], $input['password']))
                {
                    Sentry::logout();
                    return Message::result(true, trans('cmsharenjoy::app.password_reset_success'));
                }
                else
                {
                    return Message::result(false, trans('cmsharenjoy::app.password_reset_failed'));
                }
            }
            else
            {
                return Message::result(false, trans('cmsharenjoy::app.password_reset_code_invalid'));
            }
        }
        catch (\Cartalyst\Sentry\Users\UserNotFoundException $e)
        {
            return Message::result(false, trans('cmsharenjoy::app.user_not_found'));
        }
    }

    public function login($input)
    {
        try
        {
            $credentials = array(
                'email'    => $input['email'],
                'password' => $input['password'],
            );

            // authenticate user
            Sentry::authenticate($credentials, false);
        }
        catch(\Cartalyst\Sentry\Throttling\UserBannedException $e)
        {   
            return Message::result(false, trans('cmsharenjoy::app.invalid_email_password'));
        }
        catch (\RuntimeException $e)
        {
            return Message::result(false, trans('cmsharenjoy::app.invalid_email_password'));
        }

        return Message::result(true, trans('cmsharenjoy::app.success_login'));
    }

}