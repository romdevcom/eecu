
<?php $__env->startSection('content'); ?>
    <main class="pt40px pt20px-m pb80px pb50px-m">
        <section class="dual">
            <div class="container">
                <div class="row align-items-center justify-content-center justify-content-sm-between">
                    <div class="col-11 col-lg-4 dual__col">
                        <section class="contacts">
                            <h1 class="ttl1 mb25px"><?php echo e(trans('translation.menu_contacts')); ?></h1>
                            <dl class="detailsList">
                                <dt><?php echo e(trans('translation.address')); ?></dt>
                                <dd>
                                    <p class="detailsList__company"><?php echo trans('translation.contact_address'); ?></p>
                                </dd>
                                <dt><?php echo e(trans('translation.menu_contacts')); ?></dt>
                                <dd>
                                    <ul class="icon-list contacts-list mb30px">
                                        <li class="icon-list__el contacts-list__el">
                                            <i class="icon-list__ico">
                                                <span class="iconify" data-icon="carbon:phone"></span>
                                            </i>
                                            <a href="#">+ 38 (032) 245 52 62</a>
                                        </li>
                                        <li class="icon-list__el">
                                            <i class="icon-list__ico">
                                                <span class="iconify" data-icon="codicon:mail"></span>
                                            </i>
                                            <a href="#">office@enefcities.org.ua</a>
                                        </li>
                                    </ul>
                                </dd>
                                <dd>
                                    <p><?php echo e(trans('translation.project_head')); ?> <strong><?php echo e(trans('translation.project_head_name')); ?></strong></p>
                                    <p><?php echo e(trans('translation.contact_person')); ?> <strong><?php echo e(trans('translation.contact_person_name')); ?></strong></p>
                                </dd>
                                <dd>
                                    <div class="social-list">
                                        <a href="#" class="social-list__lnk">
                                            <i class="iconify" data-icon="fa-brands:facebook-f"></i>
                                        </a>
                                        <a href="#" class="social-list__lnk">
                                            <i class="iconify" data-icon="cib:viber"></i>
                                        </a>
                                    </div>
                                </dd>
                            </dl>
                        </section>
                    </div>
                    <div class="col-lg-7 dual__col">
                        <div class="shadowBlock shadowBlock--more-space">
                            <h2 class="ttl3 mb10px"><?php echo e(trans('translation.contact_us')); ?></h2>
                            <p class="description mb25px"><?php echo e(trans('translation.contact_us_text')); ?></p>
                            <form action="<?php echo e(url('form-contact')); ?>" class="eecuContact">
                                <dl class="eecuForm-wrp eecuForm-wrp--flat-m">
                                    <dt><?php echo e(trans('translation.form_name')); ?>*</dt>
                                    <dd>
                                        <input type="text" name="form_name" class="eecuForm__ctrl" placeholder="<?php echo e(trans('translation.form_name')); ?>" required>
                                    </dd>
                                    <dt><?php echo e(trans('translation.form_phone')); ?>*</dt>
                                    <dd>
                                        <input type="tel" name="form_phone" class="eecuForm__ctrl" placeholder="<?php echo e(trans('translation.form_phone')); ?>" autocomplete="" >
                                    </dd>
                                    <dt><?php echo e(trans('translation.form_email')); ?></dt>
                                    <dd>
                                        <input type="email" name="form_email" class="eecuForm__ctrl" placeholder="<?php echo e(trans('translation.form_email')); ?>" autocomplete="email">
                                    </dd>
                                    <dt><?php echo e(trans('translation.form_message')); ?></dt>
                                    <dd>
                                        <textarea name="form_message" class="eecuForm__ctrl" placeholder="<?php echo e(trans('translation.form_message')); ?>" style="height:100px"></textarea>
                                    </dd>
                                    <input type="hidden" name="token" id="token">
                                    <input type="hidden" name="action" id="action">
                                    <dd class="eecuForm-wrp__actions mt10px">
                                        <button class="eecuForm__btn mb15px"><?php echo e(trans('translation.send')); ?></button>
                                    </dd>
                                </dl>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('js'); ?>
    <script src="https://www.google.com/recaptcha/api.js?render=6Lct7QMfAAAAAK7LSYqewbZcIlhkQLPxwiEArhtU&hl=uk"></script>
    <script src="<?php echo e(url('assets/js/mask.js')); ?>"></script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('main', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /vol/var/www/eecu/eea-benchmark.enefcities.org.ua/resources/views/contacts.blade.php ENDPATH**/ ?>