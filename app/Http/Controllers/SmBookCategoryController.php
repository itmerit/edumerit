<?php

namespace App\Http\Controllers;

use App\SmBookCategory;
use Illuminate\Http\Request;
use App\Rules\UniqueCategory;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Auth;

class SmBookCategoryController extends Controller
{
    public function __construct()
	{
        $this->middleware('PM');
        // User::checkAuth();
    }
    
    public function index()
    {
        try{
            $bookCategories = SmBookCategory::where('school_id',Auth::user()->school_id)->orderby('id','DESC')->get();
            return view('backEnd.library.bookCategoryList', compact('bookCategories'));
        }catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'category_name' => ["required",new UniqueCategory(0)],
        ]);
        
        try{
            $categories = new SmBookCategory();
            $categories->category_name = $request->category_name;
            $categories->school_id = Auth::user()->school_id;
            $categories->academic_id = getAcademicId();
            $results = $categories->save();

            if ($results) {
                Toastr::success('Operation successful', 'Success');
                return redirect('book-category-list');
            } else {
                Toastr::error('Operation Failed', 'Failed');
                return redirect()->back();
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
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

        try{
            // $editData = SmBookCategory::find($id);
            if (checkAdmin()) {
                $editData = SmBookCategory::find($id);
            }else{
                $editData = SmBookCategory::where('id',$id)->where('school_id',Auth::user()->school_id)->first();
            }
            $bookCategories = SmBookCategory::where('school_id',Auth::user()->school_id)->get();
            return view('backEnd.library.bookCategoryList', compact('bookCategories', 'editData'));
        }catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'category_name' => ["required",new UniqueCategory($id)]
        ]);
        
        try{
            // $categories =  SmBookCategory::find($id);
             if (checkAdmin()) {
                $categories = SmBookCategory::find($id);
            }else{
                $categories = SmBookCategory::where('id',$id)->where('school_id',Auth::user()->school_id)->first();
            }
            $categories->category_name = $request->category_name;
            $results = $categories->update();
            if ($results) {
                Toastr::success('Operation successful', 'Success');
                return redirect('book-category-list');
            } else {
                Toastr::error('Operation Failed', 'Failed');
                return redirect()->back();
            }
        }catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }

    }


    public function destroy($id)
    {

        $tables = \App\tableList::getTableList('book_category_id', $id);
        $tables1 = \App\tableList::getTableList('sb_category_id', $id);
        try {
            if ($tables==null && $tables1==null) {
                if (checkAdmin()) {
                    $result = SmBookCategory::destroy($id);
                }else{
                    $result = SmBookCategory::where('id',$id)->where('school_id',Auth::user()->school_id)->delete();
                }
                if ($result) {
                    Toastr::success('Operation successful', 'Success');
                    return redirect()->back();
                } else {
                    Toastr::error('Operation Failed', 'Failed');
                    return redirect()->back();
                }
            }else{
                 $msg = 'This data already used in  : ' . $tables . $tables1 . ' Please remove those data first';
                Toastr::error( $msg, 'Failed');
                return redirect()->back();
            }

        } catch (\Illuminate\Database\QueryException $e) {

            $msg = 'This data already used in  : ' . $tables . $tables1 . ' Please remove those data first';
            Toastr::error( $msg, 'Failed');
            return redirect()->back();
        }

    }

    public function deleteBookCategoryView(Request $request, $id)
    {
        try{
            $title = "Are you sure to detete this Book category?";
            $url = url('delete-book-category/' . $id);
            return view('backEnd.modal.delete', compact('id', 'title', 'url'));
        }catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }


    }

    public function deleteBookCategory($id)
    {

        $tables = \App\tableList::getTableList('book_category_id', $id);


        try {
            if ($tables==null) {
                // $result = SmBookCategory::destroy($id);
                 if (checkAdmin()) {
                    $result = SmBookCategory::destroy($id);
                }else{
                    $result = SmBookCategory::where('id',$id)->where('school_id',Auth::user()->school_id)->delete();
                }
                if ($result) {
                    Toastr::success('Operation successful', 'Success');
                    return redirect()->back();
                } else {
                    Toastr::error('Operation Failed', 'Failed');
                    return redirect()->back();
                }
            } else {
                $msg = 'This data already used in  : ' . $tables . ' Please remove those data first';
                Toastr::error($msg, 'Failed');
                return redirect()->back();
        }

        } catch (\Illuminate\Database\QueryException $e) {

            $msg = 'This data already used in  : ' . $tables . ' Please remove those data first';
            Toastr::error($msg, 'Failed');
            return redirect()->back();
        }

    }
}