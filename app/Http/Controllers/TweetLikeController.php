<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

// 追加
use App\Models\Tweet;
// 🔽 追加
use App\Services\TweetLikeService;

class TweetLikeController extends Controller
{
    // 🔽 追加
    protected $tweetLikeService;

    // 🔽 追加
    public function __construct(TweetLikeService $tweetLikeService)
    {
        $this->tweetLikeService = $tweetLikeService;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Tweet $tweet)
    {
        // $tweet->liked()->attach(auth()->id());
        // 🔽 編集
        $this->tweetLikeService->likeTweet($tweet, auth()->user());
        return back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Tweet $tweet)
    {
        // $tweet->liked()->detach(auth()->id());
        // 🔽 編集
        $this->tweetLikeService->dislikeTweet($tweet, auth()->user());
        return back();
    }
}
