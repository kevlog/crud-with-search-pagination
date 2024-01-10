<?php

// Start session
session_start();

$postData = $userData = array();

// Get session data
$sessData = !empty($_SESSION['sessData'])?$_SESSION['sessData']:'';

// Get status message from session
if(!empty($sessData['status']['msg'])){
    $statusMsg = $sessData['status']['msg'];
    $statusMsgType = $sessData['status']['type'];
    unset($_SESSION['sessData']['status']);
}

// Get posted data from session
if(!empty($sessData['postData'])){
    $postData = $sessData['postData'];
    unset($_SESSION['sessData']['postData']);
}

// Get user data
if(!empty($_GET['id'])){
    include 'DB.class.php';
    $db = new DB();
    $conditions['where'] = array(
        'id' => $_GET['id'],
    );
    $conditions['return_type'] = 'single';
    $userData = $db->getRows('users', $conditions);
}

// Pre-filled data
$userData = !empty($postData)?$postData:$userData;

// Define action
$actionLabel = !empty($_GET['id'])?'Ubah':'Tambah';

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Tambah Data Teman</title>
    <meta charset="utf-8">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <div class="container">
        <h2 style="text-align: center;">Tambah Data Teman</h2>
        <div class="row">
            <div class="col-md-offset-3 col-md-6">
                <!-- Display status message -->
                <?php if(!empty($statusMsg) && ($statusMsgType == 'success')){ ?>
                <div class="alert alert-success"><?php echo $statusMsg; ?></div>
                <?php }elseif(!empty($statusMsg) && ($statusMsgType == 'error')){ ?>
                <div class="alert alert-danger"><?php echo $statusMsg; ?></div>
                <?php } ?>

                <!-- Add/Edit form -->
                <div class="panel panel-default">
                    <!-- <div class="panel-heading"><a href="index.php" class="glyphicon glyphicon-arrow-left"></a></div> -->
                    <div class="panel-heading"><?php echo $actionLabel; ?> Data</div>
                    <div class="panel-body">
                        <form method="post" action="userAction.php" class="form">
                            <div class="form-group">
                                <label>Nama</label>
                                <input type="text" class="form-control" name="name" value="<?php echo !empty($userData['name'])?$userData['name']:''; ?>">
                            </div>
                            <div class="form-group">
                                <label>Email</label>
                                <input type="text" class="form-control" name="email" value="<?php echo !empty($userData['email'])?$userData['email']:''; ?>">
                            </div>
                            <div class="form-group">
                                <label>No. Telepon</label>
                                <input type="text" class="form-control" name="phone" value="<?php echo !empty($userData['phone'])?$userData['phone']:''; ?>">
                            </div>
                            <input type="hidden" name="id" value="<?php echo !empty($userData['id'])?$userData['id']:''; ?>">
                            <div class="row">
                                <div class="col-xs-1 col-md-1">
                                    <input type="submit" name="userSubmit" class="btn btn-success" value="MASUKKAN" title="Klik untuk Menyelesaikan"/>
                                </div>
                                <div class="col-xs-1 col-xs-offset-5 col-sm-1 col-sm-offset-8 col-md-1 col-md-offset-8">
                                    <span class="panel-heading"><a href="index.php" title="Klik untuk Kembali" class="btn btn-default"><i class="glyphicon glyphicon-arrow-left"></i> Kembali</a></span>
                                </div>
                            </div>
                        </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="navbar navbar-collapse navbar-fixed-bottom">
            <div class="container">
                <p style="text-align: center;">&copy; 2020 | By Kevin Krisna Adji Pratama</p>
            </div>
    </div>
</body>
</html>