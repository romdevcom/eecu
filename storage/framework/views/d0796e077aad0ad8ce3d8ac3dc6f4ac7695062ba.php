
<?php $__env->startSection('content'); ?>
<main class="pt40px pt20px-m pb80px pb50px-m">
    <section class="content">
        <div class="container">
            <div class="cms-editor">
                <h1><?php echo e($lang == 'en' ? $page->name_en : $page->name); ?></h1>
                <?php echo $lang == 'en' ? $page->text_en : $page->text; ?>

            </div>
        </div>
    </section>
</main>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('main', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /vol/var/www/eecu/eea-benchmark.enefcities.org.ua/resources/views/page.blade.php ENDPATH**/ ?>