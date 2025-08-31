<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $name
 * @property string $description
 * @property string $create_at
 * @property string $update_at
 * @property Role[] $roles
 */
class Accesskeuangan extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = '_accesskeuangan';

    /**
     * @var array
     */
    protected $fillable = ['name', 'description', 'create_at', 'update_at'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function roles()
    {
        return $this->belongsToMany('App\Role', '_role_accesskeuangan', 'accesskeuangan_id', 'role_id');
    }
}
