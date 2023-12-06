<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">


    <!-- BOOTSTRAP CDNJS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <title>BOTI-CSM</title>
</head>

<body>

    <div class="container col-md-8 mt-5">
        <div class="card">
            <div class="card-body">

                <div class="mb-3">
                    <div style="width:25%;margin:auto">

                        <img src="<?php echo e(asset('/assets/images/brand/logo.png')); ?>" alt="">
                    </div>
                </div>

                <table class="table table-bordered" id="myTable">

                    <tr class="text-center bg-primary text-white">
                        <td>School</td>
                        <td>alias</td>
                    </tr>

                    <?php $__currentLoopData = $schools_aliases; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $school): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td><?php echo e($school->name); ?>

                            <img src="<?php echo e($school->logo); ?>" alt="">
                        </td>
                        <td><?php echo e($school->founder_alias); ?></td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php $__currentLoopData = $schools_aliases; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $school): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td><?php echo e($school->name); ?>

                            <img src="<?php echo e($school->logo); ?>" alt="">
                        </td>
                        <td><?php echo e($school->founder_alias); ?></td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </table>

            </div>
        </div>
    </div>

</body>

</html><?php /**PATH C:\xampp\htdocs\csm\src\resources\views/aliases.blade.php ENDPATH**/ ?>