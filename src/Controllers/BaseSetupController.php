<?php

namespace Supaapps\Supalara\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class BaseSetupController extends Controller
{
    public $usersModel = '\App\User';

    /*
     * Setup the application first use
     */public function setup(Request $request)
    {
        if ($this->usersModel::count() > 0) {
            abort(403, 'Forbidden');
        }

        $user = new $this->usersModel();
        $user->name = $request->get('name');
        $user->email = $request->get('email');
        $user->password = Hash::make($request->get('password'));
        $user->save();

        return $user;
    }
}
