@extends('main')
@section('content')
    <main class="pt40px pt20px-m pb80px pb50px-m">
        <section class="dual">
            <div class="container">
                <div class="row align-items-start justify-content-between">
                    <div class="col-xl-8 dual__col">
                        <article class="news-article">
                            <h1 class="ttl1 news-article__ttl mb30px">{{$lang == 'en' ? $page->name_en : $page->name}}</h1>
                            <time class="mb20px">{{$page->date}}</time>
                            <div class="cms-editor">
                                @if(!empty($page->image))
                                    <img src="{{url('storage/' . $page->image)}}" alt="article img">
                                @endif
                                {!! $lang == 'en' ? $page->text_en : $page->text !!}
                            </div>
                        </article>
                    </div>
                    @if(!empty($news) && count($news))
                        <aside class="newsAside col-xl-3 dual__col flex-column">
                            <h2 class="ttl2 newsAside__ttl mb35px fw500">{{trans('translation.menu_news')}}</h2>
                            <div class="newsAside__list">
                                @foreach($news as $item)
                                    <a href="{{'/news/' . $item->slug}}" class="shortPost">
                                        <date class="shortPost__date">{{$item->date}}</date>
                                        <h4 class="ttl4 shortPost__ttl">{{$lang == 'en' ? $item->name_en : $item->name}}</h4>
                                        <p class="shortPost__story">{{$lang == 'en' ? $item->description_en : $item->description}}</p>
                                        <span class="shortPost__btn shortPost__btn--right">{{trans('translation.details')}}</span>
                                    </a>
                                @endforeach
                            </div>
                        </aside>
                    @endif
                </div>
            </div>
        </section>
    </main>
@endsection