@extends('pdf.viewbase')
@section('header')
    <div>Sales Report - {{ date('m/d/Y') }}</div>
@stop
@section('body')


    <table width="100%"
           cellpadding="5"
           cellspacing="5"
           border="0">
        <tr>
            <td width="50%"
                align="left">
                <img src="{{ public_path('img/SWF_Inner_Logo.png') }}"
                     style="padding-top: 5px; padding-bottom: 5px; height: 80px">
            </td>
            <td width="50%"
                align="right"
                style="font-size:14px;">
                SWFunk Industrial Contractors <br />
                1710 W Hundred Rd, <br />
                Chester, VA 23836 <br />
                Tel: 888.998.3865 <br />
                Email: info@swfunk.com
            </td>
        </tr>
    </table>


    <table class="gridtable"
           style="margin-bottom: 10px; margin-top: 20px;">
        <thead>
            <tr>
                <th class="sortableHeading"
                    data-orderBy="name">Project</th>
                <th class="sortableHeading"
                    >Facility Name</th>
                <th class="sortableHeading"
                    >Sales Persons</th>
                <th class="sortableHeading"
                    >Final Price</th>
            </tr>
        </thead>
        <tbody>
            <?php $i = 1; ?>
            @foreach ($rows as $k => $project)
                <tr class="deleteBox">
                    <td>{{ $project->name }}</td>
                    <td>{{ @$project->facility->name }}, {{ @$project->facility->location }}</td>
                    <td>
                        {{ $project->salesPersons->map(fn($item) => $item->name)->join(', ') }}
                    </td>
                    <td>{{ money($project->final_estimate) }}</td>


                    </td>
                </tr>
            @endforeach
            <tr>
                <td colspan=""
                    class="text-end">
                    <strong>Total:</strong>
                </td>
                <td>
                    <strong>{{ money($rows->sum('final_estimate')) }}</strong>
                </td>
            </tr>
        </tbody>
    </table>

@stop
