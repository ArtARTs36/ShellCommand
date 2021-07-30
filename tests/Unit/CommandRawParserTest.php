<?php

namespace ArtARTs36\ShellCommand\Tests\Unit;

use ArtARTs36\ShellCommand\CommandRawParser;
use PHPUnit\Framework\TestCase;

/**
 * Class RawParserTest
 * @package ArtARTs36\ShellCommand\Tests\Unit
 */
class CommandRawParserTest extends TestCase
{
    /**
     * @covers \ArtARTs36\ShellCommand\CommandRawParser::parse
     */
    public function testParse(): void
    {
        $raw = 'cd /var/web';

        $command = CommandRawParser::parse($raw);

        self::assertEquals("'cd' '/var/web' 2>&1", $command->__toString());

        //

        $raw = 'php artisan queue:work --delay=5';

        $command = CommandRawParser::parse($raw);

        self::assertEquals("'php' 'artisan' 'queue:work' --delay=5 2>&1", $command->__toString());
    }
}
