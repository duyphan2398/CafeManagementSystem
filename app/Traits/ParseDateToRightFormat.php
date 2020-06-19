<?php

namespace App\Traits;


use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

trait ParseDateToRightFormat
{
    /**
     *Parse date to right format when creating and updating
     */
    public static function bootParseDateToRightFormat(){
        static::creating(function(Model $model) {
            $model->created_at = Carbon::now()->timezone(config('app.timezone'));
            $model->updated_at = Carbon::now()->timezone(config('app.timezone'));
        });

        static::updating(function(Model $model) {
            $model->updated_at = Carbon::now()->timezone(config('app.timezone'));
        });
    }


    /*Get date*/
    public function parseDate($value){

        return $value ? Carbon::parse($value)->timezone(config('app.timezone'))->format('H:i d-m-Y') : '';
    }

    public function getCreatedAtAttribute($value)
    {
        return $this->parseDate($value);
    }

    public function getUpdatedAtAttribute($value)
    {
        return $this->parseDate($value);
    }

    public function getDeletedAtAttribute($value)
    {
        return $this->parseDate($value);
    }
}
