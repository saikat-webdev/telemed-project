<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MedicalHistoryController extends Controller
{
    public function index(Request $request)
    {
        return response()->json(
            $request->user()->medicalHistories()->latest('recorded_at')->paginate(15)
        );
    }
}
