<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NotActiveAccountController extends Controller
{
    public function notActivePage(){
        $user = auth()->user();

        if ($user && $user->status === 'aktif') {
            return redirect()->route('tes');
        }

        return view('not-active');
    }
}
