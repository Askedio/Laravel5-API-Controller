<?php

namespace Askedio\Tests\App;

use Illuminate\Foundation\Auth\User as Authenticatable;

class UserAlt extends Authenticatable
{
    use \Askedio\Laravel5ApiController\Traits\ModelTrait;
    use \Sofa\Eloquence\Eloquence;

    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $includes = [
        \Askedio\Tests\App\Profiles::class,
    ];

    protected $rules = [
        'update' => [
            'email' => 'email|unique:users,email',
        ],
        'create' => [
            'email' => 'email|required|unique:users,email',
        ],
    ];


    protected $primaryKey = 'id';

    public function transform(User $user)
    {
        return [
            'id'    => $user->id,
            'name'  => $user->name,
            'email' => $user->email,
        ];
    }

    public function profiles()
    {
        return $this->hasMany('Askedio\Tests\App\Profiles');
    }
}
