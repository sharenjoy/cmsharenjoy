<?php namespace Sharenjoy\Cmsharenjoy\Lib\SentryMods;

use Illuminate\Support\Facades\Facade;

class SentryMember extends Facade
{
    protected static function getFacadeAccessor(){ return 'sentry.member'; }
}