
<?php $__env->startSection('content'); ?>
<style>
    .eea-no.quarter:before{display:none;}
    .object-grid__cell.object-grid__cell--2.eea-no{max-height:202px!important;}
</style>
<main class="pt40px pt20px-m pb80px pb50px-m">
    <div class="container page-head">
        <a href="<?php echo e($lang == 'en' ? url('en/rating/communities') : url('rating/communities')); ?>" class="page-back">
            <i class="page-back__ico">
                <span class="iconify" data-icon="eva:arrow-back-outline"></span>
            </i>
            <?php echo e(trans('translation.all_communities')); ?>

        </a>
    </div>
    <?php if(!empty($community)): ?>
        <section class="object">
            <div class="container">
                <a href="<?php echo e($lang == 'en' ? url('en/rating/compare?city1=' . $community->id) : url('rating/compare?city1=' . $community->id)); ?>" class="btn-secondary btn-secondary--big object__btn mb10px"><?php echo e(trans('translation.compare')); ?></a>
                <h2 class="ttl1 mb55px mb10px-t"><?php echo e($lang == 'en' ? $community->name_en : $community->name); ?></h2>
                <?php if($years): ?>
                    <nav class="years-nav">
                        <?php $__currentLoopData = $years; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $year): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <a href="<?php echo e($lang == 'en' ? url('en/rating/communities/' . $community->slug . '/' . $year->name) : url('rating/communities/' . $community->slug . '/' . $year->name)); ?>" class="years-nav__year <?php echo e($year->id == $current_year ? 'active' : ''); ?>"><?php echo e($year->name); ?></a>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </nav>
                <?php endif; ?>
                <div class="object-grid">
                    <div class="object-grid__cell object-grid__cell--1">
                        <ul class="quarter <?php if($community->eea_member != 1): ?> eea-no <?php endif; ?>" <?php if(!$totals_year || count($totals_year) == 0): ?> style="padding-bottom:130px;" <?php endif; ?>>
                            <?php if($community->eea_member == 1): ?>
                                <li class="quarter__itm">
                                    <img src="<?php echo e(url('assets/img/logo.png')); ?>" alt="pic">
                                </li>
                                <li class="quarter__itm">
                                    <div class="indicator">
                                        <span class="indicator__val"><?php echo e($community->eea_value); ?>%</span>
                                        <span class="indicator__cat"><?php echo e(trans('translation.awarded_in')); ?> <?php echo e($community->eea_year); ?></span>
                                    </div>
                                </li>
                            <?php endif; ?>
                            <?php if($totals_year && count($totals_year)): ?>
                                <li class="quarter__itm">
                                    <div class="indicator indicator--big indicator--blue">
                                        <span class="indicator__val"><?php echo e($total_position); ?></span>
                                        <span class="indicator__cat"><?php echo e(trans('translation.rate_position')); ?></span>
                                    </div>
                                </li>
                                <?php $__currentLoopData = $totals_year; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $total_year): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php if($total_year->year_id == $current_year): ?>
                                        <li class="quarter__itm">
                                            <div class="indicator indicator--big indicator--blue">
                                                <div class="indicator__progress">
                                                    <div class="ind-ci">
                                                        <input type="text" data-thickness=".24" value="<?php echo e(intval($total_year->value)); ?>" class="ind-ci__val" data-min="<?php echo e(intval($total_year->value) < 0 ? intval($total_year->value) : 0); ?>" data-max="<?php echo e(intval($total_year->value)); ?>">
                                                    </div>
                                                </div>
                                                <span class="indicator__cat"><?php echo e(trans('translation.total_score')); ?></span>
                                            </div>
                                        </li>
                                    <?php endif; ?>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <?php else: ?>
                                <li class="quarter__itm" style="position:absolute;left:0;right:0;bottom:0;text-align:center;display:flex;justify-content:center;">
                                    <p class="object-grid__ttl" style="text-align:center"><?php echo e(trans('translation.data_not_found')); ?></p>
                                </li>
                            <?php endif; ?>
                        </ul>
                    </div>
                    <?php if($totals_year && count($totals_year)): ?>
                        <?php if($groups_array && count($groups_array)): ?>
                            <div class="object-grid__cell object-grid__cell--2">
                                <dl class="infographic">
                                    <?php $__currentLoopData = $groups_array; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $group): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <dt class="infographic__cell">
                                            <p class="infographic__ttl"><?php echo e($group['name']); ?></p>
                                            <progress id="p-000<?php echo e($key); ?>" max="<?php echo e($group['max']); ?>" data-value="<?php echo e($group['value']); ?>" class="eecu-progress"></progress>
                                        </dt>
                                        <dd class="infographic__cell infographic__cell--count"><?php echo e($group['value']); ?>/<?php echo e($group['max']); ?></dd>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </dl>
                            </div>
                            <div class="object-grid__cell object-grid__cell--3 justify-content-center">
                                <p class="object-grid__ttl mb20px" style="text-align:center"><?php echo e(trans('translation.total_years')); ?></p>
                                <script>
                                    let obj2_labels = [];
                                    let obj2_data = [];
                                    let obj2_title = '<?php echo e(trans('translation.obj2_title')); ?>';
                                    <?php $__currentLoopData = $totals_year; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $total_year): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        obj2_labels.push('<?php echo e($total_year->year_name); ?>'); obj2_data.push('<?php echo e(intval($total_year->value)); ?>');
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </script>
                                <div id="obj2-container">
                                    <canvas id="obj2" width="400" height="400"></canvas>
                                </div>
                            </div>
                        <?php endif; ?>
                    <?php else: ?>
                        <div class="object-grid__cell object-grid__cell--2 <?php if($community->eea_member != 1): ?> eea-no <?php endif; ?>" style="align-items:center;justify-content:center;max-height:332px;">
                            <p class="object-grid__ttl" style="text-align:center"><?php echo e(trans('translation.data_not_found')); ?></p>
                        </div>
                    <?php endif; ?>
                    <div class="object-grid__cell object-grid__cell--4">
                        <dl class="objBrief">
                            <dt><?php echo e(trans('translation.label_chief')); ?></dt>
                            <dd><?php echo e($community->chief); ?></dd>
                            <dt><?php echo e(trans('translation.label_contact_person')); ?></dt>
                            <dd><?php echo e($community->contact_person); ?></dd>
                            <dt><?php echo e(trans('translation.label_phone')); ?></dt>
                            <dd><?php echo e($community->phone); ?></dd>
                            <dt><?php echo e(trans('translation.label_email')); ?></dt>
                            <dd><?php echo e($community->email); ?></dd>
                        </dl>
                    </div>
                </div>
            </div>
        </section>
        <section class="rating">
            <div class="container">
                <?php if($totals): ?>
                    <ul class="dt-list" style="--dtCols: 1">
                        <li>
                            <div class="dt dt--head mb25px">
                                <div class="dt__cell"><?php echo e(trans('translation.detail_rate')); ?></div>
                            </div>
                            <ol class="acrd">
                                <?php $__currentLoopData = $totals; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $group): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <li class="acrd__option">
                                        <div class="acrd__opener">
                                            <div class="dt">
                                                <div class="dt__cell dt-ttl">
                                                    <div class="acrd-status"><?php echo e($group['name']); ?>

                                                        <i class="acrd-status__ico">
                                                            <span class="iconify" data-icon="dashicons:arrow-down-alt2"></span>
                                                        </i>
                                                    </div>
                                                </div>
                                                <div class="dt__cell"><?php echo e(isset($group['value']) ? $group['value'] : '---'); ?></div>
                                            </div>
                                        </div>
                                        <?php if($user_allowed): ?>
                                            <?php if(isset($group['list'])): ?>
                                                <div class="acrd__content">
                                                    <ol class="acrd ml20px ml5px-t">
                                                        <?php $__currentLoopData = $group['list']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sector): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                            <li class="acrd__option">
                                                                <div class="acrd__opener">
                                                                    <div class="dt">
                                                                        <div class="dt__cell dt-subttl">
                                                                            <div class="acrd-status acrd-status--simple"><?php echo e($sector['name']); ?></div>
                                                                        </div>
                                                                        <div class="dt__cell"><?php echo e(isset($sector['value']) ? $sector['value'] : '---'); ?></div>
                                                                    </div>
                                                                </div>
                                                                <?php if(isset($sector['list'])): ?>
                                                                    <div class="acrd__content">
                                                                        <ul class="dt-list dt-list--highlight">
                                                                            <?php $__currentLoopData = $sector['list']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $indicator): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                                <li>
                                                                                    <div class="dt-sub">
                                                                                        <div class="dt-sub__cell">
                                                                                            <strong><?php echo e($indicator['name']); ?></strong>
                                                                                        </div>
                                                                                        <div class="dt-sub__cell"><?php echo e(isset($indicator['value']) ? $indicator['value'] : '---'); ?></div>
                                                                                    </div>
                                                                                </li>
                                                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                                        </ul>
                                                                    </div>
                                                                <?php endif; ?>
                                                            </li>
                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                    </ol>
                                                </div>
                                            <?php endif; ?>
                                        <?php else: ?>
                                            <div class="acrd__content">
                                                <div class="dt-empty"><?php echo e(trans('translation.empty_title')); ?> <a href="<?php echo e(trans('translation.enter_link')); ?>" class="hover-hgl-bg"><?php echo e(trans('translation.empty_button')); ?></a><?php echo e(trans('translation.empty_after')); ?></div>
                                            </div>
                                        <?php endif; ?>
                                    </li>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </ol>
                        </li>
                    </ul>
                <?php endif; ?>
            </div>
        </section>
    <?php endif; ?>
</main>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('main', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /vol/var/www/eecu/eea-benchmark.enefcities.org.ua/resources/views/community.blade.php ENDPATH**/ ?>