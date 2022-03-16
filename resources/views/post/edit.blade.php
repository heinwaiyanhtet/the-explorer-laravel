@extends('master')
@section('title') Edit Post : {{env("APP_NAME")}} @endsection
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10 col-xl-8">
                <div class="d-flex justify-content-between align-items-center">
                    <h4>Create New Post</h4>
                    <p class="mb-0">
                        <i class="fas fa-calendar"></i>
                        {{date("d M Y")}}
                    </p>
                </div>

                <form action="{{route('post.update',$post->id)}}" enctype="multipart/form-data" id="post-create" method="post">
                    @csrf
                    @method('put')
                    <div class="form-floating mb-4">
                        <input type="text" name="title" class="form-control  @error('title') is-invalid @enderror" value="{{old('title',$post->title)}}" id="postTitle" placeholder="no neeed">
                        <label for="postTitle">Post Title</label>
                        @error("title")
                        <div class="invalid-feedback">
                            {{$message}}
                        </div>
                        @enderror
                    </div>
                    <div class="mb-4">
                        <img src="{{asset('storage/cover/'.$post->cover)}}" id="coverPreview" class="cover-img w-100 rounded  @error('cover') is-invalid border border-danger @enderror" alt="">
                        <input type="file" name="cover" class="d-none" id="cover" accept="image/jpeg,image/jpg">
                        @error("cover")
                        <div class="invalid-feedback">
                            {{$message}}
                        </div>
                        @enderror
                    </div>
                    <div class="form-floating mb-4">
                        <textarea name="description" class="form-control @error('description') is-invalid  @enderror" placeholder="Leave a comment here" id="floatingTextarea2" style="height: 450px">
                            {{old('description',$post->title)}}
                        </textarea>
                        <label for="floatingTextarea2">Share Your Experiences</label>
                        @error("description")
                        <div class="invalid-feedback">
                            {{$message}}
                        </div>
                        @enderror
                    </div>
                </form>

                <div class="border rounded p-4 mb-4" id="gallery">

                    <div class="d-flex align-items-stretch">
                        <div class="border px-5 rounded-2 d-flex justify-content-center align-items-center" id="upload-ui" style="height:150px">
                            <i class="fas fa-upload"></i>
                        </div>
                        <div class="d-flex overflow-scroll" style="height: 150px">
                            @forelse($post->galleries as $gallery)
                                <div class="d-flex align-items-end ms-2">
                                    <img src="{{asset('storage/gallery/'.$gallery->photo)}}" class="h-100 rounded-1" alt="">
                                    <form action="{{route('gallery.destroy',$gallery->id)}}" method="post">
                                        @csrf
                                        @method('delete')
                                        <button class="btn btn-danger btn-sm gallery-img-delete">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </form>
                                </div>
                            @empty
                            @endforelse
                        </div>
                    </div>



                    <form action="{{route('gallery.store')}}" method="post" id="gallery-upload" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="post_id" value="{{$post->id}}">
                        <div class="">
                            <input type="file" id="gallery-input" name="galleries[]" class="d-none @error('galleries') is-invalid @enderror @error('galleries.*') is-invalid @enderror" multiple>
                            @error('galleries')
                            <div class="invalid-feedback ps-2">
                                {{$message}}
                            </div>
                            @enderror
                            @error('galleries.*')
                            <div class="invalid-feedback ps-2">
                                {{$message}}
                            </div>
                            @enderror
                        </div>
                    </form>
                </div>

                <div class="text-center mb-4">
                    <button class="btn  btn-lg btn-primary" form="post-create">
                        <i class="fas fa-message"></i>
                        Update Post
                    </button>
                </div>
            </div>
        </div>
    </div>

@endsection
@push("scripts")
    <script>
        let coverPreview = document.querySelector("#coverPreview");
        let cover  = document.querySelector("#cover");
        coverPreview.addEventListener("click", _ => cover.click())
        cover.addEventListener("change", _ => {
            let reader = new FileReader();
            reader.onload = function () {
                coverPreview.src = reader.result;
            }
            reader.readAsDataURL(cover.files[0]);
        });


        let uploadUi = document.getElementById("upload-ui");
        let galleryInput = document.getElementById("gallery-input");
        let galleryUpload = document.getElementById("gallery-upload");

        uploadUi.addEventListener("click",_ => galleryInput.click());
        galleryInput.addEventListener("change",_=>galleryUpload.submit());
    </script>
@endpush
