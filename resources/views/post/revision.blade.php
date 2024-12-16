@extends('layouts.default')

@section('content')
    <h1 class="text-xl font-semibold">Revision: {{ $revision->sqid }}</h1>
    <div class="rounded-lg border p-6 dark:border-indigo-600 bg-zinc-900 mb-4">
        @if (count($older))
            <h2 class="text-lg font-semibold">Previous version ({{ $older->sqid }})</h2>
        @else
            <h2 class="text-lg font-semibold">Previous version (Original post)</h2>
        @endif
        <p class="text-base">
            <strong class="font-bold">Title:</strong> {{ $revision->title }}<br>
            <strong class="font-bold">Text:</strong> {{ $revision->text }}<br>
            <strong class="font-bold">Created by:</strong> {{ $revision->post->user->name }}<br>
            <strong class="font-bold">Created at:</strong> {{ $revision->created_at }}
        </p>
    </div>
    @if (count($older))
        <div class="rounded-lg border p-6 dark:border-indigo-600 bg-zinc-900 mb-4">
            <h2 class="text-lg font-semibold">Prior revisions</h2>
            <ul class="list-disc list-inside space-y-1 text-base mb-4 mt-2">
                @foreach ($older as $history)
                    <li class="list-item">
                        <a class="underline" href="/posts/{{ $revision->post->slug }}/history/{{ $history->sqid }}">{{ $history->title }}</a>
                        <pre>{{ print $older_diff }}</pre>
                    </li>
                @endforeach
            </ul>
        </div>
    @endif

    @if (count($newer))
        <div class="rounded-lg border p-6 dark:border-indigo-600 bg-zinc-900 mb-4">
            <h2 class="text-lg font-semibold">Newer Revisions</h2>
            <ul class="list-disc list-inside space-y-1 text-base mb-4 mt-2">
                @foreach ($newer as $history)
                    <li class="list-item">
                        <a class="underline" href="/posts/{{ $revision->post->slug }}/history/{{ $history->sqid }}">{{ $history->title }}</a>
                        <pre class="text-wrap bg-zinc-800 rounded-md p-2">{{ $newer_diff }}</pre>
                    </li>
                @endforeach
            </ul>
        </div>
   @endif
@endsection
