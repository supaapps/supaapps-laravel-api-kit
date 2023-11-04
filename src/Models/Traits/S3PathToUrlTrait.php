<?php

namespace Supaapps\LaravelApiKit\Models\Traits;

use Carbon\Carbon;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Storage;

trait S3PathToUrlTrait
{
    /**
     * @return string
     */
    public function getUrlAttribute()
    {
        if (is_null($this->path)) {
            return null;
        }

        if (App::runningUnitTests()) {
            return Storage::cloud()->url($this->path);
        } else {
            return Storage::cloud()->temporaryUrl($this->path, Carbon::now()->addDay());
        }
    }

    /**
     * @return string|null
     */
    public function getThumbUrlAttribute(): ?string
    {
        if (is_null($this->thumb_path)) {
            return null;
        }

        if (App::runningUnitTests()) {
            return Storage::cloud()->url($this->thumb_path);
        } else {
            return Storage::cloud()->temporaryUrl($this->thumb_path, Carbon::now()->addDay());
        }
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return array_merge(
            parent::toArray(),
            [ 'url' => $this->url ],
            [ 'thumb_url' => $this->thumb_url ],
        );
    }
}
