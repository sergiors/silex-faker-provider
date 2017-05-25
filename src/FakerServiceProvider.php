<?php

namespace Marabesi\FakerServiceProvider;

use Faker\Factory;
use Faker\Provider\Base as Faker;
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
        $app['faker'] = function (Container $app) {
            $faker = Factory::create($app['locale']);

            $providers = array_filter((array) $app['faker.providers'], function ($provider) {
                return class_exists($provider) && is_subclass_of($provider, Faker::class);
            });

            foreach ($providers as $provider) {
                $faker->addProvider(new $provider($faker));
            }

            return $faker;
        };

        $app['faker.providers'] = [];
    }
}
