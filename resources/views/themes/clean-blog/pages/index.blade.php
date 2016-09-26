@extends('themes.clean-blog.layouts.default')

@section("header")
    <!-- Page Header -->
    <!-- Set your background image for this header on the line below. -->
    <header class="intro-header" style="background-image: url('https://source.unsplash.com/category/nature/1600x900')">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 col-lg-offset-2 col-md-10 col-md-offset-1">
                    <div class="site-heading">
                        <h1>xiaojinhua</h1>
                        <hr class="small">
                        <span class="subheading">Hi,I'm xiaojinhua</span>
                    </div>
                </div>
            </div>
        </div>
    </header>
@endsection

@section('content')
    <div class="col-lg-8 col-lg-offset-2 col-md-10 col-md-offset-1">
        @include('themes.clean-blog.partials.posts-list', $posts)
    </div>
@endsection