
<?php $__env->startSection('css'); ?>
    <link rel="stylesheet" href="<?php echo e(url('mapplic/mapplic.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(url('mapplic/magnific-popup.css')); ?>">
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
    <main class="pt100px pt60px-m pb130px pb70px-m">
        <section class="object">
            <div class="container">
                <h1 class="ttl1 mb20px mb10px-t"><?php echo e(trans('translation.communities')); ?></h1>
            </div>
        </section>
        <div class="col-lg-12 visual__col">
            <div id="map" class="visual__pic"></div>
        </div>
        <section class="rating">
            <div class="container">
                <ul class="dt-list dt-list__low" style="--dtCols: 3">
                    <li>
                        <div class="dt dt--head">
                            <div class="dt__cell"><?php echo e(trans('translation.communities')); ?></div>
                            <?php $__currentLoopData = $years; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $year): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="dt__cell"><?php echo e($year->name); ?></div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </li>
                    <?php $__currentLoopData = $communities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $community): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li>
                            <div class="dt ">
                                <div class="dt__cell">
                                    <a href="<?php echo e($lang == 'en' ? url('en/rating/communities/' . $community->slug) : url('rating/communities/' . $community->slug)); ?>">
                                        <?php echo e($lang == 'en' ? $community->name_en : $community->name); ?>

                                    </a>
                                </div>
                                <?php $__currentLoopData = $years; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $year): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div class="dt__cell "><?php echo e(isset($values[$community->id]) && isset($values[$community->id][$year->id]) ? $values[$community->id][$year->id] : '---'); ?></div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        </li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </ul>
            </div>
        </section>
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
<?php echo $__env->make('main', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /vol/var/www/eecu/eea-benchmark.enefcities.org.ua/resources/views/communities-rating.blade.php ENDPATH**/ ?>