# GizmoAPI
Gizmo API wrapper for PHP.

## Installation
TODO: Describe the installation process

## Usage
```php
require_once 'vendor/autoload.php';
use Pisa\GizmoAPI\Gizmo;

$gizmo = new Gizmo([
    'http' => [
        'base_uri' => 'http://url_to_gizmo_api_here:8080',
        'auth'     => ['username', 'password'],
    ],
]);

$host = $gizmo->hosts->get(1);

$user = $gizmo->users->get(1);
```

The full API documentation at [wiki](/wikis/ApiIndex)

## History
TODO: Write history

## Credits
`Jani "Zachu" Korhonen <jani.korhonen@hel.fi>`

## License
The MIT License (MIT)

Copyright (c) 2015 The City of Helsinki, Youth Department, Pelitalo Maximum Gaming, 



Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:



The above copyright notice and this permission notice shall be included in
all copies or substantial portions of the Software.



THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT.  IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
THE SOFTWARE.