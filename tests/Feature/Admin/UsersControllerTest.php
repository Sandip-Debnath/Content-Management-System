<?php
namespace Tests\Feature\Admin;

use App\Models\Role;
use App\Models\User;
use Tests\TestCase;

class UsersControllerTest extends TestCase
{
    protected $admin;
    protected $createdRecords = [];

    protected function setUp(): void
    {
        parent::setUp();

        // Ensure Admin role exists
        $adminRole = Role::firstOrCreate(
            ['title' => 'Admin'],
            ['title' => 'Admin']// If missing, create it
        );

        $this->admin = User::create([
            'name'              => 'AdminUser_' . uniqid(),
            'email'             => uniqid() . '@example.com',
            'password'          => bcrypt('secret'),
            'user_type'         => 'admin',
            'email_verified_at' => now(),
        ]);
        $this->admin->roles()->sync([$adminRole->id]);

        $this->createdRecords[] = $this->admin;
    }

    protected function tearDown(): void
    {
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
    public function index_view()
    {
        $this->withHeader('X-Requested-With', '');
        $this->actingAs($this->admin, 'web')
            ->get(route('admin.users.index'))
            ->assertOk()
            ->assertViewIs('admin.users.index');
    }

    /** @test */
    public function store_creates_user_with_roles()
    {
        $role                   = Role::firstOrCreate(['title' => 'Editor']);
        $this->createdRecords[] = $role;

        $email = uniqid() . '@example.com';

        $this->actingAs($this->admin, 'web')
            ->post(route('admin.users.store'), [
                'name'     => 'User_' . uniqid(),
                'email'    => $email,
                'password' => 'secret',
                'roles'    => [$role->id],
            ])
            ->assertRedirect(route('admin.users.index'));

        $this->assertDatabaseHas('users', ['email' => $email]);
        $this->assertDatabaseHas('role_user', ['role_id' => $role->id]);

        $newUser = User::where('email', $email)->first();
        if ($newUser) {
            $this->createdRecords[] = $newUser;
        }
    }

    /** @test */
    public function edit_and_update_user()
    {
        $user = User::create([
            'name'     => 'EditUser_' . uniqid(),
            'email'    => uniqid() . '@example.com',
            'password' => bcrypt('secret'),
        ]);
        $this->createdRecords[] = $user;

        $role                   = Role::firstOrCreate(['title' => 'Editor']);
        $this->createdRecords[] = $role;

        $newEmail = uniqid() . '@example.com';

        $this->actingAs($this->admin, 'web')
            ->put(route('admin.users.update', $user), [
                'name'     => 'Updated_' . uniqid(),
                'email'    => $newEmail,
                'password' => 'secret',
                'roles'    => [$role->id],
            ])
            ->assertRedirect(route('admin.users.index'));

        $this->assertDatabaseHas('users', ['id' => $user->id, 'email' => $newEmail]);
        $this->assertDatabaseHas('role_user', ['user_id' => $user->id, 'role_id' => $role->id]);
    }

    /** @test */
    public function destroy_user()
    {
        $user = User::create([
            'name'     => 'DeleteUser_' . uniqid(),
            'email'    => uniqid() . '@example.com',
            'password' => bcrypt('secret'),
        ]);
        $this->createdRecords[] = $user;

        $this->actingAs($this->admin, 'web')
            ->delete(route('admin.users.destroy', $user))
            ->assertRedirect();

        $this->assertSoftDeleted('users', ['id' => $user->id]);
    }

    /** @test */
    public function mass_destroy_users()
    {
        $user1 = User::create([
            'name'     => 'MassDel1_' . uniqid(),
            'email'    => uniqid() . '@example.com',
            'password' => bcrypt('secret'),
        ]);
        $user2 = User::create([
            'name'     => 'MassDel2_' . uniqid(),
            'email'    => uniqid() . '@example.com',
            'password' => bcrypt('secret'),
        ]);

        $this->createdRecords[] = $user1;
        $this->createdRecords[] = $user2;

        $this->actingAs($this->admin, 'web')
            ->delete(route('admin.users.massDestroy'), [
                'ids' => [$user1->id, $user2->id],
            ])
            ->assertNoContent();

        $this->assertSoftDeleted('users', ['id' => $user1->id]);
        $this->assertSoftDeleted('users', ['id' => $user2->id]);
    }
}
