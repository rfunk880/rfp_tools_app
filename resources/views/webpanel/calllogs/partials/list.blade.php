<?php $i = 1; ?>
@foreach ($calllogs as $k => $calllog)
    <tr class="deleteBox">
        <td><input type="checkbox" name="ids[]" value="{{ $calllog->id }}"></td><td>
        <a href="{{ sysRoute('calllogs.show', encryptIt($calllog->id)) }}">
            {!! $calllog->media->count() ? '<i class="fa fa-file"></i>' : ''!!}
             {{ $calllog->subject }} {{-- <br /> {{ $calllog->phone }} --}} </a>
        </td>
        <td>{{ @$calllog->project->pn }}</td>
        <td>
        {!! $calllog->recepients->map(function($item)
        {
            return '<span class="p-2 mb-2 me-2">'.$item->name.' ('.$item->phone.')</span><br/>'; })->join(' ') !!}
            </td>
        <td>{{ $calllog->project->projectDate($calllog->created_at) }}</td>
        <td class="text-end">
           @if(canDelete())
                <a title="Delete Calllog"
                   href="#"
                   class="btn-sm ajaxdelete me-1"
                   data-id="<?php echo $calllog->id; ?>"
                   data-url="<?php echo sysUrl('calllogs/delete/' . encryptIt($calllog->id)); ?>"
                   data-token="<?php echo urlencode(md5($calllog->id)); ?>">
                    <i class="text-danger ti ti-trash"></i>
                </a>
            @endif
        </td>
    </tr>
    <?php $i++; ?>
@endforeach