<?php

namespace TraitsTests;


use kalanis\kw_locks\Interfaces\IKLTranslations;
use kalanis\kw_locks\Traits\TLang;
use kalanis\kw_locks\Translations;


class LangTest extends \CommonTestClass
{
    public function testSimple(): void
    {
        $lib = new XLang();
        $this->assertNotEmpty($lib->getKlLang());
        $this->assertInstanceOf(Translations::class, $lib->getKlLang());
        $lib->setKlLang(new XTrans());
        $this->assertInstanceOf(XTrans::class, $lib->getKlLang());
        $lib->setKlLang(null);
        $this->assertInstanceOf(Translations::class, $lib->getKlLang());
    }
}


class XLang
{
    use TLang;
}


class XTrans implements IKLTranslations
{
    public function iklLockedByOther(): string
    {
        return 'mock';
    }

    public function iklProblemWithStorage(): string
    {
        return 'mock';
    }

    public function iklCannotUseFile(string $lockFilename): string
    {
        return 'mock';
    }

    public function iklCannotUsePath(string $path): string
    {
        return 'mock';
    }

    public function iklCannotOpenFile(string $lockFilename): string
    {
        return 'mock';
    }

    public function iklCannotUseOS(): string
    {
        return 'mock';
    }
}
