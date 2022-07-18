# Impersonate users with Backpack for Laravel
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
php artisan vendor:publish maurohmartinez/impersonate-users-backpack-laravel --provider="MHMartinez\ImpersonateUser\Providers\ImpersonateUserServiceProvider"
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
## Credits
- [Mauro Martinez](https://inspiredpulse.com/) Developer
- [Cristian Tabacitu](https://tabacitu.ro/) For creating [Backpack for Laravel](https://backpackforlaravel.com/)!