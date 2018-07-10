<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Models\User;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $oldUser = DB::table('member')->get();

        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('title');
            $table->integer('engroup')->unsigned()->nullable();
            $table->integer('enstatus')->unsigned()->nullable();
            $table->string('email');
            $table->string('password');
            $table->integer('password_updated')->default(0);
            $table->rememberToken();
            $table->timestamps();
            $table->timestamp('last_login');
            $table->timestamp('vip_record');
        });

        foreach($oldUser as $user) {
            $newUser = new User();
            $newUser->id = $user->Id;
            $newUser->name = $user->Nickname;
            $newUser->title = $user->Title;
            $newUser->engroup = $user->En_Group;
            $newUser->enstatus = $user->En_Status;
            $newUser->email = $user->Username;
            $newUser->password = $user->PasswordMD5;
            //$newUser->password = password_hash($user->PasswordMD5, PASSWORD_BCRYPT);
            $newUser->save();
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
