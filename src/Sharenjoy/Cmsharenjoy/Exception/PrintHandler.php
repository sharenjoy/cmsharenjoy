<?php namespace Sharenjoy\Cmsharenjoy\Exception;

use Illuminate\Support\Contracts\MessageProviderInterface;
use Exception;

class PrintHandler implements HandlerInterface {


    /**
     * Handle Sharenjoy Exceptions
     *
     * @param \Sharenjoy\Cmsharenjoy\Exception\SharenjoyException
     * @return void
     */
    public function handle(SharenjoyException $exception)
    {
        // $this->sendException($exception);
        // Debugbar::info('Error: '.class_basename(get_class($exception)).' '.$exception->getMessage());
    }

    /**
     * Send Exception to notifier
     * @param  \Exception $exception Send notification of exception
     * @return void
     */
    protected function sendException(Exception $e)
    {
        $this->notifier->notify('Error: '.get_class($e), $e->getMessage());
    }

}
