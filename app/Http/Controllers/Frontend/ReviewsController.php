<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyReviewRequest;
use App\Http\Requests\StoreReviewRequest;
use App\Http\Requests\UpdateReviewRequest;
use App\Models\Review;
use App\Models\ServiceRequest;
use App\Models\User;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ReviewsController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('review_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $reviews = Review::with(['service_request', 'user'])->get();

        return view('frontend.reviews.index', compact('reviews'));
    }

    public function create()
    {
        abort_if(Gate::denies('review_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $service_requests = ServiceRequest::pluck('zip_code', 'id')->prepend(trans('global.pleaseSelect'), '');

        $users = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('frontend.reviews.create', compact('service_requests', 'users'));
    }

    public function store(StoreReviewRequest $request)
    {
        //validate comment and rating
        $request->validate([
            'comments' => 'Comment must be at least 10 characters',
            'rating' => 'required|integer|between:1,5',
        ]);

        //Create a new review
        $review = Review::create($request->all());
        //If review is saved then send a json response else send a json response with error
        if($review){
            return response()->json(['status' => 'success', 'message' => 'Review submitted successfully' ]);
        }else{
            return response()->json(['status' => 'error', 'message' => 'Review not submitted' ]);
        }

        return redirect()->route('frontend.reviews.index');
    }

    public function edit(Review $review)
    {
        abort_if(Gate::denies('review_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $service_requests = ServiceRequest::pluck('zip_code', 'id')->prepend(trans('global.pleaseSelect'), '');

        $users = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $review->load('service_request', 'user');

        return view('frontend.reviews.edit', compact('review', 'service_requests', 'users'));
    }

    public function update(UpdateReviewRequest $request, Review $review)
    {
        $review->update($request->all());

        return redirect()->route('frontend.reviews.index');
    }

    public function show(Review $review)
    {
        abort_if(Gate::denies('review_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $review->load('service_request', 'user');

        return view('frontend.reviews.show', compact('review'));
    }

    public function destroy(Review $review)
    {
        abort_if(Gate::denies('review_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $review->delete();

        return back();
    }

    public function massDestroy(MassDestroyReviewRequest $request)
    {
        $reviews = Review::find(request('ids'));

        foreach ($reviews as $review) {
            $review->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
