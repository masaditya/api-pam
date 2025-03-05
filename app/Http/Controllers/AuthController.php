<?php

namespace App\Http\Controllers;

use App\Models\EmployeeDetails;
use App\Models\RoleUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    /**
     * Login user and create token
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        // Validate request inputs
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
            'android_id' => 'required|string', // Require android_id in the request
        ]);

        // Find user by email
        $user = User::where('email', $request->email)->first();

        // Periksa apakah pengguna terdaftar
        if (!$user) {
            return response()->json([
                'code' => 404,
                'message' => __('Alamat email yang Anda masukkan tidak terdaftar di sistem kami.')
            ], 404);
        }

        // Check user status
        if ($user->status === 'deactive') {
            return response()->json(['message' => __('auth.failedBlocked')], 403);
        }

        if ($user->login === 'disable') {
            return response()->json(['message' => __('auth.failedLoginDisabled')], 403);
        }

        // Verify password
        if (!Hash::check($request->password, $user->password)) {
            return response()->json([
                'code' => 401,
                'message' => __('Kata sandi yang Anda masukkan tidak sesuai.')
            ], 401);
        }

        // Check android_id
        if ($user->android_id) {
            // If android_id is set, verify it matches the provided one
            if ($user->android_id !== $request->android_id) {
                return response()->json(['message' => 'Device ID does not match.'], 403);
            }
        } else {
            // If android_id is null, update it with the provided android_id
            $user->android_id = $request->android_id;
            $user->save();
        }

        $roleUser = RoleUser::with('role')
            ->where('user_id', $user->id)
            ->orderByDesc('role_id')
            ->first();
        
        if ($roleUser && $roleUser->role) {
            $lastRole = $roleUser->role; // Ambil data role terakhir melalui 
        } else {
            return response()->json(['message' => 'Role not found'], 404);
        }

        // User authenticated successfully, generate Sanctum token
        $token = $user->createToken('API Token')->plainTextToken;

        return response()->json([
            'message' => 'Login successful',
            'token' => $token,
            'user' => [
                'id' => $user->id,
                'company_id' => $user->company_id,
                'name' => $user->name,
                'email' => $user->email,
                'two_factor_confirmed' => $user->two_factor_confirmed,
                'two_factor_email_confirmed' => $user->two_factor_email_confirmed,
                'image' => $user->image,
                'country_phonecode' => $user->country_phonecode,
                'mobile' => $user->mobile,
                'gender' => $user->gender,
                'salutation' => $user->salutation,
                'locale' => $user->locale,
                'status' => $user->status,
                'login' => $user->login,
                'onesignal_player_id' => $user->onesignal_player_id,
                'last_login' => $user->last_login,
                'email_notifications' => $user->email_notifications,
                'country_id' => $user->country_id,
                'dark_theme' => $user->dark_theme,
                'rtl' => $user->rtl,
                'two_fa_verify_via' => $user->two_fa_verify_via,
                'two_factor_code' => $user->two_factor_code,
                'two_factor_expires_at' => $user->two_factor_expires_at,
                'admin_approval' => $user->admin_approval,
                'permission_sync' => $user->permission_sync,
                'google_calendar_status' => $user->google_calendar_status,
                'created_at' => $user->created_at,
                'updated_at' => $user->updated_at,
                'customised_permissions' => $user->customised_permissions,
                'stripe_id' => $user->stripe_id,
                'pm_type' => $user->pm_type,
                'pm_last_four' => $user->pm_last_four,
                'trial_ends_at' => $user->trial_ends_at,
                'headers' => $user->headers,
                'register_ip' => $user->register_ip,
                'location_details' => $user->location_details,
                'inactive_date' => $user->inactive_date,
                'twitter_id' => $user->twitter_id,
                'android_id' => $user->android_id,
                'role_id' => $lastRole->id ?? null, // Tambahkan role_id
                'role_name' => $lastRole->name ?? null // Tambahkan role_name
            ]
        ], 200);
    }

    public function getUser(Request $request)
    {
        // Get the currently authenticated user
        $user = Auth::user();

        // Check if the user is authenticated
        if (!$user) {
            return response()->json(['message' => 'Unauthenticated.'], 401);
        }

        $employeeDetails = EmployeeDetails::where('user_id', $user->id)->with(['user:id,name,email,image', 'designation:id,name', 'team:id,team_name', 'companies:id,company_name,latitude,longitude'])->first();;

        if (!$employeeDetails) {
            return response()->json(['message' => 'Company details not found.'], 404);
        }

        $roleUser = RoleUser::with('role')
            ->where('user_id', $user->id)
            ->orderByDesc('role_id')
            ->first();
        
        if ($roleUser && $roleUser->role) {
            $lastRole = $roleUser->role; // Ambil data role terakhir melalui 
        } else {
            return response()->json(['message' => 'Role not found'], 404);
        }

        // Return the company details
        return response()->json([
            'message' => 'User details retrieved successfully.',
            'data' => [
                'id_user' => $employeeDetails->user_id,
                'employee_id' => $employeeDetails->employee_id,
                'company_id' => $employeeDetails->company_id,
                'company_address_id' => $employeeDetails->company_address_id,
                'company_name' => $employeeDetails->companies->company_name,
                'latitude' => $employeeDetails->companies->latitude,
                'longitude' => $employeeDetails->companies->longitude,
                'name' => $employeeDetails->user->name,
                'image' => $employeeDetails->user->image,
                'email' => $employeeDetails->user->email,
                'designation' => $employeeDetails->designation->name,
                'team' => $employeeDetails->team->team_name,
                'role_id' => $lastRole->id ?? null, // Tambahkan role_id
                'role' => $lastRole->name ?? null // Tambahkan role_name
            ],
        ]);
    }

    public function updateEmail(Request $request, $id)
    {
        // Validasi input
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|unique:users,email',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Cari user berdasarkan ID
        $user = User::find($id);

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not found.'
            ], 404);
        }

        // Perbarui email
        $user->email = $request->email;
        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'Email updated successfully.',
            'data' => [
                'user_id' => $user->id,
                'email' => $user->email,
            ],
        ], 200);
    }

    public function updatePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        // Get the currently authenticated user
        $user = Auth::user();

        // Check if the user is authenticated
        if (!$user) {
            return response()->json(['message' => 'Unauthenticated.'], 401);
        }

        $user->password = Hash::make($request->password);
        $user->save();

        return response()->json(['message' => 'Password updated successfully'], 200);
    }
    
    public function logout(Request $request)
    {
        try {
            // Revoke the token that was used to authenticate the current request
            $request->user()->currentAccessToken()->delete();

            return response()->json(['message' => 'Logout successful', 'status' => 'success'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Logout failed', 'status' => 'error'], 500);
        }
    }

}
