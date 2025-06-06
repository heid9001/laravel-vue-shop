<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Enums\Role;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Collection;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table){
            $table->string('surname');
            $table->string('patronymic');
            $table->string('firstname');
            $table->string('role')->default(Role::User->value);
        });

        // Create admin user using collection
        collect([
            [
                'name'  => 'admin',
                'email' => 'admin@example.com',
                'password' => Hash::make('password'),
                'firstname' => 'Admin',
                'surname' => 'User',
                'patronymic' => 'Admin',
                'role' => Role::Admin,
            ]
        ])->each(function ($userData) {
            User::create($userData);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['surname', 'patronymic', 'firstname', 'role']);
        });

        // Remove admin user
        User::where('email', 'admin@example.com')->delete();
    }
};
