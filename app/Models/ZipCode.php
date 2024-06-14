<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;



class ZipCode extends Model
{
    protected $table = 'zip_codes';

    protected $fillable = [
        'code',
        'city',
        'state',
    ];

    // Define any relationships or additional methods here

    //Add the getZipCode method here
    public function getZipCode($zip)
    {
        $zip = $this->where('code', $zip)->first();
        if ($zip) {
            return [
                'lat' => $zip->latitude,
                'lng' => $zip->longitude,
            ];
        }
        return null;
    }

    //Add the calculate
}




