<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
// 🔽 追加
use App\Http\Requests\StoreTweetRequest;
// 🔽 追加
use App\Http\Requests\UpdateTweetRequest;
use Illuminate\Http\Request;
// 🔽 追加
use App\Models\Tweet;
// 🔽 追加
use App\Services\TweetService;

class TweetController extends Controller
{
    // 🔽 追加
    protected $tweetService;

    // 🔽 追加
    public function __construct(TweetService $tweetService)
    {
        $this->tweetService = $tweetService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // 🔽 ポリシー追加
        $this->authorize('viewAny', Tweet::class);

        // $tweets = Tweet::with('user')->latest()->get();
        $tweets = $this->tweetService->allTweets();
        return response()->json($tweets);
    }

    /**
     * Store a newly created resource in storage.
     */

    // 🔽 編集(バリデーションクラス実装のため)
    // public function store(Request $request)
    public function store(StoreTweetRequest $request)
    {
        // 🔽 ポリシー追加
        $this->authorize('create', Tweet::class);

        // 🔽 削除(バリデーションクラス実装のため)
        // $request->validate([
        //     'tweet' => 'required|max:255',
        // ]);

        // 🔽 編集(サービスクラス実装のため)
        // $tweet = $request->user()->tweets()->create($request->only('tweet'));
        $tweet = $this->tweetService->createTweet($request->only('tweet'), $request->user());
        return response()->json($tweet, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Tweet $tweet)
    {
        // 🔽 ポリシー追加
        $this->authorize('view', $tweet);

        return response()->json($tweet);
    }

    /**
     * Update the specified resource in storage.
     */

    // 🔽 編集（バリデーションクラス実装）
    // public function update(Request $request, Tweet $tweet)
    public function update(UpdateTweetRequest $request, Tweet $tweet)
    {
        // 🔽 ポリシー追加
        $this->authorize('update', $tweet);

        // 🔽 削除（バリデーションクラス実装）
        // $request->validate([
        //     'tweet' => 'required|string|max:255',
        // ]);

        // 🔽 編集（サービスクラス実装）
        // $tweet->update($request->all());
        $updatedTweet = $this->tweetService->updateTweet($tweet, $request->all());

        return response()->json($tweet);
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Tweet $tweet)
    {
        // 🔽 ポリシー追加
        $this->authorize('delete', $tweet);

        // $tweet->delete();
        $this->tweetService->deleteTweet($tweet);
        return response()->json(['message' => 'Tweet deleted successfully']);
    }
}

