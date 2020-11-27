<?php

   function handle(): array
   {
       if (empty($_POST['hash']) || empty($_POST['file'])) {
           header('HTTP/1.1 400 Bad Request');

           return ['warn', 'CRITICAL ERROR: Bad Request'];
       }

       $secret = getenv('FLAG');
       if (isset($_POST['nonce'])) {
           $secret = @hash_hmac('sha512', $_POST['nonce'], $secret);
       }

       print_r($secret);
       $hash = @hash_hmac('sha512', $_POST['file'], $secret);

       if ($hash !== $_POST['hash']) {
           header('HTTP/1.1 403 Forbidden');

           return ['warn', 'CRITICAL ERROR: Access Denied'];
       }

       return ['success', exec('cat '.$_POST['file'])];
   }
[$status, $message] = handle();
?>

<html>
<head>
    <title>Access denied</title>
    <style>

        .warn {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 200px;
            border: 3px solid darkred;
            color: darkred;
            font-size: 500%;
            animation: blink 2s infinite;
        }

        .success {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 200px;
            border: 3px solid green;
            color: green;
            font-size: 500%;
        }

        @keyframes blink {
            from {
                opacity: 1;
            }
            to {
                opacity: 0;
            }
        }
    </style>
</head>
<body style="background: black">
<div>
    <h2 class="<?= $status ?>"><?= $message ?></h2>
</div>
<!-- follow the robots -->
</body>
</html>