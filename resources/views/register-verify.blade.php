@extends('main')
@section('content')
    <main class="pt40px pt20px-m pb80px pb50px-m">
        <section class="msg">
            <div class="container">
                @if($verify)
                    <div class="dual-center">
                        <p class="dual-center__ttl mb35px ta-center fw600 clr-pimary">Вітаємо!</p>
                        <div class="cms-editor mb30px">
                            <p>Ваша електронна адреса підтверджена.</p>
                            <p>Тепер адміністратор системи повинен підтвердити ваш статус менеджера громади, після чого вам прийде повідомлення на електронну пошту, і ви зможете повноцінно вносити дані в систему.</p>
                        </div>
                        <ul class="msg-actions">
                            <li class="msg-actions__itm">
                                <a href="{{url('dashboard')}}" class="btn-primary btn-primary--big msg-actions__btn">
                                    {{!empty($user) ? trans('translation.cabinet') : trans('translation.enter')}}
                                </a>
                            </li>
                        </ul>
                    </div>
                @else
                    <div class="dual-center">
                        <p class="dual-center__ttl mb35px ta-center fw600 clr-orange">Емейл не підтверджено</p>
                        <div class="cms-editor mb30px">
                            <p>Вибачте, але це посилання для підтвердження електронної адреси не є дійсним. Якщо ви його копіювали з листа, перевірте, чи скопіювали всі символи. В разі виникнення проблем напишіть нашому адміністратору.</p>
                        </div>
                        <ul class="msg-actions">
                            <li class="msg-actions__itm">
                                <a href="{{url('register')}}" class="btn-secondary btn-secondary--big msg-actions__btn">
                                    {{trans('translation.register')}}
                                </a>
                            </li>
                        </ul>
                    </div>
                @endif
            </div>
        </section>
    </main>
@endsection