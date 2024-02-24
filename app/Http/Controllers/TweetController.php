<?php

namespace App\Http\Controllers;

// 🔽 追加
use App\Http\Requests\StoreTweetRequest;
// 🔽 追加
use App\Http\Requests\UpdateTweetRequest;
use App\Models\Tweet;
use Illuminate\Http\Request;
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

        // 追加
        // $tweets = Tweet::with('user')->latest()->get();
        $tweets = $this->tweetService->allTweets();
        return view('tweets.index', compact('tweets'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // 🔽 ポリシー追加
        $this->authorize('create', Tweet::class);

        // 追加
        return view('tweets.create');
    }

    /**
     * Store a newly created resource in storage.
     */

    // 🔽 編集（バリデーションクラス実装）
    // public function store(Request $request)
    public function store(StoreTweetRequest $request)
    {
        // 🔽 ポリシー追加
        $this->authorize('create', Tweet::class);

        // 🔽 削除（バリデーションクラス実装）
        // 追加
        // $request->validate([
        //     'tweet' => 'required|max:255',
        // ]);

        // 🔽 編集（サービスクラス実装）
        // $request->user()->tweets()->create($request->only('tweet'));
        $tweet = $this->tweetService->createTweet($request->only('tweet'), $request->user());
        return redirect()->route('tweets.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Tweet $tweet)
    {
        // 🔽 ポリシー追加
        $this->authorize('view', $tweet);

        // 追加
        return view('tweets.show', compact('tweet'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Tweet $tweet)
    {
        // 🔽 ポリシー追加
        $this->authorize('update', $tweet);

        // 追加
        return view('tweets.edit', compact('tweet'));
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
        // 追加
        // $request->validate([
        //     'tweet' => 'required|max:255',
        // ]);

        // $tweet->update($request->only('tweet'));
        $updatedTweet = $this->tweetService->updateTweet($tweet, $request->all());
        return redirect()->route('tweets.show', $tweet);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Tweet $tweet)
    {
        // 🔽 ポリシー追加
        $this->authorize('delete', $tweet);

        // 追加
        // $tweet->delete();
        $this->tweetService->deleteTweet($tweet);
        return redirect()->route('tweets.index');
    }
}
