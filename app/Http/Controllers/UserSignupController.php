<?php

namespace App\Http\Controllers;

use App\Mail\VerifyMail;
use App\Models\Order;
use App\Models\Slider;
use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Yoeunes\Toastr\Facades\Toastr;

class UserSignupController extends Controller
{
    public function showSignupForm()
    {
        return view('frontend.pages.userSignup');
    }

    public function showSigninForm()
    {
        return view('frontend.pages.userSignin');
    }

    public function signupStore(Request $request)
    {

        $this->validate($request, [

            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'business_type' => 'required',
            'age' => 'required',
            'phone' => 'required|string|max:15',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
        ]);
        //dd($request->all());
        $input = $request->all();
        $input['password'] = Hash::make($input['password']);
        $verificationCode = rand(100000, 999999);
        try {
            $user = User::create([

                'first_name' => $input['first_name'],
                'last_name' => $input['last_name'],
                'name' => $input['first_name'] . ' ' . $input['last_name'],
                'business_type' => $input['business_type'],
                'age' => $input['age'],
                'is_receive_promotional_notification' => $input['is_receive_promotional_notification'] ?? 0,
                'is_receive_account_notification' => $input['is_receive_account_notification'] ?? 0,
                'email' => $input['email'],
                'phone' => $input['phone'],
                'password' => $input['password'],
                'verification_code' => null,
                'email_verified_at' => now(),
                'is_registration_by' => 'user',

            ]);
            $user->assignRole('User');
            //Mail::to($request->email)->send(new VerifyMail($user));
            Toastr::success('User Register Successfully', 'Success');
            return redirect()->route('show.signin')->with('success', 'Account created, please verify your email.');
        } catch (QueryException $e) {
            if ($e->getCode() === '23000') {
                Toastr::error('The email has already been taken. Please choose a different email.', 'Error');
                return redirect()->back();
            }
            Toastr::error('An error occurred: ' . $e->getMessage(), 'Error');
            return redirect()->back();
        }
    }

    public function userHome()
    {
        $slider = Slider::where('status', 1)->get();
        return view('frontend.pages.userHome', compact('slider'));
    }

    public function userPasswordChange()
    {
        return view('frontend.pages.userPasswordChange');
    }

    public function changeUserPassword(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'password' => 'required',
            'new_password' => 'required|min:8|different:password',
            're_new_password' => 'required|same:new_password',
        ]);
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        $user = Auth::user();
        // Check if current password matches
        if (!Hash::check($request->password, $user->password)) {
            return redirect()->back()
                ->with('error', 'The current password is incorrect.')
                ->withInput();
        }
        // Update password
        $user->password = Hash::make($request->new_password);
        $user->save();
        Toastr::success('Password changed successfully', 'Success');
        return redirect()->back();

    }

    public function userInvoice($id)
    {
        $order = Order::with(['user', 'orderItems', 'payment' => function($query) {
            $query->where('order_type', 'wash');
        }])->findOrFail($id);
        return view('frontend.pages.userInvoice', compact('order'));
    }
}
