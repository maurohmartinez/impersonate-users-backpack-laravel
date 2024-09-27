# Impersonate users with Backpack for Laravel

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Total Downloads][ico-downloads]][link-packagist]

This is a simple package to allow admins impersonate users.

![alt text](https://github.com/maurohmartinez/impersonate-users-backpack-laravel/blob/main/src/sample.gif?raw=true)

## Install
In your terminal:
```sh
# install the package
composer require maurohmartinez/impersonate-users-backpack-laravel
```

In case you want/need to publish files to further customize this package:

```sh
php artisan vendor:publish --provider="MHMartinez\ImpersonateUser\app\Providers\ImpersonateUserServiceProvider"
```

## Usage
1- Add in your `UserCrudController`.
```php
use \MHMartinez\ImpersonateUser\app\Http\Controllers\Operations\ImpersonateUserOperation;
```
This will add a button for List and Show Operations to impersonate users.

2- Next step is to handle the logic to indicate which admins have permission to impersonate others, or can be impersonated. You just need to modify a bit your `User Model` to implement the interface `ImpersonateInterface` like this:
```php
class User extends Authenticatable implements ImpersonateInterface
```
And then add the following two methods in your `User Model`:
```php
/**
 * If you use Laravel-Backpack/PermissionManager you can do like this.
 * But you can also add any logic you need. 
*/
public function canImpersonateOthers(): bool
{
    return $this->can('permission_to_impersonate'); // or replace "permission_to_impersonate" with the right permission
}
/**
 * Following the same example, you can deny admins from impersonating super admins. 
*/
public function canBeImpersonated(): bool
{
    return !$this->hasRole('superadmin'); // or replace "superadmin" with the right permission
}
```
3- Now, you just need to add the button to exit impersonating (no worries, it will only show up when needed). For example, you can add the button in `topbar_right_content.blade.php` like this:
```php
@include('impersonate_user::exit_impersonated')
```

4- Important — If you want to impersonate non-admin users you will need to skip the backpack middleware that determines if the user is admin. This is because this operation will need to allow your impersonated non-admin user to use a backpack route to log you back. How to do it?

- Publish the config file if you haven't done so (described here, above step one).
```php
php artisan vendor:publish --provider="MHMartinez\ImpersonateUser\app\Providers\ImpersonateUserServiceProvider" --tag=config
```
- Add the middleware classname like it follows, and you will be good to go:
```php
return [
    'session_key' => 'impersonating_user',
    'base_guard' => 'backpack',
    'admin_middleware' => Path\To\Middleware\IsAdmin::class,
];
```
This will allow this operation to skip that middleware when logging out impersonated non-admin users.

## Credits
- [Mauro Martinez](https://inspiredpulse.com/) Developer
- [Cristian Tabacitu](https://tabacitu.ro/) For creating [Backpack for Laravel](https://backpackforlaravel.com/)!

[ico-version]: https://img.shields.io/packagist/v/maurohmartinez/impersonate-users-backpack-laravel.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/maurohmartinez/impersonate-users-backpack-laravel.svg?style=flat-square

[link-packagist]: https://packagist.org/packages/maurohmartinez/impersonate-users-backpack-laravel