# Change Log
All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](http://keepachangelog.com/)
and this project adheres to [Semantic Versioning](http://semver.org/).

## [Unreleased]

### Added
- Add missing from address to preview images. 
### Changed
- Change licence to LGPLv3 to match other Phlib projects.

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
