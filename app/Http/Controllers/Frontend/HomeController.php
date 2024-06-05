<?php

namespace App\Http\Controllers\Frontend;
use App\Http\Controllers\Controller;
use App\Models\ServiceRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use Gate;


class HomeController
{
   
    public function index(Request $req)
    {
        abort_if(Gate::denies('service_request_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        //if users timezone or zipcode is not set, redirect them to the profile page with message
        if(!auth()->user()->timezone && !auth()->user()->zip_code && !auth()->user()->city && !auth()->user()->state) {
            return redirect()->route('frontend.profile.index')->with('message', 'Please set your timezone and zip code, city and state to view service requests');
        }

        if($req->has('zip') && $req->has('radius')) {
            $serviceRequests = $this->findNearbyRequests($req->zip, $req->radius);
        } else {
           
            $serviceRequests = ServiceRequest::with(['service', 'pet', 'user'])
            ->where('closed', 0)
            ->where('pending', 0)
            ->whereDate('from', '>=', now())
            ->whereDate('to', '<=', now()->addMonth(1))
            ->orderBy('created_at', 'desc')
            ->where('user_id', '!=', Auth::id())
            ->paginate(10);
        }
        return view('frontend.home', compact('serviceRequests'));
    }

    public function findNearbyRequests($zip, $max_radius)
{
    $radius = $max_radius; // Radius in miles

    try {
        $response = Http::get("https://api.zippopotam.us/us/$zip");
        $data = $response->json();
        $latitude = $data['places'][0]['latitude'];
        $longitude = $data['places'][0]['longitude'];
    } catch (\Exception $e) {
        return collect(); // Return an empty collection on error
    }

    $requests = ServiceRequest::with(['service', 'pet', 'user'])
        ->where('closed', 0)
        ->whereDate('from', '>=', now())
        ->whereDate('to', '<=', now()->addMonth(1))
        ->orderBy('created_at', 'asc')
        ->where('user_id', '!=', Auth::id())
        ->take(10)->get();

    $filteredRequests = $requests->filter(function ($request) use ($latitude, $longitude, $radius) {
        try {
            $response = Http::get("https://api.zippopotam.us/us/{$request->zip_code}");
            $data = $response->json();
            $requestLatitude = $data['places'][0]['latitude'];
            $requestLongitude = $data['places'][0]['longitude'];
            $distance = $this->calculateDistance(
                $latitude,
                $longitude,
                $requestLatitude,
                $requestLongitude
            );
            return $distance <= $radius;
        } catch (\Exception $e) {
            // Handle invalid or null zip code for each request
            return false;
        }
    });

    return $filteredRequests;
}

public function calculateDistance($lat1, $lon1, $lat2, $lon2)
{
    $earthRadius = 3959; // Radius of the earth in miles

    $dLat = deg2rad($lat2 - $lat1);
    $dLon = deg2rad($lon2 - $lon1);

    $a = sin($dLat / 2) * sin($dLat / 2) +
        cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
        sin($dLon / 2) * sin($dLon / 2);

    $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

    $distance = $earthRadius * $c; // Distance in miles

    return $distance;
}


}
