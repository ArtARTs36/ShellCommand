ShellCommand
----

### Examples:

1*. Simple
```php
use ArtARTs36\ShellCommand\ShellCommand;

$command = (new ShellCommand('rm', false))
    ->addCutOption('rf')
    ->addParameter('/');

$command->execute();
```
