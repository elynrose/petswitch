<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyBookingRequest;
use App\Http\Requests\StoreBookingRequest;
use App\Http\Requests\UpdateBookingRequest;
use App\Models\Booking;
use App\Models\ServiceRequest;
use App\Models\User;
use App\Models\Credit;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Auth;
use Carbon\Carbon;

class BookingsController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('booking_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $bookings = Booking::with(['service_request', 'pet', 'user'])
            ->where('decline', '0')
            ->where('service_request_id', '!=', null)
            ->where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();

        return view('frontend.bookings.index', compact('bookings'));
    }

    public function create()
    {
        abort_if(Gate::denies('booking_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $service_requests = ServiceRequest::pluck('from', 'id')->prepend(trans('global.pleaseSelect'), '');

        $users = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('frontend.bookings.create', compact('service_requests', 'users'));
    }

    public function store(StoreBookingRequest $request)
    {
        //if this pet has an active booking status, do not allow the user to book another service request
        $active_booking = Booking::where('service_request_id', $request->service_request_id)
        ->where('decline', '0')
        ->where('user_id', Auth::id())
        ->first();

        if(!$active_booking){

        $booking = Booking::create($request->all());

  }
        if($booking){

            //Get the hours between the from and to date
            $credits_earned = Carbon::parse($booking->service_request->from)->diffInHours(Carbon::parse($booking->service_request->to));

            $booking->service_request->pending = '1'; //Set the service request status to 'pending'
            $booking->service_request->save();



            //If booking and credit creation is successful, send an email notification to the user who created the service request and the user who booked the service request
            $booked = [
                'booking_id' => $booking->id,
                'pet_name' => $booking->service_request->pet->name,
                'service_name' => $booking->service_request->service->name,
                'from' => $booking->service_request->from,
                'to' => $booking->service_request->to,
                'created_by' => $booking->service_request->user->name,
                'email' => $booking->service_request->user->email,
                'phone' => $booking->service_request->user->phone,
                'credits_earned' => Carbon::parse($booking->service_request->from)->diffInHours(Carbon::parse($booking->service_request->to)),
            ];

            /*
            $booking->created_by->notify(new BookingCreated([
                'booked' => $booked,
                'requester_email' => $booking->user->email,
                'requester_name' => $booking->user->name,
            ]));
            $booking->service_request->created_by->notify(new BookingCreated([
                'booked' => $booked,
                'requester_email' => $booking->user->email,
                'requester_name' => $booking->user->name,
            ]));
*/
            // Send an sms text notification via twilio api to both parties using phone number

          //  $this->sendSMS($booking->user->phone, 'Your booking has been created successfully. Please reach out to '.$booked['created_by'].' at '.$booked['phone'].' to finalize the arrangements. We will notify you when '.$booked['pet_name'].' should be ready for pickup.');

        }

        return redirect()->route('frontend.bookings.index');
    }


    public function edit(Booking $booking)
    {
        abort_if(Gate::denies('booking_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $service_requests = ServiceRequest::pluck('from', 'id')->prepend(trans('global.pleaseSelect'), '');

        $users = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $booking->load('service_request', 'user');

        return view('frontend.bookings.edit', compact('booking', 'service_requests', 'users'));
    }

    public function update(UpdateBookingRequest $request, Booking $booking)
    {
        $booking->update($request->all());

        return redirect()->route('frontend.bookings.index');
    }

    public function show(Booking $booking)
    {
        abort_if(Gate::denies('booking_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $booking->load('service_request', 'user');

        return view('frontend.bookings.show', compact('booking'));
    }

    public function destroy(Booking $booking)
    {
        abort_if(Gate::denies('booking_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $booking->delete();

        return back();
    }

    public function massDestroy(MassDestroyBookingRequest $request)
    {
        $bookings = Booking::find(request('ids'));

        foreach ($bookings as $booking) {
            $booking->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }


    public function completed(Request $request)
    {
        $booking = Booking::with(['service_requests', 'pet'])->where('service_request_id', $request->service_request_id)
        ->where('decline', '0')->first();
        //Reopen the service request
        $booking->service_request->closed = '1';
        $booking->service_request->pending = '2'; //Set the service request status to 'completed'
        $booking->service_request->save();

        //Award the user who booked the service request with credits
        $total_credits = Credit::where('user_id', $booking->user_id)->first();
        $credits = Carbon::parse($booking->service_request->from)->diffInHours(Carbon::parse($booking->service_request->to));
        //Award the user who created the service request with the credits earned
                    
        //Update credit table for the user  
        $total_credits->points += $credits;
        $total_credits->save();
        
        $booked = [
            'booking_id' => $booking->id,
            'pet_name' => $booking->service_request->pet->name,
            'service_name' => $booking->service_request->service->name,
            'from' => $booking->service_request->from,
            'to' => $booking->service_request->to,
            'created_by' => $booking->service_request->user->name,
            'requester_email' => $booking->service_request->user->email,
            'booker_email' => $booking->user->email,
            'credits_earned' => Carbon::parse($booking->service_request->from)->diffInHours(Carbon::parse($booking->service_request->to)),
        ];
        
          /*
        $booking->created_by->notify(new BookingDeclined([
            'booked' => $booked,
            'requester_email' => $booking->user->email,
            'requester_name' => $booking->user->name,
        ]));

      $booking->service_request->created_by->notify(new BookingDeclined([
            'booked' => $booked,
            'requester_email' => $booking->user->email,
            'requester_name' => $booking->user->name,
        ])); */

        // Send an sms text notification via twilio api to both parties using phone number

       // $this->sendSMS($booking->user->phone, 'Your booking has been declined. We will notify you when a new booking is created.');

        return redirect()->route('frontend.service-requests.index');
    }



    public function decline(Request $request)
    {
        $booking = Booking::find($request->booking_id);
        $booking->decline = 1;
        $booking->save();
        
        $credits = Carbon::parse($booking->service_request->from)->diffInHours(Carbon::parse($booking->service_request->to));
        
        //Check how many credits this user has
        $total_credits = Credit::where('user_id', Auth::id())->first();
        
        if(is_null($total_credits)){
            $total_credits = new Credit();
            $total_credits->user_id = Auth::id();
            $total_credits->points = 0;
            $total_credits->save();
        } elseif($total_credits->points >= $credits) {
            $total_credits->points -= $credits;
            $total_credits->save();
        }

        //Reopen the service request
        $booking->service_request->closed = '0';
        $booking->service_request->pending = '0';
        $booking->service_request->save();

        //Send a notification to the user who created the service request and the user who booked the service request
        $booked = [
            'booking_id' => $booking->id,
            'pet_name' => $booking->service_request->pet->name,
            'service_name' => $booking->service_request->service->name,
            'from' => $booking->service_request->from,
            'to' => $booking->service_request->to,
            'created_by' => $booking->service_request->user->name,
            'email' => $booking->service_request->user->email,
            'credits_earned' => Carbon::parse($booking->service_request->from)->diffInHours(Carbon::parse($booking->service_request->to)),
        ];
  /*
        $booking->created_by->notify(new BookingDeclined([
            'booked' => $booked,
            'requester_email' => $booking->user->email,
            'requester_name' => $booking->user->name,
        ]));

      $booking->service_request->created_by->notify(new BookingDeclined([
            'booked' => $booked,
            'requester_email' => $booking->user->email,
            'requester_name' => $booking->user->name,
        ])); */

        // Send an sms text notification via twilio api to both parties using phone number

       // $this->sendSMS($booking->user->phone, 'Your booking has been declined. We will notify you when a new booking is created.');

        return redirect()->route('frontend.bookings.index');
    }

    //Create a function to send an sms text notification to both parties using twilio api
    
    public function sendSMS($to, $message){
        $sid    =   env('TWILIO_SID');      
        $token  =   env('TWILIO_AUTH_TOKEN');
        $from   =   env('TWILIO_PHONE_NUMBER');
        $client = new TwilioClient($sid, $token);
        $client->messages->create(
            $to,
            [
                'from' => $from,
                'body' => $message
            ]
        );
    }
}
