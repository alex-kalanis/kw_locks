<?php

namespace BasicTests;


use CommonTestClass;
use kalanis\kw_locks\Interfaces\IPassedKey;
use kalanis\kw_locks\LockException;
use kalanis\kw_locks\Methods\SemaphoreLock;
use kalanis\kw_semaphore\Interfaces\ISemaphore;
use kalanis\kw_semaphore\SemaphoreException;


class SemaphoreTest extends CommonTestClass
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
        return new SemaphoreLock(new XSemaphore());
    }

    protected function getFailLib(): IPassedKey
    {
        return new SemaphoreLock(new XFSemaphore());
    }
}


class XSemaphore implements ISemaphore
{
    protected $semaphore = false;

    public function want(): bool
    {
        $this->semaphore = true;
        return true;
    }

    public function has(): bool
    {
        return $this->semaphore;
    }

    public function remove(): bool
    {
        $this->semaphore = false;
        return true;
    }
}


class XFSemaphore implements ISemaphore
{
    public function want(): bool
    {
        throw new SemaphoreException('mock');
    }

    public function has(): bool
    {
        throw new SemaphoreException('mock');
    }

    public function remove(): bool
    {
        throw new SemaphoreException('mock');
    }
}
