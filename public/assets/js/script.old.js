"use strict";
document.addEventListener("DOMContentLoaded", (function () {
    if ($("#obj1").length > 0) {
        var obj1Ctx = document.getElementById("obj1");
        var obj1Data = {
            label: obj1_title,
            data: obj1_data,
            backgroundColor: ["rgba(0, 99, 132, 0.6)", "rgba(30, 99, 132, 0.6)", "rgba(60, 99, 132, 0.6)", "rgba(90, 99, 132, 0.6)", "rgba(120, 99, 132, 0.6)", "rgba(150, 99, 132, 0.6)", "rgba(180, 99, 132, 0.6)", "rgba(210, 99, 132, 0.6)", "rgba(240, 99, 132, 0.6)", "rgba(210, 99, 132, 0.6)", "rgba(240, 99, 132, 0.6)", "rgba(210, 99, 132, 0.6)", "rgba(240, 99, 132, 0.6)", "rgba(210, 99, 132, 0.6)"],
            borderColor: ["rgba(0, 99, 132, 1)", "rgba(30, 99, 132, 1)", "rgba(60, 99, 132, 1)", "rgba(90, 99, 132, 1)", "rgba(120, 99, 132, 1)", "rgba(150, 99, 132, 1)", "rgba(180, 99, 132, 1)", "rgba(210, 99, 132, 1)", "rgba(240, 99, 132, 1)", "rgba(120, 99, 132, 1)", "rgba(150, 99, 132, 1)", "rgba(180, 99, 132, 1)", "rgba(210, 99, 132, 1)", "rgba(240, 99, 132, 1)"],
            borderWidth: 2,
            hoverBorderWidth: 0
        };
        var obj1Options = {scales: {yAxes: [{barPercentage: .5}]}, elements: {rectangle: {borderSkipped: "left"}}};
        var obj1 = new Chart(obj1Ctx, {
            type: "horizontalBar",
            data: {
                labels: obj1_labels,
                datasets: [obj1Data]
            },
            options: obj1Options
        })
    }
    if ($("#obj2").length > 0) {
        var obj2Ctx = document.getElementById("obj2").getContext("2d");
        var obj2 = new Chart(obj2Ctx, {
            type: "line",
            data: {
                labels: obj2_labels,
                datasets: [{
                    label: '',
                    data: obj2_data,
                    backgroundColor: "#01428C",
                    borderWidth: 1,
                    tension: .2
                }]
            },
            options: {
                legend: {
                    display: false
                },
                maintainAspectRatio: false,
                scales: {
                    yAxes: [
                        {
                            ticks: {
                                beginAtZero: true,
                                //stepSize: 500
                            }
                        }
                    ]
                }
            }
        })
    }
}));
(function ($) {
    "use strict";

    let mask_phone = $('input[type="tel"]');
    if(mask_phone.length){
        var mod_tel_mask = '+38 (000) 000-0000';
        var mod_tel_placeholder = "+38 (0--) --- -- --";
        var mod_tel_val = '+380';
        $(mask_phone).mask(mod_tel_mask, {placeholder: mod_tel_placeholder});
    }

    $('.eecuForm').on('submit', function(e){
        let fields = [];
        let action = $(this).attr('action');
        $('.eecuForm input').each(function(){
            console.log($(this).attr('name'));
            fields[$(this).attr('name')] = $(this).val();
        });
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            type: 'POST',
            data: {fields: JSON.stringify(Object.assign({}, fields))},
            url: action,
            success: function(data){
                let popup_title = data == 'success' ? 'Вітаємо!' : 'Помилка!';
                let popup_text = data == 'success' ? 'Для завершення реєстрації потрібно ще підтвердити вашу електронну пошту. Ми надіслали вам листа з інструкціями на цю адресу. Перевірте вашу поштову скриньку і перейдіть за посиланням, яке міститься в листі.' : data;
                Swal.fire({
                    icon: 'info',
                    title: popup_title,
                    text: popup_text,
                    footer: '',
                    showCloseButton: true,
                    confirmButtonText: 'Зрозуміло',
                    confirmButtonColor: '#004899',
                    allowOutsideClick: false,
                    buttonsStyling: false,
                    customClass: {
                        popup: 'eecModal',
                        confirmButton: 'btn-secondary btn-secondary--big'
                    },
                    backdrop: 'rgba(0,0,0, 0.6)'
                })
                if(data == 'success'){
                    $('.eecuForm input').each(function(){
                        $(this).val('');
                    });
                }
            },
            error: function (errors) {
                console.log(errors);
            }
        });
        e.preventDefault();
    });

    $('.eecuContact').on('submit', function(e){
        let fields = [];
        let action = $(this).attr('action');
        $('.eecuContact input,.eecuContact textarea').each(function(){
            fields[$(this).attr('name')] = $(this).val();
        });
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            type: 'POST',
            data: {fields: JSON.stringify(Object.assign({}, fields))},
            url: action,
            success: function(data){
                let popup_title = data == 'success' ? 'Вітаємо!' : 'Помилка!';
                let popup_text = data == 'success' ? 'Ваше повідомлення відправлене.' : data;
                Swal.fire({
                    icon: 'info',
                    title: popup_title,
                    text: popup_text,
                    footer: '',
                    showCloseButton: true,
                    confirmButtonText: 'Зрозуміло',
                    confirmButtonColor: '#004899',
                    allowOutsideClick: false,
                    buttonsStyling: false,
                    customClass: {
                        popup: 'eecModal',
                        confirmButton: 'btn-secondary btn-secondary--big'
                    },
                    backdrop: 'rgba(0,0,0, 0.6)'
                })
                if(data == 'success'){
                    $('.eecuContact input,.eecuContact textarea').each(function(){
                        $(this).val('');
                    });
                }
            },
            error: function (errors) {
                console.log(errors);
            }
        });
        e.preventDefault();
    });

    let domain = location.protocol + '//' + location.host + '/' + document.documentElement.lang;
    domain = domain == 'https://eecu.sitegist.com/uk' ? 'https://eecu.sitegist.com' : domain;

    let qs_typing_timer;
    let qs_done_typing_interval = 200;
    $('[name=quick-search]').on('keyup paste', function(){
        $('.searchBlock-form').removeClass('active');
        clearTimeout(qs_typing_timer);
        let qs_this_item = $(this);
        //тут куди вписувати результат чи повідомлення
        let qs_results_wrapper = $('.srch-sgs');
        let qs_value = qs_this_item.val().toLowerCase();
        let qs_lang = $('html').attr('lang');
        let qs_posts = $(this).attr('data-posts');
        let qs_string_more = qs_lang == 'uk' ? 'Введіть більше для результату' : 'Enter more for results';
        let qs_string_no_results = qs_lang == 'uk' ? '<p>Нічого не знайдено<p>' : '<p>No results<p>';
        let action = domain + '/' + 'post/search-communities';
        console.log(action);
        if(qs_value.length > 1){
            qs_typing_timer = setTimeout(function(){
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    type: 'POST',
                    data: {value: qs_value},
                    url: action,
                    success: function(data){
                        if(data.length > 0){
                            $('.searchBlock-form').addClass('active');
                            $('.searchBlock-form__results').html(data);
                        }else{
                            $('.searchBlock-form__results').html(qs_string_no_results);
                        }
                    },
                    error: function (errors) {
                        console.log(errors);
                    }
                });
            }, qs_done_typing_interval);
        }else{
            qs_results_wrapper.html(qs_string_more);
        }
    });

    var pageData = document.querySelector(".footer-copy__data");
    pageData.innerHTML = (new Date).getFullYear();
    $("img").on("dragstart", (function (event) {
        event.preventDefault()
    }));
    $(window).on("scroll touchmove", (function () {
        $("body").toggleClass("scroll", $(document).scrollTop() > 0)
    }));

    function windowWidth() {
        return window.innerWidth
    }

    var debounce = function debounce(func, delay) {
        var inDebounce;
        return function () {
            var context = this;
            var args = arguments;
            clearTimeout(inDebounce);
            inDebounce = setTimeout((function () {
                return func.apply(context, args)
            }), delay)
        }
    };
    if ($(".obj-select").length > 0) {
        $(".obj-select").select2({placeholder: "Додати громаду", allowClear: 1})
    }
    if ($(".ind-ci").length > 0) {
        $(".ind-ci__val").knob({readOnly: true, fgColor: "#004899"})
    }
    (function () {
        if ($(".cards-animation1").length > 0) {
            gsap.from(".cards-animation1  .card", {scale: .5, duration: .5, stagger: .1})
        }
        if ($(".eecu-progress").length > 0) {
            var progressList = $(".eecu-progress");
            $.each(progressList, (function (index, value) {
                var progressbar = $(value), currentValue = progressbar.attr("data-value");
                setTimeout((function () {
                    progressbar.attr("value", currentValue)
                }), 250 * index)
            }))
        }
    })();
    var body = document.querySelector("body"), menu = document.querySelector(".header-main"),
        btn = document.querySelector(".menu-opener");
    btn.addEventListener("click", (function () {
        body.classList.toggle("menu-opened")
    }));
    (function () {
        $(".acrd .acrd__content").hide();
        $(".acrd .acrd__option--opened > .acrd__content").show();
        $(".acrd .acrd__opener").click((function () {
            if ($(this).parent().hasClass("acrd__option--opened")) {
                $(this).parent().removeClass("acrd__option--opened").children(".acrd__content").slideUp(300);
                $(this).siblings(".acrd__content").slideUp(300)
            } else {
                $(this).parent().addClass("acrd__option--opened");
                $(this).siblings(".acrd__content").slideDown(300)
            }
            return false
        }))
    })();
    $((function () {
        $(".tabs-nav a").click((function () {
            $(".tabs-nav li").removeClass("active");
            $(this).parent().addClass("active");
            var currentTab = $(this).attr("href");
            $(".tabs-content li").hide();
            $(currentTab).show();
            return false
        }))
    }))
})(jQuery);