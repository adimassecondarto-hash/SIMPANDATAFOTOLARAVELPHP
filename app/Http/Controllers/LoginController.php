<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use App\Models\Login;

class LoginController extends Controller
{
    public function lihatLogin()
    {
        return view('datadims.login');
    }

    public function Login(Request $request)
    {
        try {

            // 0. VALIDASI
            $request->validate([
                'username' => 'required|string',
                'password' => 'required|string',
            ]);

            $user = Login::where('username', $request->username)->first();

            

            // 1. CEK BLOKIR // ambil statusblokir di privat method(function)
          if ($this->statusblokir($user)) {
                return back()->withErrors([
                    'username' => 'Akun diblokir sekarang pada tanggal' . $user->blocked_until,
                ]);
            
            

           // 2. PASSWORD  DAN USERNAME SALAH MASUK KE SINI
          }else if (!$user || ! Hash::check($request->password, $user->password)) {


            if($user){
              $this->sistemblok($user);
              return;
            }else{
               return back()->withErrors([
                    'password' => 'Password salah. Percobaan ke-'
                ]);
              }
              
        }else{
         //3. PASSWORD BENAR MASUK SINI //
        $this->berhasillogin($user);
        $request->session()->put('username', $user->username);

        return redirect('/datadims/dasboard');
        }
        } catch (\Exception $e) {

            Log::error('Error saat login: ' . $e->getMessage());

            return back()->withErrors([
                'username' => 'Terjadi kesalahan sistem.'
            ]);
        }
    }
    
    private function statusblokir($user)
    {
      return $user && $user->blocked_until && now()->lessThan($user->blocked_until);
    }

    private function berhasillogin($user)
    {
        $user->failed_attempts = 0;
        $user->blocked_until = null;
        $user->is_locked = false;
        $user->save();
    }
     
    private function sistemblok($user)
    {
        $user->failed_attempts += 1;
        Log::info('Failed attempts sekarang: ' . $user->failed_attempts);
             
        if ($user->failed_attempts >= 3) {
           $user->is_locked = true;
           $user->blocked_until = now()->addMinutes(50);
           Log::warning('User diblokir: ' . $user->username);

           }
        $user->save();
    }

    public function Logout(Request $request)
    {
        $user = $request->session()->get('username');
        // pengecekan succes atau gagal
         if($user){
          Log::info('logout succes ' . $user);
        }else{
          Log::info('logout gagal ' . $user);
        }
        $request->session()->forget('username');
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/datadims/login');
        
   
        
        
    }
}