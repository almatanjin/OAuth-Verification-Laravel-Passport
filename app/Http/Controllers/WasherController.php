<?php

namespace App\Http\Controllers;

use App\Mail\VerifyEmail;
use App\Models\Washer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class WasherController extends Controller
{
    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
        ]);
        if ($validator->fails()) {
            return response(['errors' => $validator->errors()->all()], 422);
        }
        $request['password'] = Hash::make($request['password']);
        $request['remember_token'] = Str::random(10);
        $washer = Washer::create($request->toArray());
        $token = $washer->createToken('token')->accessToken;
        $response = ['token' => $token, 'washer_id' => $washer->id];
        return response($response, 200);
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email|max:255',
            'password' => 'required|string|min:6',
        ]);
        if ($validator->fails()) {
            return response(['errors' => $validator->errors()->all()], 422);
        }
        $user = Washer::where('email', $request->email)->first();
        if ($user) {
            if (Hash::check($request->password, $user->password)) {
                $token = $user->createToken('token')->accessToken;
                $response = ['token' => $token];
                return response($response, 200);
            } else {
                $response = ["message" => "Password mismatch"];
                return response($response, 422);
            }
        } else {
            $response = ["message" => 'Washer does not exist'];
            return response($response, 422);
        }
    }

    public function sendOtp($washer)
    {
        $otp = rand(100000, 999999);
        $washer->update(
            [
                'otp' => $otp
            ]
        );

        Mail::to($washer->email)->send(new VerifyEmail($otp));
    }

    public function verifyEmail($id)
    {
        $washer = Washer::where('id', $id)->first();
        if (!$washer || $washer->is_verified == 1) {
            return redirect('/');
        }

        $this->sendOtp($washer);

        $response = ["message" => 'OTP Send to your email'];
        return response($response, 200);
    }

    public function verifyOTP(Request $request)
    {
        Validator::make($request->all(), [
            'email' => 'required|string|email|max:255',
            'otp' => 'required|string|max:6',
        ]);
        $washer = Washer::where('email', $request->email)->where('otp', $request->otp)->first();
        if (!$washer) {
            return response()->json(['success' => false, 'message' => 'Wrong OTP !']);
        }

        $currentTime = time();
        $time = $washer->updated_at->getTimestamp();

        if ($currentTime >= $time && $time >= $currentTime - (90 + 5)) { //90 seconds
            $washer->update([
                'is_verified' => true
            ]);

            return response()->json(['success' => true, 'message' => 'Mail has been verified']);
        }

        return response()->json(['success' => false, 'message' => 'Your OTP has been Expired']);
    }

    public function setLocation(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|integer',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric'
        ]);

        if ($validator->fails()) {
            return response(['errors' => $validator->errors()->all()], 422);
        }

        $washer = Washer::findOrFail($request->id);

        $washer->update([
            'latitude' => $request->latitude,
            'longitude' => $request->longitude
        ]);

        return response()->json(['success' => true, 'message' => 'Location Updated']);
    }

    public function uploadImage(Request $request, string $id)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Store the image
        $imagePath = $request->file('image')->store('public/images');

        $washer = Washer::findOrFail($id);
        $washer->update([
            'image' => $imagePath
        ]);
        return response()->json(['message' => 'Image uploaded successfully'], 200);
    }

    public function showImage(string $id)
    {
        $washer = Washer::findOrFail($id);
        return  $washer->image ? Storage::download($washer->image) : response()->json(['message' => 'No Image uploaded for this id'], 422);
    }

    public function aboutService(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|integer',
            'about_service' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response(['errors' => $validator->errors()->all()], 422);
        }
        $washer = Washer::findOrFail($request->id);
        $washer->update([
            'about_yourself' => $request->about_service
        ]);
        return response()->json(['message' => 'About service added successfully', 'data' => ["about_service" => $washer->about_yourself]], 200);
    }
}
