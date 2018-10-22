<?php

namespace Tantana5\Setting;

use Illuminate\Support\Facades\Facade;

class SettingFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'Setting';
    }
}
