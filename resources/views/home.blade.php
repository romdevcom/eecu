@extends('main')
@section('css')
    <link rel="stylesheet" href="{{url('mapplic/mapplic.css')}}">
    <link rel="stylesheet" href="{{url('mapplic/magnific-popup.css')}}">
@endsection
@section('content')
    <div class="visual">
        <div class="container">
            <div class="row visual__wrp">
                <div class="col-lg-6 visual__col">
                    <h1 class="visual__ttl ttl2 clr-pimary mb40px">
                        {{trans('translation.home_h1')}} <em>{{trans('translation.home_h1_em')}}</em>
                    </h1>
                    <div class="searchBlock mb60px mb40px-m">
                        <h3 class="searchBlock__ttl ttl3 mb25px">
                            <i class="iconify searchBlock__ico" data-icon="akar-icons:location"></i>
                            {{trans('translation.choose_community')}}
                        </h3>
                        <form action="{{$lang == 'en' ? url('en/rating/communities') : url('rating/communities')}}" id="" class="searchBlock-form">
                            <div class="searchBlock-form__inst">
                                <input name="quick-search" class="searchBlock-form__ctrl" type="text" placeholder="{{trans('translation.find_city')}}">
                                <button class="searchBlock-form__btn" type="submit"></button>
                            </div>
                            <ul class="searchBlock-form__results"></ul>
                        </form>
                    </div>
                    <p class="visual__txt">{{trans('translation.home_description')}}</p>
                </div>
                <div class="col-lg-6 visual__col">
                    <div id="map" class="visual__pic">
{{--                        <img src="{{url('assets/img/map.png')}}" alt="map">--}}
                    </div>
                </div>
            </div>
        </div>
    </div>
    <main class="pt100px pt60px-m pb130px pb70px-m">
        <section class="cards cards-animation1">
            <div class="container posr">
                <h2 class="ttl1 mb55px mb30px-m">{{trans('translation.main_groups')}}</h2>
                <div class="row cards__main">
                    @foreach($groups as $group)
                        <div class="col-11 col-sm-6 col-md-4 col-xl-3 mb30px">
                            <div class="card">
                                <figure class="card__wrapper">
                                    <div class="card__img mb15px">
                                        <img src="{{$group->icon_home}}" alt="img">
                                    </div>
                                    <figcaption>
                                        <h5 class="ttl5 mb10px">{{$lang == 'en' ? $group->name_en : $group->name}}</h5>
                                        <p>{{$lang == 'en' ? $group->description_en : $group->description}}</p>
                                    </figcaption>
                                </figure>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
        <section class="steps">
            <div class="container">
                <h2 class="ttl1 mb55px mb30px-m ta-center">{{trans('translation.home_how_title')}}</h2>
                <div class="row justify-content-md-center">
                    <div class="col-lg-10">
                        <ul class="step-list">
                            <li class="step-list__el">
                                <div class="step">
                                    <span class="step__pic">
                                        <img src="{{url('assets/img/ico/ic-user.png')}}" alt="step pic">
                                    </span>
                                    <p class="step__ttl">{{trans('translation.home_how_1_title')}}</p>
                                    <p class="step__desc">{{trans('translation.home_how_1_text')}}</p>
                                </div>
                            </li>
                            <li class="step-list__el">
                                <div class="step">
                                    <span class="step__pic">
                                        <img src="{{url('assets/img/ico/carbon_user-profile.png')}}" alt="step pic">
                                    </span>
                                    <p class="step__ttl">{{trans('translation.home_how_2_title')}}</p>
                                    <p class="step__desc">{{trans('translation.home_how_2_text')}}</p>
                                </div>
                            </li>
                            <li class="step-list__el">
                                <div class="step">
                                    <span class="step__pic">
                                        <img src="{{url('assets/img/ico/carbon_data-table-reference.png')}}" alt="step pic">
                                    </span>
                                    <p class="step__ttl">{{trans('translation.home_how_3_title')}}</p>
                                    <p class="step__desc">{{trans('translation.home_how_3_text')}}</p>
                                </div>
                            </li>
                            <li class="step-list__el step-list__el--simple">
                                <div class="step">
                                    <p class="step__ttl">{{trans('translation.home_how_4_title')}}</p>
                                    <p class="step__desc">{{trans('translation.home_how_4_text')}}</p>
                                    <a href="{{url('register')}}" class="btn-secondary btn-secondary--big mt20px">{{trans('translation.home_how_register')}}</a>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </section>
        @if(isset($news) && !empty($news))
            <section class="posts">
                <div class="container">
                    <h2 class="ttl1 mb55px mb30px-m ta-center">{{trans('translation.news')}}</h2>
                    <div class="row justify-content-center">
                        <div class="col-lg-10">
                            <div class="row">
                                @foreach($news as $item)
                                    <div class="col-md-6 posts__col">
                                        <a href="{{$lang == 'en' ? url('en/news/' . $item->slug) : url('news/' . $item->slug)}}" class="shortPost">
                                            <date class="shortPost__date">{{$item->date}}</date>
                                            <h4 class="ttl4 shortPost__ttl">{{$lang == 'en' ? $item->name_en : $item->name}}</h4>
                                            <p class="shortPost__story">{{$lang == 'en' ? $item->description_en : $item->description}}</p>
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
@section('js')
    <script src="{{url('mapplic/jquery.mousewheel.js')}}"></script>
    <script src="{{url('mapplic/magnific-popup.js')}}"></script>
    <script src="{{url('mapplic/mapplic.js')}}"></script>
    @if(isset($communities) && count($communities))
        @php
        $label_place = $lang == 'en' ? 'place' : 'місце';
        $label_points = $lang == 'en' ? 'points' : 'балів';
        $label_data_not_available = $lang == 'en' ? 'data not available' : 'дані ще недоступні';
        @endphp
        <script>
            let json = {
                mapwidth: "800",
                mapheight: "600",
                minheight: "600",
                maxheight: "800",
                action: "tooltip",
                fillcolor: "#343f4b",
                maxscale: "4",
                bgcolor: "#ffffff",
                fullscreen: false,
                hovertip: true,
                hovertipdesc: true,
                smartip: false,
                deeplinking: true,
                linknewtab: false,
                minimap: false,
                animations: false,
                zoom: false,
                zoombuttons: false,
                clearbutton: true,
                zoomoutclose: false,
                closezoomout: false,
                mousewheel: false,
                mapfill: false,
                sidebar: false,
                search: false,
                searchdescription: false,
                alphabetic: false,
                thumbholder: false,
                hidenofilter: false,
                highlight: false,
                topLat: "49.4",
                leftLng: "21.95",
                bottomLat: "43.85",
                rightLng: "40.200653",
                levels: [
                    {
                        id: "ukraine",
                        title: "Ukraine",
                        map: "https://eea-benchmark.enefcities.org.ua/assets/mapplic/ukraine2.svg",
                        minimap: "",
                        show: "true",
                        locations: [
                            @foreach($communities as $community)
                                {
                                    id: "{{$community->slug}}",
                                    title: "{{$lang == 'en' ? $community->name_en : $community->name}}",
                                    pin: "pin-circular",
                                    {{--label: "{{$community->name}}",--}}
                                    action: "open-link",
                                    lat: "{{$community->lat}}",
                                    lng: "{{$community->lng}}",
                                    level: "ukraine",
                                    color: "#343f4b",
                                    description: "<p><span class='blue full'>{{trans('translation.rating_data')}} {{$current_year_repo->name}} {{trans('translation.of_year')}}</span>{!! isset($markers[$community->id]) ? '<span class=\'full\'><span class=\'blue bold\'>' . $markers[$community->id]['position'] . '</span> ' . $label_place . '</span><span class=\'full\'><span class=\'blue bold\'>' . $markers[$community->id]['value'] . '</span> ' . $label_points . '</span>' . $markers[$community->id]['eecu'] : '<span>' . $label_data_not_available . '</span>' !!}</p>",
                                    link: "{{$lang == 'en' ? url('en/rating/communities/' . $community->slug) : url('rating/communities/' . $community->slug)}}"
                                },
                            @endforeach
                        ]
                    }
                ],
                styles: [],
                categories: []
            };

            $(document).ready(function() {
                let map = $('#map').mapplic({
                    //source: 'https://eecu.sitegist.com/mapplic/ukraine.json',
                    source: json,
                    //height: 600,
                    //width: 800,
                    lightbox: true,
                    maxscale: 1
                });
            });
        </script>
    @endif
@endsection