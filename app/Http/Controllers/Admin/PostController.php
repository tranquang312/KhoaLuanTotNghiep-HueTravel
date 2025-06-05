<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;
use App\Traits\FlashMessage;

class PostController extends Controller
{
    use FlashMessage;

    public function index(Request $request)
    {
        $query = Post::with(['user', 'destination']);

        // Filter by approval status
        if ($request->has('status')) {
            switch ($request->status) {
                case 'approved':
                    $query->where('is_approved', true);
                    break;
                case 'pending':
                    $query->whereNull('is_approved');
                    break;
                case 'rejected':
                    $query->where('is_approved', false);
                    break;
            }
        }

        $posts = $query->latest()->paginate(5);

        return view('admin.posts.index', compact('posts'));
    }

    public function show(Post $post)
    {
        $post->load(['user', 'destination', 'likes']);
        return view('admin.posts.show', compact('post'));
    }

    public function approve(Post $post)
    {
        try {
            $post->update(['is_approved' => true]);
            $this->flashSuccess('Bài viết đã được duyệt thành công.');
        } catch (\Exception $e) {
            $this->flashError('Có lỗi xảy ra khi duyệt bài viết.');
        }
        
        return redirect()->route('admin.posts.index');
    }

    public function reject(Post $post)
    {
        try {
            $post->update(['is_approved' => false]);
            $this->flashSuccess('Bài viết đã bị từ chối.');
        } catch (\Exception $e) {
            $this->flashError('Có lỗi xảy ra khi từ chối bài viết.');
        }
        
        return redirect()->route('admin.posts.index');
    }

    public function updateStatus(Request $request, Post $post)
    {
        $request->validate([
            'status' => 'required|in:approved,rejected'
        ]);

        try {
            $post->update([
                'is_approved' => $request->status === 'approved'
            ]);

            $this->flashSuccess($request->status === 'approved'
                ? 'Bài viết đã được duyệt thành công.'
                : 'Bài viết đã bị từ chối.');
        } catch (\Exception $e) {
            $this->flashError('Có lỗi xảy ra khi cập nhật trạng thái bài viết.');
        }

        return redirect()->route('admin.posts.show', $post);
    }
}
