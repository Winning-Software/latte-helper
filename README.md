# Latte Helper

<p>
<!-- Version Badge -->
<img src="https://img.shields.io/badge/Version-0.1.1-blue" alt="Version 0.1.1">
<!-- License Badge -->
<img src="https://img.shields.io/badge/License-GPL--3.0--or--later-40adbc" alt="License GPL-3.0-or-later">
</p>

An unofficial helper package for rendering Latte templates in Symfony apps.

---

# Installation

```shell
composer require cloudbase/latte-helper
```

# Usage

This package provides an abstract controller which you can use to render latte templates. The simplest approach is to
just extend `AbstractLatteController` and call `renderTemplate`:

```php
class IndexController extends AbstractLatteController
{
    #[Route('/', name: 'app_index')]
    public function index(): Response
    {
        return $this->renderTemplate('index.latte', [
            'app_name' => 'Test',
        ]);
    }
}
```

By default, templates will be served from your projects `/views` directory. You can change this by setting the 
`$templateDir` property on your controller. If you are serving templates from a different directory, it is recommended
to create a base controller which your other controllers can extend:

```php
class BaseAppController extends AbstractLatteController
{
    // This path should be relative to your project root.
    protected string $templateDir = '/templates/frontend';
}

class AppIndexController extends BaseAppController
{
    #[Route('/', name: 'app_index')]
    public function index(): Response
    {
        // You can omit the .latte suffix if desired
        return $this->renderTemplate('index');
    }
}
```

The above `AppIndexController` will respond with the template at `/templates/frontend/index.latte` - `$templateDir` should 
be relative to your project root.

Sometimes we might require the same data across multiple pages. You can override the `globalData` method of the `AbstractLatteController` 
to pass in anything you might need in more than one place:

```php
class BaseAppController extends AbstractLatteController
{
    protected string $templateDir = '/templates/frontend';
    
    protected function globalData(): array
    {   
        return [
            'my_global' => true,       
        ];  
    }
}
```

This controller or any of its inheritors will now make the `my_global` variable available for use in templates along 
with the data passed into `renderTemplate`.