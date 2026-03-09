<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SelectedOption extends Model
{
    protected $table = 'selected_option';

    protected $fillable = [
        'configurableitems',
        'optionId',
        'tag',
        'SelectedUnderAttribute',
        'SelectedOptionKey',
        'SelectedOptionValue',
        'SelectedOptionSlug',
        'SelectedUserId',
        'SelectedOptionCost'
    ];
}
