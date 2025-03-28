<?php

namespace App\Http\Controllers\Inventory_Admin\Accounts;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ClientModel;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

class ProfileManager extends Controller
{
    public function goToProfile(){
        $client_id = session()->get('loggedInInventoryAdmin')['id'];
        $client = ClientModel::where('id', $client_id)->first();
        return view('admin.accounts.profile', [
            'client' => $client
        ]);
    }
    public function updateProfile(Request $request){
        $client_id = session()->get('loggedInInventoryAdmin')['id'];
        $client = ClientModel::where('id', $client_id)->first();
        
        // Initial validation rules
        $rules = [
            'full_name' => 'required|max:100',
            'position'  => 'required',
            'old_password' => 'required',
        ];
        
        // Custom messages for initial validation
        $messages = [
            'full_name.required' => 'Full name is required',
            'position.required' => 'Position is required',
            'old_password.required' => 'Old password is required',
        ];
        
        // Add conditional validation for email
        if ($request->filled('email') && $request->email !== $client->email) {
            $rules['email'] = 'required|min:6|max:30|regex:/^[a-zA-Z0-9._%+-]+@gmail\.com$/|unique:clients,email';
            $messages['email.required'] = 'Email is required!';
            $messages['email.regex'] = 'Email must be a valid Gmail address!';
            $messages['email.unique'] = 'Email is already taken!';
        }
        
        // Create the validator
        $validator = Validator::make($request->all(), $rules, $messages);
        
        // Check if validation fails
        if ($validator->fails()) {
            return response()->json([
                'message' => $validator->getMessageBag()->all(),  // Return all error messages
                'status' => 400,
            ]);
        } else {
            // Check if old password is correct
            if (!Hash::check($request->input('old_password'), $client->password)) {
                return response()->json([
                    'message' => ['Old password is incorrect'],  // Specific message for password mismatch
                    'status' => 404,
                ]);
            } else {
                // Handle password update logic
                $updateData = [
                    'fullname' => $request->fullname,
                    'position' => $request->position,
                    'username' => $request->username,
                    'email' => $request->email,
                ];
        
                // Validate new password if provided
                if ($request->filled('new_password')) {
                    $passwordValidator = Validator::make($request->all(), [
                        'new_password' => 'required|min:6|max:50',
                        're_password' => 'required|min:6|same:new_password',
                    ], [
                        're_password.same' => 'Retype password does not match',
                        're_password.required' => 'Retype password is required',
                    ]);
        
                    // Check if password validation fails
                    if ($passwordValidator->fails()) {
                        return response()->json([
                            'message' => $passwordValidator->getMessageBag()->all(),  // Return all error messages for password validation
                            'status' => 404,
                        ]);
                    }
        
                    // Update password
                    $updateData['password'] = Hash::make($request->input('new_password'));
                }
        
                // Perform client update
                $client->update($updateData);
        
                // Respond with success or error message
                if ($client) {
                    return response()->json([
                        'message' => 'Update account success!',
                        'status' => 200,
                    ]);
                } else {
                    return response()->json([
                        'message' => 'Check your internet connection!',
                        'status' => 500,
                    ]);
                }
            }
        }        
    }
}
