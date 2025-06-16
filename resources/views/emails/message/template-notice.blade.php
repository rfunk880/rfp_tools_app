@extends('layouts.mail')
@section('content')

A notice has been issued for the following project.<br/><br />
Project:<b> {{ $project->name }}</b><br />
Location:<b> {{ @$project->facility->name }}</b> {{ @$project->facility->location }}<br /><br />
{{ $project->public_notes }}<br /><br />
Bid Due Date: {{ toAppDate($project->subcontractor_bid_due) }}<br /><br />
@if ($project->bid_document)
    <a href="{{ $project->bid_document ? $project->bid_document : '#' }}"
        target="_blank">View Bid Documents</a><br /><br />
@endif
<i>{!! $model->content !!}</i><br /><br />
@if ($project->estimators->count())
    Estimator: {{ @$project->estimators[0]->name }}  {{ @$project->estimators[0]->phone }} 
    {{ @$project->estimators[0]->email }}<br/>
@endif
<br/>
Submit all quotes to estimating@swfunk.com<br /><br />
<p style="color: red; font-weight: bold;">Please reply to this email if you do not intend to submit a proposal.</p>
If you are having difficulty accessing bid documents or have general questions about this RFP, please contact
Scott Mistr (smistr@swfunk.com or 804-414-2560).<br /><br />


@if ($model->getMedia('attachments'))

@endif
@stop