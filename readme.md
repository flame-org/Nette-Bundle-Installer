# Nette Bundle Installer

This is a [custom installer](http://getcomposer.org/doc/articles/custom-installers.md) for Composer packaging system which
helps installing Nette Bundles.

## Creating an Bundle
Please read Composer's manual on [custom installers](http://getcomposer.org/doc/articles/custom-installers.md) for an introduction.

To create a new **Nette Bundle**, simply create it as a *Composer package*. There are only 2 differences from normal Composer packages:

1. Set *type* to `nette-bundle`
2. Add this installer as a requirement: `flame/bundle-installer`