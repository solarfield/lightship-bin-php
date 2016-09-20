# lightship-bin-php

Lightship's command line utility binary (phar).


# How to Use

```
php vendor/bin/lightship.phar
```


# Help

```
Lightship's command line utility

Usage:
    lightship <command> [<args>...]

Some common commands are:
    help          Show this help information
    app           Create a new app project
    webdep        Symlink an app dependency to the front-end web area
    version       Show the version of this command line utility

See 'lightship help <command>' for more information on a specific command.
```


# Building

```
php -d phar.readonly=0 -f build.php
```

Generated *.phar will be placed in ./target/



