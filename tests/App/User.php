<?php

namespace Askedio\Tests\App;

use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use \Askedio\Laravel5ApiController\Traits\ApiTrait;
    use \Askedio\Laravel5ApiController\Traits\SearchableTrait;

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
      'profiles',
      'addresses',
  ];

    protected $rules = [
    'update' => [
      'email' => 'email|unique:users,email',
    ],
    'create' => [
      'email' => 'email|required|unique:users,email',
    ],
  ];

    protected $searchable = [
      'columns' => [
          'users.name'  => 10,
          'users.email' => 5,
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
        return $this->belongsToMany('App\Profiles');
    }

    public function addresses()
    {
        return $this->belongsToMany('App\Addresses');
    }
}
