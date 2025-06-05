<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'destination_id' => 'required|exists:destinations,id',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $images = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('posts', 'public');
                $images[] = $path;
            }
        }

        $post = Post::create([
            'user_id' => auth()->id(),
            'destination_id' => $request->destination_id,
            'title' => $request->title,
            'content' => $request->content,
            'images' => $images,
            'likes_count' => 0,
            'is_approved' => null // Posts need approval by admin
        ]);
        
        return redirect()->route('profile.posts')->with('success', 'Bài viết của bạn đã được gửi và đang chờ duyệt.');
       
    }

    public function like(Post $post)
    {
        $user = auth()->user();

        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $like = $post->likes()->where('user_id', $user->id)->first();

        if ($like) {
            $like->delete();
            $post->decrement('likes_count');
            $is_liked = false;
        } else {
            $post->likes()->create(['user_id' => $user->id]);
            $post->increment('likes_count');
            $is_liked = true;
        }

        return response()->json([
            'likes_count' => $post->likes_count,
            'is_liked' => $is_liked
        ]);
    }



    public function destroy(Post $post)
    {
        if (auth()->id() !== $post->user_id) {
            return response()->json([
                'success' => false,
                'message' => 'Bạn không có quyền xóa bài viết này'
            ], 403);
        }

        try {
            // Delete post images from storage
            if ($post->images) {
                $images = is_array($post->images) ? $post->images : json_decode($post->images);
                if (is_array($images)) {
                    foreach ($images as $image) {
                        if (Storage::disk('public')->exists($image)) {
                            Storage::disk('public')->delete($image);
                        }
                    }
                }
            }

            // Delete likes associated with the post
            $post->likes()->delete();

            // Delete the post
            $post->delete();

            return response()->json([
                'success' => true,
                'message' => 'Bài viết đã được xóa thành công'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi xóa bài viết: ' . $e->getMessage()
            ], 500);
        }
    }
}
