<?php

namespace App\Http\Controllers;
use App\ResponseFormatter;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

use function PHPUnit\Framework\isNull;

class ProfileController extends Controller
{
        public function getProfile()
        {
            $user = Auth::user();

            return ResponseFormatter::success($user->api_response);
        }

        public function updateProfile()
    {
        $validator = Validator::make(request()->all(), [
            'name' => 'required|min:2|max:100',
            'email' => 'required|email',
            'photo' => 'nullable|image|max:1024',
            'username' => 'nullable|min:2|max:20',
            'phone' => 'nullable|numeric',
            'store_name' => 'nullable|min:2|max:100',
            'gender' => 'required|in:Laki-Laki,Perempuan,Lainnya',
            'birth_date' => 'nullable|date_format:Y-m-d',
        ]);

        if ($validator->fails()) {
            return ResponseFormatter::error(400, $validator->errors());
        }

        $payload = $validator->validated();
        if (!is_null(request()->photo)) {
            $payload['photo'] = request()->file('photo')->store(
                'user-photo', 'public'
            );
        }

        Auth::user()->update($payload);
        return ResponseFormatter::success([
            'name' => Auth::user()->name,
            'email' => Auth::user()->email,
            'photo' => Auth::user()->photo ? asset('storage/' . Auth::user()->photo) : null,
            'username' => Auth::user()->username,
            'phone' => Auth::user()->phone,
            'store_name' => Auth::user()->store_name,
            'gender' => Auth::user()->gender,
            'birth_date' => Auth::user()->birth_date,
        ], 'Profile updated successfully');
    }


}
