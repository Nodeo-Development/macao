<?php

/*
 * Copyright (c) Nodeo - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Written by Juan <juan.valero@nodeo.fr>, 2021
 */

namespace Macao\DI;

use JetBrains\PhpStorm\ArrayShape;
use JetBrains\PhpStorm\Pure;

class ContainerBuilder
{
    private array $binders;

    private bool $autoWiring = false;

    #[Pure]
    private function __construct(AbstractBinder ...$binder)
    {
        $this->binders = $binder;
    }

    #[Pure]
    public static function new(AbstractBinder $binder): self
    {
        return new ContainerBuilder($binder);
    }

    public function withAutoWiring(): self
    {
        $this->autoWiring = true;

        return $this;
    }

    public function build(): ContainerImpl
    {
        $bindings = [];

        foreach ($this->binders as $binder) {
            $binder->configure();
            $bindings += $binder->getBindings();
        }

        return new ContainerImpl(
            $bindings,
            $this->getOptions()
        );
    }

    #[ArrayShape(['autoWiring' => 'bool'])]
    #[Pure]
    private function getOptions(): array
    {
        return [
            'autoWiring' => $this->autoWiring
        ];
    }
}
