<?php


namespace App\Models;

use App\Traits\ParseTimeStamp;
use Illuminate\Database\Eloquent\Model;

class Table extends Model
{
    use ParseTimeStamp;

    protected $fillable = [
        'name',
        'note',
        'status'
    ];

    protected $attributes = [
        'status' => 'Empty'
    ];
    // ======================================================================
    // Relationships
    // ======================================================================

    public function receipts(){
        return $this->hasMany(Receipt::class);
    }
}

