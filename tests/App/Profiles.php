<?php

namespace Askedio\Tests\App;

use Illuminate\Database\Eloquent\Model;

class Profiles extends Model
{
    use \Askedio\Laravel5ApiController\Traits\ApiTrait;
    use \Askedio\Laravel5ApiController\Traits\SearchableTrait;

    protected $includes = [
        'profiles',
    ];

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'phone',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
    ];

    protected $rules = [
    ];

    protected $searchable = [
        'columns' => [
            'profile.phone' => 10,
        ],
    ];

    protected $primaryKey = 'id';

    public function transform(Profiles $profile)
    {
        return [
            'id'    => 'iii',
            'phone' => 'iii',
        ];
    }
}
