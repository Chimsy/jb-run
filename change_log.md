# Change Log
## Introduction
Current Version: `Laravel Framework 9.48.0`

This is a detailed log, of the changes that were put in place to upgrade this template to support the most recent versions of the Laravel Framework

---
## Upgrading From Laravel 8.x to 9.x
**Date:** 19 January 2023 <br>
**Link:** Laravel Upgrade Instructions https://laravel.com/docs/9.x/upgrade 
1. **Update Dependencies**
    * `laravel/framework` to `^9.0`
    * `nunomaduro/collision` to `^6.1`
2. **Trusted Proxies** <br>
   * Within your `app/Http/Middleware/TrustProxies.php` file, update use `Fideloper\Proxy\TrustProxies` as Middleware to use `Illuminate\Http\Middleware\TrustProxies` as Middleware. 
   * Next, within `app/Http/Middleware/TrustProxies.php`, you should update the `$headers` property definition:
   * // Before... <br>
    `protected $headers = Request::HEADER_X_FORWARDED_ALL;` <br>
    
   * // After...<br>
    `protected $headers =`  <br>
    `Request::HEADER_X_FORWARDED_FOR |` <br>
    `Request::HEADER_X_FORWARDED_HOST |` <br>
    `Request::HEADER_X_FORWARDED_PORT |` <br>
    `Request::HEADER_X_FORWARDED_PROTO |` <br>
    `Request::HEADER_X_FORWARDED_AWS_ELB;`

3. `rm -rfv config/trustedproxy.php`

4. `sudo rm -rfv vendor/`
    - To remove downloaded dependencies, execute this inside app base dir

5. `sudo rm -rfv composer.lock`
    - To remove previously locked versions of the composer dependencies

6. `composer update`
---
## Upgrading From Laravel 7.x to 8.x
**Date:** 16 January 2023 <br>
**Link:** Laravel Upgrade Instructions https://laravel.com/docs/8.x/upgrade
1. Update Dependencies
   * `guzzlehttp/guzzle` to `^7.0.1`
   * `facade/ignition to` `^2.3.6`
   * `laravel/framework` to `^8.0`
   * `laravel/ui to` `^3.0` 
   * `nunomaduro/collision` to `^5.0` 
   * `phpunit/phpunit` to `^9.0`
   * `laravel/dusk` to `^6.25`

2. `sudo rm -rfv vendor/`
    - To remove downloaded dependencies, execute this inside app base dir
   
3. `sudo rm -rfv composer.lock`
    - To remove previously locked versions of the composer dependencies

4. Create a new Directory in `/app/` and name it `Model`. 
   - Move all the application models into the new Directory `Model`.
---
## Upgrading From Laravel 6.x to 7.x
**Date:** 15 January 2023
1. `sudo rm -rfv vendor/`
    - To remove downloaded dependencies, execute this inside app base dir
2. `sudo rm -rfv composer.lock`
    - To remove previously locked versions of the composer dependencies
3. **Updating Dependencies:**
    - Follow all instructions from the documentation to the dot in renaming your required dependencies as specified by the Laravel Framework. Click & Follow the link Below: [https://laravel.com/docs/7.x/upgrade](https://laravel.com/docs/7.x/upgrade)
    - run `composer upgrade`
    - there is an error [(click here for details)](https://stackoverflow.com/questions/50840960/script-php-artisan-packagediscover-handling-the-post-autoload-dump-event-retur) that came from the updating dependencies in composer and this is how you solve that error:
        1. In `App\Exceptions\Handler`:
            - add `Use Throwable;`,
            - change methods to accept instances of `Throwable` instead of `Exceptions` as follows:
                - `public function report(Throwable $exception);`,
                - `public function render($request, Throwable $exception);`,
        2. In `config\session.php`:
            - `'secure' => env('SESSION_SECURE_COOKIE', null)`,
        3. Then run:
            - `sudo rm -rfv vendor/`
            - `sudo rm -rfv composer.lock`
            - `composer upgrade`
---




