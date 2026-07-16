# Volcengine Lip Sync PHP SDK for RunAPI

[![Packagist](https://img.shields.io/packagist/v/runapi-ai/volcengine-lip-sync)](https://packagist.org/packages/runapi-ai/volcengine-lip-sync)
[![License](https://img.shields.io/github/license/runapi-ai/volcengine-lip-sync-php)](https://github.com/runapi-ai/volcengine-lip-sync-php/blob/main/LICENSE)

The Volcengine Lip Sync PHP SDK is the Composer package for Volcengine Lip Sync
on RunAPI. Use it when your PHP application needs associative-array request
bodies, task status lookup, polling helpers, file helpers, and consistent RunAPI
errors.

This README is the PHP package guide for the public `volcengine-lip-sync-php` split
repository. For model details, use https://runapi.ai/models/volcengine-lip-sync; for API
reference, use https://runapi.ai/docs#volcengine-lip-sync; for SDK docs, use
https://runapi.ai/docs#sdk-volcengine-lip-sync.

## Install

```bash
composer require runapi-ai/volcengine-lip-sync
```

## Quick start

```php
<?php

require __DIR__ . "/vendor/autoload.php";

use RunApi\VolcengineLipSync\VolcengineLipSyncClient;

$client = new VolcengineLipSyncClient(); // reads RUNAPI_API_KEY

$task = $client->lipSyncVideo->create([
    'model' => 'volcengine-lip-sync',
    'align_audio' => true,
    'align_audio_reverse' => true,
    'enable_vocal_separation' => true,
    'mode' => 'lite',
    'source_audio_url' => 'https://cdn.runapi.ai/public/samples/volcengine-lip-sync-voice-adam.mp3',
    'source_video_url' => 'https://cdn.runapi.ai/public/samples/volcengine-lip-sync-source.mp4',
    'template_start_seconds' => 0.5,
]);

$status = $client->lipSyncVideo->get($task->id);

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

echo $result->videos[0]->url . PHP_EOL;
```

Use `create()` to submit a task and return quickly, `get()` to fetch the latest
task state, and `run()` when a script should create and poll until completion.
In web request handlers, prefer `create()` plus webhook or later `get()`
polling so a worker is not held open.

RunAPI-generated file URLs are temporary. Download and store generated files
in your own durable storage within the retention window; do not treat returned
URLs as long-term assets.

## Language notes

Pass request parameters as associative arrays with snake_case keys. The
available resources are `lipSyncVideo`. Keep `RUNAPI_API_KEY` in the environment
or your secret manager; never commit API keys or callback secrets.

## Links

- Model page: https://runapi.ai/models/volcengine-lip-sync
- SDK docs: https://runapi.ai/docs#sdk-volcengine-lip-sync
- Product docs: https://runapi.ai/docs#volcengine-lip-sync
- Pricing and rate limits: https://runapi.ai/models/volcengine-lip-sync
- Full catalog: https://runapi.ai/models
- GitHub repository: https://github.com/runapi-ai/volcengine-lip-sync-php
- Multi-language SDK repository: https://github.com/runapi-ai/volcengine-lip-sync-sdk

## License

Licensed under the Apache License, Version 2.0.
