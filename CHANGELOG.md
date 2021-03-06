# Change Log
All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](http://keepachangelog.com/)
and this project adheres to [Semantic Versioning](http://semver.org/).

## [Unreleased]

## [3.0.0] - 2021-05-23

### Added
- Add specific support for PHP v7 and v8 (`^7.3 | ^8.0`).
- Add dependency requirement for `ext-mbstring`.
- Add missing from address to preview images.
- Add type declarations for parameters and return types.
- Add `EmailClient::getInboxUrl()` and `EmailClient::getToastUrl()` for clear
  method signatures, requiring an instance of `SubjectPreview` as the param.
- Add `with*` methods to `SubjectPreview` to create a modified new instance.
### Changed
- **BC break**: Namespace changed to `Phlib\LitmusSubjectPreview`.
- **BC break**: `SubjectPreview` is immutable, requiring constructor parameters.
- **BC break**: `EmailClient::hasInstance()` renamed to `EmailClient::create()` 
  to make it clearer that it returns a new instance, not a singleton.
- Change licence to LGPLv3 to match other Phlib projects.
- Unknown email client throws `DomainException` in
  `EmailClient::getInstance()` instead of generic `Exception`.
- `EmailClient::setHasToast()` returns self instead of the given value, to
  match fluent behaviour of other setters.
- Strings for subject/body/sender are multi-byte safe when truncated.
  Newlines are removed.
- `EmailClient` which does not support toast returns an empty array for 
  `getToastSize()` instead of `null`.
### Removed
- **BC break**: Removed support for PHP versions <= 7.2 as they are no longer
  [actively supported](https://php.net/supported-versions.php)
  by the PHP project.
- **BC break**: Removed `SubjectPreview::getEndpoint()`.
- **BC break**: Removed `SubjectPreview::getEmailClient()`.
  Use `EmailClient::getInstance()`.
- **BC break**: Removed `EmailClient::setSubjectPreview()`.
- **BC break**: Removed `EmailClient::getUrl()`.
  Use `getInboxUrl()` or `getToastUrl()`.
- **BC break**: Removed setters from `SubjectPreview` to give an immutable 
  object using constructor params. Modify an existing subject using `with*` 
  methods.
- **BC break**: Removed setters from `EmailClient` to give an immutable object
  from pre-defined values for each client.

## [2.0.1] - 2021-04-03

- Import subject line preview elements from
  [`phlib/litmus-reseller-sdk`](https://github.com/phlib/litmus-reseller-sdk),
  keeping SemVer compatibility with original
  [`yzalis/litmus`](https://packagist.org/packages/yzalis/litmus).
  This version SHOULD NOT be installed alongside v2.x of
  `phlib/litmus-reseller-sdk` as it contains the same classes.
  This version is for implmentations that want to continue to use these
  features without the Reseller SDK, by simply changing the package name.
  The next major version will be fully split from `phlib/litmus-reseller-sdk`.
- Update maintainer info to Phlib.
  Thanks to [blaugueux](https://github.com/blaugueux)!
