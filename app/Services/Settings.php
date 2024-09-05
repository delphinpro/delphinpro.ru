<?php
/*
 * Site delphinpro.ru
 * Copyright (c) 2023-2024.
 */

namespace App\Services;

use ArrayAccess;
use Illuminate\Support\Str;
use RuntimeException;

/**
 * Site settings
 *
 * @property-read bool   enableAnalytics
 * @property-read string analyticsCode
 * @property-read bool   displayComments
 * @property-read bool   enableComments
 * @property-read int    adminPaginationCount
 */
class Settings implements ArrayAccess
{
    protected null|array|int $data = null;

    protected string $filename;

    public function __construct(string $filename)
    {
        $this->filename = $filename;
        $this->load();
    }

    public function __get($name)
    {
        return $this->get($name);
    }

    public function __set($name, $value)
    {
        throw new RuntimeException('Unable to write a read-only property: '.self::class.'::'.$name);
    }

    public function __isset(string $name): bool
    {
        return array_key_exists($name, $this->data);
    }

    public function get($name, $default = null)
    {
        $method = Str::camel('get_'.$name);

        if (method_exists($this, $method)) {
            return $this->$method();
        }

        return $this->data[$name] ?? $default;
    }

    public function set(string $name, mixed $value = null): void
    {
        $this->data[$name] = $this->castValue($name, $value);
    }

    public function save(): void
    {
        $string = '<?'."php\nreturn ".var_export($this->data, true).";\n";
        file_put_contents($this->filename, $string);
    }

    public function offsetExists(mixed $offset): bool
    {
        return array_key_exists($offset, $this->data);
    }

    public function offsetGet(mixed $offset): mixed
    {
        return $this->get($offset);
    }

    public function offsetSet(mixed $offset, mixed $value): void
    {
        $this->set($offset, $value);
    }

    public function offsetUnset(mixed $offset): void
    {
        unset($this->data[$offset]);
    }

    protected function load(): void
    {
        $this->data = [];

        if (!file_exists($this->filename)) {
            $this->data = $this->getDefaultSettings();

            $string = '<?'."php\nreturn ".var_export($this->data, true).";\n";

            if (file_put_contents($this->filename, $string) === false) {
                return;
            }
        }

        $this->data = include $this->filename;

        if (!is_array($this->data)) {
            $this->data = $this->getDefaultSettings();
        } else {
            $this->data = array_merge(
                $this->getDefaultSettings(),
                $this->data
            );
        }
    }

    protected function castValue(string $name, mixed $value): mixed
    {
        return match ($name) {
            'enableAnalytics',
            'displayComments',
            'enableComments'       => (bool)$value,
            'adminPaginationCount' => (int)$value,
            default                => $value,
        };
    }

    private function getDefaultSettings(): array
    {
        return [
            'enableAnalytics'      => false,
            'analyticsCode'        => '',
            'displayComments'      => false,
            'enableComments'       => false,
            'adminPaginationCount' => 15,
        ];
    }
}
