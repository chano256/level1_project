<?php

namespace level1_project\Http\Controllers;

use Illuminate\Http\Request;
use level1_project\Post;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class PostsController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // Passing middleware for only auth users accepting only index and show pages
        $this->middleware('auth', ['except' => ['index', 'show']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Post::orderBy('title', 'asc')->paginate(5);
        // $posts =  Post::all();

        // // In posts folder with file index.php
        // return view('posts.index')->with('posts' ,$posts);
        // We can also use mysql other than Eloquent
        // return DB::select('SELECT * FROM posts');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, 
            [
                'title' => 'required',
                'body' => 'required',
                'cover_image' => 'image|nullable|max:1999'
            ]
        );

        // Handle file upload
        if ($request->hasFile('cover_image')) {
            // Get file name with extension
            $fileNameWithExt = $request->file('cover_image')->getClientOriginalName();

            // Get file name with extension
            $fileName = pathinfo($fileNameWithExt, PATHINFO_FILENAME);

            // Get file name with extension
            $extension = $request->file('cover_image')->getClientOriginalExtension();
            
            // File name to  store
            $fileNameToStore = $fileName . '_' . time() . '.' . $extension;

            // Upload image
            $path = $request->file('cover_image')->storeAs('public/cover_images', $fileNameToStore);

        } else {
            $fileNameToStore = 'noimage.jpg';
        }

        $post = new Post();
        $post->title = $request->input('title');
        $post->body = $request->input('body');
        $post->user_id = auth()->user()->id;
        $post->cover_image = $fileNameToStore;
        $post->save();

        return redirect('/posts')->with('success', 'Post Created');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return Post::find($id);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $post = Post::find($id);
        
        // Check for correct user
        if (auth()->user()->id !== $post->user_id) {
            return view('/posts')->with('error', 'Unauthorized page');
        }

        return view('posts.edit')->with('post', $post);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, 
            [
                'title' => 'required',
                'boday' => 'required',
                'cover_image' => 'image|nullable|max:1999'
            ]
        );

        // Handle file upload
        if ($request->hasFile('cover_image')) {
            // Get file name with extension
            $fileNameWithExt = $request->file('cover_image')->getClientOriginalName();

            // Get file name with extension
            $fileName = pathinfo($fileNameWithExt, PATHINFO_FILENAME);

            // Get file name with extension
            $extension = $request->file('cover_image')->getClientOriginalExtension();
            
            // File name to  store
            $fileNameToStore = $fileName . '_' . time() . '.' . $extension;

            // Upload image
            $path = $request->file('cover_image')->storeAs('public/cover_images', $fileNameToStore);

        }

        $post = Post::find($id);
        $post->title = $request->input('title');
        $post->body = $request->input('body');
        $post->save();

        return redirect('/posts')->with('success', 'Post Upadted');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $post = Post::find($id);

        // Check for correct user
        if (auth()->user()->id !== $post->user_id) {
            return view('/posts')->with('error', 'Unauthorized page');
        }

        if ($post->cover_image != 'noimage.jpg') {
            Storage::delete('public/cover_images' . $post->cover_image);
        }

        $post->delete();
        return redirect('/posts')->with('success', 'Post Deleted');
    }
}
