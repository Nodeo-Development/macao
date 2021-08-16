<?php

/*
 * Copyright (c) Nodeo - All Rights Reserved.
 * Unauthorized copying of this file, via any medium, is strictly prohibited.
 */

namespace Test\DI;

use Macao\DI\ContainerBuilder;
use Macao\DI\Exceptions\DependencyNotFoundException;
use Macao\DI\Exceptions\UnresolvableDependencyException;
use PHPUnit\Framework\TestCase;

class DITest extends TestCase
{
    public function testAutoWiring()
    {
        $container = ContainerBuilder::new(new MockBinder())
            ->withAutoWiring()
            ->build();

        $d1 = $container->get(D::class);
        $d2 = $container->get(D::class);

        self::assertFalse($d1 === $d2);
        self::assertEquals($d1, $d2);
    }

    public function testBindScalar()
    {
        $container = ContainerBuilder::new(new MockBinder())
            ->build();

        self::assertEquals('localhost', $container->get('DB_HOST'));
        self::assertEquals(3309, $container->get('DB_PORT'));
    }

    public function testBindScalarOnClass()
    {
        $container = ContainerBuilder::new(new MockBinder())
            ->withAutoWiring()
            ->build();

        self::assertEquals(
            $container->get('DB_HOST'),
            $container->get(F::class)->getDBHOST()
        );
    }

    public function testNamedScalarOnClass()
    {
        $container = ContainerBuilder::new(new MockBinder())
            ->withAutoWiring()
            ->build();

        self::assertEquals(
            'localhost',
            $container->get(G::class)->getDatabaseHost()
        );
    }

    public function testBindInstance()
    {
        $b1 = new B();
        $binder = new MockBinder($b1);

        $container = ContainerBuilder::new($binder)
            ->withAutoWiring()
            ->build();

        $b2 = $container->get(B::class);
        $b3 = $container->get(B::class);

        self::assertEquals('Hello from B !', $b1->sayHello());

        $d = $container->get(D::class);

        $b4 = $d->getA()->getB();

        self::assertTrue($b1 === $b2 && $b2 === $b3 && $b3 === $b4);
    }

    public function testBindSingleton()
    {
        $container = ContainerBuilder::new(new MockBinder())
            ->withAutoWiring()
            ->build();

        $a1 = $container->get(IA::class);
        $a2 = $container->get(IA::class);

        self::assertInstanceOf(IA::class, $a1);
        self::assertInstanceOf(A::class, $a1);

        $d = $container->get(D::class);

        $a3 = $d->getA();

        self::assertTrue($a1 === $a2 && $a2 === $a3);
    }

    public function testRaisesNotFoundExceptionWithoutAutoWiring()
    {
        $this->expectException(DependencyNotFoundException::class);

        $container = ContainerBuilder::new(new MockBinder())
            ->build();

        $container->get(D::class);
    }

    public function testRaisesUnresolvableExceptionWithoutAutoWiring()
    {
        $this->expectException(UnresolvableDependencyException::class);

        $container = ContainerBuilder::new(new MockBinder())
            ->build();

        self::assertTrue($container->has(IE::class));

        $container->get(IE::class); // IE is resolvable but
        // autoWiring is turned off and D was not bound
    }

    public function testRaisesExceptionOnCircularDependency()
    {
        $this->expectException(UnresolvableDependencyException::class);

        $container = ContainerBuilder::new(new MockBinder())
            ->withAutoWiring()
            ->build();

        $container->get(H::class);
    }
}
