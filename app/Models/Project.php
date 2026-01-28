<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    protected $fillable = ['name','description','team_lead_id'];

    public function employees() {
        return $this->belongsToMany(User::class);
    }

    public function teamLead() {
        return $this->belongsTo(User::class,'team_lead_id');
    }
}
