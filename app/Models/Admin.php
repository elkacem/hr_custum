<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;


class Admin extends Authenticatable
{

     protected $guard = 'admin';
        // Set fillable fields (update as needed)
    protected $fillable = ['username', 'email', 'password'];

    // Hide password fields when serializing
    protected $hidden = ['password', 'remember_token'];

    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function isModerator()
    {
        return $this->role === 'moderator';
    }
}
