<?php namespace Sharenjoy\Cmsharenjoy\Repo\Member;

use Sharenjoy\Cmsharenjoy\Core\EloquentBaseRepository;
use Sharenjoy\Cmsharenjoy\Service\Validation\ValidableInterface;
use Sentry, Input, Mail, Hash, Config, Session, Message;

class MemberRepository extends EloquentBaseRepository implements MemberInterface {

    protected $repoName = 'member';

    public function __construct(Member $member, ValidableInterface $validator)
    {
        $this->validator = $validator;
        $this->model     = $member;
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
        
        $input['password'] = Hash::make($input['password']);

        // create user
        $member = $this->model->create($input);
        $this->store($member->id, array('sort' => $member->id));

        return $member;
    }

    public function update($id, array $input)
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
        $member = $this->model->find($id)->fill($input);

        // Update the user
        if ( ! $member->save())
        {
            Message::merge(array('errors' => 'User information was not updated'))->flash();
            return false;
        }

        return true;
    }

    protected function composeInputData(array $data) { return $data; }

}