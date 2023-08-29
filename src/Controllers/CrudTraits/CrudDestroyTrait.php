<?php

namespace Supaapps\Supalara\Controllers\CrudTraits;

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
