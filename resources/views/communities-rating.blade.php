@extends('main')
@section('css')
    <link rel="stylesheet" href="{{url('mapplic/mapplic.css')}}">
    <link rel="stylesheet" href="{{url('mapplic/magnific-popup.css')}}">
@endsection
@section('content')
    <main class="pt100px pt60px-m pb130px pb70px-m">
        <section class="object">
            <div class="container">
                <h1 class="ttl1 mb20px mb10px-t">{{trans('translation.communities')}}</h1>
            </div>
        </section>
        <div class="col-lg-12 visual__col">
            <div id="map" class="visual__pic"></div>
        </div>
        <section class="rating">
            <div class="container">
                <ul class="dt-list dt-list__low" style="--dtCols: 3">
                    <li>
                        <div class="dt dt--head">
                            <div class="dt__cell">{{trans('translation.communities')}}</div>
                            @foreach($years as $year)
                                <div class="dt__cell">{{$year->name}}</div>
                            @endforeach
                        </div>
                    </li>
                    @foreach($communities as $community)
                        <li>
                            <div class="dt ">
                                <div class="dt__cell">
                                    <a href="{{$lang == 'en' ? url('en/rating/communities/' . $community->slug) : url('rating/communities/' . $community->slug)}}">
                                        {{$lang == 'en' ? $community->name_en : $community->name}}
                                    </a>
                                </div>
                                @foreach($years as $year)
                                    <div class="dt__cell ">{{isset($values[$community->id]) && isset($values[$community->id][$year->id]) ? $values[$community->id][$year->id] : '---'}}</div>
                                @endforeach
                            </div>
                        </li>
                    @endforeach
                </ul>
            </div>
        </section>
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