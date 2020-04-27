<?php


namespace App\Exports;


use App\Models\Receipt;
use App\Models\Schedule;
use Carbon\Carbon;
use Illuminate\Database\Query\Builder;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ReceiptExport implements FromQuery, WithHeadings, ShouldAutoSize
{
    use Exportable;
    private $from = null;
    private $to = null;
    public function __construct($from, $to)
    {
        $this->from = $from;
        $this->to = $to;
    }

    public function query()
    {
        $receipts = Receipt::query();

        $receipts->when($this->from, function ($q) use (&$request){
            $q->where('created_at', '>=', Carbon::parse($this->from)->format('Y-m-d'));
        });
        $receipts->when($this->to, function ($q) use (&$request){
            $q->where('created_at', '<=', Carbon::parse($this->to)->format('Y-m-d'));
        });
        return $receipts->select( [
            'id',
            'status',
            'billing_at',
            'receipt_at',
            'export_at',
            'sale_excluded_price',
            'sale_included_price',
            'table_id',
            'table_name',
            'user_id',
            'user_name',
            'created_at'
        ]);
    }

    public function headings(): array
    {
        return [
            'ID',
            'STATUS',
            'BILLING_AT',
            'RECEIPT_AT',
            'EXPORT_AT',
            'SALE_EXCLUDED_PRICE',
            'SALE_INCLUDED_PRICE',
            'TABLE_ID',
            'TABLE_NAME',
            'USER_ID',
            'USER_NAME',
            'CREATED_AT'
        ];
    }
}
