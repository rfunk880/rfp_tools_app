@extends('pdf.viewbase')
@section('header')
<div>Meeting Report - {{ date("m/d/Y")}}</div>
@stop
@section('body')

    <table width="100%" cellpadding="5" cellspacing="5" border="0">
        <tr>
            <td width="100%" align="center">
                <strong>SW Funk Industrial – Bid Report - {{ date("d/m/Y")}}</strong>
            </td>
        </tr>
    </table>

    @php
        $itemsGroup = $rows->groupBy('status');
    @endphp
    

    @if (isset($itemsGroup[\App\Models\Project::STATUS_PROSPECT]))
        <span class="badge badge-pill bg-success">Prospect</span><br />
        <table class="gridtable" style="margin-bottom: 10px; margin-top: 20px;">
            <thead>
                <tr>
                    <th>PN#</th>
                    <th>Bid Due</th>
                    <th>Site Visit</th>
                    <th>Facility</th>
                    <th>Project Name</th>
                    <th>Estimators</th>
                    <th>Sales Persons</th>
                    <th>Client(s)</th>
                </tr>
            </thead>
            <tbody>
                @include('pdf.reports.partials.tbody', [
                    'projects' => $itemsGroup[\App\Models\Project::STATUS_PROSPECT],
                ])
            </tbody>
        </table>
        <div style="page-break-before:always">&nbsp;</div>
    @endif
    @if (isset($itemsGroup[\App\Models\Project::STATUS_BIDDING]))
        <span class="badge badge-pill bg-success"> BIDDING</span><br />
        <table class="gridtable" style="margin-bottom: 10px; margin-top: 20px;">
            <thead>
                <tr>
                    <th>PN#</th>
                    <th>Bid Due</th>
                    <th>Site Visit</th>
                    <th>Facility</th>
                    <th>Project Name</th>
                    <th>Estimators</th>
                    <th>Sales Persons</th>
                    <th>Client(s)</th>
                </tr>
            </thead>
            <tbody>
                @include('pdf.reports.partials.tbody', [
                    'projects' => $itemsGroup[\App\Models\Project::STATUS_BIDDING],
                ])
            </tbody>
        </table>
        <div style="page-break-before:always">&nbsp;</div>
    @endif
    @if (isset($itemsGroup[\App\Models\Project::STATUS_PENDING]))
        <span class="badge badge-pill bg-success"> Pending</span><br />
        <table class="gridtable" style="margin-bottom: 10px; margin-top: 20px;">
            <thead>
                <tr>
                    <th>PN#</th>
                    <th>Bid Due</th>
                    <th>Site Visit</th>
                    <th>Facility</th>
                    <th>Project Name</th>
                    <th>Estimators</th>
                    <th>Sales Persons</th>
                    <th>Client(s)</th>
                </tr>
            </thead>
            <tbody>
                @include('pdf.reports.partials.tbody', [
                    'projects' => $itemsGroup[\App\Models\Project::STATUS_PENDING],
                ])
            </tbody>
        </table>
        <div style="page-break-before:always">&nbsp;</div>
    @endif
    @if (isset($itemsGroup[\App\Models\Project::STATUS_WON]))
        <span class="badge badge-pill bg-success"> Won - excluding PO Status: Awarded</span><br />
        <table class="gridtable" style="margin-bottom: 10px; margin-top: 20px;">
            <thead>
                <tr>
                    <th>PN#</th>
                    <th>Bid Due</th>
                    <th>Site Visit</th>
                    <th>Facility</th>
                    <th>Project Name</th>
                    <th>Estimators</th>
                    <th>Sales Persons</th>
                    <th>Client(s)</th>
                </tr>
            </thead>
            <tbody>
                @include('pdf.reports.partials.tbody', [
                    'projects' => $itemsGroup[\App\Models\Project::STATUS_WON],
                ])
            </tbody>
        </table>
        <div style="page-break-before:always">&nbsp;</div>
    @endif
    
    <script type="text/php">
        if ( isset($pdf) ) {
            $pdf->page_text(290, 820, "Header: {PAGE_NUM} of {PAGE_COUNT}", null, 7, array(0,0,0));
        }
    </script>
@stop
