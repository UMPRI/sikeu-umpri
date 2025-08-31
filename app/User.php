<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;

/**
 * @property int $id
 * @property string $name
 * @property string $email
 * @property int $Faculty_Id
 * @property string $password
 * @property string $remember_token
 * @property string $created_at
 * @property string $updated_at
 * @property Role[] $roles
 */
class User extends Authenticatable
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = '_user';

    /**
     * @var array
     */
    protected $fillable = ['name', 'email', 'Faculty_Id', 'password', 'remember_token', 'created_at', 'updated_at'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function roles()
    {
        return $this->belongsToMany('App\Role', '_role_user', 'user_id', 'role_id');
    }
    public function akses()
    {
        $akses = [];
        $roles = $this->roles()->where('app','Keuangan')->get();
        $i = 0;
        foreach($roles as $role){
            $accesses = Role::find($role->id)->accesskeuangans()->get();
            foreach($accesses as $access){
                $akses[$i] = $access->name;
                $i++;
            }
        }
        return $akses;
    }
}
