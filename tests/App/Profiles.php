<?php

namespace Askedio\Tests\App;

use Illuminate\Database\Eloquent\Model;

class Profiles extends Model
{
    use \Askedio\Laravel5ApiController\Traits\ModelTrait;
    use \Sofa\Eloquence\Eloquence;

    protected $includes = [
    ];

    public function user()
    {
        return $this->belongsTo('Askedio\Tests\App\User');
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
