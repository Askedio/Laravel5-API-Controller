<?php

namespace Askedio\Tests\App;

use Illuminate\Database\Eloquent\Model;

class Profiles extends Model
{
    use \Askedio\Laravel5ApiController\Traits\ModelTrait;
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
}
