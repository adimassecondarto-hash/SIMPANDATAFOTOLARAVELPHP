<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DataRekapDanStudi;
use App\Models\Login;
use Illuminate\Support\Facades\Storage;
use Exception;

class FotoRekapDanUjianController extends Controller
{
    // Tampilkan dashboard dua //
    public function dasboarddua(Request $request)
{
    try {
        // Ambil username dari session
    $username = $request->session()->get('username');

    if (!$username) {
        return redirect('/datadims/login');
    }

    $user = Login::where('username', $username)->first();
     // Ambil semua data semester + relasi rekap studi (hasManyThrough) dari models lalu  kirim ke blade foreach //
    // cek eror juga pada data semester // 
    if ($user) {
         $dataSemester = $user->dataSemester()->with('dataRekapDanStudi')->get();
         $datarekapnilai = $dataSemester->pluck('dataRekapDanStudi')->flatten();
         return view('datadims.dasboarddua', compact('datarekapnilai', 'username'));
    }else{
         $request->session()->forget('username');
        return redirect('/datadims/login')->withErrors([
            'username' => 'Session tidak valid, silahkan login kembali.'
        ]);
    }

    } catch (\Exception $e) {
        \Log::error('Error ambil data rekap nilai: '.$e->getMessage());
        $datarekapnilai = collect(); // kalau error tetap kosong
    }

}

    //Simpan foto rekap studi dan kartu ujian//
    public function rekapstudidanujian(Request $request)
    {
        try {
           $this->validasitipegambar($request);
            // ambil session buat upload studi //
            $pengguna = $request->session()->get('username');
            $user = Login::where('username', $pengguna)->first();
            
            /// user isi data dulu baru redirect ke login juga //
            if($user) {
             // diambil dari models datarekapstudi //
            $fotokartudanrekapstudi = $user->dataSemester()->latest()->first();
            $semester = $fotokartudanrekapstudi;


            // Simpan file ke storage pada upload foto studi dan ujian //
            
            $pathStudi = $request->file('foto_kartu_studi')->store('foto_kartu_studi', 'public');
            $pathUjian = $request->file('foto_kartu_ujian')->store('foto_kartu_ujian', 'public');
            
            // info data tersimpan
            \Log::info('data berhasil disimpan: semester_id=' . $semester->id . ', foto_kartu_studi=' . $pathStudi . ', foto_kartu_ujian=' . $pathUjian);
          
            // Simpan data ke database
            DataRekapDanStudi::create([
                'data_semester_id' => $semester->id,
                'foto_kartu_studi' => $pathStudi,
                'foto_kartu_ujian' => $pathUjian
            ]);
            return redirect()->back()->with('success', 'Data berhasil ditambahkan');
        }else{
           return redirect('/datadims/login');
        }

           

        } catch (Exception $e) {
            // digunakan untuk cek error //
            // cek error di log.laravel pada user //
            if (isset($user)){
    
                 \Log::info('eror pada redirect login:' . $fotokartudanrekapstudi);
                \Log::info('eror redirect' . $user->username . ':' . $user->id);
            
            // cek error di log.laravel pada rekapfoto //
            }else if (isset($fotokartudanrekapstudi) && !$fotokartudanrekapstudi) {
                \Log::error('gagal input data pada rekap studi', $e->getMessage());
                return back()->withErrors(['error' => 'Tidak ada data semester untuk user ini']);
            }
            if($pathUjian){
                \Log::error('Gagal upload foto_kartu_studi: '.$e->getMessage());
                return back()->withErrors(['error' => 'gagal upload foto kartu ujian']);

            }else if($pathStudi){
                
                \Log::error('Gagal upload foto_kartu_studi: '.$e->getMessage());
                return back()->withErrors(['error' => 'gagal upload foto kartu studi']);;
            }
            
            if(!$semester){
                return back()->withErrors(['error' => 'tidak ada data semester']);
            }
            
            \Log::error('Error rekap studi & ujian: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Gagal tambah data']);

            
        }
    }
    private function validasitipegambar($request)
    {
        // Validasi file upload yang di input //
            $request->validate([
                'foto_kartu_studi' => 'required|mimes:pdf|max:51200',
                'foto_kartu_ujian' => 'required|mimes:pdf|max:51200'
            ]);
            
    }
    
    


}