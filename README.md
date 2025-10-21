# Latte Helper

<p>
<!-- Version Badge -->
<img src="https://img.shields.io/badge/Version-0.3.2-blue" alt="Version 0.3.2">
<!-- License Badge -->
<img src="https://img.shields.io/badge/License-GPL--3.0--or--later-40adbc" alt="License GPL-3.0-or-later">
</p>

An unofficial helper package for rendering Latte templates in Symfony applications.

## 🚀 Installation
```bash
composer require cloudbase/latte-helper
```

## 🧩 Usage

This package provides an abstract controller to simplify rendering Latte templates in Symfony. To get started, extend 
`AbstractLatteController` and use `renderTemplate()`:

```php
class IndexController extends AbstractLatteController
{
    #[Route('/', name: 'app_index')]
    public function index(): Response
    {
        return $this->renderTemplate('index.latte', [
            'appName' => 'Test',
        ]);
    }
}
```

By default, templates are loaded from your project’s /views directory. You can change this by setting the `$templateDir` 
property on your controller.

## 🏗️ Custom Template Directory

If you use a different directory, it’s recommended to create a base controller that others extend:

```php
class BaseAppController extends AbstractLatteController
{
    // Path relative to your project root
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

The example above renders `/templates/frontend/index.latte`.

`$templateDir` should always be relative to your project root.

## 💡 Template Variables

Every template automatically receives an `$app` variable - an instance of `LatteAwareApplication`.

This provides access to _some_ common Symfony features (similar to the `app` variable in Twig):

```latte
{varType CloudBase\LatteHelper\Classes\LatteAwareApplication $app}

<div n:foreach="$app->getFlashes('error') as $error" class="p-4 text-red-500 border-l-2 border-red-500">
    {$error}
</div>

<div n:if="$app->getUser()">
    <!-- User is logged in -->
</div>

<div n:if="$app->getRequestStack()->getCurrentRequest()->isMethod('POST')">
    <!-- Request is a POST request -->
</div>
```

## ⚙️ Using `$app` in Controllers

You can also access the same application instance within your controller:

```php
class IndexController extends AbstractLatteController
{
    #[Route('/', name: 'app_index')]
    public function index(): Response
    {
        $welcomeText = sprintf(
            'Welcome to %s!',
            $this->getApp()->getEnvironmentOption('app_name') ?? 'Your App'
        );
        
        return $this->renderTemplate('index', [
            'text' => $welcomeText,
        ]);
    }
}

```

## 🌐 Global Template Data

Need data available across multiple templates?

Override the `globalData()` method in your base controller:

```php
class BaseAppController extends AbstractLatteController
{
    protected string $templateDir = '/templates/frontend';
    
    protected function globalData(): array
    {
        return array_merge(
            parent::globalData(), // Keeps the $app variable
            [
                'myGlobal' => true,
            ]
        );
    }
}
```

All controllers inheriting from this class will have access to `$myGlobal` in addition to `$app` and local template 
variables.

## 🧩 Custom Latte Extensions

To register your own Latte extensions, create a `config/latte.php` file in your Symfony project.

This file should return an array of class names and their constructor arguments:

```php
<?php

return [
    App\Classes\MyCustomExtension::class => [],
];
```

This allows you to use custom Latte tags, filters, and functions throughout your templates.