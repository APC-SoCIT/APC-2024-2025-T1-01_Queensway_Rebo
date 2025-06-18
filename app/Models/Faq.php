<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Faq extends Model
{
    protected $fillable = ['question', 'keywords', 'answer'];

    // Accessor to get keywords as array
    public function getKeywordsArrayAttribute()
    {
        return explode(',', $this->keywords);
    }
}
