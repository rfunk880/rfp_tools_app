<?php $i = 1; ?>
@foreach ($messages as $k => $message)
    <tr class="deleteBox">
        <td><input type="checkbox" name="ids[]" value="{{ $message->id }}"></td><td>
        <a href="{{ sysRoute('messages.show', encryptIt($message->id)) }}">
            {!! $message->media->count() ? '<i class="fa fa-file"></i>' : ''!!}
             {{ $message->subject }} {{-- <br /> {{ $message->phone }} --}} </a>
        </td>
        <td>{{ @$message->project->pn }}</td>
        <td>
        {!! $message->recepients->map(function($item)
        {
            return '<span class="p-2 mb-2 me-2">'.$item->name.'&lt;'.$item->email.'&gt;</span><br/>'; })->join(' ') !!}
        {!! @$message->metadata['extra_emails'] ? '<span class="p-2">'.implode(", ", $message->metadata['extra_emails']).'</span>' : '' !!}
        </td>
        <td>{{ $message->project ? $message->project->projectDate($message->created_at) : toAppDate($message->created_at, 'm-d-Y H:i') }}</td>
        <td class="text-end">
            @if(canDelete())
                <a title="Delete Message"
                   href="#"
                   class="btn-sm ajaxdelete me-1"
                   data-id="<?php echo $message->id; ?>"
                   data-url="<?php echo sysUrl('messages/delete/' . encryptIt($message->id)); ?>"
                   data-token="<?php echo urlencode(md5($message->id)); ?>">
                    <i class="text-danger ti ti-trash"></i>
                </a>
            @endif
        </td>
    </tr>
    <?php $i++; ?>
@endforeach