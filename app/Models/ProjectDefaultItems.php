<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProjectDefaultItems extends Model
{
    protected $table = 'project_default_items';

    // A default item belongs to a project
    public function project()
    {
        return $this->belongsTo(Project::class, 'projectId');
    }
}
