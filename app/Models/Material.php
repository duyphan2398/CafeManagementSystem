<?php


namespace App\Models;

use App\Traits\ParseDateToRightFormat;
use Illuminate\Database\Eloquent\Model;
use Maatwebsite\Excel\Concerns\Exportable;


class Material extends Model
{
    use Exportable;
    use ParseDateToRightFormat;
    protected $fillable = [
        'name',
        'amount',
        'unit',
        'note'
    ];

    // ======================================================================
    // Relationships
    // ======================================================================
    public function products(){
        return $this->belongsToMany( Product::class, 'ingredients')->withPivot('quantity','unit');;
    }
}
