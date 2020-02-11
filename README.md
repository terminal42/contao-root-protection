terminal42/contao-root-protection
=====================================

A Contao bundle that allows you to protect individual root pages via HTTP Basic Authentication.


## Features

- Require visitors to enter a username-password combination prior accessing the website (configuration per root page)


## Installation

Choose the installation method that matches your workflow!


### Installation via Contao Manager

Search for `terminal42/contao-root-protection` in the Contao Manager and add it to your installation. Finally,
update the packages.

### Manual installation

Add a composer dependency for this bundle. Therefore, change in the project root and run the following:

```bash
composer require terminal42/contao-root-protection
```

Depending on your environment, the command can differ, i.e. starting with `php composer.phar …` if you do not have 
composer installed globally.

Then, update the database via the Contao install tool.


## Configuration


## License

This bundle is released under the [MIT license](LICENSE)
