@extends('layouts.default')

@section('content')
    <h1 class="text-xl font-semibold">{{ $title }}</h1>
    <div class="rounded-lg border p-6 dark:border-indigo-200 bg-zinc-900 mb-4">
        <p class="text-base">{{ $text }}</p>
    </div>

    @if ($previous)
        <div class="rounded-lg border p-6 dark:border-indigo-600 bg-zinc-900 mb-4">
            @if ($previous->sqid)
                <h2 class="text-lg font-semibold">Previous revision ({{ $previous->sqid }})</h2>
            @else
                <h2 class="text-lg font-semibold">Original post {{ $previous->sqid }}</h2>
            @endif
            <p class="text-base">
                <strong class="font-bold">Title:</strong> {{ $previous->title }}<br>
                <strong class="font-bold">Text:</strong> {{ $previous->text }}<br>
                <strong class="font-bold">Changed by:</strong> {{ $previous->user->name }}<br>
                <strong class="font-bold">Changed at:</strong> {{ $previous->created_at }}
            </p>
        </div>
    @endif

    <div class="rounded-lg border p-6  dark:border-indigo-600 bg-zinc-900">
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
            @if ($hasRevisions)
                <li><a class="underline" href="{{ $slug }}?original=true">Original post</a> ({{ $created_at }})</li>
            @endif
        </ul>
    </div>
@endsection
