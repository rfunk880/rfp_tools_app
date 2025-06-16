<?php $i = 1; ?>
@foreach ($facilities as $k => $facility)
    <tr class="deleteBox">
        <td>{{ SN($facilities, $k + 1)}}</td>
        <td>{{ @$facility->name }}</td>
        <td>{{ @$facility->owner }}</td>
        <td>{{ @$facility->location }}</td>
        <td>{{ @$facility->projects->count() }}</td>
        
        <td class="text-end">
        	
            {{-- @can(\App\Module::CONTACTS_EDIT)
                <a href="<?php echo sysRoute('facilities.live-edit', encryptIt($facility->id)); ?>" title="Edit Facility" class="btn-sm me-1">
                    <i class="ti ti-edit"></i>
                </a>
            @endcan --}}
            @if($facility->projects->count() == 0)
            @if(canEdit())
                <a title="Delete Facility"
                   href="#"
                   class="btn-sm ajaxdelete me-1"
                   data-id="<?php echo $facility->id; ?>"
                   data-url="<?php echo sysUrl('facilities/delete/' . encryptIt($facility->id)); ?>"
                   data-token="<?php echo urlencode(md5($facility->id)); ?>">
                    <i class="text-danger ti ti-trash"></i>
                </a>
            @endif
            @endif
        </td>
    </tr>
    <?php $i++; ?>
@endforeach
