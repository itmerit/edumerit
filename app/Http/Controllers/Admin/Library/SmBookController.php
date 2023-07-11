<?php

namespace App\Http\Controllers\Admin\Library;

use App\SmBook;
use App\SmStaff;
use App\SmParent;
use App\SmStudent;
use App\SmSubject;
use App\tableList;
use Carbon\Carbon;
use App\SmBookIssue;
use App\ApiBaseMethod;
use App\LibrarySubject;
use App\SmBookCategory;
use App\SmLibraryMember;
use App\Rules\UniqueSubject;
use Illuminate\Http\Request;
use App\Rules\UniqueSubjectCode;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\Admin\Library\SmBookRequest;
use App\Http\Requests\Admin\Library\SaveIssueBookRequest;
use App\Http\Requests\Admin\Library\LibrarySubjectRequest;

class SmBookController extends Controller
{
    public function __construct()
    {
        $this->middleware('PM');
        // User::checkAuth();
    }

    public function index(Request $request)
    {

        try {
            $books = SmBook::leftjoin('library_subjects', 'sm_books.book_subject_id', '=', 'library_subjects.id')
                    ->leftjoin('sm_book_categories', 'sm_books.book_category_id', '=', 'sm_book_categories.id')
                    ->select('sm_books.*', 'library_subjects.subject_name', 'sm_book_categories.category_name')
                    ->orderby('sm_books.id', 'DESC')
                    ->get();
           
       
            return view('backEnd.library.bookList', compact('books'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function addBook(Request $request)
    {
        try {
            $categories = SmBookCategory::get();
            $subjects = LibrarySubject::get();

            return view('backEnd.library.addBook', compact('categories', 'subjects'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function saveBookData(SmBookRequest $request)
    {
        try {
            $books = new SmBook();
            $books->book_title = $request->book_title;
            $books->book_category_id = $request->book_category_id;
            $books->book_number = $request->book_number;
            $books->isbn_no = $request->isbn_no;
            $books->publisher_name = $request->publisher_name;
            $books->author_name = $request->author_name;
            if (@$request->subject) {
                $books->book_subject_id = $request->subject;
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
            $books->created_by = auth::user()->id;
            $books->school_id = Auth::user()->school_id;
            if(moduleStatusCheck('University')){
                $books->un_academic_id = getAcademicId();
            }else{
                $books->academic_id = getAcademicId();
            }
            $books->save();

            Toastr::success('Operation successful', 'Success');
            return redirect('book-list');
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function editBook(Request $request, $id)
    {
        try {
            if (checkAdmin()) {
                $editData = SmBook::find($id);
            } else {
                $editData = SmBook::where('id', $id)->first();
            }
            $categories = SmBookCategory::get();
            $subjects = LibrarySubject::get();

            return view('backEnd.library.addBook', compact('editData', 'categories', 'subjects'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function updateBookData(SmBookRequest $request, $id)
    {
        try {
            if (checkAdmin()) {
                $books = SmBook::find($id);
            } else {
                $books = SmBook::where('id', $id)->first();
            }
            $books->book_title = $request->book_title;
            $books->book_category_id = $request->book_category_id;
            $books->book_number = $request->book_number;
            $books->isbn_no = $request->isbn_no;
            $books->publisher_name = $request->publisher_name;
            $books->author_name = $request->author_name;
            if (@$request->subject) {
                $books->book_subject_id = $request->subject;
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
            $books->updated_by =auth()->user()->id;
            $books->update();

            Toastr::success('Operation successful', 'Success');
            return redirect('book-list');
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function deleteBookView(Request $request, $id)
    {
        try {
            $title = __('common.are_you_sure_to_delete');
            $url = url('delete-book/' . $id);
            return view('backEnd.modal.delete', compact('id', 'title', 'url'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function deleteBook(Request $request, $id)
    {
        $tables = \App\tableList::getTableList('book_id', $id);
        try {
            if ($tables == null) {

                if (checkAdmin()) {
                    $result = SmBook::destroy($id);
                } else {
                    $result = SmBook::where('id', $id)->delete();
                }
                if ($result) {
                    Toastr::success('Operation successful', 'Success');
                    return redirect()->back();
                } else {
                    Toastr::error('Operation Failed', 'Failed');
                    return redirect()->back();
                }
                Toastr::success('Operation successful', 'Success');
                return redirect()->back();
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

    public function memberList(Request $request)
    {

        try {
            $activeMembers = SmLibraryMember::with('roles','studentDetails','staffDetails','parentsDetails','memberTypes')->where('school_id', Auth::user()->school_id)->where('active_status', '=', 1)->get();
           
            return view('backEnd.library.memberLists', compact('activeMembers'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function issueBooks(Request $request, $member_type, $student_staff_id)
    {

        try {
            $memberDetails = SmLibraryMember::where('student_staff_id', '=', $student_staff_id)->first();

            if ($member_type == 2) {
                $getMemberDetails = SmStudent::where('user_id', '=', $student_staff_id)
                                    ->select('first_name','last_name','full_name', 'email', 'mobile', 'student_photo')
                                    ->first();
            }elseif($member_type == 3) {
                $getMemberDetails = SmParent::where('user_id', '=', $student_staff_id)
                                    ->select('guardians_name', 'guardians_email', 'guardians_mobile', 'guardians_photo')
                                    ->first();
            } else {
                $getMemberDetails = SmStaff::where('user_id', '=', $student_staff_id)
                                    ->select('full_name', 'email', 'mobile', 'staff_photo')
                                    ->first();
            }
            
            $books = SmBook::where('school_id', Auth::user()->school_id)->get();
            $totalIssuedBooks = SmBookIssue::where('school_id', Auth::user()->school_id)->where('member_id', '=', $student_staff_id)->get();

            return view('backEnd.library.issueBooks', compact('memberDetails', 'books', 'getMemberDetails', 'totalIssuedBooks'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function saveIssueBookData(SaveIssueBookRequest $request)
    {
        $input = $request->all();
        if (ApiBaseMethod::checkUrl($request->fullUrl())) {
            $validator = Validator::make($input, [
                'book_id' => "required",
                'due_date' => "required|after:now",
                'user_id' => "required",
            ]);
        } else {
            $validator = Validator::make($input, [
                'book_id' => "required",
                'due_date' => "required|after:now",
            ]);
        }

        $check_issue_status = SmBookIssue::where('member_id', $request->member_id)
                            ->where('book_id', $request->book_id)
                            ->where('issue_status', '=', 'I')
                            ->first();
        if ($check_issue_status) {
            Toastr::warning('You have already issued this book', 'Failed');
            return redirect()->back();
        }
        $book_quantity = SmBook::find($request->book_id);
        $book_quantity = $book_quantity->quantity;

   
        if ($book_quantity == 0) {
            Toastr::warning('This book not available now', 'Failed');
            return redirect()->back();
        }

        try {
            $bookIssue = new SmBookIssue();
            $bookIssue->book_id = $request->book_id;
            $bookIssue->member_id = $request->member_id;
            $bookIssue->given_date = date('Y-m-d');
            $bookIssue->due_date = date('Y-m-d', strtotime($request->due_date));
            $bookIssue->issue_status = 'I';
            $bookIssue->school_id = Auth::user()->school_id;

            if(moduleStatusCheck('University')){
                $bookIssue->un_academic_id = getAcademicId();
            }else{
                $bookIssue->academic_id = getAcademicId();
            }

            $bookIssue->created_by = auth()->user()->id;
            $results = $bookIssue->save();
            $bookIssue->toArray();

            if ($results) {
                $books = SmBook::find($request->book_id);
                $books->quantity = $books->quantity - 1;
                $books->update();               
            }

            if($bookIssue->member->memberTypes->id == '2'){
                $compact['slug'] = 'student';
                $compact['user_email'] = $bookIssue->member->studentDetails->email;
                $compact['due_date'] = date('Y-m-d', strtotime($request->due_date));
                $compact['student_name'] = $bookIssue->member->studentDetails->full_name;
                $compact['class_name'] = $bookIssue->member->studentDetails->defaultClass->class->class_name;
                $compact['section_name'] = $bookIssue->member->studentDetails->defaultClass->section->section_name;
                $compact['roll_no'] = $bookIssue->member->studentDetails->roll_no;
                $compact['issue_date'] = date('Y-m-d');
                $compact['book_title'] = $bookIssue->books->book_title;
                $compact['book_no'] = $bookIssue->books->book_number;
                @send_sms($bookIssue->member->studentDetails->mobile, 'student_library_book_issue', $compact);
            }elseif($bookIssue->member->memberTypes->id == '3'){
                $compact['slug'] = 'parent';
                $compact['user_email'] = $bookIssue->member->parentsDetails->guardians_email;
                $compact['due_date'] = date('Y-m-d', strtotime($request->due_date));
                $compact['issue_date'] = date('Y-m-d');
                $compact['book_title'] = $bookIssue->books->book_title;
                $compact['book_no'] = $bookIssue->books->book_number;
                $compact['parent_name'] = $bookIssue->member->parentsDetails->guardians_name;
                @send_sms($bookIssue->member->parentsDetails->guardians_mobile, 'parent_library_book_issue', $compact);
            }

            Toastr::success('Operation successful', 'Success');
            return redirect()->back();
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function returnBookView(Request $request, $issue_book_id)
    {
        try {
            return view('backEnd.library.returnBookView', compact('issue_book_id'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function returnBook(Request $request, $issue_book_id)
    {

        try {
            $user = Auth()->user();
            if ($user) {
                $updated_by = $user->id;
            } else {
                $updated_by = $request->updated_by;
            }
            $return = SmBookIssue::find($issue_book_id);
            $return->issue_status = "R";
            $return->updated_by = Auth()->user()->id;
            $results = $return->update();

            if ($results) {
                $books_id = SmBookIssue::where('id', $issue_book_id)
                ->select('book_id')
                ->first();
                $books = SmBook::find($books_id->book_id);
                $books->quantity = $books->quantity + 1;
                 $books->update();            
            }

            if($return->member->memberTypes->id == '2'){
                $compact['slug'] = 'student';
                $compact['user_email'] = $return->member->studentDetails->email;
                $compact['due_date'] = $return->due_date;
                $compact['student_name'] = $return->member->studentDetails->full_name;
                $compact['class_name'] = $return->member->studentDetails->defaultClass->class->class_name;
                $compact['section_name'] = $return->member->studentDetails->defaultClass->section->section_name;
                $compact['roll_no'] = $return->member->studentDetails->roll_no;
                $compact['issue_date'] = $return->given_date;
                $compact['book_title'] = $return->books->book_title;
                $compact['book_no'] = $return->books->book_number;
                $compact['return_date'] = date('Y-m-d');
                @send_sms($return->member->studentDetails->mobile, 'student_return_issue_book', $compact);
            }elseif($return->member->memberTypes->id == '3'){
                $compact['slug'] = 'parent';
                $compact['user_email'] = $return->member->parentsDetails->guardians_email;
                $compact['due_date'] = $return->due_date;
                $compact['issue_date'] = $return->given_date;
                $compact['book_title'] = $return->books->book_title;
                $compact['book_no'] = $return->books->book_number;
                $compact['parent_name'] = $return->member->parentsDetails->guardians_name;
                $compact['return_date'] = date('Y-m-d');
                @send_sms($return->member->parentsDetails->guardians_mobile, 'parent_return_issue_book', $compact);
            }

            Toastr::success('Operation successful', 'Success');
            return redirect()->back();
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function allIssuedBook(Request $request)
    {
        try {
            $books = SmBook::select('id', 'book_title')->get();
            $subjects = LibrarySubject::select('id', 'subject_name')->get();
            $now = Carbon::now();
            $issueBooks = SmBookIssue::whereHas('books')->get();
            return view('backEnd.library.allIssuedBook', compact('books', 'subjects', 'now','issueBooks'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function searchIssuedBook(Request $request)
    {
        try {
            $book_id = $request->book_id;
            $book_number = $request->book_number;
            $subject_id = $request->subject_id;
            $now = Carbon::now();
            $issueBooks = SmBookIssue::whereHas('books', function ($query) use ($request) {
                $query->where('id', $request->book_id);
            })->get();

            if ($request->book_number) {
                $issueBooks = SmBookIssue::whereHas('books', function ($query) use ($request) {
                    $query->where('id', $request->book_id)->where('book_number', $request->book_number);
                })->get();
            }

            if ($request->subject_id) {
                $issueBooks = SmBookIssue::whereHas('books', function ($query) use ($request) {
                    $query->where('id', $request->book_id)->where('book_subject_id', $request->subject_id);
                })->get();
            }

            if ($request->subject_id && $request->book_number) {
                $issueBooks = SmBookIssue::whereHas('books', function ($query) use ($request) {
                    $query->where('id', $request->book_id)->where('book_number', $request->book_number)->where('subject_id', $request->subject_id);
                })->get();
            }

            $books = SmBook::select('id', 'book_title')->where('active_status', 1)->get();
            $subjects = LibrarySubject::select('id', 'subject_name')->where('active_status', 1)->get();

       
            return view('backEnd.library.allIssuedBook', compact('issueBooks', 'books', 'subjects', 'book_id', 'book_number', 'subject_id', 'now'));
        } catch (\Exception $e) {
            Toastr::error($e->getMessage(), 'Failed');
            return redirect()->back();
        }
    }

    public static function pp($data)
    {

        echo "<pre>";
        print_r($data);
        exit;
    }

    public function bookListApi(Request $request)
    {

        try {
            $books = DB::table('sm_books')
                ->join('library_subjects', 'sm_books.subject', '=', 'library_subjects.id')
                ->where('sm_books.school_id', Auth::user()->school_id)
                ->get();
            if (ApiBaseMethod::checkUrl($request->fullUrl())) {

                return ApiBaseMethod::sendResponse($books, null);
            }
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    //Library Book Subjects

    public function subjectList(Request $request)
    {
        try {
            $subjects = LibrarySubject::where('school_id',auth()->user()->school_id)->with('category')->get();
            $bookCategories = SmBookCategory::get();
           
            // return $subjects;
            return view('backEnd.library.subject', compact('subjects', 'bookCategories'));
        } catch (\Exception $e) {
            Toastr::error($e->getMessage(), 'Failed');
            return redirect()->back();
        }
    }
    public function store(LibrarySubjectRequest $request)
    {
        try {
            $subject = new LibrarySubject();
            $subject->subject_name = $request->subject_name;
            $subject->subject_type = $request->subject_type;
            $subject->sb_category_id = $request->category;
            $subject->subject_code = $request->subject_code;
            $subject->school_id = Auth::user()->school_id;
            if(moduleStatusCheck('University')){
                $subject->un_academic_id = getAcademicId();
            }else{
                $subject->academic_id = getAcademicId();
            }
            $subject->save();

            Toastr::success('Operation successful', 'Success');
            return redirect()->back();
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }
    public function edit(Request $request, $id)
    {
        try {
            if (checkAdmin()) {
                $subject = LibrarySubject::find($id);
            } else {
                $subject = LibrarySubject::where('id', $id)->first();
            }
            $subjects = LibrarySubject::where('school_id',auth()->user()->school_id)->with('category')->get();

            $bookCategories = SmBookCategory::get();

            return view('backEnd.library.subject', compact('subject', 'subjects', 'bookCategories'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }
    public function update(LibrarySubjectRequest $request)
    {
        try {
            if (checkAdmin()) {
                $subject = LibrarySubject::find($request->id);
            } else {
                $subject = LibrarySubject::where('id', $request->id)->first();
            }
            $subject->subject_name = $request->subject_name;
            $subject->subject_type = $request->subject_type;
            $subject->sb_category_id = $request->category;
            $subject->subject_code = $request->subject_code;
            $subject->save();

            Toastr::success('Operation successful', 'Success');
            return redirect()->route('library_subject');
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function delete(Request $request, $id)
    {
        try {
            $tables = tableList::getTableList('book_subject_id', $id);
            try {
                if ($tables == null) {
                    // $delete_query = $section = LibrarySubject::destroy($request->id);
                    if (checkAdmin()) {
                        $delete_query = LibrarySubject::destroy($request->id);
                    } else {
                        $delete_query = LibrarySubject::where('id', $request->id)->where('school_id', Auth::user()->school_id)->delete();
                    }
                    if ($delete_query) {
                        Toastr::success('Operation successful', 'Success');
                        return redirect()->route('library_subject');
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
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    
}
