<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Models\Post;
use App\Models\Like;
use Auth;
class PostController extends Controller
{
	public function Post()
	{
		$posts = Post::orderBy('created_at', 'desc')->get();

		foreach ($posts as &$value) {

			$value->countlike = Like::where(['like' => '1','post_id' => $value->id])->get()->count();
			$value->countdislike = Like::where(['like' => '0','post_id' => $value->id])->get()->count();

		}
		return view('post.index', ['post' => $posts]);
	}


	public function postLikePost(Request $request)
	{
		$post_id = $request['postId'];
		$is_like = $request['isLike'];
		$update = false;
		$post = Post::find($post_id);
		if (!$post) {
			return null;
		}
		$user = Auth::user();
		$like = $user->likes()->where('post_id', $post_id)->first();

		if ($like) {
			$update = true;
		} else {
			$like = new Like();
		}

		$like->like = $is_like;
		$like->user_id = $user->id;
		$like->post_id = $post->id;
		$update ? $like->update() : $like->save() ; 

		$countlike = Like::where(['like' => 1,'post_id' => $post_id])->get()->count();
		$countdislike = Like::where(['like' => 0,'post_id' => $post_id])->get()->count();

		echo json_encode(['countlike'=> $countlike,'countdislike'=> $countdislike]);
	}
}
