<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash; 
use App\Models\Login;
use Exception;
class LoginController extends Controller
{
    public function lihatLogin()
    {
        return view('datadims.login');
    }
    // tempat proses login
    public function Login(Request $request)
    {

    try{


        // validasi username password //
        $request->validate([
        'username' => 'required|string',
        'password' => 'required|string',
    ]);

    $username = $request->username;
    $password = $request->password;

    $user = Login::where('username', $username)->first();

    

    if ($user->is_locked && $user->blocked_until && now()->lessThan($user->blocked_until)) {
        return back()->withErrors([
            'username' => 'Akun diblokir sementara karena terlalu banyak percobaan login.'
        ]);
    }

    if (Hash::check($password, $user->password)) {

        Login::where('username', $username)->update([
            'failed_attempts' => 0,
            'is_locked' => false,
            'blocked_until' => null,
        ]);

        //  GANTI INI
        $request->session()->put('username', $username);

        return redirect('/datadims/dasboard');
    }

    // password salah
    $failed = $user->failed_attempts + 1;
    $is_locked = $failed >= 3 ? true : false;
    $blocked_until = $is_locked ? now()->addHour() : null;

    Login::where('username', $username)->update([
        'failed_attempts' => $failed,
        'is_locked' => $is_locked,
        'blocked_until' => $blocked_until,
    ]);

    return back()->withErrors([
        'password' => 'Password salah.'
    ]);

     
   }catch(\Exception $e){
     if($blocked_until){
        Log::error('Gagal memblok akun nya: '.$e->getMessage());
        Log::info('berhasil memblok akun' .$e->getMessage());
    }
     if (!$user) {
        return back()->withErrors([
            'username' => 'Username tidak ditemukan.'
        ]);
        Log::error('username bermasalah '.$e->getMessage());
    }

    if (!$password) {
        Log::error('password bermasalah '.$e->getMessage());
    }

    }

  }

    // tempat logout 
    public function Logout(Request $request)
    {
        $request->session()->forget('username');
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/datadims/login');
    }
}