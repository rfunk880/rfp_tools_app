<?php
namespace App\Exports;

use App\Models\Company;
use App\Models\Contact;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;

class ContactExport implements FromQuery, WithMapping, WithHeadings
{
    public function map($contact): array
    {
        return [
            @$contact->company->name,
            @$contact->company->type,
            @$contact->company->phone,
            @$contact->company->city,
            @$contact->company->state,
            @$contact->name,
            @$contact->email,
            @$contact->cell,
            @$contact->phone,
            @$contact->title,
            @$contact->location,
            @$contact->notes,
            @$contact->company->name,
            @$contact->company->type,
            @$contact->tags->pluck('name')->join(',')
        ];
    }


    public function query()
    {
        return Contact::query()->with(['company', 'tags']);
    }

    public function headings(): array
    {
        return [
            'Company Name',
            'Company Type',
            'Company Phone',
            'Company City',
            'Company State',
            'Name',
            'Email',
            'Cell',
            'Phone',
            'Title',
            'Location',
            'Notes',
            'Tags'
        ];
    }
}
