@extends('main')
@section('content')
    <main class="pt40px pt20px-m pb80px pb50px-m">
        <section class="msg">
            <div class="container">
                <div class="dual-center dual-center--wide">
                    <p class="dual-center__ttl mb35px ta-center fw500">Ой... Такої сторінки не існує</p>
                    <a href="{{url('/')}}" class="btn-secondary btn-secondary--big">Повернутись на головну</a>
                    <div class="dual-center__pic">
                        <img src="{{url('assets/img/error2.png')}}" alt="404">
                    </div>
                </div>
            </div>
        </section>
    </main>
@endsection