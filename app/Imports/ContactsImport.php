<?php
namespace App\Imports;

use App\Models\Tag;
use App\Models\Company;
use App\Models\Contact;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ContactsImport implements ToCollection, WithHeadingRow
{

    public $companies = [];
    public $tags = [];

    public function collection(Collection $rows)
    {
        foreach ($rows as $row) 
        {
            $row = $row->toArray();
            $company_id = $this->getCompanyId($row);
            $contact = Contact::create([
                'name'     => @$row['name'],
                'email'    => @$row['email'],
                'cell' => @$row['cell'],
                'phone' => @$row['phone'],
                'notes' => @$row['notes'],
                'location' => @$row['location'],
                'title' => @$row['title'],
                'company_id' => $company_id
            ]);

            if (@$row['trades'] != '') {
                $tags = explode(",", $row['trades']);
                $tagIds = [];
                foreach ($tags as $tag) {
                    $tagIds[] = $this->getTagId(trim($tag));
                }
                $contact->tags()->attach($tagIds);
            }
            if (@$row['tags'] != '') {
                $tags = explode(",", $row['tags']);
                $tagIds = [];
                foreach ($tags as $tag) {
                    $tagIds[] = $this->getTagId(trim($tag));
                }
                $contact->tags()->attach($tagIds);
            }
        }
    }

    public function getTagId($tag)
    {
        $key = strtolower($tag);
        if (isset($this->tags[$key])) {
            return $this->tags[$key];
        }
        $tag = Tag::firstOrCreate([
            'name' => $tag
        ]);
        $this->tags[$key] = $tag->id;
        return $tag->id;
    }

    public function getCompanyId($row)
    {
        $key = strtolower($row['company_name']);
        if (isset($this->companies[$key])) {
            return $this->companies[$key];
        }
        $company = Company::firstOrCreate([
            'name' => @$row['company_name'],
            'phone' => @$row['company_phone'],
            'city' => @$row['company_city'],
            'state' => @$row['company_state'],
        ]);
        $company->type = @$row['company_type'] ?? Company::TYPE_SUBCONTRACTOR;
        $company->save();
        $this->companies[$key] = $company->id;
        return $company->id;
    }
}
