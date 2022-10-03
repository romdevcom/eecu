@extends('main')
@section('content')
    <main class="pt40px pt20px-m pb80px pb50px-m">
        <div class="container page-head mb70px pb30px-m">
            <a href="{{$lang == 'en' ? url('en/rating/communities') : url('rating/communities')}}" class="page-back">
                <i class="page-back__ico">
                    <span class="iconify" data-icon="eva:arrow-back-outline"></span>
                </i>
                {{trans('translation.all_communities')}}
            </a>
        </div>
        <section class="object">
            <div class="container">
                <h1 class="ttl1 mb20px mb10px-t">{{trans('translation.compare_h1')}}</h1>
                <p class="mb25px mb15px-t">{{trans('translation.compare_description')}}</p>
                @if(!empty($communities))
                    @php
                    if($lang == 'en'){
						$form_url = isset($is_admin_compare) && $is_admin_compare ? url('en/rating/compare-admin/' . $cities_get) : url('en/rating/compare/' . $cities_get);
                    }else{
						$form_url = isset($is_admin_compare) && $is_admin_compare ? url('rating/compare-admin/' . $cities_get) : url('rating/compare/' . $cities_get);
                    }
                    @endphp
                    <form action="{{$form_url}}" type="get">
                        <ul class="objFilter">
                            <li class="objFilter__el">
                                <select class="obj-select" name="city1">
                                    <option></option>
                                    @foreach($communities as $community)
                                        <option value="{{$community->id}}" {{$community->id == $city1 ? 'selected' : ''}}>{{$lang == 'en' ? $community->name_en : $community->name}}</option>
                                    @endforeach
                                </select>
                            </li>
                            <li class="objFilter__el">
                                <select class="obj-select" name="city2">
                                    <option></option>
                                    @foreach($communities as $community)
                                        <option value="{{$community->id}}" {{$community->id == $city2 ? 'selected' : ''}}>{{$lang == 'en' ? $community->name_en : $community->name}}</option>
                                    @endforeach
                                </select>
                            </li>
                            <li class="objFilter__el">
                                <select class="obj-select" name="city3">
                                    <option></option>
                                    @foreach($communities as $community)
                                        <option value="{{$community->id}}" {{$community->id == $city3 ? 'selected' : ''}}>{{$lang == 'en' ? $community->name_en : $community->name}}</option>
                                    @endforeach
                                </select>
                            </li>
                            <li class="objFilter__el objFilter__el--action">
                                <button class="btn-secondary btn-secondary--big objFilter__btn">{{trans('translation.compare')}}</button>
                            </li>
                        </ul>
                    </form>
                @endif
                @if($years)
                    <nav class="years-nav">
                        @foreach($years as $year)
                            @php
                                if($lang == 'en'){
                                    $compare_url = isset($is_admin_compare) && $is_admin_compare ? url('en/rating/compare-admin/' . $year->name . $cities_get) : url('en/rating/compare/' . $year->name . $cities_get);
                                }else{
                                    $compare_url = isset($is_admin_compare) && $is_admin_compare ? url('rating/compare-admin/' . $year->name . $cities_get) : url('rating/compare/' . $year->name . $cities_get);
                                }
                            @endphp
                            <a href="{{$compare_url}}" class="years-nav__year {{$year->id == $current_year ? 'active' : ''}}">{{$year->name}}</a>
                        @endforeach
                    </nav>
                @endif
            </div>
        </section>
        @if(!empty($city1_name) || !empty($city2_name) || !empty($city3_name))
            <section class="rating">
                <div class="container">
                    <ul class="dt-list" style="--dtCols: 3">
                        <li>
                            <div class="dt dt--head">
                                <div class="dt__cell">{{trans('translation.communities')}}</div>
                                <div class="dt__cell">{{!empty($city1_name) ? $city1_name : '---'}}</div>
                                <div class="dt__cell">{{!empty($city2_name) ? $city2_name : '---'}}</div>
                                <div class="dt__cell">{{!empty($city3_name) ? $city3_name : '---'}}</div>
                            </div>
                        </li>
                        <li>
                            <div class="dt">
                                <div class="dt__cell">{{trans('translation.total_score')}}</div>
                                <div class="dt__cell ">{{isset($totals_communities[$city1]) ? $totals_communities[$city1]['value'] : '---'}}</div>
                                <div class="dt__cell ">{{isset($totals_communities[$city2]) ? $totals_communities[$city2]['value'] : '---'}}</div>
                                <div class="dt__cell ">{{isset($totals_communities[$city3]) ? $totals_communities[$city3]['value'] : '---'}}</div>
                            </div>
                        </li>
                        <li>
                            <div class="dt">
                                <div class="dt__cell ">{{trans('translation.rate_position')}}</div>
                                <div class="dt__cell ">{{isset($totals_communities[$city1]) ? $totals_communities[$city1]['position'] : '---'}}</div>
                                <div class="dt__cell ">{{isset($totals_communities[$city2]) ? $totals_communities[$city2]['position'] : '---'}}</div>
                                <div class="dt__cell ">{{isset($totals_communities[$city3]) ? $totals_communities[$city3]['position'] : '---'}}</div>
                            </div>
                        </li>
                        <li>
                            <div class="dt dt--head mb25px">
                                <div class="dt__cell">{{trans('translation.detail_rate_two')}}</div>
                            </div>
                            @php
                                $totals_labels = isset($totals_by_city[$city1]) && is_array($totals_by_city[$city1]) ? $totals_by_city[$city1] : false;
                                $totals_labels = isset($totals_by_city[$city2]) && is_array($totals_by_city[$city2]) ? $totals_by_city[$city2] : $totals_labels;
                                $totals_labels = isset($totals_by_city[$city3]) && is_array($totals_by_city[$city3]) ? $totals_by_city[$city3] : $totals_labels;
                            @endphp
                            @if($totals_labels)
                                <ol class="acrd">
                                    @foreach($totals_labels as $group => $indicator)
                                        <li class="acrd__option">
                                            <div class="acrd__opener">
                                                <div class="dt">
                                                    <div class="dt__cell dt-ttl">
                                                        <div class="acrd-status">{{$indicator['name']}}
                                                            <i class="acrd-status__ico">
                                                                <span class="iconify" data-icon="dashicons:arrow-down-alt2"></span>
                                                            </i>
                                                        </div>
                                                    </div>
                                                    @php $isset_city1 = !empty($city1) && isset($totals_by_city[$city1]) && isset($totals_by_city[$city1][$group]) && isset($totals_by_city[$city1][$group]['value']); @endphp
                                                    @php $isset_city2 = !empty($city2) && isset($totals_by_city[$city2]) && isset($totals_by_city[$city2][$group]) && isset($totals_by_city[$city2][$group]['value']); @endphp
                                                    @php $isset_city3 = !empty($city3) && isset($totals_by_city[$city3]) && isset($totals_by_city[$city3][$group]) && isset($totals_by_city[$city3][$group]['value']); @endphp
                                                    <div class="dt__cell">{{$isset_city1 ? $totals_by_city[$city1][$group]['value'] : '---'}}</div>
                                                    <div class="dt__cell">{{$isset_city2 ? $totals_by_city[$city2][$group]['value'] : '---'}}</div>
                                                    <div class="dt__cell">{{$isset_city3 ? $totals_by_city[$city3][$group]['value'] : '---'}}</div>
                                                </div>
                                            </div>
                                            @if($user_allowed)
                                                @if(isset($indicator['list']))
                                                    <div class="acrd__content">
                                                        <ol class="acrd ml20px ml5px-t">
                                                            @foreach($indicator['list'] as $sector => $measures)
                                                                <li class="acrd__option">
                                                                    <div class="acrd__opener">
                                                                        <div class="dt">
                                                                            <div class="dt__cell dt-subttl">
                                                                                <div class="acrd-status acrd-status--simple">{{$measures['name']}}</div>
                                                                            </div>
                                                                            @php $isset_city1_sector = $isset_city1 && isset($totals_by_city[$city1][$group]['list']) && isset($totals_by_city[$city1][$group]['list'][$sector]) && isset($totals_by_city[$city1][$group]['list'][$sector]['value']); @endphp
                                                                            @php $isset_city2_sector = $isset_city2 && isset($totals_by_city[$city2][$group]['list']) && isset($totals_by_city[$city2][$group]['list'][$sector]) && isset($totals_by_city[$city2][$group]['list'][$sector]['value']); @endphp
                                                                            @php $isset_city3_sector = $isset_city3 && isset($totals_by_city[$city3][$group]['list']) && isset($totals_by_city[$city3][$group]['list'][$sector]) && isset($totals_by_city[$city3][$group]['list'][$sector]['value']); @endphp
                                                                            <div class="dt__cell ">{{$isset_city1_sector ? $totals_by_city[$city1][$group]['list'][$sector]['value'] : '---'}}</div>
                                                                            <div class="dt__cell ">{{$isset_city2_sector ? $totals_by_city[$city2][$group]['list'][$sector]['value'] : '---'}}</div>
                                                                            <div class="dt__cell ">{{$isset_city3_sector ? $totals_by_city[$city3][$group]['list'][$sector]['value'] : '---'}}</div>
                                                                        </div>
                                                                    </div>
                                                                    @if(isset($measures['list']))
                                                                        <div class="acrd__content">
                                                                            <ul class="dt-list dt-list--highlight">
                                                                                @foreach($measures['list'] as $item => $value)
                                                                                    <li>
                                                                                        <div class="dt-sub">
                                                                                            <div class="dt-sub__cell">
                                                                                                <strong>{{$value['name']}}</strong>
                                                                                            </div>
                                                                                            @php $isset_city1_item = $isset_city1_sector && isset($totals_by_city[$city1][$group]['list'][$sector]['list']) && isset($totals_by_city[$city1][$group]['list'][$sector]['list'][$item]) && isset($totals_by_city[$city1][$group]['list'][$sector]['list'][$item]['value']); @endphp
                                                                                            @php $isset_city2_item = $isset_city2_sector && isset($totals_by_city[$city2][$group]['list'][$sector]['list']) && isset($totals_by_city[$city2][$group]['list'][$sector]['list'][$item]) && isset($totals_by_city[$city2][$group]['list'][$sector]['list'][$item]['value']); @endphp
                                                                                            @php $isset_city3_item = $isset_city3_sector && isset($totals_by_city[$city3][$group]['list'][$sector]['list']) && isset($totals_by_city[$city3][$group]['list'][$sector]['list'][$item]) && isset($totals_by_city[$city3][$group]['list'][$sector]['list'][$item]['value']); @endphp
                                                                                            <div class="dt-sub__cell">{{$isset_city1_item ? $totals_by_city[$city1][$group]['list'][$sector]['list'][$item]['value'] : '---'}}</div>
                                                                                            <div class="dt-sub__cell">{{$isset_city2_item ? $totals_by_city[$city2][$group]['list'][$sector]['list'][$item]['value'] : '---'}}</div>
                                                                                            <div class="dt-sub__cell">{{$isset_city3_item ? $totals_by_city[$city3][$group]['list'][$sector]['list'][$item]['value'] : '---'}}</div>
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
                            @endif
                        </li>
                    </ul>
                </div>
            </section>
        @endif
    </main>
@endsection