<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Demo</title>
</head>
<body>
    <h2>From: <?php echo e($details['type']); ?></h2>
    <hr>
    <p><b>Name</b>: <?php echo e($details['name']); ?></p>
    <p><b>Email</b>: <?php echo e($details['email']); ?></p>
    <p><b>Tel</b>: <?php echo e($details['tel']); ?></p>
    <p><b>City</b>: <?php echo e($details['ville']); ?></p>
    <p><b>School</b>: <?php echo e($details['ecole']); ?></p>
    <p><b>Student Numbers</b>: <?php echo e($details['nombre']); ?></p>
    <p><b>Levels</b>: <?php echo e($details['niveau']); ?></p>
</body>
</html><?php /**PATH C:\xampp\htdocs\csm\src\resources\views/emails/message.blade.php ENDPATH**/ ?>