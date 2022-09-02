<?php

use kalanis\kw_storage\Interfaces\IStorage;
use PHPUnit\Framework\TestCase;


/**
 * Class CommonTestClass
 * The structure for mocking and configuration seems so complicated, but it's necessary to let it be totally idiot-proof
 */
abstract class CommonTestClass extends TestCase
{
}


class XStorage implements IStorage
{
    /** @var string */
    protected $storedData = '';

    public function canUse(): bool
    {
        return true;
    }

    public function write(string $sharedKey, $data, ?int $timeout = null): bool
    {
        if ('off' !== $sharedKey) {
            $this->storedData = $data;
            return true;
        } else {
            return false;
        }
    }

    public function read(string $sharedKey)
    {
        if ('off' !== $sharedKey) {
            return $this->storedData;
        } else {
            return 'mocked';
        }
    }

    public function remove(string $sharedKey): bool
    {
        $this->storedData = '';
        return 'off' !== $sharedKey;
    }

    public function exists(string $sharedKey): bool
    {
        return ('off' !== $sharedKey) ? !empty($this->storedData) : false;
    }

    public function lookup(string $mask): Traversable
    {
        yield from [];
    }

    public function increment(string $key): bool
    {
        return 'off' !== $key;
    }

    public function decrement(string $key): bool
    {
        return 'off' !== $key;
    }

    public function removeMulti(array $keys): array
    {
        return [];
    }
}
