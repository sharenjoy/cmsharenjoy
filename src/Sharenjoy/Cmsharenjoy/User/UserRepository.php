<?php namespace Sharenjoy\Cmsharenjoy\User;

use Sharenjoy\Cmsharenjoy\Core\EloquentBaseRepository;
use Sharenjoy\Cmsharenjoy\Service\Validation\ValidableInterface;
use Sentry, Input, Mail, Hash, Config, Session, Message;

class UserRepository extends EloquentBaseRepository implements UserInterface {

    protected $repoName = 'user';

    public function __construct(User $user, ValidableInterface $validator)
    {
        $this->validator = $validator;
        $this->model     = $user;
    }

    public function setFilterQuery($model = null, $query)
    {
        $model = $model ?: $this->model;

        if (count($query) !== 0)
        {
            extract($query);
        }
        return $model;                     
    }

    public function create(array $input)
    {   
        try
        {
            if ( ! $this->validator->with($input)->passes())
            {
                if ($this->validator->getErrorsToArray())
                {
                    foreach ($this->validator->getErrorsToArray() as $message)
                    {
                        Message::merge(array('errors' => $message))->flash();
                    }
                }
                return false;
            }
            
            // create user
            $user = Sentry::createUser(array(
                'email'       => Input::get('email'),
                'password'    => Input::get('password'),
                'name'        => Input::get('name'),
                'phone'       => Input::get('phone'),
                'description' => Input::get('description'),
                // 'permissions' => $permissions
            ));

            // sort id
            $this->store($user->id, array('sort' => $user->id));

            // activate user
            $activationCode = $user->getActivationCode();

            if(true)
            {
                $user->attemptActivation($activationCode);
            }
            else
            {
                $datas = array(
                    'id'       => $user->id,
                    'code'     => $activationCode,
                    'username' => $user->name,
                );

                // send email
                Mail::queue('cmsharenjoy::emails.auth.user-activation', $datas, function($message) use ($user)
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
            $this->validator->setRule('updateRules');
            if( ! empty($this->model->uniqueFields))
            {
                $this->validator->setUniqueUpdateFields($this->model->uniqueFields, $id);
            }
            if ( ! $this->validator->with($input)->passes())
            {
                if ($this->validator->getErrorsToArray())
                {
                    foreach ($this->validator->getErrorsToArray() as $message)
                    {
                        Message::merge(array('errors' => $message))->flash();
                    }
                }
                return false;
            }

            // Find the user using the user id
            $user = Sentry::findUserById($id);

            // Update the user details
            $user->email       = $input['email'];
            $user->name        = $input['name'];
            $user->phone       = $input['phone'];
            $user->description = $input['description'];

            // Update the user
            if ( ! $user->save())
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