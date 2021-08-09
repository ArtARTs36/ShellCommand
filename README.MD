ShellCommand
----

![PHP Composer](https://github.com/ArtARTs36/ShellCommand/workflows/Testing/badge.svg?branch=master)
[![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg)](https://opensource.org/licenses/MIT)
<a href="https://poser.pugx.org/artarts36/pushall-sender/d/total.svg">
    <img src="https://poser.pugx.org/artarts36/shell-command/d/total.svg" alt="Total Downloads">
</a>

----

### Installation:

Run: `composer require artarts36/shell-command`

---

### Examples:

#### 1. Arguments && Options

```php
use ArtARTs36\ShellCommand\ShellCommand;

$command = ShellCommand::make('git')->addArgument('push')->addOption('force');

var_dump($command->__toString()); // git 'push' --force 2>&1
```

#### 2. Execute Commands

```php
use ArtARTs36\ShellCommand\ShellCommand;

$command = ShellCommand::make('git')->addArgument('pull');
$result = $command->execute();

var_dump($result->getCommandLine());
var_dump($result->getCode());
var_dump($result->getDate());
var_dump($result->getCode());
```

#### 2. SSH

To use ssh, you need to connect the library ["artarts36/shell-command-ssh-executor"](https://github.com/ArtARTs36/php-shell-command-ssh-executor):

Run command `artarts36/shell-command-ssh-executor`

```php
$connection = \ArtARTs36\ShellCommandSshExecutor\SSH\Connection::withPassword(
    'remote.host',
    'user',
    'password'
);

$command = \ArtARTs36\ShellCommand\ShellCommand::make('ls');

$command->setExecutor(new \ArtARTs36\ShellCommandSshExecutor\SshCommandExecutor($connection));

var_dump($command->getShellResult());
```
