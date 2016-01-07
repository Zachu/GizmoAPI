# GizmoAPI
Gizmo API wrapper for PHP.

## Installation

1. Install composer

On Linux / Unix / OSX: `curl -sS https://getcomposer.org/installer | php`

On Windows: https://getcomposer.org/Composer-Setup.exe

Or follow instructions on https://getcomposer.org/doc/00-intro.md

2. Install the package

```
composer require pisa\gizmo-api
```

Since currently there are no stable releases, you have to accept the release candidates:
```
composer require pisa\gizmo-api:"^1.0@RC"
```

## Usage
```php
<?php
require_once 'vendor/autoload.php';
use Pisa\GizmoAPI\Gizmo;

$gizmo = new Gizmo([
    'http' => [
        'base_uri' => 'http://url_to_gizmo_api_here:8080',
        'auth'     => ['username', 'password'],
    ],
]);

$host = $gizmo->hosts->get(1); // Gets the host model with id 1

$user = $gizmo->users->get(1); // Gets the user model with id 1
```

The full API documentation at [wiki](https://github.com/Zachu/GizmoAPI/wiki/ApiIndex)

## History
TODO: Write history

## Credits
`Jani "Zachu" Korhonen <jani.korhonen@hel.fi>`