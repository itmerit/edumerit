<?php

namespace App\Http\Controllers\api;

use Validator;
use App\SmRoute;
use App\ApiBaseMethod;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;

class SaasRouteController extends Controller
{
    public function __construct()
    {
        $this->middleware('PM');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $input =$request->all();

        $validator = Validator::make($input, [
            'school_id' => "required|integer",
        ]);
        if ($validator->fails()) {
            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                return ApiBaseMethod::sendError('Validation Error.', $validator->errors());
            }

        }
        try{
            $routes = SmRoute::where('school_id', '=', $request->school_id)->get();

            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                return ApiBaseMethod::sendResponse($routes, null);
            }
            return view('backEnd.transport.route', compact('routes'));
        }catch (\Exception $e) {
           Toastr::error('Operation Failed', 'Failed');
           return redirect()->back(); 
        }
    }

 
    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'title' => 'required|max:200|unique:sm_routes,title',
            'far' => "required|integer",
            'school_id' => "required|integer",
            'created_by' => "required|integer"
        ]);

        if ($validator->fails()) {
            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                return ApiBaseMethod::sendError('Validation Error.', $validator->errors());
            }

        }

        
        try{
            $route = new SmRoute();
            $route->title = $request->title;
            $route->far = $request->far;
            $route->school_id = $request->school_id;
            $route->created_by=$request->created_by;
            $result = $route->save();
    
            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                if ($result) {
                    return ApiBaseMethod::sendResponse(null, 'Route has been created successfully');
                } else {
                    return ApiBaseMethod::sendError('Something went wrong, please try again');
                }
            }
        }catch (\Exception $e) {

        }
    }

    public function show(Request $request, $id)
    {
        
        try{
            $route = SmRoute::find($id);
            $routes = SmRoute::where('school_id', '=', $request->school_id)->get();
    
            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                $data = [];
                $data['route'] = $route->toArray();
                $data['routes'] = $routes->toArray();
                return ApiBaseMethod::sendResponse($data, null);
            }
            return view('backEnd.transport.route', compact('route', 'routes'));
        }catch (\Exception $e) {
 
        }
    }


    public function edit($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        $input = $request->all();
        if (ApiBaseMethod::checkUrl($request->fullUrl())) {
            $validator = Validator::make($input, [
                'title' => 'required|max:200|unique:sm_routes,title,' . $id,
                'far' => "required",
                'id' => 'required',
                'updated_by' =>'required|integer',
            ]);
        }

        if ($validator->fails()) {
            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                return ApiBaseMethod::sendError('Validation Error.', $validator->errors());
            }

        }
        
        try{
            $route = SmRoute::find($request->id);
            $route->title = $request->title;
            $route->far = $request->far;
            $route->updated_by=$request->updated_by;
            $result = $route->save();
            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                if ($result) {
                    return ApiBaseMethod::sendResponse(null, 'Route has been updated successfully');
                } else {
                    return ApiBaseMethod::sendError('Something went wrong, please try again');
                }
            } 
        }catch (\Exception $e) {

        }
    }


    public function destroy(Request $request, $id)
    {
        
        try{
            $tables = \App\tableList::getTableList('route_id',$id);
            try {
                $route = SmRoute::destroy($id);
                if ($route) {
    
                    if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                        if ($route) {
                            return ApiBaseMethod::sendResponse(null, 'Route has been deleted successfully');
                        } else {
                            return ApiBaseMethod::sendError('Something went wrong, please try again.');
                        }
                    } 
                } else {
                    Toastr::error('Operation Failed', 'Failed');
                    return redirect()->back();
                }
            } catch (\Illuminate\Database\QueryException $e) {
    
                $msg = 'This data already used in  : ' . $tables . ' Please remove those data first';
                Toastr::error('This item already used', 'Failed');
                return redirect()->back();
            } catch (\Exception $e) {
                Toastr::error('Operation Failed', 'Failed');
                return redirect()->back();
            }
        }catch (\Exception $e) {
           Toastr::error('Operation Failed', 'Failed');
           return redirect()->back(); 
        }
    }
}
