<?php

namespace App\Http\Controllers\Access;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ClientModel;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class RegisterController extends Controller
{
    public function registration (Request $request){
        $validator = Validator::make($request->all(), [
            'fullName' => 'required|max:60',
            'office' => 'required',
            'position' => 'required',
            'email' => [
                'required',
                'email',
                'max:100',
                'unique:clients,email',
                'regex:/^[a-zA-Z0-9._%+-]+@gmail\.com$/',
            ],
            'password' => 'required|min:6|max:30',
            're-password' => 'required|same:password'
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'message' => $validator->errors()
            ]);
        } else {

            $userCount = ClientModel::count();
            $role = $userCount == 0 ? "InventoryAdmin" : "User";
            $generateUsername = $request->get('office') . "."  . $this->trimFirstName($request->get('fullName')) . Str::afterLast($request->get('fullName'), ' ') . $this->generateNumbers(1);
            $client = new ClientModel();
            $client->full_name = strtolower($request->get('fullName'));
            $client->office = $request->get('office');
            $client->position = $request->get('position');
            $client->email = $request->get('email');
            $client->username = strtolower($generateUsername);
            $client->password = Hash::make($request->get('password'));
            $client->status = "Active";
            $client->role = $role;
            $client->save();
           
            return response()->json([
                'username' => $generateUsername,
                'message' => 'Registration successful!',
                'status' => 200
            ]);
        }
        return response()->json([
            'message' => 'Registration Error!',
            'status' => 500
        ]);
    }
    private function generateNumbers($length = 1){
        $numbers = '1234567890';
        $numbersLength = strlen($numbers);
        $randomNumbers = '';
        
        for ($i = 0; $i <= $length; $i++){
            $randomNumbers .= $numbers[rand(0, $numbersLength - 1)];
        }
        return $randomNumbers;
    }
    private function trimFirstName($firstName){
        $firstName = explode(' ', $firstName)[0];
        $firstName = substr($firstName, 0, 1);
        return $firstName;
    }
}
