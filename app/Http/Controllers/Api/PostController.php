<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Response;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\ApiController;
use App\Service\PostService;
use App\Model\Post;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Transformers\PostTransformer;
use League\Fractal\Manager;
use App\Model\Rating;
use App\Model\Book;
use App\Http\Requests\Api\CreatePostRequest;
use Illuminate\Support\Facades\Auth;
use App\Exceptions\Handler;
use Exception;
use DB;
use App\Http\Requests\Api\UpdatePostRequest;

class PostController extends ApiController
{
    /**
     * PostController construct
     *
     * @param Manager         $fractal     fractal
     * @param PostTransformer $transformer transformer
     *
     * @return void
     */
    public function __construct(Manager $fractal, PostTransformer $transformer)
    {
        $this->fractal = $fractal;
        $this->transformer = $transformer;
    }

    /**
     * Get a list of the posts of user.
     *
     * @param Request $request send request
     * @param int     $userId  id of user
     *
     * @return \Illuminate\Http\Response
     */
    public function getPostsOfUser(Request $request, $userId)
    {
        $posts = PostService::getPosts($request)
                    ->where('posts.user_id', $userId)
                    ->paginate(config('define.posts.limit_rows_posts_of_user'));

        return $this->responsePaginate($posts);
    }
    
    /**
     * Get list of the resource.
     *
     * @param int $id id of book
     *
     * @return \Illuminate\Http\Response
     */
    public function reviews(int $id)
    {
        $posts = PostService::getPosts()
            ->where('posts.status', Post::TYPE_REVIEW_BOOK)
            ->where('books.id', $id)
            ->paginate(config('define.posts.limit_rows'));

        return $this->responsePaginate($posts);
    }

    /**
     * Store new resource
     *
     * @param CreatePostRequest $request request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(CreatePostRequest $request)
    {
        $request['user_id'] = Auth::id();

        DB::beginTransaction();
        try {
            // Create post
            $post = Post::create($request->all());

            // Create rating when post's status is review
            if ($request->status == Post::TYPE_REVIEW_BOOK) {
                Rating::create([
                    'post_id' => $post->id,
                    'book_id' => $request->book_id,
                    'rating' => $request->rating,
                ]);

                //Update rating in table books
                $book = Book::find($request->book_id);
                $rating = ($book->rating * $book->total_rating++ + $request->rating) / $book->total_rating;
                $book = $book->update([
                    'rating' => $rating,
                    'total_rating' => $book->total_rating
                ]);
            }
            DB::commit();

            $post = $this->getItem($post, $this->transformer, 'user,rating');
            return $this->responseSuccess($post, Response::HTTP_CREATED);
        } catch (Exception $e) {
            DB::rollBack();
            throw new ModelNotFoundException();
        }
    }

    /**
     * Update the post
     *
     * @param UpdatePostRequest $request request
     * @param Post              $post    object post
     *
     * @return \Illuminate\Http\Response
     */
    public function update(UpdatePostRequest $request, Post $post)
    {
        if ($post->user_id == Auth::id()) {
            DB::beginTransaction();
            try {
                $typePost = $post->status;
                $params = $request->only(['content', 'rating']);
                $post->content = $params['content'];
                $post->save();
                if ($typePost == Post::TYPE_REVIEW_BOOK) {
                    $rating = Rating::where('post_id', $post->id)->firstOrFail();
                    if ($request->has('rating')) {
                        $book = Book::findOrFail($rating->book_id);
                        $book->rating = ($book->rating * $book->total_rating - $rating->rating + $request->rating) / $book->total_rating;
                        $rating->rating = $request->rating;
                        $rating->save();
                        $book->save();
                    }
                }
                DB::commit();

                $post = $this->getItem($post, $this->transformer, 'user,rating,book');
                return $this->responseSuccess($post, Response::HTTP_OK);
            } catch (Exception $e) {
                DB::rollBack();
                throw new ModelNotFoundException();
            }
        }
        throw new ModelNotFoundException();
    }
}
