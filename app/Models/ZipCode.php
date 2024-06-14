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

    //Add the calculate method
    public function calculate($lat1, $lon1, $lat2, $lon2)
    {
        $theta = $lon1 - $lon2;
        $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
        $dist = acos($dist);
        $dist = rad2deg($dist);
        $miles = $dist * 60 * 1.1515;
        return ($miles * 1.609344);
    }

    //Add the getNearbyMembers method
    public function getNearbyMembers($zip, $radius)
    {
        $zip = $this->getZipCode($zip);
        $zipLat = $zip['lat'];
        $zipLng = $zip['lng'];
        $members = User::all();
        $nearbyMembers = [];
        foreach ($members as $member) {
            $memberZip = $this->getZipCode($member->zip);
            $memberLat = $memberZip['lat'];
            $memberLng = $memberZip['lng'];
            $distance = $this->calculate($zipLat, $zipLng, $memberLat, $memberLng);
            if ($distance <= $radius) {
                $nearbyMembers[] = $member;
            }
        }
        return $nearbyMembers;
    }

    //Add the getNearbyRequests method
    public function getNearbyRequests($zip, $radius)
    {
        $zip = $this->getZipCode($zip);
        $zipLat = $zip['lat'];
        $zipLng = $zip['lng'];
        $requests = ServiceRequest::all();
        $nearbyRequests = [];
        foreach ($requests as $request) {
            $requestZip = $this->getZipCode($request->zip_code);
            $requestLat = $requestZip['lat'];
            $requestLng = $requestZip['lng'];
            $distance = $this->calculate($zipLat, $zipLng, $requestLat, $requestLng);
            if ($distance <= $radius) {
                $nearbyRequests[] = $request;
            }
        }
        return $nearbyRequests;
    }

    //Add the getNearbyUsers method
    public function getNearbyUsers($zip, $radius)
    {
        $zip = $this->getZipCode($zip);
        $zipLat = $zip['lat'];
        $zipLng = $zip['lng'];
        $users = User::all();
        $nearbyUsers = [];
        foreach ($users as $user) {
            $userZip = $this->getZipCode($user->zip);
            $userLat = $userZip['lat'];
            $userLng = $userZip['lng'];
            $distance = $this->calculate($zipLat, $zipLng, $userLat, $userLng);
            if ($distance <= $radius) {
                $nearbyUsers[] = $user;
            }
        }
        return $nearbyUsers;
    }

    
}




