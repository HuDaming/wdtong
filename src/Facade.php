<?php

namespace Hudm\Wdtong;

class Facade extends \Illuminate\Support\Facades\Facade
{
    public static function getFacadeAccessor()
    {
        return Wdtong::class;
    }
}