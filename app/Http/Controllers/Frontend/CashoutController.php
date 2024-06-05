<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyCashoutRequest;
use App\Http\Requests\StoreCashoutRequest;
use App\Http\Requests\UpdateCashoutRequest;
use App\Models\Cashout;
use App\Models\User;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CashoutController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('cashout_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $cashouts = Cashout::with(['user'])->get();

        return view('frontend.cashouts.index', compact('cashouts'));
    }

    public function create()
    {
        abort_if(Gate::denies('cashout_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $users = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('frontend.cashouts.create', compact('users'));
    }

    public function store(StoreCashoutRequest $request)
    {
        $cashout = Cashout::create($request->all());

        return redirect()->route('frontend.cashouts.index');
    }

    public function edit(Cashout $cashout)
    {
        abort_if(Gate::denies('cashout_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $users = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $cashout->load('user');

        return view('frontend.cashouts.edit', compact('cashout', 'users'));
    }

    public function update(UpdateCashoutRequest $request, Cashout $cashout)
    {
        $cashout->update($request->all());

        return redirect()->route('frontend.cashouts.index');
    }

    public function show(Cashout $cashout)
    {
        abort_if(Gate::denies('cashout_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $cashout->load('user');

        return view('frontend.cashouts.show', compact('cashout'));
    }

    public function destroy(Cashout $cashout)
    {
        abort_if(Gate::denies('cashout_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $cashout->delete();

        return back();
    }

    public function massDestroy(MassDestroyCashoutRequest $request)
    {
        $cashouts = Cashout::find(request('ids'));

        foreach ($cashouts as $cashout) {
            $cashout->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
