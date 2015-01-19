<?php namespace Sharenjoy\Cmsharenjoy\Service\Presenter;

use Sharenjoy\Cmsharenjoy\Service\Presenter\Exceptions\PresenterException;

trait PresentableTrait {

	protected $presenterInstance;

	public function present()
	{
		if ( ! $this->presenter or ! class_exists($this->presenter))
		{
			throw new PresenterException('Please set the $presenter property to your presenter path.');
		}

		if ( ! $this->presenterInstance)
		{
			$this->presenterInstance = new $this->presenter($this);
		}

		return $this->presenterInstance;
	}

}