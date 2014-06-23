<?php namespace Sharenjoy\Cmsharenjoy\Repo\Member;

use Sharenjoy\Cmsharenjoy\Core\EloquentBaseRepository;
use Sharenjoy\Cmsharenjoy\Service\Validation\ValidableInterface;
use Sentry, Input, Mail, Hash, Config, Session, Message;

class MemberRepository extends EloquentBaseRepository implements MemberInterface {

    public function __construct(Member $member, ValidableInterface $validator)
    {
        $this->validator = $validator;
        $this->model     = $member;

        parent::__construct();
    }

    public function create(array $input)
    {
        $result = $this->validator->valid($input);
        if ( ! $result->status) return false;

        $input = Poster::compose($input, $this->model->createComposeItem);
        $input['password'] = Hash::make($input['password']);

        // Create the model
        $model = $this->model->create($input);

        return $model;
    }

    public function update($id, array $input, $vaidatorRules = 'updateRules')
    {
        $result = $this->validator->valid($input, $vaidatorRules, [$this->model->uniqueFields, $id]);
        if ( ! $result->status) return false;

        // Compose some necessary variable to input data
        $input = Poster::compose($input, $this->model->updateComposeItem);

        // Update the model
        $model = $this->model->find($id)->fill($input);
        $model->save();

        return $model;
    }

}