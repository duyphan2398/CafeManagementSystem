<?php

namespace App\Exports;

use App\Models\Material;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;

class MaterialExport  implements FromQuery, WithHeadings, ShouldAutoSize
{
    use Exportable;

    public function query()
    {
        return Material::query()->orderBy('name');
    }

    /**
     * @inheritDoc
     */
    public function headings(): array
    {
        return [
            'ID',
            'MATERIAL NAME',
            'AMOUNT',
            'UNIT',
            'NOTE',
            'UPDATED AT'
        ];
    }
}
