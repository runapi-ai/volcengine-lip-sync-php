<?php

declare(strict_types=1);

namespace RunApi\VolcengineLipSync\Resources;

use RunApi\Core\Http\HttpClient;
use RunApi\Core\Models\TaskCreateResponse;
use RunApi\Core\RequestOptions;
use RunApi\Core\Resources\TypedConfiguredResource;
use RunApi\VolcengineLipSync\Models\CompletedVideoTaskResponse;
use RunApi\VolcengineLipSync\Models\VideoTaskResponse;

/** Lip sync video operations for Volcengine Lip Sync. */
readonly class LipSyncVideo extends TypedConfiguredResource
{
    /**
     * Create a lip sync video task and return immediately with a task id.
     *
     * @param array{
     *   mode: string,
     *   model: string,
     *   source_audio_url: string,
     *   source_video_url: string,
     *   align_audio?: bool,
     *   align_audio_reverse?: bool,
     *   callback_url?: string,
     *   enable_scene_detection?: bool,
     *   enable_vocal_separation?: bool,
     *   template_start_seconds?: float|int
     * } $params
     */
    public function create(array $params, ?RequestOptions $options = null): TaskCreateResponse
    {
        return parent::create($params, $options);
    }

    /** Fetch the current status of a lip sync video task. */
    public function get(string $id, ?RequestOptions $options = null): VideoTaskResponse
    {
        $response = parent::get($id, $options);

        /** @var VideoTaskResponse $response */
        return $response;
    }

    /**
     * Create a lip sync video task and poll until it completes.
     *
     * @param array{
     *   mode: string,
     *   model: string,
     *   source_audio_url: string,
     *   source_video_url: string,
     *   align_audio?: bool,
     *   align_audio_reverse?: bool,
     *   callback_url?: string,
     *   enable_scene_detection?: bool,
     *   enable_vocal_separation?: bool,
     *   template_start_seconds?: float|int
     * } $params
     */
    public function run(array $params, ?RequestOptions $options = null): CompletedVideoTaskResponse
    {
        $response = parent::run($params, $options);

        /** @var CompletedVideoTaskResponse $response */
        return $response;
    }

    /** Create the resource using the shared RunAPI HTTP transport. */
    public static function fromHttp(HttpClient $http): self
    {
        return new self(
            $http,
            '/api/v1/volcengine_lip_sync/lip_sync_video',
            VideoTaskResponse::class,
            CompletedVideoTaskResponse::class,
            'lip-sync-video',
            VideoTaskResponse::class,
            CompletedVideoTaskResponse::class,
        );
    }
}
