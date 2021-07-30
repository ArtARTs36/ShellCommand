ShellCommand
----

![PHP Composer](https://github.com/ArtARTs36/ShellCommand/workflows/Testing/badge.svg?branch=master)
[![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg)](https://opensource.org/licenses/MIT)
<a href="https://poser.pugx.org/artarts36/pushall-sender/d/total.svg">
    <img src="https://poser.pugx.org/artarts36/shell-command/d/total.svg" alt="Total Downloads">
</a>

----

### Installation:

`composer require artarts36/shell-command`

### Examples:

1. Arguments && Options

```php
use ArtARTs36\ShellCommand\ShellCommand;

$command = ShellCommand::make('git')->addArgument('push')->addOption('force');

var_dump($command->__toString()); // git 'push' --force 2>&1
```
