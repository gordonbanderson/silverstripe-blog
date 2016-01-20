# SilverStripe Blog Module
[![Build Status](https://travis-ci.org/gordonbanderson/silverstripe-blog.svg?branch=coverage)](https://travis-ci.org/gordonbanderson/silverstripe-blog)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/gordonbanderson/silverstripe-blog/badges/quality-score.png?b=coverage)](https://scrutinizer-ci.com/g/gordonbanderson/silverstripe-blog/?branch=coverage)
[![codecov.io](https://codecov.io/github/gordonbanderson/silverstripe-blog/coverage.svg?branch=coverage)](https://codecov.io/github/gordonbanderson/silverstripe-blog?branch=coverage)

[![Latest Stable Version](https://poser.pugx.org/silverstripe/blog/version)](https://packagist.org/packages/silverstripe/blog)
[![Latest Unstable Version](https://poser.pugx.org/silverstripe/blog/v/unstable)](//packagist.org/packages/silverstripe/blog)
[![Total Downloads](https://poser.pugx.org/silverstripe/blog/downloads)](https://packagist.org/packages/silverstripe/blog)
[![License](https://poser.pugx.org/silverstripe/blog/license)](https://packagist.org/packages/silverstripe/blog)
[![Monthly Downloads](https://poser.pugx.org/silverstripe/blog/d/monthly)](https://packagist.org/packages/silverstripe/blog)
[![Daily Downloads](https://poser.pugx.org/silverstripe/blog/d/daily)](https://packagist.org/packages/silverstripe/blog)

[![Dependency Status](https://www.versioneye.com/php/silverstripe:blog/badge.svg)](https://www.versioneye.com/php/silverstripe:blog)
[![Reference Status](https://www.versioneye.com/php/silverstripe:blog/reference_badge.svg?style=flat)](https://www.versioneye.com/php/silverstripe:blog/references)

![codecov.io](https://codecov.io/github/gordonbanderson/silverstripe-blog/branch.svg?branch=coverage)


## Documentation
[User guide](docs/en/userguide/index.md)

[Developer documentation](docs/en/index.md)

## Requirements

```
silverstripe/cms: ~3.1
silverstripe/lumberjack: ~1.1
silverstripe/tagfield: ^1.0
```

### Suggested Modules

```
silverstripe/widgets: *
silverstripe/comments: *
```

## Installation

```
composer require silverstripe/blog 2.0.x-dev
```

## Upgrading legacy blog to 2.0

If you're upgrading from blog version 1.0 to 2.0 you will need to run the `BlogMigrationTask`. Run the task using `dev/tasks/BlogMigrationTask` either via the browser or sake CLI to migrate your legacy blog to the new version data structure.


