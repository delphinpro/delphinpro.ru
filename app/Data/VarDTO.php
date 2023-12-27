<?php
/*
 * Site delphinpro.ru
 * Copyright (c) 2023.
 */

namespace App\Data;

use App\Models\Variable;
use Closure;

class VarDTO
{
    private ?array $var;

    public function __construct(string $varName)
    {
        $this->var = Variable::find($varName)?->value ?? [];
        $this->created();
    }

    public static function make(string $varName, ?Closure $prepare = null): static
    {
        return (new static($varName))
            ->prepare($prepare);
    }

    public function created(): void { }

    public function prepare(?Closure $closure): static
    {
        if ($closure) {
            $closure($this);
        }

        return $this;
    }

    public function __get(string $name)
    {
        return $this->var[$name] ?? null;
    }

    public function __set(string $name, $value): void
    {
        $this->var[$name] = $value;
    }

    public function __isset(string $name): bool
    {
        return array_key_exists($name, $this->var);
    }
}
