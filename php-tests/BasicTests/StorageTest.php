<?php

namespace BasicTests;


use CommonTestClass;
use kalanis\kw_locks\Interfaces\IPassedKey;
use kalanis\kw_locks\LockException;
use kalanis\kw_locks\Methods\StorageLock;
use kalanis\kw_storage\Interfaces\IStorage;
use kalanis\kw_storage\StorageException;
use Traversable;


class StorageTest extends CommonTestClass
{
    /**
     * @throws LockException
     */
    public function testInit(): void
    {
        $lib = $this->getPassLib();
        $lib->setKey('current');
        $this->assertFalse($lib->has()); // nothing now
        $this->assertTrue($lib->create()); // new one
        $this->assertFalse($lib->create()); // already has
        $this->assertTrue($lib->has());
        $this->assertTrue($lib->delete()); // delete current
        $this->assertFalse($lib->has()); // nothing here
    }

    /**
     * @throws LockException
     */
    public function testLockAnother(): void
    {
        $lib = $this->getPassLib();
        $lib->setKey('current');
        $this->assertFalse($lib->has()); // nothing now
        $this->assertTrue($lib->create()); // new one
        $this->assertFalse($lib->create()); // already has
        $lib->setKey('current', 'other value');
        $this->expectException(LockException::class);
        $lib->has(); // will fail - different value to compare
    }

    /**
     * @throws LockException
     */
    public function testCreateFail(): void
    {
        $lib = $this->getFailLib();
        $lib->setKey('will fail');
        $this->expectException(LockException::class);
        $lib->create(true);
    }

    /**
     * @throws LockException
     */
    public function testDeleteFail(): void
    {
        $lib = $this->getFailLib();
        $lib->setKey('will fail');
        $this->expectException(LockException::class);
        $lib->delete(true);
    }

    /**
     * @throws LockException
     */
    public function testDestructFail(): void
    {
        $lib = $this->getFailLib();
        $lib->setKey('will fail');
        $this->assertTrue(true); // just for pass test and do not shout something about risky
    }

    protected function getPassLib(): IPassedKey
    {
        return new StorageLock(new \XStorage());
    }

    protected function getFailLib(): IPassedKey
    {
        return new StorageLock(new XFallStorage());
    }
}


class XFallStorage implements IStorage
{
    public function canUse(): bool
    {
        return false;
    }

    public function write(string $sharedKey, $data, ?int $timeout = null): bool
    {
        throw new StorageException('mock');
    }

    public function read(string $sharedKey)
    {
        throw new StorageException('mock');
    }

    public function remove(string $sharedKey): bool
    {
        throw new StorageException('mock');
    }

    public function exists(string $sharedKey): bool
    {
        throw new StorageException('mock');
    }

    public function lookup(string $mask): Traversable
    {
        throw new StorageException('mock');
    }

    public function increment(string $key): bool
    {
        throw new StorageException('mock');
    }

    public function decrement(string $key): bool
    {
        throw new StorageException('mock');
    }

    public function removeMulti(array $keys): array
    {
        throw new StorageException('mock');
    }
}
