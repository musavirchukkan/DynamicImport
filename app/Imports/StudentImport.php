<?php

namespace App\Imports;

use App\Models\Student;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class StudentImport implements ToModel, WithHeadingRow
{
    private $columnMapping;

    public function __construct(array $columnMapping)
    {
        $this->columnMapping = $columnMapping;
        // return $this->columnMapping;
        log(100);
    }

    public function model(array $row)
    {
        $data = ['name'=>'test'];

        foreach ($this->columnMapping as $excelColumn => $modelAttribute) {
            if (isset($row[$excelColumn])) {
                $data[$modelAttribute] = $row[$excelColumn];
            }
        }


        return new Student($data);
    }
}
