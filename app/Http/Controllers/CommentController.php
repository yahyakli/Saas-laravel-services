<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\CommentReply;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'content' => 'required|string',
            'user_id' => 'required|string',
            'project_id' => 'required|string',
            'task_id' => 'required|string',
        ]);

        $comment = Comment::create($validated);
        return response()->json($comment, 201);
    }

    // Store a reply to a comment
    public function storeReply(Request $request)
    {
        $validated = $request->validate([
            'content' => 'required|string',
            'user_id' => 'required|string',
            'comment_id' => 'required|exists:comments,id',
        ]);

        $reply = CommentReply::create($validated);
        return response()->json($reply, 201);
    }

    // Get all comments for a task
    public function getComments($task_id)
    {
        $comments = Comment::with('replies')->where('task_id', $task_id)->get();
        return response()->json($comments);
    }

    // Delete a comment
    public function destroy($id)
    {
        $comment = Comment::findOrFail($id);
        $comment->delete();

        return response()->json(['message' => 'Comment deleted successfully']);
    }
}
