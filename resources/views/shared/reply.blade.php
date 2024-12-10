<li>
    {{ $reply->text }}
    @if ($reply->replies()->count() > 0)
        <ul class="list-disc list-inside space-y-1 indent-{{ $depth * 4 }} text-base mb-4 mt-2">
            @foreach ($reply->replies as $reply)
                @include('shared.reply', ['reply' => $reply, 'depth' => $depth + 1])
            @endforeach
        </ul>
    @endif
</li>
