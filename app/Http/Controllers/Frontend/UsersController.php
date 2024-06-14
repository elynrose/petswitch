<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\MassDestroyUserRequest;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\Role;
use App\Models\User;
use Gate;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;
use App\Models\Credit;
use App\Models\Booking;
use App\Models\ServiceRequest;
use App\Models\Pet;
use App\Models\Review;
use App\Models\Availability;
use App\Models\Service;


class UsersController extends Controller
{
    use MediaUploadingTrait;

    public function index()
    {
        abort_if(Gate::denies('user_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        //Add search functionality for zip and radius
        if (request()->input('zip') && request()->input('radius')) {
            $zip = request()->input('zip');
            $radius = request()->input('radius');
            
           $users = $this->findNearbyMembers($zip, $radius);
           
        } else {

        $users = User::with(['roles', 'media'])
        //->where('id', '!=', Auth::id())
        ->paginate(12);

        }

        return view('frontend.users.index', compact('users'));
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

    public function getZipCode($zip)
    {
        $url = "https://api.zippopotam.us/us/$zip";
        $response = json_decode(file_get_contents($url));
        $lat = $response->places[0]->latitude;
        $lon = $response->places[0]->longitude;
        return ['lat' => $lat, 'lon' => $lon];
    }

    public function create()
    {
        abort_if(Gate::denies('user_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $roles = Role::pluck('title', 'id');

        return view('frontend.users.create', compact('roles'));
    }

    public function store(StoreUserRequest $request)
    {
        $user = User::create($request->all());
        $user->roles()->sync($request->input('roles', []));
        if ($request->input('profile_photo', false)) {
            $user->addMedia(storage_path('tmp/uploads/' . basename($request->input('profile_photo'))))->toMediaCollection('profile_photo');
        }

        if ($media = $request->input('ck-media', false)) {
            Media::whereIn('id', $media)->update(['model_id' => $user->id]);
        }

        return redirect()->route('frontend.users.index');
    }

    public function edit(User $user)
    {
        abort_if(Gate::denies('user_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $roles = Role::pluck('title', 'id');

        $user->load('roles');

        return view('frontend.users.edit', compact('roles', 'user'));
    }

    public function update(UpdateUserRequest $request, User $user)
    {
        $user->update($request->all());
        $user->roles()->sync($request->input('roles', []));
        if ($request->input('profile_photo', false)) {
            if (! $user->profile_photo || $request->input('profile_photo') !== $user->profile_photo->file_name) {
                if ($user->profile_photo) {
                    $user->profile_photo->delete();
                }
                $user->addMedia(storage_path('tmp/uploads/' . basename($request->input('profile_photo'))))->toMediaCollection('profile_photo');
            }
        } elseif ($user->profile_photo) {
            $user->profile_photo->delete();
        }

        return redirect()->route('frontend.users.index');
    }

    public function show(User $user)
    {
        abort_if(Gate::denies('user_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $user->load('roles', 'userUserAlerts');

        //Get users total credits
        $total_credits = Credit::Select('points')
        ->where('user_id', $user->id)
        ->first();

        //Get users total bookings
        $total_bookings = Booking::where('decline', 0)
        ->where('user_id', $user->id)->count();

        //Get users total service requests
        $total_service_requests = ServiceRequest::where('closed', 0)->count();

        //Get users total pets
        $total_pets = Pet::where('user_id', $user->id)->count();

        //Get users total reviews
        $total_reviews = Review::where('user_id', $user->id)->count();

        //Get average of review{rating)
        $average_rating = Review::where('user_id', $user->id)->avg('rating');

        //Next available date
        $next_available_date = Availability::where('user_id', $user->id)->first();

        //Get all services 
        $services = Service::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('frontend.users.show', compact('services','user', 'total_credits', 'total_bookings', 'total_service_requests', 'total_pets', 'total_reviews', 'average_rating', 'next_available_date'));
    }

    public function destroy(User $user)
    {
        abort_if(Gate::denies('user_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $user->delete();

        return back();
    }

    public function massDestroy(MassDestroyUserRequest $request)
    {
        $users = User::find(request('ids'));

        foreach ($users as $user) {
            $user->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function storeCKEditorImages(Request $request)
    {
        abort_if(Gate::denies('user_create') && Gate::denies('user_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $model         = new User();
        $model->id     = $request->input('crud_id', 0);
        $model->exists = true;
        $media         = $model->addMediaFromRequest('upload')->toMediaCollection('ck-media');

        return response()->json(['id' => $media->id, 'url' => $media->getUrl()], Response::HTTP_CREATED);
    }
}
