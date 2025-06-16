<?php $i = 1; ?>
@foreach ($companies as $k => $company)
    <tr class="deleteBox">
        <td><input type="checkbox" class="form-check-input checkbox-slaves" name="ids[]" value="{{ $company->id}}"></td>
        <td><a href="{{ sysRoute('companies.show', encryptIt($company->id)) }}">{{ $company->name }} {{-- <br />
                {{ $company->phone }} --}}</a></td>
        <td>{{ $company->type }}</td>
        <td>{{ $company->location }}</td>
        <td>{{ $company->contacts->count() }}</td>
        <td>
            {{ @$company->primaryContact->name }}<br />
            {{ @$company->primaryContact->email }}<br />
            {{ @$company->primaryContact->phone }}
        </td>
        <td>{{ $company->lastUpdatedAt() }}</td>
        <td class="text-end">
            @if(canEdit())
                <a href="<?php echo sysRoute('companies.edit', encryptIt($company->id)); ?>"
                   title="Edit Contact"
                   class="btn-sm me-1">
                    <i class="ti ti-edit"></i>
                </a>
            @endif
            @if(canDelete())
                <a title="Delete Contact"
                   href="#"
                   class="btn-sm ajaxdelete me-1"
                   data-id="<?php echo $company->id; ?>"
                   data-url="<?php echo sysUrl('companies/delete/' . encryptIt($company->id)); ?>"
                   data-token="<?php echo urlencode(md5($company->id)); ?>">
                    <i class="text-danger ti ti-trash"></i>
                </a>
            @endif
        </td>
    </tr>
    <?php $i++; ?>
@endforeach
