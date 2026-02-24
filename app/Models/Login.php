<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Login extends Model
{
    protected $table = 'logins';
    // hanya kolom ini yang di izin kan ubah nama user dan input request user yang harus di isi disini
    protected $fillable = [
        'username',
        'is_locked',
        'blocked_until',
        'failed_attempts',
    ];
    
    protected $hidden = [
        'password',
        'remember_token',

    ];
    // protect password untuk tidak di izinkan kolom user ubah isi form 
    protected $guarded = [
        'password',
        'id',
       

    ];

    protected $casts = [
       'blockend_until' => 'datetime',
    ];
    
        
   // HASH BCRYPT PASSWORD //
    public function setHashPassword($value)
    {
            $this->attributes['password'] = bcrypt($value);
    }
    
   
  public function datarekapnilai()
{
    try {
        return $this->hasManyThrough(
            DataRekapDanStudi::class,
            Data_Semester::class,
            'login_id',        // foreign key di data_semester
            'data_semester_id', // foreign key di data_rekap_dan_studi
            'id',              // local key login
            'id'               // local key data_semester
        );
    } catch (\Exception $e) {
        \Log::error('Error relasi datarekapnilai: ' . $e->getMessage());
        return collect(); // biar gak error, tetap return collection kosong
    }
}
 public function dataSemester()
    {
        return $this->hasMany(Data_Semester::class, 'login_id','id');
    }
}