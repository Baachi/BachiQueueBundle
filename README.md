# Queue bundle

[![Build Status](https://secure.travis-ci.org/Baachi/CouchDB.png?branch=master)](http://travis-ci.org/Baachi/BachiQueueBundle)

## Install ##

Add it to your composer.json file

```
{
    "require": {
        "bachi/queue-bundle": "*"
    }
}
```

## Usage ##

```php
<?php

$jobs = $this->container->get('bachi.queue_manager')->get('main')->retrieve(5);
foreach ($jobs as $job) {
    $job->doSomething();
}
```

### Storages ###

 * Array
 * Filesystem
 * Redis
 * RDBMS

## Licence ##

This bundle is licenced under the MIT licence.
For more information read the full licence.