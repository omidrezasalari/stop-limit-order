<?php

namespace StopLimit\Classes\Facades;

use Illuminate\Support\Facades\Auth;

class ApiAuth
{
    public function userId()
    {
        return Auth::user()->id;
    }
}