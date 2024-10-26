<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\CommentReply;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

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
    
        // Retrieve the JWT token, assuming it's stored in the request's Authorization header
        $token = $request->bearerToken();

        $spring_url = env('SPRING_URL');
    
        // Step 1: Check if the user exists via an external API with the JWT token in headers
        $userResponse = Http::get($spring_url . '/auth/user/' . $validated['user_id']);
    
        if ($userResponse->failed()) {
            // Step 2: If user does not exist, return a 404 error
            return response()->json(['error' => 'User not found'], 404);
        }

        $express_url = env('EXPRESS_URL');

        $postRequest = Http::withToken($token)
                        ->get($express_url . '/projects/' . $validated['project_id']);


        if ($postRequest->failed()) {
            // Step 2: If user does not exist, return a 404 error
            return response()->json(['error' => 'Post not found'], 404);
        }

        $taskRequest = Http::withToken($token)
                        ->get($express_url . '/tasks/' . $validated['task_id']);


        if ($taskRequest->failed()) {
            // Step 2: If user does not exist, return a 404 error
            return response()->json(['error' => 'Task not found'], 404);
        }
    
        // Step 3: Proceed with creating the comment if user exists
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
    public function getComments(Request $request, $task_id)
    {

        $token = $request->bearerToken();

        $express_url = env('EXPRESS_URL');
    
        $taskResponse = Http::withToken($token)
                        ->get($express_url . '/tasks/' . $task_id);
    
        if ($taskResponse->failed()) {
            return response()->json(['error' => 'User not found'], 404);
        }

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
