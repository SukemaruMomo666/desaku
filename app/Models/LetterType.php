<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LetterType extends Model
{
    protected $fillable = [
        'name',
        'code',
        'description',
        'requirements',
        'form_fields',
        'template_file',
        'is_active',
    ];

    protected $casts = [
        'requirements' => 'array',
        'form_fields' => 'array',
        'is_active' => 'boolean',
    ];

    public function requests()
    {
        return $this->hasMany(LetterRequest::class);
    }
}
