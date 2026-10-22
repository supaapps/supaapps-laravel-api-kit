<?php

namespace Supaapps\LaravelApiKit\Support;

use InvalidArgumentException;

final class ContainerImages
{
    /** @return array{build: string, runtime: string} */
    public static function forBranch(string $branch): array
    {
        $digests = [
            '8.2' => [
                'build' => '91d9a31546a8bc71e28f7a8a23570d3d188297a1eb54d1486f3f379371dab8af',
                'runtime' => '400474e8bdc551fb5aa306d24f622857a571e565ce600ef9863075d91edb5287',
            ],
            '8.3' => [
                'build' => 'daaf25553dd9ad264d379f20e4ae64df0af224a9ce160b3e9ad749e54637ecd8',
                'runtime' => '3c6616ef391a704b0335f59628bd0bd3d121fd794176d394b11d58fc26c1e349',
            ],
            '8.4' => [
                'build' => '3d649a08e4f5a5e177c30c9a1914428ca5543ac88c5959a5fce7f4c0b4dba1cf',
                'runtime' => 'aa6eb4356ee9aa0ba819ecdba20b43ae3b35d1668dc250a58a3dc9a8c0668398',
            ],
        ];
        if (!isset($digests[$branch])) {
            throw new InvalidArgumentException('Unsupported PHP branch');
        }
        $images = [];
        foreach ($digests[$branch] as $profile => $digest) {
            $images[$profile] = "ghcr.io/supaapps/docker-laravel:php{$branch}-{$profile}-20261022.1@sha256:{$digest}";
        }
        return $images;
    }
}
