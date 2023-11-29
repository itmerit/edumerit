<?php

namespace App\Http\Controllers\api;
use App\LibrarySubject;
use App\SmBook;
use App\SmSubject;
use App\ApiBaseMethod;
use App\SmBookCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use App\Scopes\ActiveStatusSchoolScope;
use Illuminate\Support\Facades\Validator;

class ApiSmBookController extends Controller
{
    public function Library_index(Request $request)
    {

        try {
            $books = DB::table('sm_books')
                ->leftjoin('library_subjects', 'sm_books.book_subject_id', '=', 'library_subjects.id')
                ->leftjoin('sm_book_categories', 'sm_books.book_category_id', '=', 'sm_book_categories.id')
                ->select('sm_books.*', 'library_subjects.subject_name', 'sm_book_categories.category_name')
                ->get();

            return ApiBaseMethod::sendResponse($books, null);

        } catch (\Exception $e) {
            return ApiBaseMethod::sendError('Error.', $e->getMessage());
        }
    }

    public function library_subject_index(Request $request){
        try {
            $subjects = LibrarySubject::where('active_status', 1)->orderBy('id', 'DESC')->where('school_id', $request->user()->id)->get();

            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                return ApiBaseMethod::sendResponse($subjects, null);
            }
            return view('backEnd.academics.subject', compact('subjects'));
        } catch (\Exception $e) {
            return ApiBaseMethod::sendError('Error.', $e->getMessage());
        }
    }
    public function saas_Library_index(Request $request, $school_id)
    {

        try {

            $books = SmBook::withOutGlobalScope(ActiveStatusSchoolScope::class)->where('school_id', $school_id)->get()->map(function ($q) {
                return ([
                    'id'=>$q->id,
                    'book_title'=>$q->book_title,
                    'book_number'=>$q->book_number,
                    'isbn_no'=>$q->isbn_no,
                    'category_name'=>$q->bookCategory->category_name,
                    'publisher_name'=>$q->publisher_name,
                    'author_name'=>$q->author_name,
                    'quantity'=>$q->quantity,
                    'book_price'=>$q->book_price,
                    'subject_name'=>$q->bookSubject->subject_name
                ]);
            });
            return ApiBaseMethod::sendResponse($books, null);

        } catch (\Exception $e) {
            return ApiBaseMethod::sendError('Error.', $e->getMessage());
        }
    }
    public function saveBookData(Request $request)
    {
        $input = $request->all();
        if (ApiBaseMethod::checkUrl($request->fullUrl())) {
            $validator = Validator::make($input, [
                'book_title' => "required|max:200",
                'book_category_id' => "required",
                'user_id' => "required",
                'quantity' => "sometimes|nullable|integer|min:0",
                'book_price' => "sometimes|nullable|integer|min:0",
                'school_id' => "required",
            ]);
        }

        if ($validator->fails()) {
            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                return ApiBaseMethod::sendError('Validation Error.', $validator->errors());
            }
        }


        try {

            $user = Auth()->user();

            if ($user) {
                $user_id = $user->id;
            } else {
                $user_id = $request->user_id;
            }

            $books = new SmBook();
            $books->book_title = $request->book_title;
            $books->book_category_id = $request->book_category_id;
            $books->book_number = $request->book_number;
            $books->isbn_no = $request->isbn_no;
            $books->publisher_name = $request->publisher_name;
            $books->author_name = $request->author_name;
            $books->school_id = $request->school_id;
            if (@$request->subject_id) {
                $books->book_subject_id = $request->subject_id;
            }
            $books->rack_number = $request->rack_number;
            if (@$request->quantity != "") {
                $books->quantity = $request->quantity;
            }
            if (@$request->book_price != "") {
                $books->book_price = $request->book_price;
            }
            $books->details = $request->details;
            $books->post_date = date('Y-m-d');
            $books->created_by = $user_id;

            $results = $books->save();

            return ApiBaseMethod::sendResponse(null, 'New Book has been added successfully.');


        } catch (\Exception $e) {
            return ApiBaseMethod::sendError('Error.', $e->getMessage());
        }
    }
    public function saas_saveBookData (Request $request)
    {
        $input = $request->all();
        if (ApiBaseMethod::checkUrl($request->fullUrl())) {
            $validator = Validator::make($input, [
                'book_title' => "required|max:200",
                'book_category_id' => "required",
                'user_id' => "required",
                'quantity' => "sometimes|nullable|integer|min:0",
                'book_price' => "sometimes|nullable|integer|min:0",
                'school_id' => "required",
            ]);
        }

        if ($validator->fails()) {
            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                return ApiBaseMethod::sendError('Validation Error.', $validator->errors());
            }

        }


        try {

            $user = Auth()->user();

            if ($user) {
                $user_id = $user->id;
            } else {
                $user_id = $request->user_id;
            }

            $books = new SmBook();
            $books->book_title = $request->book_title;
            $books->book_category_id = $request->book_category_id;
            $books->book_number = $request->book_number;
            $books->isbn_no = $request->isbn_no;
            $books->publisher_name = $request->publisher_name;
            $books->author_name = $request->author_name;
            $books->school_id = $request->school_id;
            if (@$request->subject_id) {
                $books->book_subject_id = $request->subject_id;
            }
            $books->rack_number = $request->rack_number;
            if (@$request->quantity != "") {
                $books->quantity = $request->quantity;
            }
            if (@$request->book_price != "") {
                $books->book_price = $request->book_price;
            }
            $books->details = $request->details;
            $books->post_date = date('Y-m-d');
            $books->created_by = $user_id;

            $results = $books->save();

            return ApiBaseMethod::sendResponse(null, 'New Book has been added successfully.');

        } catch (\Exception $e) {
            return ApiBaseMethod::sendError('Error.', $e->getMessage());
        }
    }
    public function editBook(Request $request, $id)
    {


        try {
            $editData = SmBook::find($id);
            $categories = SmBookCategory::all();
            $subjects = SmSubject::all();

            $data = [];
            $data['editData'] = $editData->toArray();
            $data['categories'] = $categories->toArray();
            $data['subjects'] = $subjects->toArray();
            return ApiBaseMethod::sendResponse($data, null);

        } catch (\Exception $e) {
            return ApiBaseMethod::sendError('Error.', $e->getMessage());
        }
    }
    public function saas_editBook(Request $request,$school_id, $id)
    {


        try {
            $editData = SmBook::where('school_id',$school_id)->find($id);
            $categories = SmBookCategory::where('school_id',$school_id)->get();
            $subjects = SmSubject::where('school_id',$school_id)->get();


            $data = [];
            $data['editData'] = $editData->toArray();
            $data['categories'] = $categories->toArray();
            $data['subjects'] = $subjects->toArray();
            return ApiBaseMethod::sendResponse($data, null);

        } catch (\Exception $e) {
            return ApiBaseMethod::sendError('Error.', $e->getMessage());
        }
    }
    public function updateBookData(Request $request, $id)
    {


        $input = $request->all();
        if (ApiBaseMethod::checkUrl($request->fullUrl())) {
            $validator = Validator::make($input, [
                'book_title' => "required",
                'book_category_id' => "required",
                'user_id' => "required",
                'quantity' => "sometimes|nullable|integer|min:0",
                'book_price' => "sometimes|nullable|integer|min:0"
            ]);
        }

        if ($validator->fails()) {
            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                return ApiBaseMethod::sendError('Validation Error.', $validator->errors());
            }

        }
        try {

            $user = Auth()->user();

            if ($user) {
                $user_id = $user->id;
            } else {
                $user_id = $request->user_id;
            }

            $books = SmBook::find($id);
            $books->book_title = $request->book_title;
            $books->book_category_id = $request->book_category_id;
            $books->book_number = $request->book_number;
            $books->isbn_no = $request->isbn_no;
            $books->publisher_name = $request->publisher_name;
            $books->author_name = $request->author_name;
            if (@$request->subject) {
                $books->subject_id = $request->subject;
            }
            $books->rack_number = $request->rack_number;
            if (@$request->quantity != "") {
                $books->quantity = $request->quantity;
            }
            if (@$request->book_price != "") {
                $books->book_price = $request->book_price;
            }
            $books->details = $request->details;
            $books->post_date = date('Y-m-d');
            $books->updated_by = $user_id;
            $results = $books->update();

            return ApiBaseMethod::sendResponse(null, 'Book Data has been updated successfully');


        } catch (\Exception $e) {
            return ApiBaseMethod::sendError('Error.', $e->getMessage());
        }
    }
    public function saas_updateBookData(Request $request, $id)
    {


        $input = $request->all();
        if (ApiBaseMethod::checkUrl($request->fullUrl())) {
            $validator = Validator::make($input, [
                'book_title' => "required",
                'book_category_id' => "required",
                'user_id' => "required",
                'quantity' => "sometimes|nullable|integer|min:0",
                'book_price' => "sometimes|nullable|integer|min:0",
                'school_id' => "required"
            ]);
        }

        if ($validator->fails()) {
            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                return ApiBaseMethod::sendError('Validation Error.', $validator->errors());
            }

        }
        try {

            $user = Auth()->user();

            if ($user) {
                $user_id = $user->id;
            } else {
                $user_id = $request->user_id;
            }

            $books = SmBook::find($id);
            $books->book_title = $request->book_title;
            $books->book_category_id = $request->book_category_id;
            $books->book_number = $request->book_number;
            $books->isbn_no = $request->isbn_no;
            $books->publisher_name = $request->publisher_name;
            $books->author_name = $request->author_name;
            if (@$request->subject) {
                $books->subject_id = $request->subject;
            }
            $books->rack_number = $request->rack_number;
            if (@$request->quantity != "") {
                $books->quantity = $request->quantity;
            }
            if (@$request->book_price != "") {
                $books->book_price = $request->book_price;
            }
            $books->details = $request->details;
            $books->school_id = $request->school_id;
            $books->post_date = date('Y-m-d');
            $books->updated_by = $user_id;
            $results = $books->update();

            return ApiBaseMethod::sendResponse(null, 'Book Data has been updated successfully');

        } catch (\Exception $e) {
            return ApiBaseMethod::sendError('Error.', $e->getMessage());
        }
    }
    public function deleteBookView(Request $request, $id)
    {

        try {
            $title = "Are you sure to detete this Book?";
            $url = url('delete-book/' . $id);
            return ApiBaseMethod::sendResponse($id, null);
        } catch (\Exception $e) {
            return ApiBaseMethod::sendError('Error.', $e->getMessage());
        }
    }
    public function saas_deleteBookView(Request $request,$school_id, $id)
    {

        try {
            $title = "Are you sure to detete this Book?";
            $url = url('school/'.$school_id.'/'.'delete-book/' . $id);
            return ApiBaseMethod::sendResponse($id, null);

        } catch (\Exception $e) {
            return ApiBaseMethod::sendError('Error.', $e->getMessage());
        }
    }
    public function deleteBook(Request $request, $school_id, $id)
    {

        try {
            $tables = \App\tableList::getTableList('book_id', $id);
            try {
                $result = SmBook::where('school_id', $school_id)->destroy($id);
                return ApiBaseMethod::sendResponse(null, 'Operation successful');
            } catch (\Illuminate\Database\QueryException $e) {
                $msg = 'This data already used in  : ' . $tables . ' Please remove those data first';
                return ApiBaseMethod::sendError('Error.', $msg);
            }
        } catch (\Exception $e) {
            return ApiBaseMethod::sendError('Error.', $e->getMessage());
        }
    }
}
