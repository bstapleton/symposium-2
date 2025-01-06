<li>
    <div class="rounded-lg border p-2 dark:border-indigo-600 bg-zinc-900 mb-2">
        <h2 class="text-lg font-semibold"><a href="/posts/{{ $post->slug }}">{{ $post->title }}</a></h2>
        <p class="text-sm"><em class="italic">by {{ $post->user->name }} on {{ $post->created_at }}</em></p>
    </div>
</li>
