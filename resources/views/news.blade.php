@extends('main')
@section('content')
    <main class="pt40px pt20px-m pb80px pb50px-m">
        @if(isset($news) && !empty($news))
            <section class="posts">
                <div class="container">
                    <h1 class="ttl1 mb55px mb30px-m ta-center">{{trans('translation.news')}}</h1>
                    <div class="row justify-content-center">
                        <div class="col-lg-10">
                            <div class="row">
                                @foreach($news as $item)
                                    <div class="col-md-6 posts__col">
                                        <a href="{{'news/' . $item->slug}}" class="shortPost">
                                            <date class="shortPost__date">{{$item->date}}</date>
                                            <h4 class="ttl4 shortPost__ttl">{{$item->name}}</h4>
                                            <p class="shortPost__story">{{$item->description}}</p>
                                            <span class="shortPost__btn">{{trans('translation.details')}}</span>
                                        </a>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        @endif
    </main>
@endsection