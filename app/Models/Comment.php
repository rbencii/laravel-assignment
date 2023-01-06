<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Comment extends Model
{
    use HasFactory, SoftDeletes;

//     Comment - a kiállított tárgyakhoz hozzá lehet szólni
// id
// text (szöveg)
// időbélyegek
    protected $fillable = [
        'text',
        'author_id',
        'item_id',
    ];

    public function author() {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function item() {
        return $this->belongsTo(Item::class, 'item_id');
    }

}
