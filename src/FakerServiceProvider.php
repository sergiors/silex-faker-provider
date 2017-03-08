<?php

namespace Marabesi\FakerServiceProvider;

use Faker\Factory;
use Pimple\ServiceProviderInterface;
use Pimple\Container;

/**
 * Faker service for silex 2
 *
 * @author Matheus Marabesi <matheus.marabesi@gmail.com>
 */
class FakerServiceProvider implements ServiceProviderInterface
{
    /**
     * {@inheritdoc}
     */
    public function register(Container $app)
    {
        $app['faker'] = Factory::create($app['locale']);
        $app['faker.providers'] = [];

        $providers = array_filter((array) $app['faker.providers'], function ($provider) {
            return class_exists($provider) && is_subclass_of($provider, 'Faker\\Provider\\Base');
        });

        foreach ($providers as $provider) {
            $app['faker']->addProvider(new $provider($app['faker']));
        }
    }
}
