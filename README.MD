ShellCommand
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
