<?php

namespace Spectator\Tests;

use Spectator\RequestFactory;
use cebe\openapi\spec\OpenApi;
use Illuminate\Support\Facades\Config;
use Spectator\Exceptions\MissingSpecException;

class RequestFactoryTest extends TestCase
{
    public function test_sets_and_gets_spec_name()
    {
        $name = 'Test.v1.json';

        $factory = new RequestFactory();

        $factory->using($name);

        $this->assertSame($name, $factory->getSpec());
    }

    public function test_resets_spec_name()
    {
        $name = 'Test.v1.json';

        $factory = new RequestFactory();

        $factory->using($name);

        $factory->reset();

        $this->assertNull($factory->getSpec());
    }

    public function test_resolves_yaml_spec()
    {
        $name = 'Test.v1.yaml';

        $factory = new RequestFactory();

        $factory->using($name);

        $spec = $factory->resolve();

        $this->assertInstanceOf(OpenApi::class, $spec);
        $this->assertSame('Test.v1', $spec->info->title);
    }

    public function test_resolves_json_spec()
    {
        $name = 'Test.v1.json';

        $factory = new RequestFactory();

        $factory->using($name);

        $spec = $factory->resolve();

        $this->assertInstanceOf(OpenApi::class, $spec);
        $this->assertSame('Test.v1', $spec->info->title);
    }

    public function test_throws_exception_on_invalid_source()
    {
        $this->expectException(MissingSpecException::class);
        $this->expectExceptionMessage('A valid spec source must be defined.');

        Config::set('spectator.default', 'invalid');

        $name = 'Test.v1.json';

        $factory = new RequestFactory();

        $factory->using($name);

        $spec = $factory->resolve();
    }

    public function test_throws_exception_on_missing_spec_name()
    {
        $this->expectException(MissingSpecException::class);
        $this->expectExceptionMessage('Cannot resolve schema without target spec.');

        $factory = new RequestFactory();

        $spec = $factory->resolve();
    }

    public function test_throws_exception_on_invalid_spec_name()
    {
        $this->expectException(MissingSpecException::class);
        $this->expectExceptionMessage('A valid spec source must be defined.');

        $name = 'Missing.v1.json';

        $factory = new RequestFactory();

        $factory->using($name);

        $spec = $factory->resolve();
    }

    public function test_throws_exception_on_invalid_spec_extension()
    {
        $this->expectException(MissingSpecException::class);
        $this->expectExceptionMessage('The spec source was invalid.');

        $name = 'Invalid.v1.txt';

        $factory = new RequestFactory();

        $factory->using($name);

        $spec = $factory->resolve();
    }
}
