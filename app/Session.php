<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Session extends Model
{

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function submissions()
    {
        return $this->hasMany('App\Submission');
    }

    protected $fillable = ['time', 'finished'];

}
