<?php

namespace BasicTests;


use CommonTestClass;
use kalanis\kw_files\Access\Factory;
use kalanis\kw_files\FilesException;
use kalanis\kw_locks\Interfaces\IPassedKey;
use kalanis\kw_locks\LockException;
use kalanis\kw_locks\Methods\FilesLock;
use kalanis\kw_paths\PathsException;
use kalanis\kw_storage\Interfaces\IStorage;
use kalanis\kw_storage\StorageException;
use Traversable;


class FilesTest extends CommonTestClass
{
    /**
     * @throws FilesException
     * @throws LockException
     * @throws PathsException
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
     * @throws FilesException
     * @throws LockException
     * @throws PathsException
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
     * @throws FilesException
     * @throws LockException
     * @throws PathsException
     */
    public function testCreateFail(): void
    {
        $lib = $this->getFailLib();
        $lib->setKey('will fail');
        $this->expectException(LockException::class);
        $lib->create(true);
    }

    /**
     * @throws FilesException
     * @throws LockException
     * @throws PathsException
     */
    public function testDeleteFail(): void
    {
        $lib = $this->getFailLib();
        $lib->setKey('will fail');
        $this->expectException(LockException::class);
        $lib->delete(true);
    }

    /**
     * @throws FilesException
     * @throws LockException
     * @throws PathsException
     */
    public function testDestructFail(): void
    {
        $lib = $this->getFailLib();
        $lib->setKey('will fail');
        $this->assertTrue(true); // just for pass test and do not shout something about risky
    }

    /**
     * @throws FilesException
     * @throws PathsException
     * @return IPassedKey
     */
    protected function getPassLib(): IPassedKey
    {
        return new FilesLock((new Factory())->getClass(new \XStorage()));
    }

    /**
     * @throws FilesException
     * @throws PathsException
     * @return IPassedKey
     */
    protected function getFailLib(): IPassedKey
    {
        return new FilesLock((new Factory())->getClass(new XFFallStorage()));
    }
}


class XFFallStorage implements IStorage
{
    public function canUse(): bool
    {
        return false;
    }

    public function isFlat(): bool
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
