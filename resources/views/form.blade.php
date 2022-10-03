@extends('main')
@section('content')
<main class="pt40px pt20px-m pb80px pb50px-m">
    <section class="dual">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 dual__col">
                    <div class="dual-center">
                        <p class="dual-center__ttl mb5px ta-center">{{trans('translation.signup')}}</p>
                        <p class="dual-center__subttl mb30px ta-center">{{trans('translation.signup_text')}}</p>
                        <form action="{{url('form-submit')}}" class="eecuForm">
                            <dl class="eecuForm-wrp eecuForm-wrp--flat-m">
                                <dt>{{trans('translation.form_firstname')}}*</dt>
                                <dd>
                                    <input type="text" name="form_firstname" class="eecuForm__ctrl" placeholder="{{trans('translation.form_firstname')}}" required>
                                </dd>
                                <dt>{{trans('translation.form_lastname')}}*</dt>
                                <dd>
                                    <input type="text" name="form_lastname" class="eecuForm__ctrl" placeholder="{{trans('translation.form_lastname')}}" autocomplete="lastname" required>
                                </dd>
                                <dt>{{trans('translation.form_position')}}*</dt>
                                <dd>
                                    <input type="text" name="form_position" class="eecuForm__ctrl" placeholder="{{trans('translation.form_position')}}" autocomplete="" required>
                                </dd>
                                <dt>{{trans('translation.form_community')}}*</dt>
                                <dd>
                                    <input type="text" name="form_community" class="eecuForm__ctrl" placeholder="{{trans('translation.form_community')}}" autocomplete="" required>
                                </dd>
                                <dt>{{trans('translation.form_email')}}*</dt>
                                <dd>
                                    <input type="email" name="form_email" class="eecuForm__ctrl" placeholder="{{trans('translation.form_email')}}" autocomplete="email" required>
                                </dd>
                                <dt>{{trans('translation.form_phone')}}</dt>
                                <dd>
                                    <input type="tel" name="form_phone" class="eecuForm__ctrl" placeholder="{{trans('translation.form_phone')}}">
                                </dd>
                                <dt>{{trans('translation.form_password')}}*</dt>
                                <dd>
                                    <input type="password" name="form_password" class="eecuForm__ctrl" placeholder="********" autocomplete="current-password" required>
                                </dd>
                                <dt>{{trans('translation.form_password_repeat')}}*</dt>
                                <dd>
                                    <input type="password" name="form_password_repeat" class="eecuForm__ctrl" placeholder="********" autocomplete="new-password" required>
                                </dd>
                                <input type="hidden" name="token" id="token">
                                <input type="hidden" name="action" id="action">
                                <dd class="eecuForm-wrp__actions mt10px">
                                    <button class="eecuForm__btn mb15px">{{trans('translation.signup')}}</button>
                                </dd>
                            </dl>
                        </form>
                    </div>
                </div>
                <div class="col-lg-6 dual__col">
                    <div class="present">
                        <h1 class="ttl2 present__ttl">{{trans('translation.signup_title')}}</h1>
                        <img class="present__pic" src="./assets/img/map3.png" alt="pic">
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>
@endsection
@section('js')
    <script src="https://www.google.com/recaptcha/api.js?render=6Lct7QMfAAAAAK7LSYqewbZcIlhkQLPxwiEArhtU&hl=uk"></script>
    <script src="{{url('assets/js/mask.js')}}"></script>
@endsection