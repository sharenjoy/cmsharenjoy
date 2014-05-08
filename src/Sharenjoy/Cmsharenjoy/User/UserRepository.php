<?php namespace Sharenjoy\Cmsharenjoy\User;

use Sharenjoy\Cmsharenjoy\Core\EloquentBaseRepository;
use Sharenjoy\Cmsharenjoy\Service\Validation\ValidableInterface;
use Sentry, Input, Mail, Hash, Config, Session, Message, Debugbar;

class UserRepository extends EloquentBaseRepository implements UserInterface {

    public function __construct(User $user, ValidableInterface $userValidator, ValidableInterface $accountValidator)
    {
        $this->userValidator    = $userValidator;
        $this->accountValidator = $accountValidator;
        $this->model            = $user;
    }

    public function finalProcess($action, $model = null, $data = null)
    {
        switch ($action)
        {
            case 'get-index':
                foreach ($model as $key => $value)
                {
                    $model[$key]->user_name = $value->account->first_name.' '.$value->account->last_name;
                    $model[$key]->phone     = $value->account->phone;
                }
                break;
            case 'get-update':
                $model->first_name  = $model->account->first_name;
                $model->last_name   = $model->account->last_name;
                $model->phone       = $model->account->phone;
                $model->description = $model->account->description;
                break;
            case 'post-create':
                break;
            case 'post-update':
                break;
            default:
                break;
        }
        return $model;
    }

    public function create(array $input)
    {   
        try
        {
            $vali = false;
            if ( ! $this->accountValidator->with($input)->passes())
            {
                if ($this->accountValidator->getErrorsToArray())
                {
                    foreach ($this->accountValidator->getErrorsToArray() as $message)
                    {
                        Message::merge(array('errors' => $message))->flash();
                    }
                }
                $vali = true;
            }
            if ( ! $this->userValidator->with($input)->passes())
            {
                if ($this->userValidator->getErrorsToArray())
                {
                    foreach ($this->userValidator->getErrorsToArray() as $message)
                    {
                        Message::merge(array('errors' => $message))->flash();
                    }
                }
                $vali = true;
            }
            if ($vali) return false;
            
            // create user
            $user = Sentry::createUser(array(
                'email'       => Input::get('email'),
                'password'    => Input::get('password'),
                // 'permissions' => $permissions
            ));

            // sort id
            $this->storeById($user->id, array('sort' => $user->id));

            // activate user
            $activationCode = $user->getActivationCode();

            // create account info
            $input['user_id'] = $user->id;
            $account = $user->account()->getRelated()->create($input);

            if(true)
            {
                $user->attemptActivation($activationCode);
            }
            else
            {
                $userName = $account->first_name.' '.$account->last_name;
                $datas = array(
                    'id'       => $user->id,
                    'code'     => $activationCode,
                    'username' => $userName,
                );

                // send email
                Mail::queue('cmsharenjoy::mail.user-activation', $datas, function($message) use ($user)
                {
                    $message->from(Config::get('mail.from.address'), Config::get('mail.from.name'))
                            ->subject('Account activation');
                    $message->to($user->getLogin());
                });
            }
        }
        catch (\Cartalyst\Sentry\Users\LoginRequiredException $e)
        {
            Message::merge(array('errors' => 'Login field is required.'))->flash();
            return false;
        }
        catch (\Cartalyst\Sentry\Users\PasswordRequiredException $e)
        {
            Message::merge(array('errors' => 'Password field is required.'))->flash();
            return false;
        }
        catch (\Cartalyst\Sentry\Users\UserExistsException $e)
        {
            Message::merge(array('errors' => 'User with this login already exists.'))->flash();
            return false;
        }
        catch (\Cartalyst\Sentry\Groups\GroupNotFoundException $e)
        {
            Message::merge(array('errors' => 'Group was not found.'))->flash();
            return false;
        }

        return true;
    }

    public function update($id, array $input)
    {
        try
        {
            $vali = false;
            if ( ! $this->accountValidator->with($input)->passes())
            {
                if ($this->accountValidator->getErrorsToArray())
                {
                    foreach ($this->accountValidator->getErrorsToArray() as $message)
                    {
                        Message::merge(array('errors' => $message))->flash();
                    }
                }
                $vali = true;
            }

            $this->userValidator->setRule('updateRules');
            if( ! empty($this->model->uniqueFields))
            {
                $this->userValidator->setUniqueUpdateFields($this->model->uniqueFields, $id);
            }

            if ( ! $this->userValidator->with($input)->passes())
            {
                if ($this->userValidator->getErrorsToArray())
                {
                    foreach ($this->userValidator->getErrorsToArray() as $message)
                    {
                        Message::merge(array('errors' => $message))->flash();
                    }
                }
                $vali = true;
            }
            if ($vali) return false;

            // Find the user using the user id
            $user = Sentry::findUserById($id);

            // Update the user details
            $user->email = $input['email'];

            // Update the user
            if ($user->save())
            {
                // update account info
                $account = $user->account()->getRelated()->where('user_id', $id)->first();
                $account->fill($input)->save();

                return true;
            }
            else
            {
                Message::merge(array('errors' => 'User information was not updated'))->flash();
                return false;
            }
        }
        catch (Cartalyst\Sentry\Users\UserExistsException $e)
        {
            Message::merge(array('errors' => 'User with this login already exists.'))->flash();
            return false;
        }
        catch (Cartalyst\Sentry\Users\UserNotFoundException $e)
        {
            Message::merge(array('errors' => 'User was not found.'))->flash();
            return false;
        }

        return true;
    }

    protected function composeInputData(array $data)
    {   
        return $data;
    }

}