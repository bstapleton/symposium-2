<?php

namespace App\Repositories;

use App\Models\Post;
use App\Models\PostRevision;

class PostRepository implements IEloquentRepository
{
    public function all()
    {
        $posts = Post::with('revisions')->paginate(20);

        foreach ($posts as $post) {
            $postRevision = $this->getLatestRevision($post);
            if ($postRevision) {
                $post->created_at = $postRevision->created_at;
                $post->title = $postRevision->title;
            }
        }

        return $posts;
    }

    public function show(string $slug)
    {
        $post = Post::where('slug', $slug)->firstOrFail();
        // TODO: calls to the history repository should be handled in the service layer following the creation of the post
        $postHistoryRepository = new PostRevisionRepository();
        $postRevision = $this->getLatestRevision($post);

        if ($postRevision) {
            $post->created_at = $postHistoryRepository->oldest($postRevision->id)
                ? $postHistoryRepository->oldest($postRevision->id)->created_at
                : $postRevision->created_at;
            $post->updated_at = $postRevision->created_at;
            $post->title = $postRevision->title;
            $post->text = $postRevision->text;
            $post->previous = $postHistoryRepository->previous($postRevision->id);
            $post->next = $postHistoryRepository->next($postRevision->id);
        } else {
            $post->previous = null;
            $post->next = null;
            $post->updated_at = $post->created_at;
        }

        return $post;
    }

    public function store(array $data)
    {
        $post = Post::create($data);

        return $post;
    }

    public function destroy(int $id)
    {
        return Post::destroy($id);
    }

    /**
     * If the post has a history, we want the latest one to form the title and content of what gets displayed
     *
     * @param Post $post
     * @return PostRevision
     */
    private function getLatestRevision(Post $post)
    {
        $postHistoryRepository = new PostRevisionRepository();

        return $postHistoryRepository
            ->all()
            ->where('post_id', $post->id)
            ->reverse()
            ->first();
    }
}
