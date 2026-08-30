<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Signatory extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'position',
        'nip',
        'is_active',
    ];

    public function letterRequests()
    {
        return $this->hasMany(LetterRequest::class);
    }
}
