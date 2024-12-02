<?php

declare(strict_types=1);

namespace Kenzer\Utility;

use ArrayAccess;
use Closure;
use Countable;
use InvalidArgumentException;
use Kenzer\Interface\Data\Arrayable;
use Kenzer\Interface\Data\Jsonable;

class AttributeBag implements Arrayable, ArrayAccess, Countable, Jsonable
{
    public function __construct(
        private array $data = []
    ) {
    }

    public static function create(array $data = [])
    {
        return new static($data);
    }

    public function has(string $key)
    {
        return array_key_exists($key, $this->data);
    }

    public function get(string $key, mixed $default = null)
    {
        return $this->has($key) ? $this->data[$key] : $default;
    }

    public function dot(string $name, mixed $default = null)
    {
        $path = explode('.', $name);
        $value = $this->data[array_shift($path)] ?? null;

        if ($value === null) {
            return $default;
        }

        foreach ($path as $key) {
            if (! isset($value[$key])) {
                return $default;
            }
            $value = $value[$key];
        }

        return $value;
    }

    public function hasDot(string $name)
    {
        $path = explode('.', $name);
        $value = $this->data[array_shift($path)] ?? null;

        return $value != null;
    }

    public function map(Closure $callback)
    {
        $this->data = ArrayHelper::map($this->toArray(), $callback);
        return $this;
    }

    public function set(string $key, mixed $value)
    {
        $this->data[$key] = $value;
    }

    public function remove(string ...$keys)
    {
        foreach ($keys as $key) {
            if ($this->has($key)) {
                unset($this->data[$key]);
            }
        }
    }

    public function getBoolean(string $key, bool $default = false) : bool
    {
        return $this->has($key) ? (bool) $this->data[$key] : $default;
    }

    public function getNumber(string $key, ?int $default = null) : ?int
    {
        return $this->has($key) ? (int) $this->data[$key] : $default;
    }

    public function offsetExists(mixed $offset) : bool
    {
        return $this->has($offset);
    }

    public function offsetGet(mixed $offset) : mixed
    {
        return $this->get($offset);
    }

    public function offsetSet(mixed $offset, mixed $value) : void
    {
        $this->set($offset, $value);
    }

    public function offsetUnset(mixed $offset) : void
    {
        $this->remove($offset);
    }

    public function count() : int
    {
        return count($this->data);
    }

    public function isEmpty() : bool
    {
        return empty($this->data);
    }

    public function hasNull(string $key)
    {
        return $this->has($key) ? $this[$key] != null : true;
    }

    public function toArray() : array
    {
        return $this->data;
    }

    public function toJson() : string
    {
        return json_encode($this->data);
    }

    public function append(string $key, mixed $data)
    {
        if (! $this->has($key)) {
            $this->set($key, [$data]);

            return;
        }

        if (! is_array($this->data[$key])) {
            throw new InvalidArgumentException("can not append an element to '$key'; '$key' is not an array");
        }

        $this->data[$key][] = $data;
    }

    public function only(string ...$keys)
    {
        $attrs = [];

        foreach ($keys as $key) {
            if ($this->has($key)) {
                $attrs[$key] = $this[$key];
            }
        }

        return $attrs;
    }

    public function extend(mixed $data)
    {
        if (is_array($data)) {
            $this->data = [...$this->data, ...$data];

            return $this;
        }

        if ($data instanceof Arrayable) {
            $this->data = [...$this->data, ...$data->toArray()];

            return $this;
        }

        throw new InvalidArgumentException('invalid extension data');
    }
}
