<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

//     Item - egy kiállított tárgy alapvető adatai
// id
// name (string)
// description (szöveg)
// obtained (dátum)
// image (string, lehet null)
// időbélyegek
    protected $fillable = [
        'name',
        'description',
        'obtained',
        'image',
    ];

    public function labels() {
        return $this->belongsToMany(Label::class);
    }

    public function comments() {
        return $this->hasMany(Comment::class, 'item_id');
    }

}
