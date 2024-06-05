<?php

namespace App\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Credit extends Model
{
    use SoftDeletes, HasFactory;

    public $table = 'credits';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'service_request_id',
        'points',
        'created_at',
        'user_id',
        'updated_at',
        'deleted_at',
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function service_request()
    {
        return $this->belongsTo(ServiceRequest::class, 'service_request_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
