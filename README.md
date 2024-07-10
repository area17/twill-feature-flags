# Feature Flags
## Twill Capsule

### Description

This Capsule allows you to easily enable/disable features on your application. Features not flagged to be available publicly will still be available on non-public domains. This way company staff and QA teams can still test these features on hiden domains, even if they are served by the same server/database.

![Screenshot 1](docs/screenshot01.png)

![Screenshot 2](docs/screenshot02.png)

## Installing

Require the Composer package:

``` bash
composer require area17/twill-feature-flags
``` 

Enable the Capsule in config/twill.php:

``` php
    'capsules' => [
        'list' => [
            [
                'name' => 'FeatureFlags',
                'enabled' => true,
            ],
            ...
```

Load Capsule helpers by adding calling the loader to your AppServiceProvider:

``` php
/**
 * Register any application services.
 *
 * @return void
 */
public function register()
{
    \A17\TwillFeatureFlags\Services\Helpers::load();
}
```

Add a configuration to config/app.php, to set your public available domains

``` php 
/*
|--------------------------------------------------------------------------
| Domains
|--------------------------------------------------------------------------
|
*/

'domains' => [
    'publicly_available' => explode(',', env('PUBLICLY_AVAILABLE_DOMAINS')),
],
```
 
Add the production domains list (comma separated) to your .env file:

``` dotenv
PUBLICLY_AVAILABLE_DOMAINS=my-production-domain.com
```  

## Using

Once installed and configured, you can go to https://your-domain/featureFlags to create/enable/disable feature flags.

And, on your code, you can just use the helper to show/hide features from your website:

``` php 
if (feature('booking')) {
    // whatever your feature has to do
}
```

Or in Blade:

``` php 
@include('partials.global.head', ['noIndex' => !feature('feature-x')])

@if(feature('booking'))
    // Render the feature
@endif
```

Don't forget to add the feature flags to your navigation too.

## Allow users logged in Twill option
You can allow your users to have access to a feature in public available domains if they are logged in on Twill. But there are some caveats for it to work:

- Twill must be int he same domain of the web application, meaning that `ADMIN_APP_URL` must be empty or have the same domain as the frontend; or
- The Laravel session domain should set in order for the apps to share the session cookie:

```dotenv
SESSION_DOMAIN=.laravel-twill-project.test
```

Also, make sure your sessions are working fine, when you switch to a shared domain they might break and a browser cookie clear might be needed.
