<?php
namespace App\Http\Controllers\Frontend;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\MassDestroyServiceRequestRequest;
use App\Http\Requests\StoreServiceRequestRequest;
use App\Http\Requests\UpdateServiceRequestRequest;
use App\Models\Pet;
use App\Models\Service;
use App\Models\ServiceRequest;
use App\Models\User;
use Gate;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Symfony\Component\HttpFoundation\Response;
use Auth;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;
use App\Models\Credit;
use App\Jobs\SendServiceRequestEmailJob;
use App\Mail\ServiceRequestEmail;
use App\Models\UserAlert;




class ServiceRequestsController extends Controller
{
    

    public function index(Request $request)
    {
        abort_if(Gate::denies('service_request_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        
        $today = Carbon::now()->timezone(Auth::user()->timezone);

        $zip = $request->input('zip');
        $radius = $request->input('radius');

        if ($zip && $radius) {
            $serviceRequests = $this->findNearbyRequests($zip, $radius);
        } else {
            $serviceRequests = ServiceRequest::with(['service', 'pet', 'user', 'booking'])
               // ->where('closed', 0)
                ->orderBy('created_at', 'desc')
                ->where('user_id', Auth::id())
                ->take(10)
                ->paginate();
        }

        return view('frontend.serviceRequests.index', compact('serviceRequests', 'today'));
    }

    public function findNearbyRequests($zip, $maxRadius)
    {
        $radius = $maxRadius; // Radius in miles

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
            ->where('user_id', Auth::id())
            ->take(10)
            ->get();

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

    public function create()
    {
        abort_if(Gate::denies('service_request_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $services = Service::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $pets = Pet::with('media')->where('user_id', auth()->id())->get();

        $users = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('frontend.serviceRequests.create', compact('pets', 'services', 'users'));
    }

    public function store(StoreServiceRequestRequest $request)
    {
        $credits = Carbon::parse($request->from)->diffInHours(Carbon::parse($request->to));

        $userCredit = Credit::where('user_id', auth()->id())->first();

        if (!$userCredit) {
            $userCredit = Credit::create([
                'user_id' => auth()->id(),
                'points' => 24,
            ]);
        }

        if ($userCredit->points == 0 || $userCredit->points < $credits) {
            return back()->with('error', 'You do not have enough credit to make a request. Start by caring for someone\'s pet. To earn credits, you can care for someone\'s pet or invite a friend to join the platform.');
        }

        if ($request->from > $request->to) {
            return back()->with('error', 'From date must be less than To date');
        }

        $serviceRequest = ServiceRequest::create($request->all());

        if ($media = $request->input('ck-media', false)) {
            Media::whereIn('id', $media)->update(['model_id' => $serviceRequest->id]);
        }

        $userCredit->service_request_id = $serviceRequest->id;
        $userCredit->points -= $credits;
        
        if($userCredit->save()){
            //Send an email to all users within 10 miles of the zip code
            $zip = $request->zip_code;
            $radius = 10;
            $users = $this->findNearbyMembers($zip, $radius);
          
            
            //Create the user alert
              $userAlerts = new UserAlert();
              $userAlerts->alert_text = 'A new service request has been posted near you. Check it out now!';
              $userAlerts->alert_link = route('frontend.service-requests.index');
              $userAlerts->save();
           
              foreach ($users as $user) {
              //Create a user alert for each user, insert into the user_user_alerts table
                $userAlerts->users()->attach($user->id);

            }
        }
        
        return redirect()->route('frontend.service-requests.index');
    }

    public function edit(ServiceRequest $serviceRequest)
    {
        abort_if(Gate::denies('service_request_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $services = Service::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $pets = Pet::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $users = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $serviceRequest->load('service', 'pet', 'user');

        return view('frontend.serviceRequests.edit', compact('pets', 'serviceRequest', 'services', 'users'));
    }

    public function update(UpdateServiceRequestRequest $request, ServiceRequest $serviceRequest)
    {
        $serviceRequest->update($request->all());

        return redirect()->route('frontend.service-requests.index');
    }

    public function show(ServiceRequest $serviceRequest)
    {
        abort_if(Gate::denies('service_request_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $serviceRequest->load('service', 'pet', 'user');

        return view('frontend.serviceRequests.show', compact('serviceRequest'));
    }

    public function destroy(ServiceRequest $serviceRequest)
    {
        abort_if(Gate::denies('service_request_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $serviceRequest->delete();

        return back();
    }

    public function massDestroy(MassDestroyServiceRequestRequest $request)
    {
        $serviceRequests = ServiceRequest::find(request('ids'));

        foreach ($serviceRequests as $serviceRequest) {
            $serviceRequest->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function storeCKEditorImages(Request $request)
    {
        abort_if(Gate::denies('service_request_create') && Gate::denies('service_request_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $model = new ServiceRequest();
        $model->id = $request->input('crud_id', 0);
        $model->exists = true;
        $media = $model->addMediaFromRequest('upload')->toMediaCollection('ck-media');

        return response()->json(['id' => $media->id, 'url' => $media->getUrl()], Response::HTTP_CREATED);
    }


    public function findNearbyMembers($zip, $max_radius)
    {
        $members = User::with(['roles', 'media'])
        ->where('id', '!=', Auth::id())
        ->get();
        $nearbyMembers = [];
        $zip = $this->getZipCode($zip);
        $zipLat = $zip['lat'];
        $zipLon = $zip['lon'];
        foreach ($members as $user) {
            $userZip = $this->getZipCode($user->zip);
            $userLat = $userZip['lat'];
            $userLon = $userZip['lon'];
            $distance = $this->calculateDistance($zipLat, $zipLon, $userLat, $userLon);
            if ($distance <= $max_radius) {
                $users[] = $user;
            }
        }
        return $users;
    }
    

    public function getZipCode($zip)
    {
        $url = "https://api.zippopotam.us/us/$zip";
        $response = json_decode(file_get_contents($url));
        $lat = $response->places[0]->latitude;
        $lon = $response->places[0]->longitude;
        return ['lat' => $lat, 'lon' => $lon];
    }

}
