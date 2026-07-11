<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;

#[Fillable(['user_id', 'category_id', 'amount', 'description', 'date'])]
/**
 * A financial expense belonging to a user and category.
 */
class Expense extends Model
{
    public function user() 
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
