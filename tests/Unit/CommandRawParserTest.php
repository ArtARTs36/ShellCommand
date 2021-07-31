<?php

namespace ArtARTs36\ShellCommand\Tests\Unit;

use ArtARTs36\ShellCommand\CommandRawParser;
use PHPUnit\Framework\TestCase;

class CommandRawParserTest extends TestCase
{
    public function providerForTestParse(): array
    {
        return [
            [
                'cd /var/web',
                "'cd' '/var/web' 2>&1",
            ],
            [
                'php artisan queue:work --delay=5',
                "'php' 'artisan' 'queue:work' --delay=5 2>&1",
            ],
        ];
    }

    /**
     * @dataProvider providerForTestParse
     * @covers \ArtARTs36\ShellCommand\CommandRawParser::parse
     * @covers \ArtARTs36\ShellCommand\CommandRawParser::createCommand
     */
    public function testParse(string $raw, string $prepared): void
    {
        self::assertEquals($prepared, (string) CommandRawParser::parse($raw));
    }
}
