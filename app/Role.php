<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $name
 * @property string $description
 * @property string $created_at
 * @property string $updated_at
 * @property Access[] $accesses
 * @property Accesskeuangan[] $accesskeuangans
 * @property User[] $users
 */
class Role extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = '_role';

    /**
     * @var array
     */
    protected $fillable = ['name', 'description', 'created_at', 'updated_at'];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function accesskeuangans()
    {
        return $this->belongsToMany('App\Accesskeuangan', '_role_accesskeuangan', 'role_id', 'accesskeuangan_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function users()
    {
        return $this->belongsToMany('App\User', '_role_user', 'role_id', 'user_id');
    }
}
