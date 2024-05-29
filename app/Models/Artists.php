<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Artist extends Model
{
  protected $table = 'artists';

  protected $fillable = [
    'id',
    'username',
    'first_name',
    'last_name',
    'email',
    'avatar',
    'currency',
  ];

  protected $hidden = [
    'password',
    'remember_token',
  ];

  protected function casts(): array
  {
    return [
      'email_verified_at' => 'datetime',
      'password' => 'hashed',
    ];
  }
}
