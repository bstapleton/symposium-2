<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Models\Post;
use App\Models\PostRevision;
use App\Repositories\PostRevisionRepository;
use App\Repositories\PostRepository;
use Illuminate\Contracts\View\View;
use SebastianBergmann\Diff\Differ;
use SebastianBergmann\Diff\Output\UnifiedDiffOutputBuilder;

class PostController extends Controller
{
    public function __construct(protected PostRepository $repository, protected PostRevisionRepository $historyRepository)
    {
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePostRequest $request)
    {
        $post = $this->repository->store($request->validated());

        return view('post.show', ['post' => $post]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Post $post): View
    {
        $hasRevisions = $post->revisions->count() > 0;
        $revisions = $post->revisions->reverse();

        // Shift the latest revision so it can be the main content of the view
        $latest = $revisions->shift();

        if ($latest && $revisions->count()) {
            // There's still revisions left in the collection, use the previous latest version
            $previous = $revisions->first();
        } elseif ($latest) {
            // Revision was shifted, but still want to show the original post content as the previous latest version
            $previous = $post;
        }

        // Set the local variables depending on if a 'latest' ever got set above
        if ($latest) {
            $title = $latest->title;
            $text = $latest->text;
        } else {
            $title = $post->title;
            $text = $post->text;
        }


        return view('post.show', [
            'title' => $title,
            'text' => $text,
            'sqid' => $sqid ?? null,
            'slug' => $post->slug,
            'created_at' => $post->created_at,
            'hasRevisions' => $hasRevisions,
            'previous' => $previous ?? null,
            'revisions' => $revisions,
            'replies' => $post->replies->reverse()
        ]);
    }

    public function revision(Post $post, string $sqid): View
    {
        $history = PostRevision::find(PostRevision::keyFromSqid($sqid));
        $older = $history->post->revisions->where('id', '<', $history->id)->reverse();
        $newer = $history->post->revisions->where('id', '>', $history->id)->reverse();

        $differ = new Differ(new UnifiedDiffOutputBuilder);

        if ($older->count()) {
            $oldDiff = $differ->diff($older->first()->text, $history->text);
        }

        if ($newer->count()) {
            $newDiff = $differ->diff($history->text, $newer->first()->text);
        }

        return view('post.revision', [
            'revision' => $history,
            'older' => $older,
            'newer' => $newer,
            'olderDiff' => $oldDiff ?? null,
            'newerDiff' => $newDiff ?? null,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Post $post)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePostRequest $request, Post $post)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        //
    }
}
