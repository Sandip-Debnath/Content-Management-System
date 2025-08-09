<?php
namespace Tests\Unit\Controllers\Admin;

use App\Http\Controllers\Admin\UsersController;
use App\Http\Requests\MassDestroyUserRequest;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Mockery;
use Tests\TestCase;

class UsersControllerUnitTest extends TestCase
{
    protected $admin;
    protected $controller;
    protected $createdRecords = [];

    protected function setUp(): void
    {
        parent::setUp();

        // Stub all Gate checks to allow in unit tests
        Gate::shouldReceive('denies')->andReturn(false);
        Gate::shouldReceive('allows')->andReturn(true);

        // Ensure Admin role exists
        $adminRole = Role::firstOrCreate(['title' => 'Admin']);

        $this->admin = User::create([
            'name'              => 'AdminUser_' . uniqid(),
            'email'             => uniqid() . '@example.com',
            'password'          => bcrypt('secret'),
            'user_type'         => 'admin',
            'email_verified_at' => now(),
        ]);
        $this->admin->roles()->sync([$adminRole->id]);

        $this->createdRecords[] = $this->admin;

        $this->controller = new UsersController();
    }

    protected function tearDown(): void
    {
        Mockery::close();

        foreach (array_reverse($this->createdRecords) as $record) {
            if (method_exists($record, 'forceDelete')) {
                $record->forceDelete();
            } else {
                $record->delete();
            }
        }
        parent::tearDown();
    }

    /** @test */
    public function store_creates_user_and_assigns_roles()
    {
        $role                   = Role::firstOrCreate(['title' => 'Editor']);
        $this->createdRecords[] = $role;

        $email = uniqid() . '@example.com';

        $request = Mockery::mock(StoreUserRequest::class);
        $request->shouldReceive('validated')->once()->andReturn([
            'name'     => 'User_' . uniqid(),
            'email'    => $email,
            'password' => 'secret',
        ]);
        $request->shouldReceive('input')->with('roles', [])->once()->andReturn([$role->id]);

        $response = $this->controller->store($request);

        $newUser                = User::where('email', $email)->first();
        $this->createdRecords[] = $newUser;

        $this->assertNotNull($newUser);
        $this->assertDatabaseHas('role_user', [
            'role_id' => $role->id,
            'user_id' => $newUser->id,
        ]);
        $this->assertTrue($response->isRedirect(route('admin.users.index')));
    }

    /** @test */
    public function update_modifies_user_and_roles()
    {
        $role                   = Role::firstOrCreate(['title' => 'Editor']);
        $this->createdRecords[] = $role;

        $user = User::create([
            'name'     => 'OldName_' . uniqid(),
            'email'    => uniqid() . '@example.com',
            'password' => bcrypt('secret'),
        ]);
        $this->createdRecords[] = $user;

        $newEmail = uniqid() . '@example.com';
        $newName  = 'NewName_' . uniqid();

        $request = Mockery::mock(UpdateUserRequest::class);
        $request->shouldReceive('all')->once()->andReturn([
            'name'     => $newName,
            'email'    => $newEmail,
            'password' => 'secret',
            'roles'    => [$role->id],
        ]);
        $request->shouldReceive('input')->with('roles', [])->once()->andReturn([$role->id]);

        $response = $this->controller->update($request, $user);
        $user->refresh();

        $this->assertEquals($newName, $user->name);
        $this->assertEquals($newEmail, $user->email);
        $this->assertDatabaseHas('role_user', [
            'role_id' => $role->id,
            'user_id' => $user->id,
        ]);
        $this->assertTrue($response->isRedirect(route('admin.users.index')));
    }

    /** @test */
    public function destroy_soft_deletes_user_and_redirects()
    {
        $user = User::create([
            'name'     => 'DeleteMe_' . uniqid(),
            'email'    => uniqid() . '@example.com',
            'password' => bcrypt('secret'),
        ]);
        $this->createdRecords[] = $user;

        $response = $this->controller->destroy($user);

        $this->assertSoftDeleted('users', ['id' => $user->id]);
        $this->assertTrue($response->isRedirect());
    }

    /** @test */
    public function mass_destroy_deletes_multiple_users()
    {
        $user1 = User::create([
            'name'     => 'Mass1_' . uniqid(),
            'email'    => uniqid() . '@example.com',
            'password' => bcrypt('secret'),
        ]);
        $user2 = User::create([
            'name'     => 'Mass2_' . uniqid(),
            'email'    => uniqid() . '@example.com',
            'password' => bcrypt('secret'),
        ]);
        $this->createdRecords[] = $user1;
        $this->createdRecords[] = $user2;

        $request = Mockery::mock(MassDestroyUserRequest::class);
        $request->shouldReceive('input')->with('ids')->once()->andReturn([$user1->id, $user2->id]);

        $response = $this->controller->massDestroy($request);

        $this->assertSoftDeleted('users', ['id' => $user1->id]);
        $this->assertSoftDeleted('users', ['id' => $user2->id]);
        $this->assertEquals(204, $response->status());
    }
}
