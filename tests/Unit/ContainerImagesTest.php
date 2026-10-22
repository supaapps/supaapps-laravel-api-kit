<?php

namespace Tests\Unit;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Supaapps\LaravelApiKit\Support\ContainerImages;

class ContainerImagesTest extends TestCase
{
    public function testSupportedBranchesUseMatchingImmutableProfiles(): void
    {
        foreach (['8.2', '8.3', '8.4'] as $branch) {
            foreach (ContainerImages::forBranch($branch) as $profile => $image) {
                self::assertMatchesRegularExpression(
                    '~^ghcr.io/supaapps/docker-laravel:php' . preg_quote($branch, '~')
                    . '-' . $profile . '-20261022\.1@sha256:[a-f0-9]{64}$~',
                    $image
                );
            }
        }
    }

    public function testLegacyImageSelectionIsRejected(): void
    {
        $this->expectException(InvalidArgumentException::class);
        ContainerImages::forBranch('v2.1');
    }
}
