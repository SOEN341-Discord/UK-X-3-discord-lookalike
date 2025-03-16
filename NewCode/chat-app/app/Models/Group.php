<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    use HasFactory;

    // Allow mass assignment for these fields
    protected $fillable = [
        'name',
        'avatar_url',
        'description'
    ];

    // A group can have many messages (if you're using groups for messaging)
    public function messages()
    {
        return $this->hasMany(Message::class);
    }

    // Many-to-many relationship: a group has many users
    public function users()
    {
        return $this->belongsToMany(User::class);
    }
}
