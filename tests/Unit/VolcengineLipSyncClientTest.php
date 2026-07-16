<?php

declare(strict_types=1);

namespace RunApi\VolcengineLipSync\Tests\Unit;

use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;
use RunApi\Core\ClientOptions;
use RunApi\Core\Errors\ValidationException;
use RunApi\Core\Tests\Fixtures\QueueHttpClient;
use RunApi\VolcengineLipSync\Models\CompletedVideoTaskResponse;
use RunApi\VolcengineLipSync\Resources\LipSyncVideo;
use RunApi\VolcengineLipSync\VolcengineLipSyncClient;

final class VolcengineLipSyncClientTest extends TestCase
{
    public function testExposesTypedResources(): void
    {
        $client = new VolcengineLipSyncClient(new ClientOptions(apiKey: 'k', httpClient: new QueueHttpClient([]), maxRetries: 0));

        self::assertInstanceOf(LipSyncVideo::class, $client->lipSyncVideo);
    }

    public function testCreatePostsCompactedBodyToCorrectPath(): void
    {
        $transport = new QueueHttpClient([
            new Response(200, [], '{"id":"task_1"}'),
        ]);
        $client = new VolcengineLipSyncClient(new ClientOptions(apiKey: 'k', httpClient: $transport, maxRetries: 0));

        $task = $client->lipSyncVideo->create([
            'model' => 'volcengine-lip-sync',
            'align_audio' => true,
            'align_audio_reverse' => true,
            'enable_vocal_separation' => true,
            'mode' => 'lite',
            'source_audio_url' => 'https://cdn.runapi.ai/public/samples/volcengine-lip-sync-voice-adam.mp3',
            'source_video_url' => 'https://cdn.runapi.ai/public/samples/volcengine-lip-sync-source.mp4',
            'template_start_seconds' => 0.5,
            'callback_url' => '',
            'seed' => null,
        ]);

        $body = json_decode((string) $transport->requests[0]->getBody(), true, flags: JSON_THROW_ON_ERROR);

        self::assertSame('task_1', $task->id);
        self::assertSame('/api/v1/volcengine_lip_sync/lip_sync_video', $transport->requests[0]->getUri()->getPath());
        self::assertSame('volcengine-lip-sync', $body['model']);
        self::assertArrayNotHasKey('callback_url', $body);
        self::assertArrayNotHasKey('seed', $body);
    }

    public function testRunReturnsTypedCompletedResponseAndPreservesUnknownFields(): void
    {
        $transport = new QueueHttpClient([
            new Response(200, [], '{"id":"task_1"}'),
            new Response(200, [], '{"id":"task_1","status":"completed","videos":[{"url":"https://file.runapi.ai/result"}],"extra_field":"kept"}'),
        ]);
        $client = new VolcengineLipSyncClient(new ClientOptions(apiKey: 'k', httpClient: $transport, maxRetries: 0));

        $result = $client->lipSyncVideo->run([
            'model' => 'volcengine-lip-sync',
            'align_audio' => true,
            'align_audio_reverse' => true,
            'enable_vocal_separation' => true,
            'mode' => 'lite',
            'source_audio_url' => 'https://cdn.runapi.ai/public/samples/volcengine-lip-sync-voice-adam.mp3',
            'source_video_url' => 'https://cdn.runapi.ai/public/samples/volcengine-lip-sync-source.mp4',
            'template_start_seconds' => 0.5,
        ]);

        self::assertInstanceOf(CompletedVideoTaskResponse::class, $result);
        self::assertSame('https://file.runapi.ai/result', $result->videos[0]->url);
        self::assertSame('kept', $result->toArray()['extra_field']);
        self::assertSame('/api/v1/volcengine_lip_sync/lip_sync_video/task_1', $transport->requests[1]->getUri()->getPath());
    }

    public function testCompletedResponseRequiresResultFiles(): void
    {
        $transport = new QueueHttpClient([
            new Response(200, [], '{"id":"task_1"}'),
            new Response(200, [], '{"id":"task_1","status":"completed"}'),
        ]);
        $client = new VolcengineLipSyncClient(new ClientOptions(apiKey: 'k', httpClient: $transport, maxRetries: 0));

        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('videos is required');

        $client->lipSyncVideo->run([
            'model' => 'volcengine-lip-sync',
            'align_audio' => true,
            'align_audio_reverse' => true,
            'enable_vocal_separation' => true,
            'mode' => 'lite',
            'source_audio_url' => 'https://cdn.runapi.ai/public/samples/volcengine-lip-sync-voice-adam.mp3',
            'source_video_url' => 'https://cdn.runapi.ai/public/samples/volcengine-lip-sync-source.mp4',
            'template_start_seconds' => 0.5,
        ]);
    }

    public function testRejectsInvalidContractEnum(): void
    {
        $client = new VolcengineLipSyncClient(new ClientOptions(apiKey: 'k', httpClient: new QueueHttpClient([]), maxRetries: 0));

        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('mode must be one of the allowed values');

        $client->lipSyncVideo->create([
        'model' => 'volcengine-lip-sync',
        'align_audio' => true,
        'align_audio_reverse' => true,
        'enable_vocal_separation' => true,
        'source_audio_url' => 'https://cdn.runapi.ai/public/samples/volcengine-lip-sync-voice-adam.mp3',
        'source_video_url' => 'https://cdn.runapi.ai/public/samples/volcengine-lip-sync-source.mp4',
        'template_start_seconds' => 0.5,
        'mode' => 'not-valid',
        ]);
    }

    public function testSecondaryResourceUsesItsOwnPath(): void
    {
        $transport = new QueueHttpClient([
            new Response(200, [], '{"id":"task_2"}'),
        ]);
        $client = new VolcengineLipSyncClient(new ClientOptions(apiKey: 'k', httpClient: $transport, maxRetries: 0));

        $client->lipSyncVideo->create([
            'model' => 'volcengine-lip-sync',
            'align_audio' => true,
            'align_audio_reverse' => true,
            'enable_vocal_separation' => true,
            'mode' => 'lite',
            'source_audio_url' => 'https://cdn.runapi.ai/public/samples/volcengine-lip-sync-voice-adam.mp3',
            'source_video_url' => 'https://cdn.runapi.ai/public/samples/volcengine-lip-sync-source.mp4',
            'template_start_seconds' => 0.5,
        ]);

        self::assertSame('/api/v1/volcengine_lip_sync/lip_sync_video', $transport->requests[0]->getUri()->getPath());
    }
}
