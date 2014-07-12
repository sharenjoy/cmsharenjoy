<?php namespace Sharenjoy\Cmsharenjoy\Repo\Member;

use Sharenjoy\Cmsharenjoy\Core\EloquentBaseRepository;
use Sharenjoy\Cmsharenjoy\Service\Validation\ValidableInterface;
use Sentry, Input, Mail, Hash, Config, Session, Message, Poster;

class MemberRepository extends EloquentBaseRepository implements MemberInterface {

    public function __construct(Member $member, ValidableInterface $validator)
    {
        $this->validator = $validator;
        $this->model     = $member;

        parent::__construct();
    }

    public function create(array $input)
    {
        // Compose some necessary variable to input data
        $input = Poster::compose($input, $this->model->createComposeItem);

        $result = $this->validator->valid($input);
        if ( ! $result->status) return false;

        $input = Poster::compose($input, ['password']);        

        // Create the model
        $model = $this->model->create($input);

        return $model;
    }

    public function update($id, array $input, $vaidatorRules = 'updateRules')
    {
        // Compose some necessary variable to input data
        $input = Poster::compose($input, $this->model->updateComposeItem);

        $result = $this->validator->valid($input, $vaidatorRules, [$this->model->uniqueFields, $id]);
        if ( ! $result->status) return false;

        // Update the model
        $model = $this->model->find($id)->fill($input);
        $model->save();

        return $model;
    }

}