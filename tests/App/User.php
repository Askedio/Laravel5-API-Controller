<?php

namespace Askedio\Tests\App;

use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use \Askedio\Laravel5ApiController\Traits\ApiTrait;
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

    /**
     * The attributes for validation.
     *
     * @var array
     */
    protected $rules = [
      'update' => [
      ],
      'create' => [
            'name' => 'required|min:2',
      ],
    ];

    /**
     * Searchable rules.
     *
     * @var array
     */
    protected $searchable = [
        'columns' => [
            'users.name'  => 10,
            'users.email' => 5,
        ],
    ];

    protected $includes = ['test'];

    public function transform(User $user)
    {
        return [
          'id'    => $user->id,
          'name'  => $user->name,
          'email' => $user->email,
      ];
    }
}
