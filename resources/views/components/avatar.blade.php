@props(['user', 'subtitle'])
<span class="d-flex align-items-center">
    <span>
        @if ($user->profile_photo_path)
            <img src="{{ $user->profile_thumb_url }}" style="width:40px;height:40px; class="rounded-circle" />
        @else
            <span class="rounded-circle bg-dark text-white d-flex align-items-center justify-content-center" style="width:40px;height:40px;">
                {{ $user->initialLetters }}
            </span>
        @endif
    </span>
    <span class="d-flex flex-column ms-2">
        <span>{{ $user->name }}</span>
        @if(@$subtitle)
        <span class="text-sm">{{ $subtitle }}</span>
        @endif
    </span>
</span>