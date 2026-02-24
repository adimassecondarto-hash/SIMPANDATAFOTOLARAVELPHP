<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Login;
use App\Models\Data_Semester;
use Exception;
use Illuminate\Support\Facades\Storage;

class DataSemesterController extends Controller
{
    public function dasboard(Request $request)
    {
        try {
            // 1. cek session
            if (!$request->session()->has('username')) {
                return redirect('/datadims/login');
            }

            // 2. ambil username dari session
            $username = $request->session()->get('username');
             

            if (empty($username)) {
                return redirect('/datadims/login');
             }
            // 3. ambil user login
            $user = Login::where('username', $username)->first();

            if (!$user) {
                return redirect('/datadims/login')->withErrors([
                    'username' => 'Session tidak valid, silahkan login kembali.'
                ]);
            }else{
          
            // 4. ambil data semester user login
            $dataSemester = $user->dataSemester()->get();

            // 5. ambil semua user untuk blokir tampilan
            $allUsers = Login::all();

            // 6. kirim data ke Blade
            return view('datadims.dasboard', compact('dataSemester', 'allUsers', 'username'));
            }
        } catch (Exception $e) {
            \Log::error('Error saat mengakses dashboard: '.$e->getMessage());

            return redirect('/datadims/login')->withErrors([
                'username' => 'Terjadi kesalahan, silahkan login kembali.'
            ]);
        }
    }
    
   public function kartukhs(Request $request)
{
    try {

        if (!$request->session()->has('username')) {
            return redirect('/datadims/login');
        }
       
        $this->validasidata($request);

        $username = $request->session()->get('username');
        $user = Login::where('username', $username)->first();

        if (!$user) {
            return redirect('/datadims/login');
        }

        // simpan foto
        $path = $request->file('foto_semester')->store('foto_semester','public');

        // simpan data + foto
        Data_Semester::create([
            'login_id' => $user->id,
            'nama' => $request->nama,
            'npm' => $request->npm,
            'foto_semester' => $path
        ]);

        return redirect()->back()->with('Data berhasil ditambahkan');

    } catch (\Exception $e) {
        \Log::error($e->getMessage());
        return back()->withErrors(['error'=>'Gagal tambah data']);
    }
}
private function validasidata($request) 
    {
     $request->validate([
            'nama' => 'required|string|max:255',
            'npm'  => 'required|string|max:50',
            'foto_semester' => 'required|mimes:pdf|max:51200'
        ]);
    }
}