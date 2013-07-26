# Nette Module Installer

This is a [custom installer](http://getcomposer.org/doc/articles/custom-installers.md) for Composer packaging system which
helps installing Nette Modules.

## Creating an Module
To create a new **Nette Module**, simply create it as a *Composer package*. There are only a few differences from normal Composer packages:

1. Set *type* to `nette-module`
2. Add this installer as a requirement: `flame/module-installer: @dev`
3. Set **extra** section of composer.json

Here is an example:
```json
"extra": {
    "module": {
      "class": "Flame\\CMS\\ErrorModule\\DI\\ErrorExtension",
      "name": "Error"
    }
  }
```
**Name** is optional

Look at this [composer.json](https://github.com/flame-cms/Angular-Module/blob/master/composer.json) for full example.

Your extension will be added into app/config/extensions.(php|neon) file

*Please create first the file with php or neon extension*