@extends('main')
@section('content')
<main class="pt40px pt20px-m pb80px pb50px-m">
    <section class="object">
        <div class="container">
            <h1 class="ttl1 mb20px mb10px-t">{{trans('translation.communities')}}</h1>
            <p class="mb25px mb15px-t">{{trans('translation.communities_compare')}}</p>
            @if(!empty($communities))
                <form action="{{$lang == 'en' ? url('en/communities-compare') : url('communities-compare')}}" type="get">
                    <ul class="objFilter">
                        <li class="objFilter__el">
                            <select class="obj-select" name="city1">
                                <option></option>
                                @foreach($communities as $community)
                                    <option value="{{$community->id}}">{{$lang == 'en' ? $community->name_en : $community->name}}</option>
                                @endforeach
                            </select>
                        </li>
                        <li class="objFilter__el">
                            <select class="obj-select" name="city2">
                                <option></option>
                                @foreach($communities as $community)
                                    <option value="{{$community->id}}">{{$lang == 'en' ? $community->name_en : $community->name}}</option>
                                @endforeach
                            </select>
                        </li>
                        <li class="objFilter__el">
                            <select class="obj-select" name="city3">
                                <option></option>
                                @foreach($communities as $community)
                                    <option value="{{$community->id}}">{{$lang == 'en' ? $community->name_en : $community->name}}</option>
                                @endforeach
                            </select>
                        </li>
                        <li class="objFilter__el objFilter__el--action">
                            <button class="btn-secondary btn-secondary--big objFilter__btn">{{trans('translation.compare')}}</button>
                        </li>
                    </ul>
                </form>
            @endif
        </div>
    </section>
    <section class="rating">
        <div class="container">
            <ul class="dt-list dt-list__low" style="--dtCols: 4">
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