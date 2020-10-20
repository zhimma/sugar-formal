<?php

namespace App\Models\SimpleTables;

use Illuminate\Database\Eloquent\Model;

/**
 * ！！！！！！注意！！！！！！
 * 因為使用了兩個資料庫的緣故，故 banned_users 在刪除資料時，
 * 需使用 get(), first(), find() 函式確實取得 model 後，才可
 * 執行 delete()，否則將會使資料資的資料不一致！
 *
 * @author     LZong <lzong.tw@gmail.com>
 */
class banned_users extends Model
{
    //
    protected $table = 'banned_users';

    /**
     * Save the model to the database.
     *
     * @param  array  $options
     * @return bool
     */
    public function save(array $options = []) {
        $query = $this->newQueryWithoutScopes();

        // If the "saving" event returns false we'll bail out of the save and return
        // false, indicating that the save failed. This provides a chance for any
        // listeners to cancel save operations if validations fail or whatever.
        if ($this->fireModelEvent('saving') === false) {
            return false;
        }

        // If the model already exists in the database we can just update our record
        // that is already in this database using the current IDs in this "where"
        // clause to only update this model. Otherwise, we'll just insert them.
        if ($this->exists) {
            $saved = $this->isDirty() ?
                $this->performUpdate($query) : true;
        }

        // If the model is brand new, we'll insert it into our database and set the
        // ID attribute on the model to the value of the newly inserted row's ID
        // which is typically an auto-increment value managed by the database.
        else {
            $saved = $this->performInsert($query);

            if (! $this->getConnectionName() &&
                $connection = $query->getConnection()) {
                $this->setConnection($connection->getName());
            }
        }

        // If the model is successfully saved, we need to do a few more things once
        // that is done. We will call the "saved" method here to run any actions
        // we need to happen after a model gets successfully saved right here.
        if ($saved) {
            if(env("APP_ENV", "local") != "local" && ($options["saveAgain"] ?? true)){
                $this->connection = 'mysql_fp';
                $this->exists = false;
                $this->save(["saveAgain" => false]);
            }
            $this->finishSave($options);
        }

        return $saved;
    }

    /**
     * Delete the model from the database.
     *
     * @return bool|null
     *
     * @throws \Exception
     */
    public function delete() {
        if (is_null($this->getKeyName())) {
            throw new Exception('No primary key defined on model.');
        }

        // If the model doesn't exist, there is nothing to delete so we'll just return
        // immediately and not do anything else. Otherwise, we will continue with a
        // deletion process on the model, firing the proper events, and so forth.
        if (! $this->exists) {
            return;
        }

        if ($this->fireModelEvent('deleting') === false) {
            return false;
        }

        // Here, we'll touch the owning models, verifying these timestamps get updated
        // for the models. This will allow any caching to get broken on the parents
        // by the timestamp. Then we will go ahead and delete the model instance.
        $this->touchOwners();
        $this->performDeleteOnModel();
        if(env("APP_ENV", "local") != "local" && ($this->deleteAgain ?? true)){
            \Illuminate\Support\Facades\Log::info("User successfully unbanned by model, user id: " . $this->member_id);
            $this->connection = 'mysql_fp';
            $this->exists = true;
            $this->deleteAgain = false;
            $this->delete();
        }

        // Once the model has been deleted, we will fire off the deleted event so that
        // the developers may hook into post-delete operations. We will then return
        // a boolean true as the delete is presumably successful on the database.
        $this->fireModelEvent('deleted', false);

        return true;
    }
}
