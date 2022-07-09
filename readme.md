# Inline SVG Icons For Laravel Blade


<a href="https://github.com/orchidsoftware/blade-icons/actions"><img src="https://github.com/orchidsoftware/blade-icons/workflows/Tests/badge.svg"></a>
<a href="https://packagist.org/packages/orchid/blade-icons"><img alt="Packagist" src="https://img.shields.io/packagist/dt/orchid/blade-icons.svg"></a>
<a href="https://packagist.org/packages/orchid/blade-icons"><img alt="Packagist Version" src="https://img.shields.io/packagist/v/orchid/blade-icons.svg"></a>


## Introduction

This is a package for the Laravel framework that allows 
you to use the Blade component to insert inline SVG images.

## Installation

Run this at the command line:
```php
$ composer require orchid/blade-icons
```
This will update `composer.json` and install the package into the `vendor/` directory.

## Base Usage

Register a directory with your files in the service provider:
```php
namespace App\Providers;

use Orchid\Icons\IconFinder;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function boot(IconFinder $iconFinder) : void
    {
        $iconFinder->registerIconDirectory('fa', storage_path('app/fontawesome'));
    }
}
```

When calling the directory method with the first argument, we pass the prefix to call our icons and the second directory where they are located.

After that, we can call the component in our blade templates:

```blade
<x-orchid-icon path="fa.home" />
```

If you use one or two sets of icons that do not repeat, then it is not necessary to specify a prefix in the component:

```blade
<x-orchid-icon path="home" />
```

You can also list some attributes that should be applied to your icon:

```blade
<x-orchid-icon 
    path="home" 
    class="icon-big" 
    width="2em" 
    height="2em" />
```

### Default Sizes

If you are using icons from the same set, it makes sense to specify a default size value:

```php
namespace App\Providers;

use Orchid\Icons\IconFinder;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function boot(IconFinder $iconFinder) : void
    {
        $iconFinder
            ->registerIconDirectory('fa', storage_path('app/fontawesome'))
            ->setSize('54px', '54px');
    }
}
```

If you use different sets, for example, in the public part of the application and in the admin panel, then you can dynamically change the value in the middleware:

```php
<?php
 
namespace App\Http\Middleware;
 
use Closure;
 use Orchid\Icons\IconFinder;
 
class ExampleMiddleware
{
    /**
     * @var \Orchid\Icons\IconFinder 
     */
    protected $iconFinder;

    /**
     * ExampleMiddleware constructor.
     *
     * @param IconFinder $iconFinder
     */
    public function __construct(IconFinder $iconFinder)
    {
        $this->iconFinder = $iconFinder;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    { 
        $iconFinder->setSize('54px', '54px');

        return $next($request);
    }
}
```

## License

The MIT License (MIT). Please see [License File](license.md) for more information.

