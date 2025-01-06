@extends('layouts.default')

@section('content')
    <h1 class="text-xl font-semibold">{{ $title }}</h1>
    <div class="rounded-lg border p-6 dark:border-indigo-200 bg-zinc-900 mb-4">
        <p class="text-base">{{ $text }}</p>
    </div>

    @if ($previous && $show_revisions)
        <div class="rounded-lg border p-6 dark:border-indigo-600 bg-zinc-900 mb-4">
            @if ($previous->sqid)
                <h2 class="text-lg font-semibold">Previous version ({{ $previous->sqid }})</h2>
            @else
                <h2 class="text-lg font-semibold">Previous version (Original post)</h2>
            @endif
            <p class="text-base">
                <strong class="font-bold">Title:</strong> {{ $previous->title }}<br>
                <strong class="font-bold">Text:</strong> {{ $previous->text }}<br>
                <strong class="font-bold">Changed by:</strong> {{ $previous->user->name }}<br>
                <strong class="font-bold">Changed at:</strong> {{ $previous->created_at }}
            </p>
        </div>
    @endif

    @if ($has_revisions && $show_revisions)
        <div class="rounded-lg border p-6  dark:border-indigo-600 bg-zinc-900 mb-4">
            @if ($previous)
                <h2 class="text-lg font-semibold">Version history ({{ $revisions->count() + 2 }})</h2>
            @else
                <h2 class="text-lg font-semibold">Version history (1)</h2>
            @endif
            <ul class="list-disc list-inside space-y-1 text-base mb-4 mt-2">
                <li class="list-item"><em class="italic"><span class="font-bold">[Current]</span> {{ $title }} ({{ $created_at }})</em></li>
                @foreach ($revisions as $revision)
                    <li class="list-item"><a class="underline" href="{{ $slug }}/history/{{ $revision->sqid }}">[{{ $revision->sqid }}] {{ $revision->title }}</a> ({{ $revision->created_at }})</li>
                @endforeach
                @if ($has_revisions)
                    <li><a class="underline" href="{{ $slug }}?original=true">Original post</a> ({{ $created_at }})</li>
                @endif
            </ul>
        </div>
    @endif

    <div class="rounded-lg border p-6 dark:border-indigo-600 bg-zinc-900 mb-4">
        <h2 class="text-lg font-semibold">Replies</h2>
        @if ($replies->count())
            <ul class="list-disc list-inside space-y-1 indent-0 text-base mb-4 mt-2">
                @foreach ($replies as $reply)
                    @include('shared.reply', ['reply' => $reply, 'depth' => 1])
                @endforeach
            </ul>
        @else
            <p class="text-base">No replies yet.</p>
        @endif
    </div>

    @if ($previous && $show_revisions)
        <div class="rounded-lg border p-6 dark:border-indigo-600 bg-zinc-900 mb-4">
            <h2 class="text-lg font-semibold">Replies to original post</h2>
            @if ($original_replies->count())
                <ul class="list-disc list-inside space-y-1 text-base mb-4 mt-2">
                    @foreach ($original_replies as $reply)
                        @include('shared.reply', ['reply' => $reply, 'depth' => 1])
                    @endforeach
                </ul>
            @else
                <p class="text-base">No replies to original version.</p>
            @endif
        </div>
    @endif
@endsection
