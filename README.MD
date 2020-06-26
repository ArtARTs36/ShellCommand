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

1*. Simple
```php
use ArtARTs36\ShellCommand\ShellCommand;

$command = (new ShellCommand('rm', false))
    ->addCutOption('rf')
    ->addParameter('/');

$command->execute();
```
