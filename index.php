<?php

// Start session
session_start();

// Get session data
$sessData = !empty($_SESSION['sessData'])?$_SESSION['sessData']:'';

// Get status message from session
if(!empty($sessData['status']['msg'])){
    $statusMsg = $sessData['status']['msg'];
    $statusMsgType = $sessData['status']['type'];
    unset($_SESSION['sessData']['status']);
}

// Load pagination class
require_once 'Pagination.class.php';

// Load and initialize database class
require_once 'DB.class.php';
$db = new DB();

// Page offset and limit
$perPageLimit = 5;
$offset = !empty($_GET['page'])?(($_GET['page']-1)*$perPageLimit):0;

// Get search keyword
$searchKeyword = !empty($_GET['sq'])?$_GET['sq']:'';
$searchStr = !empty($searchKeyword)?'?sq='.$searchKeyword:'';

// Search DB query
$searchArr = '';
if(!empty($searchKeyword)){
    $searchArr = array(
        'name' => $searchKeyword,
        'email' => $searchKeyword,
        'phone' => $searchKeyword
    );
}

// Get count of the users
$con = array(
    'like_or' => $searchArr,
    'return_type' => 'count'
);
$rowCount = $db->getRows('users', $con);

// Initialize pagination class
$pagConfig = array(
    'baseURL' => 'index.php'.$searchStr,
    'totalRows' => $rowCount,
    'perPage' => $perPageLimit
);
$pagination = new Pagination($pagConfig);

// Get users from database
$con = array(
    'like_or' => $searchArr,
    'start' => $offset,
    'limit' => $perPageLimit,
    'order_by' => 'id DESC',
);
$users = $db->getRows('users', $con);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Daftar Kontak Teman</title>
    <meta charset="utf-8">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="icon" type="image/x-icon" href="assets/favicon.ico"/>
</head>
<body>
    <div class="container">
        <h2 style="text-align: center;">Daftar Kontak Teman</h2>

        <!-- Display status message -->
        <?php if(!empty($statusMsg) && ($statusMsgType == 'success')){ ?>
        <div class="alert alert-success"><?php echo $statusMsg; ?></div>
        <?php }elseif(!empty($statusMsg) && ($statusMsgType == 'error')){ ?>
        <div class="alert alert-danger"><?php echo $statusMsg; ?></div>
        <?php } ?>

        <div class="row">
            <div class="col-md-3 search-panel">
                <!-- Search form -->
                <form>
                <div class="input-group">
                    <input type="text" name="sq" class="form-control" placeholder="Cari data..." value="<?php echo $searchKeyword; ?>">
                    <div class="input-group-btn">
                        <button title="Klik untuk Mencari" class="btn btn-default" type="submit">
                            <i class="glyphicon glyphicon-search"></i>
                        </button>
                    </div>
                </div>
                </form>
            </div>
            <div class="col-md-4 col-md-offset-5">
                <!-- Add link -->
                <span class="pull-right">
                    <a href="addEdit.php" title="Klik untuk Menambah Data" class="btn btn-primary"><i class="glyphicon glyphicon-plus"></i> Tambah Data</a>
                    <br>
                </span>
            </div>
        </div>
        <!-- Data list table --> 
        <table class="table table-striped table-bordered">
            <br>
            <thead>
                <tr>
                    <th>No.</th>
                    <th>Nama</th>
                    <th>Email</th>
                    <th>No. Telepon</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if(!empty($users)){ $count = 0; 
                    foreach($users as $user){ $count++;
                ?>
                <tr>
                    <td><?php echo '#'.$count; ?></td>
                    <td><?php echo $user['name']; ?></td>
                    <td><?php echo $user['email']; ?></td>
                    <td><?php echo $user['phone']; ?></td>
                    <td>
                        <a title="Sunting" href="addEdit.php?id=<?php echo $user['id']; ?>" class="glyphicon glyphicon-edit"></a>
                        <a title="Hapus" href="userAction.php?action_type=delete&id=<?php echo $user['id']; ?>" class="glyphicon glyphicon-trash" onclick="return confirm('Yakin ingin Menghapus Data?')"></a>
                    </td>
                </tr>
                <?php } }else{ ?>
                <tr><td colspan="5">No user(s) found......</td></tr>
                <?php } ?>
            </tbody>
            <tfoot>
                
            </tfoot>
        </table>
        
        <!-- Display pagination links -->
        <?php echo $pagination->createLinks(); ?>
    </div>
    <div class="navbar navbar-collapse navbar-fixed-bottom">
            <div class="container">
                <p style="text-align: center;">&copy; 2020 | By Kevin</p>
            </div>
    </div>
</body>
</html>