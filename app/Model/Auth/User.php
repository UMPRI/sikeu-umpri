<?php

namespace App\Model\Auth;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laratrust\Traits\LaratrustUserTrait;
use Auth;
use DB;
/**
 * @property Role[] $roles
 * @property int $id
 * @property string $name
 * @property string $email
 * @property int $Faculty_Id
 * @property string $password
 * @property string $remember_token
 * @property string $created_at
 * @property string $updated_at
 */
class User extends Authenticatable
{
    use LaratrustUserTrait;
    use Notifiable;
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
    protected $hidden = [
        'password', 'remember_token',
    ];
    public function roles()
    {
        return $this->belongsToMany('App\Model\Auth\Role', '_role_user', 'user_id', 'role_id');
    }
}
