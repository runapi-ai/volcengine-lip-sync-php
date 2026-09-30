# Changelog

## [v0.2.0](https://github.com/runapi-ai/volcengine-lip-sync-php/releases/tag/v0.2.0) - 2026-09-30

### Changed
- Send request parameters to the service without local validation. Model ids and parameter values the service supports work without an SDK upgrade; static types and enum constants remain for completion.
  Migration: Invalid parameters now throw `ValidationException` built from the service's 400 response, including its status and message, instead of a `ValidationException` thrown locally before the request.


## [v0.1.1](https://github.com/runapi-ai/volcengine-lip-sync-php/releases/tag/v0.1.1) - 2026-07-16

### Changed
- Add the Volcengine Lip Sync Composer package for PHP applications.
