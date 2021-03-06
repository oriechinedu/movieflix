@extends('layouts.app1')

@section('content')
@php $page_name = 'playmovie'; @endphp
@section('header')
@include('browser-header')
@endsection


<style>
	.movie_thumb{}
	.btn_opaque{font-size:20px; border: 1px solid #939393;text-decoration: none;margin: 10px;background-color: rgba(0, 0, 0, 0.74); color: #fff;}
	.btn_opaque:hover{border: 1px solid #939393;text-decoration: none;background-color: rgba(57, 57, 57, 0.74);color:#fff;}
	.video_cover {
	position: relative;padding-bottom: 30px;
	-webkit-user-select: none;  /* Chrome all / Safari all */
      -moz-user-select: none;     /* Firefox all */
      -ms-user-select: none;      /* IE 10+ */
      -o-user-select: none;
      user-select: none;
    }
	}
	.video_cover:after {
	content : "";
	display: block;
	position: absolute;
	top: 0;
	left: 0;
	background-image: url({{ $movie->poster }}); 
	width: 100%;
	height: 100%;
	opacity : 0.2;
	z-index: -1;
	background-size:cover;
	}
	.select_black{background-color: #000;height: 45px;padding: 12px;font-weight: bold;color: #fff;}
	.profile_manage{font-size: 25px;border: 1px solid #ccc;padding: 5px 30px;text-decoration: none;}
	.profile_manage:hover{font-size: 25px;border: 1px solid #fff;padding: 5px 30px;text-decoration: none;color:#fff;}
</style>
<!-- VIDEO PLAYER -->

<div class="video_cover">
	<div class="container" style="padding-top:100px; text-align: center;">
		<div class="row">
			<div class="col-lg-12">
				
				
				<style>
				.intrinsic-container {
				  position: relative;
				  height: 0;
				  overflow: hidden;
				}

				/* 16x9 Aspect Ratio */
				.intrinsic-container-16x9 {
				  padding-bottom: 56.25%;
				}

				/* 4x3 Aspect Ratio */
				.intrinsic-container-4x3 {
				  padding-bottom: 75%;
				}

				.intrinsic-container video {
				  position: absolute;
				  top:0;
				  left: 0;
				  width: 100%;
				  height: 100%;
				}
				</style>
				@if (strtolower($movie->genre->name) === 'live tv')

				@push('scripts')
					 <script src="{{ asset('plugins/flowplayer.min.js')}}"></script>
					<script src="{{asset('plugins/flowplayer/flowplayer.hlsjs.min.js')}}"></script>
				@endpush
				@push('styles')
					<link rel="stylesheet" href="{{ asset('plugins/skin/skin.css')}}">
				@endpush
				<div class="intrinsic-container intrinsic-container-16x9 flowplayer" data-swf="flowplayer.swf" data-ratio="0.4167">
  					<video style="border:0px; width:100%; height:100%;"> 
  						<source src="{{$movie->url}}" type="application/x-mpegURL">	 
  						<img src="{{$movie->poster}}">
  						Your browser does not support the video tag, kindly update or change your browser!	
  					</video>
				</div>
	

				@else
					<div class="intrinsic-container intrinsic-container-16x9">
  					<video src="{{$movie->url}}" allowfullscreen style="border:0px; width:100%; height:100%;" controlsList="nodownload" preload="metadata" autoplay controls></video>
					</div>
				
				@endif
				<!-- loads jwplayer as video player -->
			{{-- 	<script src="https://content.jwplatform.com/libraries/O7BMTay5.js"></script>
				<div id="video_player_div">{{ $movie->title}}</div>
				<script>
					jwplayer("video_player_div").setup({
						"file": "{{ $movie->url}}",
						"image": "{{ $movie->poster}}",
						"width": "100%",
						aspectratio: "16:9",
						listbar: {
						  position: 'right',
						  size: 260
						},
					  });
				</script> --}}
				
			</div>
		</div>
	</div>
</div>
<div class="container" style="margin-top: 30px;">
	<div class="row">
		<div class="col-lg-8">
			<div class="row">
				<div class="col-lg-2">
					<img src="{{$movie->poster}}" style="height: 60px; margin:20px;" />
				</div>
				<div class="col-lg-10">
					<!-- VIDEO TITLE -->
					<h3>
						{{$movie->title}}
					</h3>
					<!-- RATING CALCULATION -->
					
				</div>
			</div>
		</div>
		
		<div class="col-lg-4">
			<!-- ADD OR DELETE FROM PLAYLIST -->
			<span id="mylist_button_holder">
			</span>
			<span id="mylist_add_button" style="display:none;">
			<a href="#" class="btn btn-danger btn-md" style="font-size: 16px; margin-top: 20px;" 
				onclick=""> 
			<i class="fa fa-plus"></i> Add to My list
			</a>
			</span>
			<span id="mylist_delete_button" style="display:none;">
			<a href="#" class="btn btn-default btn-md" style="font-size: 16px; margin-top: 20px;" 
				onclick=""> 
			<i class="fa fa-check"></i> Added to My list
			</a>
			</span>
			<!-- MOVIE GENRE -->
			<div style="margin-top: 10px;">
				<strong>Genre</strong> : 
				<a href="#">
				{{ title_case($movie->genre->name) }}
				</a>
			</div>
			<!-- MOVIE YEAR -->
			<div>
				<strong>Year</strong> : {{ $movie->year}}
			</div>
			<div>
				<strong>Rating</strong> : {{ $movie->rating}}
			</div>
		</div>
	</div>
	<div class="row" style="margin-top:20px;">
		<div class="col-lg-12">
			<div class="bs-component">
				<ul class="nav nav-tabs">
					<li class="active" style="width:33%;">
						<a href="#about" data-toggle="tab">
						About
						</a>
					</li>
					<li style="width:33%;">
						<a href="#cast" data-toggle="tab">
						Cast
						</a>
					</li>
					<li style="width:34%;">
						<a href="#more" data-toggle="tab">
						More
						</a>
					</li>
				</ul>
				<div id="myTabContent" class="tab-content">
					<!-- TAB FOR TITLE -->
					<div class="tab-pane active in" id="about">
						<p>
							{{ $movie->description}}
						</p>
					</div>
					<!-- TAB FOR ACTORS -->
					<div class="tab-pane " id="cast">
						<p>
							@php 
								$casts = explode(',', $movie->casts);
							@endphp

							@if (count($casts) > 0) 
								@foreach ($casts as $cast)
									{{ title_case($cast)}}
									@unless ($loop->last),@endunless
								@endforeach
							@endif
						
						</p>
					</div>
					<!-- TAB FOR SAME CATEGORY MOVIES -->
					<div class="tab-pane  " id="more">
						<p>
						<div class="content">
							<div class="grid">
								@if (!is_null($movie->related()))
									@foreach ($movie->related() as $related)

										@if (in_array(auth()->user()->type, (array)$related->genre->types) && $related->id !== $movie->id)

										@php 

										$title	=	$related['title'];
										$link	=	route('view.movie', [$related->id, str_slug($related->title)]);
										$thumb	=	$related->poster;
										@endphp
				
										@include('thumb')
										@endif
									@endforeach
								@endif
										
							</div>
						</div>
						</p>
					</div>
				</div>
			</div>
		</div>
	</div>
	<hr style="border-top:1px solid #333;">
	
</div>


@endsection