<?php

namespace App\Http\Controllers;

use App\Enums\FeatureFlag;
use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Models\Post;
use App\Models\PostRevision;
use App\Repositories\PostRevisionRepository;
use App\Repositories\PostRepository;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
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
        $user = Auth::user();
        $hasRevisions = $post->revisions->count() > 0 && $user && $user->feature_flag === FeatureFlag::REVISIONS_SYSTEM;
        $displayRevisions = $user && $user->feature_flag === FeatureFlag::REVISIONS_SYSTEM;
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
            'has_revisions' => $hasRevisions,
            'show_revisions' => $displayRevisions,
            'previous' => $previous ?? null,
            'revisions' => $revisions,
            'replies' => $latest ? $latest->replies : $post->replies->reverse(),
            'original_replies' => $latest ? $post->replies : null,
        ]);
    }

    public function revision(Post $post, string $sqid): View
    {
        $currentRevision = PostRevision::find(PostRevision::keyFromSqid($sqid));
        $olderList = $currentRevision->post->revisions->where('id', '<', $currentRevision->id)->reverse();
        $newerList = $currentRevision->post->revisions->where('id', '>', $currentRevision->id)->reverse();

        $differ = new Differ(new UnifiedDiffOutputBuilder);

        if ($olderList->count()) {
            $oldDiff = $differ->diff($olderList->first()->text, $currentRevision->text);
        } else {
            $oldDiff = $differ->diff($post->text, $currentRevision->text);
        }

        if ($newerList->count()) {
            $newDiff = $differ->diff($currentRevision->text, $newerList->first()->text);
        }

        return view('post.revision', [
            'revision' => $currentRevision,
            'post_id' => $post->id,
            'older' => $olderList,
            'newer' => $newerList,
            'older_diff' => $oldDiff,
            'newer_diff' => $newDiff ?? null,
            'created_at' => $post->created_at,
            'replies' => $currentRevision->replies,
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
