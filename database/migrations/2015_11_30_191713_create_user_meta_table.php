<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Models\UserMeta;

class CreateUserMetaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $oldUser = DB::table('member')->get();

        Schema::create('user_meta', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

            $table->string('phone')->nullable();

            $table->boolean('is_active')->default(0);
            $table->string('activation_token')->nullable();

            $table->boolean('marketing')->default(0);
            $table->boolean('terms_and_cond')->default(1);

            $table->timestamps();

            $table->string('city')->nullable();
            $table->string('blockcity')->nullable();
            $table->string('area')->nullable();
            $table->string('blockarea')->nullable();
            $table->string('budget')->nullable();
            $table->string('birthdate')->nullable();
            $table->integer('height')->nullable()->default(0);
            $table->integer('weight')->nullable()->default(0);
            $table->char('cup', 1)->nullable();
            $table->string('body')->nullable();
            $table->text('about')->nullable();
            $table->text('style')->nullable();
            $table->string('situation')->nullable();
            $table->string('occupation')->nullable();
            $table->string('education')->nullable();
            $table->string('marriage')->nullable();
            $table->string('drinking')->nullable();
            $table->string('smoking')->nullable();
            $table->char('isHideArea', 1)->default(0)->nullable();
            $table->char('isHideCup', 1)->default(0)->nullable();
            $table->char('isHideWeight', 1)->default(0)->nullable();
            $table->char('isHideOccupation', 1)->default(0)->nullable();
            $table->string('country')->nullable();
            $table->text('memo')->nullable();
            $table->string('pic')->nullable();
            $table->string('domainType')->nullable();
            $table->string('blockdomainType')->nullable();
            $table->string('domain')->nullable();
            $table->string('blockdomain')->nullable();
            $table->string('job')->nullable();
            $table->string('realName')->nullable();
            $table->string('assets')->nullable();
            $table->string('income')->nullable();
            $table->string('notifmessage', 50)->nullable();
            $table->string('notifhistory', 50)->nullable();

        });

        foreach($oldUser as $user) {
            $newUser = new UserMeta();
            $newUser->user_id = $user->Id;
            $newUser->is_active = 1;
            if($user->City == '台北市') $newUser->city = '臺北市';
            else if($user->City == '台中市') $newUser->city = '臺中市';
            else if($user->City == '台南市') $newUser->city = '臺南市';
            else if($user->City == '台東縣') $newUser->city = '臺東縣';
            else $newUser->city = $user->City;
            $newUser->blockcity = 0;
            $newUser->area = $user->Area;
            $newUser->blockarea = 0;
            $newUser->budget = $user->Buget;
            $newUser->birthdate = $user->Birthday;
            $newUser->height = $user->Height;
            $newUser->weight = $user->Weight;
            $newUser->cup = $user->Cup;
            $newUser->body = $user->Shape;
            $newUser->about = $user->About;
            $newUser->style = $user->Memo;
            $newUser->situation = NULL;
            if($user->En_Group == 1) $newUser->occupation = $user->Occupation;
            else if($user->En_Group == 2) $newUser->occupation = $user->Job;
            $newUser->education = $user->Education;
            $newUser->marriage = $user->Marrige;
            $newUser->drinking = $user->Drinking;
            $newUser->smoking = $user->Smoking;
            if($user->Is_HideArea == 'N') $newUser->isHideArea = 0;
            else if($user->Is_HideArea == 'Y') $newUser->isHideArea = 1;
            if($user->Is_HideCup == 'N') $newUser->isHideCup = 0;
            else if($user->Is_HideCup == 'Y') $newUser->isHideCup = 1;
            if($user->Is_HideWeight == 'N') $newUser->isHideWeight = 0;
            else if($user->Is_HideWeight == 'Y') $newUser->isHideWeight = 1;
            if($user->Is_HideOccupation == 'N') $newUser->isHideOccupation = 0;
            else if($user->Is_HideOccupation == 'Y') $newUser->isHideOccupation = 1;
            $newUser->country = $user->Country;
            if($user->Pic != NULL) $newUser->pic = '/img/Member/' . substr($user->Pic, 0, 4) . '/' . substr($user->Pic, 4, 2) . '/' . substr($user->Pic, 6, 2) . '/' . $user->Pic;
            else $newUser->pic = NULL;
            $newUser->domainType = $user->DomainType;
            $newUser->domain = $user->Domain;
            $newUser->assets = $user->Assets;
            $newUser->income = $user->Income;
            $newUser->notifmessage = '不通知';
            $newUser->notifhistory = '顯示普通會員信件';
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
        Schema::drop('user_meta');
    }
}
