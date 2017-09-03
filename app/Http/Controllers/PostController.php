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
			$value ->btnlike = (Auth::user()->likes()->where('post_id', $value->id)->first() && Auth::user()->likes()->where('post_id', $value->id)->first()->like == 1 ? 'You like this post'  : 'Like');  
			$value->btnunlike = (Auth::user()->likes()->where('post_id', $value->id)->first() && Auth::user()->likes()->where('post_id', $value->id)->first()->like == 0 ? 'You don\'t like this post' : 'Dislike');
		}
		return view('post.index', ['post' => $posts]);
	}


	public function postLikePost(Request $request)
	{
		$post_id = $request['postId'];
		$is_like = $request['isLike'];
		$update = false;
		$delete = true;
		$post = Post::find($post_id);
		if (!$post) {
			return null;
		}
		$user = Auth::user();
		$like = $user->likes()->where('post_id', $post_id)->first();

		if ($like) {
			$update = true;
			if ($like->like == $is_like) {
				$like->delete();
				$countlike = Like::where(['like' => 1,'post_id' => $post_id])->get()->count();
				$countdislike = Like::where(['like' => 0,'post_id' => $post_id])->get()->count();
				$btnlike = (Auth::user()->likes()->where('post_id', $post_id)->first() && Auth::user()->likes()->where('post_id', $post_id)->first()->like == 1 ? 'You like this post'  : 'Like');  
				$btnunlike = (Auth::user()->likes()->where('post_id', $post_id)->first() && Auth::user()->likes()->where('post_id', $post_id)->first()->like == 0 ? 'You don\'t like this post' : 'Dislike');
				echo json_encode(['countlike'=> $countlike,'countdislike'=> $countdislike,'btnlike'=>$btnlike , 'btnunlike'=>$btnunlike]);
				return ;
			}
		} else {

			$like = new Like();
			
		}
		$like->like = $is_like;
		$like->user_id = $user->id;
		$like->post_id = $post->id;
		$update ? $like->update() : $like->save() ; 

		$countlike = Like::where(['like' => 1,'post_id' => $post_id])->get()->count();
		$countdislike = Like::where(['like' => 0,'post_id' => $post_id])->get()->count();
		$btnlike = (Auth::user()->likes()->where('post_id', $post_id)->first() && Auth::user()->likes()->where('post_id', $post_id)->first()->like == 1 ? 'You like this post'  : 'Like');  
		$btnunlike = (Auth::user()->likes()->where('post_id', $post_id)->first() && Auth::user()->likes()->where('post_id', $post_id)->first()->like == 0 ? 'You don\'t like this post' : 'Dislike');

		echo json_encode(['countlike'=> $countlike,'countdislike'=> $countdislike,'btnlike'=>$btnlike , 'btnunlike'=>$btnunlike]);
	}
}
