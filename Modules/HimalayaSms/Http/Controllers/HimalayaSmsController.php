<?php

namespace Modules\HimalayaSms\Http\Controllers;

use App\SmSmsGateway;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Validator;
use Illuminate\Contracts\Support\Renderable;

class HimalayaSmsController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        return view('himalayasms::index');
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return view('himalayasms::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function updateSetting(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'himalayasms_senderId' => 'required',
            'himalayasms_key' => 'required',
            'himalayasms_routeId' => 'required',
            'himalayasms_campaign' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with(['HimalayaSms' => 'active']);
        }
        try{

            $gateway = SmSmsGateway::where('gateway_name','Himalayasms')->first();
            if($gateway){
                $gateway->himalayasms_senderId  = $request->himalayasms_senderId;
                $gateway->himalayasms_key  = $request->himalayasms_key;
                $gateway->himalayasms_routeId  = $request->himalayasms_routeId;
                $gateway->himalayasms_campaign  = $request->himalayasms_campaign;
                $gateway->save();
                Toastr::success('Operation Failed', 'Failed');
                return redirect()->back()->with(['HimalayaSms' => 'active']);
        }
        else{
            Toastr::error('Module Not Find', 'Failed');
            return redirect()->back();
        }

    } catch (\Exception $e) {
        Toastr::error('Operation Failed', 'Failed');
        return redirect()->back();
    }


    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        return view('himalayasms::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        return view('himalayasms::edit');
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        //
    }
}
