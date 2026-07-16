<?php

declare(strict_types=1);

namespace RunApi\VolcengineLipSync;

final class Types
{
    /**
     * Allowed model slugs for lip sync video requests.
     *
     * @var list<string>
     */
    public const LIP_SYNC_VIDEO_MODELS = ['volcengine-lip-sync'];

    private function __construct()
    {
    }
}
