<?php

namespace Askedio\Tests\App;

use Illuminate\Foundation\Auth\User as Authenticatable;

class UserAlt extends Authenticatable
{
    use \Askedio\Laravel5ApiController\Traits\ModelTrait;
    use \Askedio\Laravel5ApiController\Traits\SearchableTrait;

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
      'profiles',
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
          'users.name'     => 10,
          'users.email'    => 5,
          'profiles.phone' => 5,
      ],
      'joins' => [
        'profiles' => ['users.id', 'profiles.user_id', 'users.id', 'profiles.user_id'],
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
