<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Data_Semester extends Model
{
    protected $table = 'data_semester';

    
    protected $fillable = [
        'login_id',
        'nama',
        'npm',
        'foto_semester',

    ];
   

    public function Login()
    {
        return $this->belongsTo(Login::class);
    }

    public function dataRekapDanStudi()
    {
        return $this->hasMany(DataRekapDanStudi::class,'data_semester_id','id');
    }
}
