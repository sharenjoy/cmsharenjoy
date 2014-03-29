<?php namespace Sharenjoy\Cmsharenjoy\Exception;

interface HandlerInterface {

    /**
     * Handle Sharenjoy Exceptions
     *
     * @param \Sharenjoy\Cmsharenjoy\Exception\SharenjoyException
     * @return void
     */
    public function handle(SharenjoyException $exception);

}