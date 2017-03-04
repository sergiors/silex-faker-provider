Faker Service Provider
======================

A [Faker](https://github.com/fzaninotto/Faker) service provider for [Silex 2](http://silex.sensiolabs.org/), inspired in [EmanueleMinotto/FakerServiceProvider](https://github.com/EmanueleMinotto/FakerServiceProvider)

## How to instal

Install Silex using [Composer](http://getcomposer.org/). Install the FakerServiceProvider adding `marabesi/faker-service-provider` to your composer.json

```
$ composer require marabesi/faker-service-provider
```

## Usage

```php
use Marabesi\FakerServiceProvider\FakerServiceProvider;

$app['locale'] = 'it_IT';

$app->register(new FakerServiceProvider(), array(
    'faker.providers' => array(
        'CompanyNameGenerator\\FakerProvider',
       // add here the provider that you need
    ),
));
```

Start to use it

```php
$app->get('/hello', function () use ($app) {
    return 'Hello ' . $app['faker']->name;
});
```
