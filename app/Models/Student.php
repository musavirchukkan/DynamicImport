<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;
    protected $guarded=[];
    protected $table = 'students';
    public function getColumnNames()
    {
        $columnNames = $this->getConnection()->getSchemaBuilder()->getColumnListing($this->getTable());
        $columnNames = array_diff($columnNames, ['id', 'created_at', 'updated_at']);
        return $columnNames;
    }
}
