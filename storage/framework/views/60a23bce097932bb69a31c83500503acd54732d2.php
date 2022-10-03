
<?php $__env->startSection('css'); ?>
    <link rel="stylesheet" href="<?php echo e(url('mapplic/mapplic.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(url('mapplic/magnific-popup.css')); ?>">
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
    <div class="visual">
        <div class="container">
            <div class="row visual__wrp">
                <div class="col-lg-6 visual__col">
                    <h1 class="visual__ttl ttl2 clr-pimary mb40px">
                        <?php echo e(trans('translation.home_h1')); ?> <em><?php echo e(trans('translation.home_h1_em')); ?></em>
                    </h1>
                    <div class="searchBlock mb60px mb40px-m">
                        <h3 class="searchBlock__ttl ttl3 mb25px">
                            <i class="iconify searchBlock__ico" data-icon="akar-icons:location"></i>
                            <?php echo e(trans('translation.choose_community')); ?>

                        </h3>
                        <form action="<?php echo e($lang == 'en' ? url('en/rating/communities') : url('rating/communities')); ?>" id="" class="searchBlock-form">
                            <div class="searchBlock-form__inst">
                                <input name="quick-search" class="searchBlock-form__ctrl" type="text" placeholder="<?php echo e(trans('translation.find_city')); ?>">
                                <button class="searchBlock-form__btn" type="submit"></button>
                            </div>
                            <ul class="searchBlock-form__results"></ul>
                        </form>
                    </div>
                    <p class="visual__txt"><?php echo e(trans('translation.home_description')); ?></p>
                </div>
                <div class="col-lg-6 visual__col">
                    <div id="map" class="visual__pic">

                    </div>
                </div>
            </div>
        </div>
    </div>
    <main class="pt100px pt60px-m pb130px pb70px-m">
        <section class="cards cards-animation1">
            <div class="container posr">
                <h2 class="ttl1 mb55px mb30px-m"><?php echo e(trans('translation.main_groups')); ?></h2>
                <div class="row cards__main">
                    <?php $__currentLoopData = $groups; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $group): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="col-11 col-sm-6 col-md-4 col-xl-3 mb30px">
                            <div class="card">
                                <figure class="card__wrapper">
                                    <div class="card__img mb15px">
                                        <img src="<?php echo e($group->icon_home); ?>" alt="img">
                                    </div>
                                    <figcaption>
                                        <h5 class="ttl5 mb10px"><?php echo e($lang == 'en' ? $group->name_en : $group->name); ?></h5>
                                        <p><?php echo e($lang == 'en' ? $group->description_en : $group->description); ?></p>
                                    </figcaption>
                                </figure>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
        </section>
        <section class="steps">
            <div class="container">
                <h2 class="ttl1 mb55px mb30px-m ta-center"><?php echo e(trans('translation.home_how_title')); ?></h2>
                <div class="row justify-content-md-center">
                    <div class="col-lg-10">
                        <ul class="step-list">
                            <li class="step-list__el">
                                <div class="step">
                                    <span class="step__pic">
                                        <img src="<?php echo e(url('assets/img/ico/ic-user.png')); ?>" alt="step pic">
                                    </span>
                                    <p class="step__ttl"><?php echo e(trans('translation.home_how_1_title')); ?></p>
                                    <p class="step__desc"><?php echo e(trans('translation.home_how_1_text')); ?></p>
                                </div>
                            </li>
                            <li class="step-list__el">
                                <div class="step">
                                    <span class="step__pic">
                                        <img src="<?php echo e(url('assets/img/ico/carbon_user-profile.png')); ?>" alt="step pic">
                                    </span>
                                    <p class="step__ttl"><?php echo e(trans('translation.home_how_2_title')); ?></p>
                                    <p class="step__desc"><?php echo e(trans('translation.home_how_2_text')); ?></p>
                                </div>
                            </li>
                            <li class="step-list__el">
                                <div class="step">
                                    <span class="step__pic">
                                        <img src="<?php echo e(url('assets/img/ico/carbon_data-table-reference.png')); ?>" alt="step pic">
                                    </span>
                                    <p class="step__ttl"><?php echo e(trans('translation.home_how_3_title')); ?></p>
                                    <p class="step__desc"><?php echo e(trans('translation.home_how_3_text')); ?></p>
                                </div>
                            </li>
                            <li class="step-list__el step-list__el--simple">
                                <div class="step">
                                    <p class="step__ttl"><?php echo e(trans('translation.home_how_4_title')); ?></p>
                                    <p class="step__desc"><?php echo e(trans('translation.home_how_4_text')); ?></p>
                                    <a href="<?php echo e(url('register')); ?>" class="btn-secondary btn-secondary--big mt20px"><?php echo e(trans('translation.home_how_register')); ?></a>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </section>
        <?php if(isset($news) && !empty($news)): ?>
            <section class="posts">
                <div class="container">
                    <h2 class="ttl1 mb55px mb30px-m ta-center"><?php echo e(trans('translation.news')); ?></h2>
                    <div class="row justify-content-center">
                        <div class="col-lg-10">
                            <div class="row">
                                <?php $__currentLoopData = $news; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div class="col-md-6 posts__col">
                                        <a href="<?php echo e($lang == 'en' ? url('en/news/' . $item->slug) : url('news/' . $item->slug)); ?>" class="shortPost">
                                            <date class="shortPost__date"><?php echo e($item->date); ?></date>
                                            <h4 class="ttl4 shortPost__ttl"><?php echo e($lang == 'en' ? $item->name_en : $item->name); ?></h4>
                                            <p class="shortPost__story"><?php echo e($lang == 'en' ? $item->description_en : $item->description); ?></p>
                                            <span class="shortPost__btn"><?php echo e(trans('translation.details')); ?></span>
                                        </a>
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        <?php endif; ?>
    </main>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('js'); ?>
    <script src="<?php echo e(url('mapplic/jquery.mousewheel.js')); ?>"></script>
    <script src="<?php echo e(url('mapplic/magnific-popup.js')); ?>"></script>
    <script src="<?php echo e(url('mapplic/mapplic.js')); ?>"></script>
    <?php if(isset($communities) && count($communities)): ?>
        <?php
        $label_place = $lang == 'en' ? 'place' : 'місце';
        $label_points = $lang == 'en' ? 'points' : 'балів';
        $label_data_not_available = $lang == 'en' ? 'data not available' : 'дані ще недоступні';
        ?>
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
                            <?php $__currentLoopData = $communities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $community): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                {
                                    id: "<?php echo e($community->slug); ?>",
                                    title: "<?php echo e($lang == 'en' ? $community->name_en : $community->name); ?>",
                                    pin: "pin-circular",
                                    
                                    action: "open-link",
                                    lat: "<?php echo e($community->lat); ?>",
                                    lng: "<?php echo e($community->lng); ?>",
                                    level: "ukraine",
                                    color: "#343f4b",
                                    description: "<p><span class='blue full'><?php echo e(trans('translation.rating_data')); ?> <?php echo e($current_year_repo->name); ?> <?php echo e(trans('translation.of_year')); ?></span><?php echo isset($markers[$community->id]) ? '<span class=\'full\'><span class=\'blue bold\'>' . $markers[$community->id]['position'] . '</span> ' . $label_place . '</span><span class=\'full\'><span class=\'blue bold\'>' . $markers[$community->id]['value'] . '</span> ' . $label_points . '</span>' . $markers[$community->id]['eecu'] : '<span>' . $label_data_not_available . '</span>'; ?></p>",
                                    link: "<?php echo e($lang == 'en' ? url('en/rating/communities/' . $community->slug) : url('rating/communities/' . $community->slug)); ?>"
                                },
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
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
    <?php endif; ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('main', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /vol/var/www/eecu/eea-benchmark.enefcities.org.ua/resources/views/home.blade.php ENDPATH**/ ?>