<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'destination_id' => 'required|exist:destinations,id',
            'comment' => 'required|string|max:1000',
            'parent_id' => 'nullable|exists:comments,id',
        ]);

        $comment = Comment::create([
            'user_id' => auth()->id(),
            'destination_id' => $request->destination_id,
            'parent_id' => $request->parent_id,
            'comment' => $request->comment,
        ]);

        if ($request->parent_id) {
            return redirect()->route('destinations.show', [
                'slug' => $comment->destination->slug,
                '#comment-'.$comment->parent_id,
            ])->with('success', 'Balasan berhasil dikirim');
        }

        return redirect()->route('destinations.show', $comment->destination->slug)
            ->with('success', 'Komentar berhasil ditambahkan');
    }

    public function like(Comment $comment)
    {
        $user = auth()->user();

        $existingLike = $comment->likes()->where('user_id', $user->id)->first();

        if ($existingLike) {
            $existingLike->delete();
            $comment->decrement('likes_count');
            $liked = false;
        } else {
            $comment->likes()->create([
                'user_id' => $user->id,
            ]);
            $comment->increment('likes_count');
            $liked = true;
        }

        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'likes' => $liked,
                'likes_count' => $comment->fresh()->likes_count,
            ]);
        }

        return back();
    }

    public function reply(Request $request, Comment $comment)
    {
        $request->validate([
            'comment' => 'required|string|max:1000',
        ]);
        $reply = Comment::create([
            'user_id' => auth()->id(),
            'destination_id' => $comment->destination_id,
            'parent_id' => $comment->id,
            'comment' => $request->comment,
        ]);

        return redirect()->route('destinations.show', [
            'slug' => $comment->destination->slug,
            '#comment-'.$comment->id,
        ])->with('success', 'Balasan berhasil dikirim');
    }
}
