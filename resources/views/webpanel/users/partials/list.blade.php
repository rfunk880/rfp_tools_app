<?php $i = 1; ?>
@foreach ($users as $user)
    <tr class="deleteBox">
        <td><x-avatar :user="$user" /></td>
        <td><a href="mailto: {{ $user->email }}">{{ $user->email }}</a></td>
        <td>{!! @\App\Models\UserType::$typeLabel[$user->user_type_id] !!}</td>
        <td>{{ date('m-d-Y', strtotime($user->created_at)) }}</td>
        <td>
            @if (@$user->last_login_at)
                {{ date('m-d-Y h:i', strtotime(@$user->last_login_at)) }}
            @else
                Not Logged In Yet
            @endif
        </td>
        <td>
            {!! @\App\Models\User::$statusLabel[$user->status] !!}</td>
        <td>
            <nav class="nav nav-icon">
                @if(canEdit())
                    <a title="Edit"
                       href="<?php echo sysRoute('users.edit', encryptIt($user->id)); ?>"
                       class="btn-sm me-1">
                        <i class="ti ti-edit"></i>
                    </a>
                @endif

                @if (isManagement())
                    <a href="<?php echo sysUrl('users/loginas/' . encryptIt($user->id)); ?>"
                       class="btn-sm me-1"
                       title="Impersonate">
                        <i class="ti ti-user-star"></i>
                    </a>
                @endif

                <a title="Reset"
                   href="{{ sysUrl('reset-password/' . encryptIt($user->id)) }}"
                   class="btn-sm me-1">
                    <i class="ti ti-refresh"></i>
                </a>

                <a title="Remove"
                   class="ajaxdelete btn-sm me-1"
                   href="#"
                   data-id="<?php echo $user->id; ?>"
                   data-title="Delete User"
                   data-url="<?php echo sysUrl('users/delete/' . encryptIt($user->id)); ?>"
                   data-token="<?php echo urlencode(md5($user->id)); ?>">
                    <i class="text-danger ti ti-trash"></i>
                </a>
            </nav>

        </td>
    </tr>

    <?php $i++; ?>
@endforeach
