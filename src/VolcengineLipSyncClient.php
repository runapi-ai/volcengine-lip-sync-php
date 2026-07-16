<?php

declare(strict_types=1);

namespace RunApi\VolcengineLipSync;

use RunApi\Core\BaseClient;
use RunApi\Core\ClientOptions;
use RunApi\VolcengineLipSync\Resources\LipSyncVideo;

/**
 * Volcengine Lip Sync RunAPI PHP client.
 *
 * The client exposes typed model resources plus the universal `files` and
 * `account` resources.
 */
final class VolcengineLipSyncClient extends BaseClient
{
    /** Lip sync video operations for Volcengine Lip Sync. */
    public readonly LipSyncVideo $lipSyncVideo;

    /** Create a Volcengine Lip Sync client with optional API key, base URL, and transport overrides. */
    public function __construct(ClientOptions $options = new ClientOptions())
    {
        parent::__construct($options);
        $this->lipSyncVideo = LipSyncVideo::fromHttp($this->http);
    }
}
