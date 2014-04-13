<?php namespace Sharenjoy\Cmsharenjoy\User;

use Sharenjoy\Cmsharenjoy\Core\EloquentBaseRepository;
use Sharenjoy\Cmsharenjoy\Service\Validation\ValidableInterface;
use Hash, Session, Message, Debugbar;

class UserRepository extends EloquentBaseRepository implements UserInterface {

    public function __construct(User $user)
    {
        $this->model = $user;
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
                $model->first_name = $model->account->first_name;
                $model->last_name = $model->account->last_name;
                $model->phone = $model->account->phone;
                break;
            case 'post-create':
                break;
            case 'post-update':
                $account = new Account($data);
                $model->account()->save($account);
                break;
            default:
                break;
        }
        return $model;
    }

    public function create(array $input)
    {
        $data = $this->composeInputData($input);

        Debugbar::info(get_class($this->model));

        $this->model->email = $input[ 'email' ];
        $this->model->password = $input[ 'password' ];

        // The password confirmation will be removed from model
        // before saving. This field will be used in Ardent's
        // auto validation.
        $this->model->password_confirmation = $input[ 'password_confirmation' ];

        // Save if valid. Password field will be hashed before save
        $this->model->save();

        if ( ! $this->model->id )
        {
            // Get validation errors (see Ardent package)
            $error = $this->model->errors()->all(':message');

            Message::merge(array('errors'=>$error))->flash();

            return false;
        }
        
        // $account = new Account($data);
        // $model->account()->save($account);

        // return $this->model->id;
    }

    public function update($id, array $input)
    {
        $data = $this->composeInputData($input);

        if(isset($this->model->uniqueFields) && count($this->model->uniqueFields))
        {
            $this->validator->setUniqueUpdateFields($this->model->uniqueFields, $id);
        }
        
        if ( ! $this->valid($data))
        {
            $this->getErrorsToFlashMessageBag();
            return false;
        }

        $model = $this->model->find($id)->fill($data);

        $model = $this->finalProcess(Session::get('onAction'), $model, $data);
        
        $model->save();

        return true;
    }

    protected function composeInputData(array $data)
    {   
        return $data;
    }

}