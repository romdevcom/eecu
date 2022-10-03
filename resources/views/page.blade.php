@extends('main')
@section('content')
<main class="pt40px pt20px-m pb80px pb50px-m">
    <section class="content">
        <div class="container">
            <div class="cms-editor">
                <h1>{{$lang == 'en' ? $page->name_en : $page->name}}</h1>
                {!! $lang == 'en' ? $page->text_en : $page->text !!}
            </div>
        </div>
    </section>
</main>
@endsection