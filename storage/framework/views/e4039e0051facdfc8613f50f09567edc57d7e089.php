<!DOCTYPE html>
<?php $lang = isset($lang) ? $lang : 'uk'; ?>
<html lang="<?php echo e($lang); ?>">

<head>
    <link rel="shortcut icon" href="/favicon-eecu-16.ico" type="image/x-icon">
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600&display=swap" rel="stylesheet">
    

    <?php if(isset($seo) && isset($seo['title'])): ?>
        <title><?php echo e($seo['title']); ?></title>
    <?php else: ?>
        <title><?php echo e(trans('translation.title')); ?></title>
    <?php endif; ?>

    <?php if(isset($seo) && isset($seo['description'])): ?>
        <meta name="description" content="<?php echo e($seo['description']); ?>">
    <?php else: ?>
        <meta name="description" content="<?php echo e(trans('translation.title')); ?>">
    <?php endif; ?>

    <link rel="stylesheet" href="<?php echo e(url('assets/css/swiper-bundle.min.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(url('assets/css/select2.min.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(url('assets/css/style.min.css?ver=' . date('Hsi'))); ?>">
    <link rel="stylesheet" href="<?php echo e(url('assets/css/rom.css?ver=' . date('Hsi'))); ?>">

    <?php echo $__env->yieldContent('css'); ?>

    <!--[if IE 9]>
        <link href="https://cdn.jsdelivr.net/gh/coliff/bootstrap-ie8/css/bootstrap-ie9.min.css"
              rel="stylesheet" />
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    <!--[if lte IE 8]>
        <link href="https://cdn.jsdelivr.net/gh/coliff/bootstrap-ie8/css/bootstrap-ie8.min.css"
              rel="stylesheet" />
        <script src="https://cdn.jsdelivr.net/g/html5shiv@3.7.3"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    <!--[if IE]>
        <p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="https://browsehappy.com/">upgrade

            your browser</a> to improve your experience and security.</p>
    <![endif]-->

</head>
<body class="<?php echo e(isset($exception) && $exception->getStatusCode() == 404 ? 'error-page' : ''); ?>">
    <div id="page-wrapper">
        <div class="pg-decor">
            <img class="pg-decor__pic" src="<?php echo e(url('assets/img/decor-2.png')); ?>" alt="decor">
        </div>
        <div class="pg-decor pg-decor--r">
            <img class="pg-decor__pic" src="<?php echo e(url('assets/img/decor-1.png')); ?>" alt="decor">
        </div>
        <header class="header">
            <div class="container">
                <div class="header__wrapper">
                    <a href="<?php echo e($lang == 'en' ? url('en/') : url('/')); ?>" class="site-logo header__l">
                        home page
                        <img src="<?php echo e(url('assets/img/logo.png')); ?>" alt="site logo">
                    </a>
                    <div class="header__r">
                        <div class="header-main hide-on-lg">
                            <div class="header-main__deco show-on-lg">
                                <img src="<?php echo e(url('assets/img/decor-1.png')); ?>" alt="decor">
                            </div>
                            <div class="header-main__wrapper">
                                <ul class="header-nav">
                                    <li>
                                        <a href="#" onclick="return false"><?php echo e(trans('translation.menu_communities')); ?></a>
                                        <ul class="sub-menu">
                                            <li>
                                                <a href="<?php echo e($lang == 'en' ? url('en/rating/communities') : url('rating/communities')); ?>"><?php echo e(trans('translation.menu_communities')); ?></a>
                                            </li>
                                            <li>
                                                <a href="<?php echo e($lang == 'en' ? url('en/rating/compare') : url('rating/compare')); ?>"><?php echo e(trans('translation.menu_compare')); ?></a>
                                            </li>
                                        </ul>
                                    </li>
                                    <li>
                                        <a href="#" onclick="return false">
                                            <?php echo e(trans('translation.menu_methodology')); ?>

                                            <button class="header-nav__btn"></button>
                                        </a>
                                        <ul class="sub-menu">
                                            <li>
                                                <a href="<?php echo e($lang == 'en' ? url('en/methodology/how-to-join') : url('methodology/how-to-join')); ?>"><?php echo e(trans('translation.menu_join')); ?></a>
                                            </li>
                                            <li>
                                                <a href="<?php echo e($lang == 'en' ? url('en/methodology/data-collection') : url('methodology/data-collection')); ?>"><?php echo e(trans('translation.menu_data')); ?></a>
                                            </li>
                                            <li>
                                                <a href="<?php echo e($lang == 'en' ? url('en/methodology/user-manual') : url('methodology/user-manual')); ?>"><?php echo e(trans('translation.menu_instruction')); ?></a>
                                            </li>
                                            <li>
                                                <a href="<?php echo e($lang == 'en' ? url('en/news') : url('news')); ?>"><?php echo e(trans('translation.menu_news')); ?></a>
                                            </li>
                                        </ul>
                                    </li>
                                    <li>
                                        <a href="#" onclick="return false"><?php echo e(trans('translation.menu_eev')); ?></a>
                                        <ul class="sub-menu">
                                            <li>
                                                <a href="<?php echo e($lang == 'en' ? url('en/eea/about') : url('eea/about')); ?>"><?php echo e(trans('translation.menu_eea_about')); ?></a>
                                            </li>
                                            <li>
                                                <a href="<?php echo e($lang == 'en' ? url('en/eea/advantages-of-participation') : url('eea/advantages-of-participation')); ?>"><?php echo e(trans('translation.menu_eea_adv')); ?></a>
                                            </li>
                                            <li>
                                                <a href="<?php echo e($lang == 'en' ? url('en/eea/partners') : url('eea/partners')); ?>"><?php echo e(trans('translation.menu_eea_partners')); ?></a>
                                            </li>
                                        </ul>
                                    </li>
                                    <li>
                                        <a href="<?php echo e($lang == 'en' ? url('en/about') : url('about')); ?>"><?php echo e(trans('translation.menu_about_project')); ?></a>
                                    </li>
                                    <li>
                                        <a href="<?php echo e($lang == 'en' ? url('en/contacts') : url('contacts')); ?>"><?php echo e(trans('translation.menu_contacts')); ?></a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="header-actions">
                            <?php $current_url = URL::current(); ?>
                            <a href="<?php echo e($lang == 'en' ? str_replace('/en', '/uk', $current_url) : str_replace('/uk', '/en', $current_url)); ?>" class="site-lng" style="margin-right:2vw"><?php echo e($lang == 'en' ? 'UA' : 'EN'); ?></a>
                            <a href="<?php echo e(url('dashboard')); ?>" class="btn-primary btn-primary--big header-actions__btn hide-on-sm">
                                <?php echo e(!empty($user) ? trans('translation.cabinet') : trans('translation.enter')); ?>

                            </a>
                            <?php if(empty($user)): ?>
                                <a href="<?php echo e($lang == 'en' ? url('en/register') : url('register')); ?>" class="hide-on-sm btn-secondary btn-secondary--big header-actions__btn"><?php echo e(trans('translation.register')); ?></a>
                            <?php endif; ?>
                        </div>
                    </div>

                    <button class="menu-opener show-on-lg"> <span></span>
                        <span></span>
                        <span></span>
                        <span></span>
                    </button>
                </div>
            </div>
        </header>
        <?php echo $__env->yieldContent('content'); ?>
        <footer class="footer">
            <div class="limiter-big">
                <div class="container">
                    <div class="row footer-main">
                        <div class="col-md-6 col-lg-6 col-xl-7 footer-main__col">
                            <a href="<?php echo e($lang == 'en' ? url('en/') : url('/')); ?>" class="site-logo mb20px mr20px">
                                <img src="<?php echo e(url('assets/img/sch.png')); ?>" alt="logo">
                            </a>
                            <a href="<?php echo e($lang == 'en' ? url('en/') : url('/')); ?>" class="site-logo mb20px">
                                <img src="<?php echo e(url('assets/img/mrgttu.png')); ?>" alt="logo">
                            </a>
                            <div class="comp-info mb30px ml45px mb50px"></div>
                            <nav class="footer-nav hide-on-md">
                                <a href="<?php echo e($lang == 'en' ? url('en/rating/communities') : url('rating/communities')); ?>" class="footer-nav__lnk"><?php echo e(trans('translation.menu_communities')); ?></a>
                                <a href="<?php echo e($lang == 'en' ? url('en/methodology/how-to-join') : url('methodology/how-to-join')); ?>" class="footer-nav__lnk"><?php echo e(trans('translation.menu_methodology')); ?></a>
                                <a href="<?php echo e($lang == 'en' ? url('en/eea/about') : url('eea/about')); ?>" class="footer-nav__lnk"><?php echo e(trans('translation.menu_eea')); ?></a>
                                <a href="<?php echo e($lang == 'en' ? url('en/about') : url('about')); ?>" class="footer-nav__lnk"><?php echo e(trans('translation.menu_about_us')); ?></a>
                                <a href="<?php echo e($lang == 'en' ? url('en/contacts') : url('contacts')); ?>" class="footer-nav__lnk"><?php echo e(trans('translation.menu_contacts')); ?> </a>
                                <a href="<?php echo e($lang == 'en' ? url('en/register') : url('register')); ?>" class="footer-nav__lnk footer-nav__lnk--highlight"><?php echo e(trans('translation.register')); ?> </a>
                            </nav>
                        </div>
                        <div class="col-md-5 col-lg-4 col-xl-3 footer-main__col">
                            <a href="<?php echo e($lang == 'en' ? url('en/register') : url('register')); ?>" class="btn-secondary btn-secondary--big mb25px"><?php echo e(trans('translation.register')); ?></a>
                            <p class="ttl5 clr-pimary mb20px"><?php echo e(trans('translation.call_us')); ?></p>
                            <ul class="icon-list contacts-list mb30px">
                                <li class="icon-list__el contacts-list__el">
                                    <i class="icon-list__ico">
                                        <span class="iconify" data-icon="carbon:phone"></span>
                                    </i>
                                    <a href="tel:<?php echo e(trans('translation.phone_link')); ?>"><?php echo e(trans('translation.phone')); ?></a>
                                </li>
                                <li class="icon-list__el">
                                    <i class="icon-list__ico">
                                        <span class="iconify" data-icon="codicon:mail"></span>
                                    </i>
                                    <a href="mailto:<?php echo e(trans('translation.email')); ?>"><?php echo e(trans('translation.email')); ?></a>
                                </li>
                            </ul>
                            <div class="social-list">
                                <a href="<?php echo e(trans('translation.facebook_link')); ?>" class="social-list__lnk" target="_blank" rel="nofollow">
                                    <i class="iconify" data-icon="fa-brands:facebook-f"></i>
                                </a>



                                <a href="https://www.youtube.com/channel/UCN6ZgOPNSt8RI08GQRxfzjA" class="social-list__lnk" target="_blank" rel="nofollow">
                                    <i class="iconify" data-icon="cib:youtube"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="row footer-second">
                        <div class="col-auto">
                            <div class="footer-copy">
                                © <span class="footer-copy__data"></span>
                                <?php echo e(trans('translation.footer_copyright')); ?>

                            </div>
                        </div>
                        <div class="col-auto">
                            <div class="site-by"><?php echo e(trans('translation.site_creation')); ?> <a href="#"><b>siteGist</b></a></div>
                        </div>
                    </div>
                </div>
            </div>
        </footer>
    </div>
    </div>
</body>
<script src="<?php echo e(url('assets/js/jquery-3.5.1.js')); ?>"></script>
<script src="<?php echo e(url('assets/js/dl-animate.js')); ?>"></script>
<script src="<?php echo e(url('assets/js/iconify.min.js')); ?>"></script>
<script src="<?php echo e(url('assets/js/swiper-6.4.8.min.js')); ?>"></script>
<script src="<?php echo e(url('assets/js/gsap.js')); ?>"></script>
<script src="<?php echo e(url('assets/js/ScrollTrigger.js')); ?>"></script>
<script src="<?php echo e(url('assets/js/Chart.min.js')); ?>"></script>
<script src="<?php echo e(url('assets/js/jquery.knob.min.js')); ?>"></script>
<script src="<?php echo e(url('assets/js/select2.min.js')); ?>"></script>
<script src="<?php echo e(url('assets/js/sweetalert2.js')); ?>"></script>
<?php echo $__env->yieldContent('js'); ?>
<script src="<?php echo e(url('assets/js/script.js?ver=' . date('Hsi'))); ?>"></script>
</html><?php /**PATH /vol/var/www/eecu/eea-benchmark.enefcities.org.ua/resources/views/main.blade.php ENDPATH**/ ?>