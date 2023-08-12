<?php

namespace Supaapps\Supalara\Controllers\CrudTraits;

use function Supaapps\Supalara\Controllers\CrudTraits\CrudTraits\abort;
use function Supaapps\Supalara\Controllers\CrudTraits\CrudTraits\response;

trait CrudDestroyTrait
{
    public function destroy($id)
    {
        if ($this->isDeletable || $this->readOnly) {
            abort('401', 'Deletion disabled');
        }
        $model = $this->model::findOrFail($id);
        $model->delete();
        return response(null, 204);
    }
}
