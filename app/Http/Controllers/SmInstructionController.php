<?php

namespace App\Http\Controllers;
use App\YearCheck;
use App\SmInstruction;
use Illuminate\Http\Request;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Auth;

class SmInstructionController extends Controller
{
    public function __construct()
	{
        $this->middleware('PM');
        // User::checkAuth();
	}

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        try{
            $instructions = SmInstruction::where('school_id',Auth::user()->school_id)->get();
            return view('backEnd.examination.instruction', compact('instructions'));
        }catch (\Exception $e) {
           Toastr::error('Operation Failed', 'Failed');
           return redirect()->back();
        }
    }
    public function store(Request $request)
    {
        $request->validate([
            'title' => "required|unique:sm_instructions",
            'description' => "required"
        ]);
        try{
            $instruction = new SmInstruction();
            $instruction->title = $request->title;
            $instruction->description = $request->description;
            $instruction->school_id = Auth::user()->school_id;
            $instruction->academic_id = getAcademicId();
            $result = $instruction->save();
            if($result){
                Toastr::success('Operation successful', 'Success');
                return redirect()->back();
                // return redirect()->back()->with('message-success', 'Instruction has been created successfully');
            }else{
                Toastr::error('Operation Failed', 'Failed');
                return redirect()->back();
                // return redirect()->back()->with('message-danger', 'Something went wrong, please try again');
            }
        }catch (\Exception $e) {
           Toastr::error('Operation Failed', 'Failed');
           return redirect()->back();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

        try{
            $instruction = SmInstruction::find($id);
            $instructions = SmInstruction::where('school_id',Auth::user()->school_id)->get();
            return view('backEnd.examination.instruction', compact('instruction', 'instructions'));
        }catch (\Exception $e) {
           Toastr::error('Operation Failed', 'Failed');
           return redirect()->back();
        }
    }
    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => "required|unique:sm_instructions,title,".$request->id,
            'description' => "required"
        ]);
        try{
            $instruction = SmInstruction::find($request->id);
            $instruction->title = $request->title;
            $instruction->description = $request->description;
            $result = $instruction->save();
            if($result){
                Toastr::success('Operation successful', 'Success');
                return redirect('instruction');
                // return redirect('instruction')->with('message-success', 'Instruction has been updated successfully');
            }else{
                Toastr::error('Operation Failed', 'Failed');
                return redirect()->back();
                // return redirect()->back()->with('message-danger', 'Something went wrong, please try again');
            }
        }catch (\Exception $e) {
           Toastr::error('Operation Failed', 'Failed');
           return redirect()->back();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

        try{
            $instruction = SmInstruction::destroy($id);
            if($instruction){
                Toastr::success('Operation successful', 'Success');
                return redirect('assign-vehicle');
                // return redirect('assign-vehicle')->with('message-success-delete', 'Instruction has been deleted successfully');
            }else{
                Toastr::error('Operation Failed', 'Failed');
                return redirect()->back();
                // return redirect()->back()->with('message-danger-delete', 'Something went wrong, please try again');
            }
        }catch (\Exception $e) {
           Toastr::error('Operation Failed', 'Failed');
           return redirect()->back();
        }
    }
}