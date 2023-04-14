# Laravel Commands for .env file

[![Latest Version on Packagist](https://img.shields.io/packagist/v/tamer-dev/laravel-env-cli.svg)](https://packagist.org/packages/tamer-dev/laravel-env-cli)
[![Total Downloads](https://img.shields.io/packagist/dt/tamer-dev/laravel-env-cli.svg)](https://packagist.org/packages/tamer-dev/laravel-env-cli)
[![License](https://img.shields.io/github/license/tamer-dev/laravel-env-cli.svg)](LICENSE.md)

This Laravel package offers to you some commands to work with your env file from the command line.
if you are making changes frequently to your env file this package will help you to do this fast and in an effective way.

This package supports the Laravel version from 5.7 to 10 .

<!-- TOC -->

- [Installation](#installation)
- [Usage:-](#usage)
    - [Set Environment Variable Command](#1-set-environment-variable-command)
    - [Read Environment Variable Command](#2-read-environment-variable-command)
    - [Backup Environment File Command](#3-backup-environment-file-command)
    - [Restore Environment File Command](#4-restore-environment-file-command)
- [Contribution](#contribution)
- [Notes](#notes)
- [License](#license)

<!-- /TOC -->

## Installation

You can install this package with [Composer](https://getcomposer.org/) using the following command:

```bash
composer require tamer-dev/laravel-env-cli
```

## Usage

#### 1- Set Environment Variable Command
command definition :-
`env:set {key} {value}`

this command by default will use .env file

optional command options:- 
`{--file=your-custom-env-name}`  if you want to set a key in custom env file

`{--b|backup}` take a backup from env file before set the key

you must provide both a key and value .

```bash
$ php artisan env:set app_name Example
# Environment variable with key 'APP_NAME' has been changed from 'Laravel' to 'Example'
```
 you can provide them as two arguments as commnd before or one argument like follwoing:- .
```bash
$ php artisan env:set app_name=Example
# Environment variable with key 'APP_NAME' has been changed from 'Laravel' to 'Example'
```

if you value have spaces you can wrapping them in quotes like follwoing:-.
```bash
$ php artisan env:set app_name "Example App"
# Environment variable with key 'APP_NAME' has been changed from 'Laravel' to '"Example App"'
```

you can create new environment variables if this key dose not exist.

```bash
$ php artisan env:set editor=vscode
# Environment variable with key 'EDITOR' has been set to 'vscode'
```

you can create or update environment variables in another file not in the default one by passing --file option like following:-

```bash
$ php artisan env:set app_name Example --file=.env.example
# Environment variable with key 'EDITOR' has been set to 'vscode'
```

also you can take a backup file from your env file before make any changes in same command by passing -b option (a new file backup with the name '.env.backup_<current_date_time>' will be created) like following:-
```bash
$ php artisan env:set app_name Example -b
# Environment variable with key 'APP_NAME' has been changed from 'Laravel' to 'Example'
```
another features :-  
- update an empty value 
- stop invalid inputs
- stop updateing in APP_KEY 


#### 2- Read Environment Variable Command

command definition :-
`env:read {key}`
this command by default will use .env file

```bash
$ php artisan env:read app_name 
# Environment variable with key [APP_NAME] have value [Laravel] file used is .env
```
optional command options:- 
`{--file=your-custom-env-name}`  if you want to read a key from a custom env file

```bash
$ php artisan env:read app_name --file=.env.example
#Environment variable with key [APP_NAME] have value [tamertest3] file used is .env.example
```

#### 3- Backup Environment File Command

this command will make a backup from env file (a new file backup with the name '.env.backup_<current_date_time>' will be created)

command definition :-
`env:backup`
this command by default will use .env file

```bash
$ php artisan env:backup 
#new environment backup file has been created in this path '/var/www/html/laravel-env-cli/.env.backup_20200517204848'
```
optional command options:- 
`{--file=your-custom-env-name}`  if you want to backup a custom env file

```bash
$ php artisan env:backup --file=.env.example
#new environment backup file has been created in this path '/var/www/html/laravel-env-cli/.env.example.backup_20200517205000'
```

#### 4- Restore Environment File Command
restore your .env file from a backup file

command definition :-
`env:restore {backupedFileName}`
this command by default will use .env file

```bash
$ php artisan env:restore .env.backup_20200517204848
#the env file '/var/www/html/laravel-env-cli/.env' 
#has been restored from this file path '/var/www/html/laravel-env-cli/.env.backup_20200517204848'
```

optional command options:- 
`{--file=your-custom-env-name}`  if you want to restore a custom env file

```bash
$ php artisan env:restore .env.backup_20200517204848 --file=.env.example
#the env file '/var/www/html/laravel-env-cli/.env.example' 
#has been restored from this file path '/var/www/html/laravel-env-cli/.env.backup_20200517204848'
```



The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

## Contribution  
contribution are welcome ,if any one want to contribute in this package you can start by picking from this list : -

- add tests
- add more commands and important features
- fix any issues 

## Notes
this package inspired by this package [imliam/laravel-env-set-command](https://github.com/imliam/laravel-env-set-command),for that all thanks to [imliam](https://github.com/imliam) 

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
