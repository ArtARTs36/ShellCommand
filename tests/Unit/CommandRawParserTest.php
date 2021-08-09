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
                "'cd' '/var/web'",
            ],
            [
                'php artisan queue:work --delay=5',
                "'php' 'artisan' 'queue:work' --delay=5",
            ],
            [
                'php artisan -cut --full',
                "'php' 'artisan' -cut --full",
            ],
            [
                'php artisan queue:work --queue="app 1" --step=2',
                "'php' 'artisan' 'queue:work' --queue=\"app 1\" --step=2",
            ],
            [
                'php && web -result="one-love"',
                "'php' && 'web' -result=\"one-love\"",
            ],
            [
                '',
                '',
            ],
        ];
    }

    /**
     * @dataProvider providerForTestParse
     * @covers \ArtARTs36\ShellCommand\CommandRawParser::createCommand
     * @covers \ArtARTs36\ShellCommand\CommandRawParser::parseRawExpression
     */
    public function testParse(string $raw, string $prepared): void
    {
        $parser = new CommandRawParser();

        self::assertEquals($prepared, (string) $parser->createCommand($raw));
    }
}
