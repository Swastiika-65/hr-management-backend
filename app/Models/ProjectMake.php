<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class ProjectMake extends Model
{
    use HasFactory;

    protected $table = 'projects'; // existing table

    protected $fillable = ['name', 'description', 'team_lead_id'];

    // ⚡ THIS IS REQUIRED
    public function users()
    {
        return $this->belongsToMany(User::class, 'project_user', 'project_id', 'user_id');
    }
}
