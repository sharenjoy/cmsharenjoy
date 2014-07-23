<?php namespace Sharenjoy\Cmsharenjoy\User;

use Sharenjoy\Cmsharenjoy\Core\EloquentBaseRepository;
use Sharenjoy\Cmsharenjoy\Service\Validation\ValidableInterface;
use Sentry, Input, Mail, Hash, Config, Session, Message;

class UserRepository extends EloquentBaseRepository implements UserInterface {

    public function __construct(User $user, ValidableInterface $validator)
    {
        $this->validator = $validator;
        $this->model     = $user;

        parent::__construct();
    }

    public function create(array $input)
    {   
        try
        {
            $result = $this->validator->valid($input);
            if ( ! $result->status) return false;
            
            // create user
            $user = Sentry::createUser(array(
                'email'       => Input::get('email'),
                'password'    => Input::get('password'),
                'name'        => Input::get('name'),
                'phone'       => Input::get('phone'),
                'description' => Input::get('description'),
                'sort'        => time(),
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

        return true;
    }

    public function update($id, array $input, $vaidatorRules = 'updateRules')
    {
        try
        {
            $result = $this->validator->valid($input, $vaidatorRules, [$this->model->uniqueFields, $id]);
            if ( ! $result->status) return false;

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

        return true;
    }

}