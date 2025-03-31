<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\ProjectDefaultItems;

class Project extends Model
{

    protected $table = 'project';
    protected $fillable = [
        'ProjectName','UserId','Status'
    ];

    public function defaultItems()
    {
        return $this->hasMany(ProjectDefaultItems::class, 'projectId');
    }
}
