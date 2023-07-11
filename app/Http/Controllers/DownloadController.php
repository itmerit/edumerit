<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Storage;
use Response;

class DownloadController extends Controller
{
    public function __construct()
	{
        $this->middleware('PM');
        // User::checkAuth();
	}
    function downloadFile(Request $request) {

    }

}
