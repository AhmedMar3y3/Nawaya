<?php

namespace App\Http\Resources\Admin\Subscription;

use Illuminate\Http\Request;
use App\Helpers\FormatArabicDates;
use Illuminate\Http\Resources\Json\JsonResource;

class InvoiceResource extends JsonResource
{
    protected $packageTitle;
    protected $subtotal;
    protected $vat;
    protected $company;

    public function __construct($resource, $packageTitle = null, $subtotal = null, $vat = null, $company = null)
    {
        parent::__construct($resource);
        $this->packageTitle = $packageTitle;
        $this->subtotal     = $subtotal;
        $this->vat          = $vat;
        $this->company      = $company;
    }

    public function toArray(Request $request): array
    {
        return [
            'invoice_id'      => $this->invoice_id,
            'invoice_date'    => $this->created_at->format('d F Y'),
            'invoice_date_ar' => FormatArabicDates::formatArabicDate($this->created_at),
            'user'            => [
                'name'  => $this->user?->full_name ?? ($this->full_name ?? '-'),
                'email' => $this->user?->email ?? '-',
                'phone' => $this->user?->phone ?? ($this->phone ?? '-'),
            ],
            'workshop'        => [
                'title' => $this->workshop?->title ?? '-',
            ],
            'package_title'   => $this->packageTitle ?? '-',
            'subtotal'        => round($this->subtotal ?? 0, 2),
            'vat'             => round($this->vat ?? 0, 2),
            'total'           => $this->paid_amount,
            'company'         => $this->company ?? [],
        ];
    }
}
