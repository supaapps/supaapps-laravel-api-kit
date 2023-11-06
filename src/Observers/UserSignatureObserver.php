<?php

namespace Supaapps\LaravelApiKit\Observers;

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
        // auth()->id() will return current logged in user id
        // based on request route auth middleware
        // @see https://github.com/laravel/framework/blob/10.x/src/Illuminate/Foundation/helpers.php#L158
        $model->created_by_id = auth()->id();
        $model->updated_by_id = auth()->id();
    }


    /**
     *
     * @param $model
     */
    public function updating($model)
    {
        $model->updated_by_id = auth()->id();
    }
}
