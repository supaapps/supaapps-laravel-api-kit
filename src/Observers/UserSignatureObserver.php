<?php

namespace Supaapps\Supalara\Observers;

use Illuminate\Support\Facades\DB;

class UserSignatureObserver
{

    /**
     * @todo We should detect user model and get ID from model, but remember:
     * we have in some projects different user providers
     */

    /**
     * Handle the User "created" event.
     *
     * @param  $model
     * @return void
     */
    public function creating($model)
    {
        if (defined('USER_ID')) {
            $model->created_by_id = USER_ID;
            $model->updated_by_id = USER_ID;
        }
    }


    /**
     *
     * @param $model
     */
    public function updating($model)
    {
        if (defined('USER_ID')) {
            $model->updated_by_id = USER_ID;
        }
    }
}
