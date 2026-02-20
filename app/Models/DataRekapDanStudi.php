<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DataRekapDanStudi extends Model
{
    protected $table = 'data_rekap_dan_studis';
    protected $fillable = [
         'data_semester_id',
        'foto_kartu_ujian',
        'foto_kartu_studi',

    ];

    public function dataSemester()
    {
        // hasMany(RelatedModel, foreignKeyDiRelated, localKey)
        return $this->belongsTo(Data_Semester::class,'data_semester_id','id');
    }
}

