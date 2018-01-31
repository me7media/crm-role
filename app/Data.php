<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

use App\User;
class Data extends Model
{

 protected $primaryKey = 'id';

    protected $fillable = [
        'day', 'time', 'com', 'hash', 'user_id',
    ];


public function user() {
    return $this->belongsTo('App\User');
  }
}