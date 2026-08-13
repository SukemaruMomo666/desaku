<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LetterRequest extends Model
{
    protected $fillable = [
        'user_id',
        'letter_type_id',
        'submitted_data',
        'uploaded_files',
        'status',
        'admin_notes',
    ];

    protected $casts = [
        'submitted_data' => 'array',
        'uploaded_files' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function letterType()
    {
        return $this->belongsTo(LetterType::class);
    }
}
