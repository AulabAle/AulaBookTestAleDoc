<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PurchasedBook extends Model
{
    use HasFactory , SoftDeletes;
    protected $fillable = [
        'user_id',
        'book_id',
        'price',
        'payment_status',
        'session_id'
    ];
    public function getDescriptionSubstring(){
        if (strlen($this->description) > 20) {
                return substr($this->description, 0 , 30) . '...';
        }
        return $this->description;
    }
    public function user() : BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    public function book() : BelongsTo
    {
        return $this->belongsTo(Book::class);
    }
}
