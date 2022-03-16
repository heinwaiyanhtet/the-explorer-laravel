<?php

namespace App\Http\Controllers;

use App\Jobs\CreateFile;
use App\Models\Post;
use Illuminate\Http\Request;
use phpDocumentor\Reflection\Types\Expression;

class PageController extends Controller
{
    public function index(){
        $posts = Post::latest("id")->paginate(10);
        return view('index',["posts" => $posts]);
    }

    public function detail($slug){
        $post = Post::where("slug",$slug)->with(['comments','galleries'])->firstOrFail();
        return view("post.detail",compact('post'));
    }

}
