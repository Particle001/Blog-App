<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Models\Comment;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CommentController extends Controller
{
    public function store(Request $request, $blogId)
    {
        // Validate the comment data
        $request->validate([
            'content' => 'required|string|max:1000',
        ]);

        // Create the new comment
        Comment::create([
            'content' => $request->content,
            'user_id' => auth()->id(),
            'blog_id' => $blogId,
        ]);

        // Redirect back to the blog post where the comment was posted
        return redirect()->route('blogs.show', ['id' => $blogId])->with('success', 'Comment posted successfully.');
    }



    public function destroy(Comment $comment)
    {
        // Get the blog associated with the comment
        $blog = $comment->blog;

        // Check if the authenticated user is the owner of the blog or the owner of the comment
        if ($blog->user_id !== auth()->id() && $comment->user_id !== auth()->id()) {
            return redirect()->back()->with('error', 'You are not authorized to delete this comment.');
        }

        $comment->delete();

        return redirect()->back()->with('success', 'Comment deleted successfully!');
    }


}
