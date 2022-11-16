<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Models\User;
use App\Models\BlogComments;
use Illuminate\Http\Request;
use DB;

class BlogController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($cat_id=0)
    {
        if ($cat_id==0) {       
            $posts = Blog::orderBy('created_at', 'DESC')->get();
        }    
        else{
            $posts = Blog::where('category_id', '=', $cat_id)->orderBy('created_at', 'DESC')->get();
        }

        $posts->load('user');
        $posts->load('comments');
        foreach ($posts as $i => $post) {
            foreach ($post->comments as $j => $comment ) {
                $posts[$i]->comments[$j]->load('comment_user');
            }
        }
     
        return view('blog.index', compact('posts'));
    }

    public function showcat($cat_id)
    {
        if ($cat_id==0) {       
            $posts = Blog::orderBy('created_at', 'DESC')->get();
        }    
        else{
            $posts = Blog::where('category_id', '=', $cat_id)->orderBy('created_at', 'DESC')->get();
        }

        $posts->load('user');
        $posts->load('comments');
        foreach ($posts as $i => $post) {
            foreach ($post->comments as $j => $comment ) {
                $posts[$i]->comments[$j]->load('comment_user');
            }
        }
     
        return view('blog.index', compact('posts'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('blog.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        #dump($request->all());
        $post = new Blog();
        $post->fill($request->all());
        $post->slug = $post->title;
        $post->save();
        return redirect()->route('blog.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Blog  $blog
     * @return \Illuminate\Http\Response
     */
    public function show(Blog $blog)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Blog  $blog
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $post = Blog::find($id);
        return view('blog.edit', compact('post'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Blog  $blog
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $req = $request->all();
        $post  = Blog::find($request->id);
        $post->fill($req);
        $post->update();
        return redirect()->route('blog.index');
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Blog  $blog
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $id  = $request->all()['id'];
        $post  = Blog::find($id);
        $post->delete();
        return redirect()->route('blog.index');
    }

    public function commentForm($id){
        if (Auth()->check()) {
            $user_id = Auth()->user()->id;
        }
        else{
            $user_id = 0;
        }
        return view('blog.createComment', compact('id', 'user_id'));
    } 

    public function makeComment(Request $request){
        $req  = $request->all();
        if ($req['user_id']!="0") {
            $user = User::find($req['user_id']);
            $username = $user->name;
        }
        else{
            $username = 'Gast';
        }

        $comment = new BlogComments;
        $comment->fill($req);
        $res = $comment->save();
        if ($res) {
            $html = '<div class="blog-comment">
                        <div class="blog-comment-header">
                            '. \Carbon\Carbon::parse($comment->created_at)->format('d.m.Y').'
                            '.$username.'</div>
                        <div class="blog-comment-body">'.$comment->comment.'</div>
                        </div>';

            $response = $html;
        }
        else{
            $response =  json_encode(['error' => 0]);
        }
        return $response;
    }

    public function deleteComment(Request $request){
        $req = $request->all();
        $id  =$req['id'];
        $comment  = BlogComments::find($id);
        $res = $comment->delete();
    }    
}
