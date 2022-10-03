@extends('main')
@section('content')
<style>
    .eea-no.quarter:before{display:none;}
    .object-grid__cell.object-grid__cell--2.eea-no{max-height:202px!important;}
</style>
<main class="pt40px pt20px-m pb80px pb50px-m">
    <div class="container page-head">
        <a href="{{$lang == 'en' ? url('en/rating/communities') : url('rating/communities')}}" class="page-back">
            <i class="page-back__ico">
                <span class="iconify" data-icon="eva:arrow-back-outline"></span>
            </i>
            {{trans('translation.all_communities')}}
        </a>
    </div>
    @if(!empty($community))
        <section class="object">
            <div class="container">
                <a href="{{$lang == 'en' ? url('en/rating/compare?city1=' . $community->id) : url('rating/compare?city1=' . $community->id)}}" class="btn-secondary btn-secondary--big object__btn mb10px">{{trans('translation.compare')}}</a>
                <h2 class="ttl1 mb55px mb10px-t">{{$lang == 'en' ? $community->name_en : $community->name}}</h2>
                @if($years)
                    <nav class="years-nav">
                        @foreach($years as $year)
                            <a href="{{$lang == 'en' ? url('en/rating/communities/' . $community->slug . '/' . $year->name) : url('rating/communities/' . $community->slug . '/' . $year->name)}}" class="years-nav__year {{$year->id == $current_year ? 'active' : ''}}">{{$year->name}}</a>
                        @endforeach
                    </nav>
                @endif
                <div class="object-grid">
                    <div class="object-grid__cell object-grid__cell--1">
                        <ul class="quarter @if($community->eea_member != 1) eea-no @endif" @if(!$totals_year || count($totals_year) == 0) style="padding-bottom:130px;" @endif>
                            @if($community->eea_member == 1)
                                <li class="quarter__itm">
                                    <img src="{{url('assets/img/logo.png')}}" alt="pic">
                                </li>
                                <li class="quarter__itm">
                                    <div class="indicator">
                                        <span class="indicator__val">{{$community->eea_value}}%</span>
                                        <span class="indicator__cat">{{trans('translation.awarded_in')}} {{$community->eea_year}}</span>
                                    </div>
                                </li>
                            @endif
                            @if($totals_year && count($totals_year))
                                <li class="quarter__itm">
                                    <div class="indicator indicator--big indicator--blue">
                                        <span class="indicator__val">{{$total_position}}</span>
                                        <span class="indicator__cat">{{trans('translation.rate_position')}}</span>
                                    </div>
                                </li>
                                @foreach($totals_year as $total_year)
                                    @if($total_year->year_id == $current_year)
                                        <li class="quarter__itm">
                                            <div class="indicator indicator--big indicator--blue">
                                                <div class="indicator__progress">
                                                    <div class="ind-ci">
                                                        <input type="text" data-thickness=".24" value="{{intval($total_year->value)}}" class="ind-ci__val" data-min="{{intval($total_year->value) < 0 ? intval($total_year->value) : 0}}" data-max="{{intval($total_year->value)}}">
                                                    </div>
                                                </div>
                                                <span class="indicator__cat">{{trans('translation.total_score')}}</span>
                                            </div>
                                        </li>
                                    @endif
                                @endforeach
                            @else
                                <li class="quarter__itm" style="position:absolute;left:0;right:0;bottom:0;text-align:center;display:flex;justify-content:center;">
                                    <p class="object-grid__ttl" style="text-align:center">{{trans('translation.data_not_found')}}</p>
                                </li>
                            @endif
                        </ul>
                    </div>
                    @if($totals_year && count($totals_year))
                        @if($groups_array && count($groups_array))
                            <div class="object-grid__cell object-grid__cell--2">
                                <dl class="infographic">
                                    @foreach($groups_array as $key => $group)
                                        <dt class="infographic__cell">
                                            <p class="infographic__ttl">{{$group['name']}}</p>
                                            <progress id="p-000{{$key}}" max="{{$group['max']}}" data-value="{{$group['value']}}" class="eecu-progress"></progress>
                                        </dt>
                                        <dd class="infographic__cell infographic__cell--count">{{$group['value']}}/{{$group['max']}}</dd>
                                    @endforeach
                                </dl>
                            </div>
                            <div class="object-grid__cell object-grid__cell--3 justify-content-center">
                                <p class="object-grid__ttl mb20px" style="text-align:center">{{trans('translation.total_years')}}</p>
                                <script>
                                    let obj2_labels = [];
                                    let obj2_data = [];
                                    let obj2_title = '{{trans('translation.obj2_title')}}';
                                    @foreach($totals_year as $total_year)
                                        obj2_labels.push('{{$total_year->year_name}}'); obj2_data.push('{{intval($total_year->value)}}');
                                    @endforeach
                                </script>
                                <div id="obj2-container">
                                    <canvas id="obj2" width="400" height="400"></canvas>
                                </div>
                            </div>
                        @endif
                    @else
                        <div class="object-grid__cell object-grid__cell--2 @if($community->eea_member != 1) eea-no @endif" style="align-items:center;justify-content:center;max-height:332px;">
                            <p class="object-grid__ttl" style="text-align:center">{{trans('translation.data_not_found')}}</p>
                        </div>
                    @endif
                    <div class="object-grid__cell object-grid__cell--4">
                        <dl class="objBrief">
                            <dt>{{trans('translation.label_chief')}}</dt>
                            <dd>{{$community->chief}}</dd>
                            <dt>{{trans('translation.label_contact_person')}}</dt>
                            <dd>{{$community->contact_person}}</dd>
                            <dt>{{trans('translation.label_phone')}}</dt>
                            <dd>{{$community->phone}}</dd>
                            <dt>{{trans('translation.label_email')}}</dt>
                            <dd>{{$community->email}}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </section>
        <section class="rating">
            <div class="container">
                @if($totals)
                    <ul class="dt-list" style="--dtCols: 1">
                        <li>
                            <div class="dt dt--head mb25px">
                                <div class="dt__cell">{{trans('translation.detail_rate')}}</div>
                            </div>
                            <ol class="acrd">
                                @foreach($totals as $group)
                                    <li class="acrd__option">
                                        <div class="acrd__opener">
                                            <div class="dt">
                                                <div class="dt__cell dt-ttl">
                                                    <div class="acrd-status">{{$group['name']}}
                                                        <i class="acrd-status__ico">
                                                            <span class="iconify" data-icon="dashicons:arrow-down-alt2"></span>
                                                        </i>
                                                    </div>
                                                </div>
                                                <div class="dt__cell">{{isset($group['value']) ? $group['value'] : '---'}}</div>
                                            </div>
                                        </div>
                                        @if($user_allowed)
                                            @if(isset($group['list']))
                                                <div class="acrd__content">
                                                    <ol class="acrd ml20px ml5px-t">
                                                        @foreach($group['list'] as $sector)
                                                            <li class="acrd__option">
                                                                <div class="acrd__opener">
                                                                    <div class="dt">
                                                                        <div class="dt__cell dt-subttl">
                                                                            <div class="acrd-status acrd-status--simple">{{$sector['name']}}</div>
                                                                        </div>
                                                                        <div class="dt__cell">{{isset($sector['value']) ? $sector['value'] : '---'}}</div>
                                                                    </div>
                                                                </div>
                                                                @if(isset($sector['list']))
                                                                    <div class="acrd__content">
                                                                        <ul class="dt-list dt-list--highlight">
                                                                            @foreach($sector['list'] as $indicator)
                                                                                <li>
                                                                                    <div class="dt-sub">
                                                                                        <div class="dt-sub__cell">
                                                                                            <strong>{{$indicator['name']}}</strong>
                                                                                        </div>
                                                                                        <div class="dt-sub__cell">{{isset($indicator['value']) ? $indicator['value'] : '---'}}</div>
                                                                                    </div>
                                                                                </li>
                                                                            @endforeach
                                                                        </ul>
                                                                    </div>
                                                                @endif
                                                            </li>
                                                        @endforeach
                                                    </ol>
                                                </div>
                                            @endif
                                        @else
                                            <div class="acrd__content">
                                                <div class="dt-empty">{{trans('translation.empty_title')}} <a href="{{trans('translation.enter_link')}}" class="hover-hgl-bg">{{trans('translation.empty_button')}}</a>{{trans('translation.empty_after')}}</div>
                                            </div>
                                        @endif
                                    </li>
                                @endforeach
                            </ol>
                        </li>
                    </ul>
                @endif
            </div>
        </section>
    @endif
</main>
@endsection