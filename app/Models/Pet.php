<?php

namespace App\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Pet extends Model implements HasMedia
{
    use SoftDeletes, InteractsWithMedia, HasFactory;

    public $table = 'pets';

    protected $appends = [
        'photos',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    
    public const GENDER_SELECT = [
        'Male' => 'Male',
        'Female' => 'Female',
        'Undisclosed' => 'Undisclosed',
    ];

    public const GETS_ALONG_WITH_RADIO = [
        'dogs' => 'Dogs',
        'cats' => 'Cats',
        'both' => 'Both',
    ];

    public const SIZE_SELECT = [
        '0-15'   => '0-15',
        '16-40'  => '16-40',
        '40-100' => '40-100',
        '100+'   => '100+',
    ];

    protected $fillable = [
        'animal_id',
        'name',
        'breed',
        'size',
        'age',
        'gets_along_with',
        'is_immunized',
        'user_id',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('thumb')->fit('crop', 50, 50);
        $this->addMediaConversion('preview')->fit('crop', 240, 240);
    }

    public function getPhotosAttribute()
    {
        $file = $this->getMedia('photos')->last();
        if ($file) {
            $file->url       = $file->getUrl();
            $file->thumbnail = $file->getUrl('thumb');
            $file->preview   = $file->getUrl('preview');
        }

        return $file;
    }

    public function animal()
    {
        return $this->belongsTo(Animal::class, 'animal_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
