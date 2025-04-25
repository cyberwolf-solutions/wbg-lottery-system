<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SubscriptionEmail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class SubscriptionEmailController extends Controller
{
    public function store(Request $request)
    {
  
        Log::info('Request Data:', $request->all());
    
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|unique:subscription_emails,email',
        ]);
    
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 422);
        }
    
        SubscriptionEmail::create(['email' => $request->email]);
    
        return response()->json(['success' => 'Thanks for subscribing!']);
    }
    
}
