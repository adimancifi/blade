# Blade Tempate Engine


### Install

```cmd
composer require adimancifi/blade
```

### Usage
```php
include "vendor/autoload.php";

$blade = new \Adimancifi\Blade\Blade(realpath(__DIR__), "cache");
echo $blade->render('view', ['nama' => 'adiman']);
```



