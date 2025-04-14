<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User; // ini penting!
use Yajra\DataTables\DataTables;
use Carbon\Carbon;



class UserController extends Controller
{
       
    public function getUsers(Request $request)
    {
        if ($request->ajax()) {
            $data = User::latest()->get();
    
            // Atur locale ke Bahasa Indonesia
            Carbon::setLocale('id');
    
            return DataTables::of($data)
                ->addIndexColumn()
                ->editColumn('created_at', function ($user) {
                    return Carbon::parse($user->created_at)->translatedFormat('d F Y');
                })
                ->make(true);
        }
    
        return view('admin.penguna.index');
    }
    
}
