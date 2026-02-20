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

    if (!$user) {
        $request->session()->forget('username');
        return redirect('/datadims/login')->withErrors([
            'username' => 'Session tidak valid, silahkan login kembali.'
        ]);
    }
    // Ambil semua data semester + relasi rekap studi (hasManyThrough) kirim ke blade foreach //
    // cek eror juga pada data semester // 
        $dataSemester = $user->dataSemester()->with('dataRekapDanStudi')->get();
        $datarekapnilai = $dataSemester->pluck('dataRekapDanStudi')->flatten();
    return view('datadims.dasboarddua', compact('datarekapnilai', 'username'));


    } catch (\Exception $e) {
        \Log::error('Error ambil data rekap nilai: '.$e->getMessage());
        $datarekapnilai = collect(); // kalau error tetap kosong
    }

}

    //Simpan foto rekap studi dan kartu ujian//
    public function rekapstudidanujian(Request $request)
    {
        try {
            // Validasi file upload yang di input //
            $request->validate([
                'foto_kartu_studi' => 'required|mimes:pdf|max:51200',
                'foto_kartu_ujian' => 'required|mimes:pdf|max:51200'
            ]);
            // ambil session buat upload studi //
            $username = $request->session()->get('username');
            $user = Login::where('username', $username)->first();
            
            /// user redirect ke login juga //
            if (!$user) {
                return redirect('/datadims/login');
            }
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
}