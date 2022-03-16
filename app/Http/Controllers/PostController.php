<?php

namespace App\Http\Controllers;

use App\Classes\FileControl;
use App\Jobs\CreateFile;
use App\Mail\PostMail;
use App\Models\Post;
use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use Illuminate\Auth\Access\Gate;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;

class PostController extends Controller
{
    public function __construct()
    {
        $this->middleware("auth")->except(["index","show"]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return redirect()->route("index");
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('post.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StorePostRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StorePostRequest $request)
    {

//        $newName = "cover_".uniqid() ."." .$request->file('cover')->extension();
//        $request->file('cover')->storeAs("public/cover",$newName);


       // CreateFile::dispatch($newName)->delay(now()->addSecond(10));


        $post = new Post();
        $post->title = $request->title;
        $post->slug = Str::slug($request->title);
        $post->description = $request->description;
        $post->excerpt = Str::words($request->description,50);
        $post->cover = FileControl::fileSave('cover','cover');
        $post->user_id = Auth::id();
        $post->save();

        //mail send share

//        $mailUsers = ['heinwaiyanhtet20042020@gmail.com','heinwaiyanhtet2020@gmail.com','sawtharsaw869@gmail.com'];
//        foreach ($mailUsers as $mailUser) {
//            Mail::to($mailUser)->later(now()->addSecond(10),new PostMail($post));
//        }

        return redirect()->route("index");
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function show(Post $post)
    {
        return redirect()->route('post.detail',$post->slug);

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function edit(Post $post)
    {
        \Illuminate\Support\Facades\Gate::authorize('update',$post);
        return view("post.edit",compact('post'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdatePostRequest  $request
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function update(UpdatePostRequest $request, Post $post)
    {


        $post->title = $request->title;
        $post->slug = Str::slug($post->title);

        $post->description = $request->description;
        $post->excerpt = Str::words($request->description,50);

        if($request->hasFile('cover'))
        {
            //delete old cover
            Storage::delete("public/cover/".$post->cover);

            //upload old cover
//            $newName = "cover_".uniqid() ."_" .$request->file('cover')->extension();
//            $request->file('cover')->storeAs("public/cover",$newName);

            //save to table
            $post->cover = FileControl::fileSave('cover','cover');
        }

        $post->update();
        return redirect()->route("post.detail", $post->slug);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function destroy(Post $post)
    {
        \Illuminate\Support\Facades\Gate::authorize('delete',$post);
        Storage::delete("public/cover/".$post->cover);
        $post->delete();
        return redirect()->route('index');
    }
}
