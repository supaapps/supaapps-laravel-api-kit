<?php

namespace Supaapps\Supalara\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class SetupController
{
    public $usersModel = '\App\User';

    /*
     * Setup the application first use
     */public function setup(Request $request)
    {
        if ($this->usersModel::count() > 0) {
            abort(403, 'Forbidden');
        }

        return $this->usersModel::create([
            'name' => $request->get('name'),
            'email' => $request->get('email'),
            'password' => Hash::make($request->get('password')),
        ]);
    }
}
