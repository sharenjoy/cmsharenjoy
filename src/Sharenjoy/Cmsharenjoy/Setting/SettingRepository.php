<?php namespace Sharenjoy\Cmsharenjoy\Setting;

use Sharenjoy\Cmsharenjoy\Core\EloquentBaseRepository;
use Sharenjoy\Cmsharenjoy\Service\Validation\ValidableInterface;
use Config;

class SettingRepository extends EloquentBaseRepository implements SettingInterface
{

    /**
     * Construct Shit
     * @param Settings $settings
     */
    public function __construct(Setting $setting, ValidableInterface $validator)
    {
        $this->validator = $validator;
        $this->model     = $setting;
    }

    /**
     * Examine the key exists or not
     * @param  string  $key
     * @return boolean
     */
    public function has($key)
    {
        $model = $this->model->where('key', $key)->first();
        
        return $model->value ? true : false;
    }

    /**
     * Get a setting by it's key or slug or whatever
     * @param  string $key The key (contact-address , website-name etc)
     * @return One God-Damn Record
     */
    public function get($key)
    {
        $model = $this->model->where('key', $key)->first();
        return $model->value;
    }

    public function finalProcess($action, $model = null, $data = null){}

}