<?php

namespace App\Http\Controllers;

use Mail;
use Auth;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\User;
use Session;

class LoginController extends Controller
{
    protected $data = [];

    /**
     * @return login page view
     */
    public function login()
    {
        $data = [];
        $data['companyData'] = \DB::table('preference')->where(['id'=>9])->first();
       // d($data['companyData']->value,1);
    	return view('auth.login', $data);
    }

    /**
     * Login authenticate operation.
     *
     * @return redirect dashboard page
     */
    public function authenticate(Request $request)
    {
        //$companyData = \DB::table('company')->where('company_id', $request->company)->first();
        //$companyData = objectToArray($companyData);
        
        //selectDatabase1($companyData['host'], $companyData['db_user'], $companyData['db_password'], $companyData['db_name']);
        
        $this->validate($request, User::$login_validation_rule);
        $data = $request->only('email', 'password');
        if (Auth::attempt($data)) {
            $role_id = Auth::user()->role_id;
            $role = \DB::table('security_role')->where('id', $role_id)->first();
            $roleArea = unserialize($role->areas);

            $pref = \DB::table('preference')->get();
            
            if(!empty($pref)) {
                $prefData = AssColumn($a=$pref, $column='id');

                foreach ($prefData as $value) {
                    $prefer[$value->field] = $value->value;
                }
            }
            
            Session::put($roleArea);
            //Session::put($companyData);
            Session::put($prefer);
            
            $curr = \DB::table('currency')->where('id',Session::get('dflt_currency_id'))->first();
            
            $currency['currency_name'] = $curr->name;
            $currency['currency_symbol'] = $curr->symbol;
            Session::put($currency);
            //Session::get('dflt_lang')
            
            return redirect()->intended('dashboard');
        }

        return back()->withInput()->withErrors(['email' => "Invalid Username & Password"]);
    }

    /**
     * logout operation.
     *
     * @return redirect login page view
     */
    public function logout()
    {
    	Auth::logout();
        \Session::flush();

    	return redirect('/login');
    }

    /**
     * forget password
     *
     * @return forget password form
     */
    public function reset()
    {
        $data = [];
        //$data['companyData'] = \DB::table('company')->get();
        
        return view('auth.passwords.email', $data);
    }

    /**
     * Send reset password link
     *
     * @return Null
     */
    public function sendResetLinkEmail(Request $request)
    {
        //setDbConnect($request->company);
        
        $this->validate($request, [
            'email' => 'required|email|exists:users',
            //'company' => 'required',
        ]);

        $data['email'] = $request->email;
        $data['token'] = base64_encode(encryptIt(rand(1000000,9999999).'_'.$request->email));
        $data['created_at'] = date('Y-m-d H:i:s');
        
        \DB::table('password_resets')->insert($data);

        Mail::send('auth.emails.password', ['data' => $data], function ($message) use ($data) {
            
            $message->from('us@example.com', 'Stock Manager');

            $message->to($data['email'])->subject('Reset Password!');

        });

        \Session::flash('status','Password reset link sent to your email address');
        return back()->withInput();

    }

    /**
     *
     * @return reset password page view
     */
    public function showResetForm(Request $request, $tokens)
    {
        $db_token = base64_decode($tokens);
        $token = decryptIt($db_token);
        $str=explode("_",$token);

        //setDbConnect($str['0']);

        $tokn = \DB::table('password_resets')->where('token', $tokens)->get();
        
        if (empty($tokn)) {

            return redirect()->intended('password/reset')->withErrors(['email' => "Invalid Password Token"]);
            exit;
        }

        $data['c_id'] = $str['0'];
        $data['token'] = $tokens;
        $data['userData'] = \DB::table('users')->where('email', $str['1'])->first();

        return view('auth.passwords.reset',$data);
    }

    /**
     *
     * @return redirect login page view
     */
    public function setPassword(Request $request)
    {
        
        if ($_POST) {

            //setDbConnect($request->c_id);

            $this->validate($request, [
                'password' => 'required|min:6|confirmed',
            ]);

            $user_pass = \Hash::make($request->password);

            \DB::table('users')->where('id', $request->id)->update(['password'=> $user_pass]);
            \DB::table('password_resets')->where('token', '=', $request->token)->delete();

            \Session::flash('status','Password reset Successfull');
            return redirect()->intended('login');
        }
    }
}
