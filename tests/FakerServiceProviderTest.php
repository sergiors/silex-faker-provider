<?php

namespace Marabesi\FakerServiceProvider\Tests;

use Marabesi\FakerServiceProvider\FakerServiceProvider;
use PHPUnit_Framework_TestCase;
use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Faker\Provider\ro_RO\Address;
use CompanyNameGenerator\FakerProvider;

/**
 * @author Matheus Marabesi <matheus.marabesi@gmail.com>
 *
 * @coversDefaultClass \Marabesi\FakerServiceProvider\FakerServiceProvider
 */
class FakerServiceProviderTest extends PHPUnit_Framework_TestCase
{
    /**
     * @covers ::register
     */
    public function testRegisterServiceProvider()
    {
        $app = new Application();
        $app->register(new FakerServiceProvider(), [
            'locale' => 'ro_RO',
        ]);
        $app->boot();

        $this->assertInstanceOf('Faker\\Generator', $app['faker']);
        $this->assertContainsOnlyInstancesOf('Faker\\Provider\\Base', $app['faker']->getProviders());

        $this->assertNotEmpty(array_filter($app['faker']->getProviders(), function ($provider) {
            return $provider instanceof Address;
        }));
    }

    public function testServiceProviders()
    {
        $app = new Application();
        $app->register(new FakerServiceProvider(), [
            'faker.providers' => [
                'CompanyNameGenerator\\FakerProvider',
            ],
        ]);
        $app->boot();

        $this->assertInstanceOf('Faker\\Generator', $app['faker']);

        $this->assertCount(2, $app['faker.providers']);
        $this->assertContainsOnlyInstancesOf('Faker\\Provider\\Base', $app['faker']->getProviders());

        $this->assertNotEmpty(array_filter($app['faker']->getProviders(), function ($provider) {
            return $provider instanceof FakerProvider;
        }));

        $this->assertSame('http://placehold.it/50.gif', $app['faker']->imageUrl(50));
    }

    public function testRequest()
    {
        $app = new Application();
        $app->register(new FakerServiceProvider());

        $app->get('/', function () use ($app) {
            return $app['faker']->randomDigit;
        });

        $request = Request::create('/');
        $response = $app->handle($request);

        $this->assertTrue($response->isOk());
        $this->assertRegExp('/\d+/', $response->getContent());
    }
}
