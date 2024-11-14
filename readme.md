<p align="center">
  <a href="https://github.com/Chimsy/GoldenChimusindeIOS">
    <img src="https://chimsy.co.zw/images/logos/chimsy.png" alt="Logo" width="255" height="255">
  </a>

<h3 align="center">Golden (Tapfuma) Chimusinde Technical Assessment</h3>


# Job Background System

This documentation provides a detailed explanation of how to use the `runBackgroundJob` function within a Laravel application. The function allows you to run background tasks asynchronously with support for prioritization, delays, retries, and logging.

## Table of Contents
1. [Overview](#overview)
2. [Technologies & Tools Used](#technologies--tools-used)
3. [Installation and Setup](#installation-and-setup)
4. [Usage](#usage)
    - [Running Background Jobs](#running-background-jobs)
    - [Configuring Retry Attempts](#configuring-retry-attempts)
    - [Configuring Delays](#configuring-delays)
    - [Setting Job Priorities](#setting-job-priorities)
5. [Examples](#examples)
    - [Running a Simple Job](#running-a-simple-job)
    - [Running a Job with Retry Attempts](#running-a-job-with-retry-attempts)
    - [Running a Job with Delay](#running-a-job-with-delay)
    - [Running a Job with Priority](#running-a-job-with-priority)
6. [Viewing and Managing Jobs](#viewing-and-managing-jobs)
7. [License](#license)

## Overview

The `runBackgroundJob` function is part of a Laravel application that manages the execution of background tasks. This system includes a database table to store job information, a model to interact with the table, and several classes to handle job execution with features such as retries, delays, and prioritization.

## Technologies & Tools Used
- **Version Control:** Git, Fork(Git Client)
- **IDE:** PhpStorm, Visual Studio Code.
- **Dev Environment:** Valet (PHP, NGINX,), MariaDB, Php 8.3.13, Composer, yarn, node.


## Installation and Setup

### Step 1: Initial Setup 

Create a database called: `jb-run` using any DBMS of your choice

```bash
mysql -u root -p
```

```mysql
CREATE DATABASE `jb-run`;
```

Copy `.env.example` into `.env`. Take Close Attention and add database credentials and the system variables at the bottom of the `.env` file

```bash
cp .env.example .env
```
Generate Application key

```bash
php artisan key:generate
```

Once All is Set with the env file run the following commands in the following order:
```bash
composer update
yarn install
```

Bundled Laravel to get things going
```bash
php artisan migrate:fresh --force --seed -v
php artisan route:clear && php artisan config:clear && php artisan cache:clear && php artisan view:clear && composer dump-autoload
```


### Step 2: Usage

### Running Background Jobs

The `runBackgroundJob` function allows you to run any method in any class asynchronously. It takes four parameters:
- `className`: The fully qualified name of the class containing the method to run.
- `method`: The name of the method to execute.
- `params`: An array of parameters to pass to the method (optional).
- `priority`: An integer representing the priority of the job (optional, default is 0).
- `delay`: An integer representing the delay in seconds before the job starts (optional, default is 0).

### Configuring Retry Attempts

Retry attempts can be configured by specifying the number of retries in the Artisan command:

```bash
php artisan background-job:run {className} {method} {params} --retries=10
```

### Configuring Delays

Delays can be configured by specifying the delay in seconds in the Artisan command:

```bash
php artisan background-job:run {className} {method} {params} --delay=10
```

### Setting Job Priorities

Job priorities can be set when creating a job. Higher priority jobs run before lower-priority ones:

```php
BackgroundJobHelper::runBackgroundJob('App\Services\SomeClass', 'someMethod', ['param1', 'param2'], 5);
```

## Examples

### Running a Simple Job

```php
BackgroundJobHelper::runBackgroundJob('App\Services\SomeClass', 'someMethod', ['param1', 'param2']);
```

### Running a Job with Retry Attempts

```php
BackgroundJobHelper::runBackgroundJob('App\Services\SomeClass', 'someMethod', ['param1', 'param2'], 0, 0);
php artisan background-job:run 'App\Services\SomeClass' 'someMethod' '["param1", "param2"]' --retries=5
```

### Running a Job with Delay

```php
BackgroundJobHelper::runBackgroundJob('App\Services\SomeClass', 'someMethod', ['param1', 'param2'], 0, 10);
```

### Running a Job with Priority

```php
BackgroundJobHelper::runBackgroundJob('App\Services\SomeClass', 'someMethod', ['param1', 'param2'], 5);
```

## Viewing and Managing Jobs

### Create the Blade Template

Create a Blade template to display background jobs:


## License

This project is licensed under the MIT License. See the [LICENSE](LICENSE) file for details.

---
