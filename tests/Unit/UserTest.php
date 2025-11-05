<?php

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;


beforeEach(function () {
    Role::query()->delete();
    Permission::query()->delete();
});

test('can create user', function () {

    /**
     * @var User $user
     */
    $user = User::create([
        'name' => 'John Doe',
        'email' => 'john@example.com',
        'password' => Hash::make('password'),
    ]);

    expect($user->name)->toBe('John Doe')
        ->and($user->email)->toBe('john@example.com')
        ->and(Hash::check('password', $user->password))->toBeTrue();
});

test('can update user', function () {

    /**
     * @var User $user
     */
    $user = User::create([
        'name' => 'John Doe',
        'email' => 'john@example.com',
        'password' => Hash::make('password'),
    ]);

    $user->update(['name' => 'John Doe Updated']);
    $user->refresh();

    expect($user->name)->toBe('John Doe Updated');
});

test('can delete user', function () {

    /**
     * @var User $user
     */
    $user = User::create([
        'name' => 'John Doe',
        'email' => 'john@example.com',
        'password' => Hash::make('password'),
    ]);

    expect($user->delete())->toBeTrue();
});

test('user has roles relationship', function () {
    $user = new User();
    expect(method_exists($user, 'roles'))->toBeTrue();
});

test('can assign role to user', function () {
    $role = Role::create(['name' => 'Super Admin']);

    /**
     * @var User $user
     */
    $user = User::create([
        'name' => 'John Doe',
        'email' => 'john@example.com',
        'password' => Hash::make('password'),
    ]);

    $user->assignRole($role);
    $user->refresh();

    expect($user->hasRole('Super Admin'))->toBeTrue()
        ->and($user->roles->pluck('name')->toArray())->toContain('Super Admin');
});

test('can remove role from user', function () {
    $role = Role::create(['name' => 'Super Admin']);

    /**
     * @var User $user
     */
    $user = User::create([
        'name' => 'John Doe',
        'email' => 'john@example.com',
        'password' => Hash::make('password'),
    ]);

    $user->assignRole($role);
    $user->removeRole($role);
    $user->refresh();

    expect($user->hasRole('Super Admin'))->toBeFalse()
        ->and($user->roles->pluck('name')->toArray())->toBeEmpty();
});

test('user inherits permission from role', function () {
    $role = Role::create(['name' => 'Editor']);
    $permission = Permission::create(['name' => 'publish article']);
    $role->givePermissionTo($permission);

    /**
     * @var User $user
     */
    $user = User::create([
        'name' => 'Jane Doe',
        'email' => 'jane@example.com',
        'password' => Hash::make('password'),
    ]);

    $user->assignRole('Editor');

    expect($user->can('publish article'))->toBeTrue();
});

test('can give permission directly to user', function () {
    $permission = Permission::create(['name' => 'delete article']);

    /**
     * @var User $user
     */
    $user = User::create([
        'name' => 'Jane Doe',
        'email' => 'jane@example.com',
        'password' => Hash::make('password'),
    ]);

    $user->givePermissionTo('delete article');

    expect($user->hasPermissionTo('delete article'))->toBeTrue();
});
