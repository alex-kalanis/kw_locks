<?php

namespace BasicTests;


use CommonTestClass;
use kalanis\kw_locks\LockException;
use kalanis\kw_locks\Methods\ClassLock;
use kalanis\kw_locks\Methods\StorageLock;
use kalanis\kw_storage\Storage\Target\Memory;


class ClassTest extends CommonTestClass
{
    /**
     * @throws LockException
     */
    public function testInit(): void
    {
        $lib = $this->getPassLib();
        $lib->setClass(new Memory()); // not need too many things
        $this->assertFalse($lib->has()); // nothing now
        $this->assertTrue($lib->create()); // new one
        $this->assertFalse($lib->create()); // already has
        $this->assertTrue($lib->has());
        $this->assertTrue($lib->delete()); // delete current
        $this->assertFalse($lib->has()); // nothing here
    }

    protected function getPassLib(): ClassLock
    {
        return new ClassLock(new StorageLock(new \XStorage()));
    }
}
