<?php
/*
Copyright 2017 iamthemanintheshower@gmail.com

Permission is hereby granted, free of charge, to any person obtaining a copy of 
this software and associated documentation files (the "Software"), to deal in 
the Software without restriction, including without limitation the rights to use, 
copy, modify, merge, publish, distribute, sublicense, and/or sell copies 
of the Software, and to permit persons to whom the Software is furnished 
to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in 
all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, 
INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A 
PARTICULAR PURPOSE AND NONINFRINGEMENT. 
IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, 
DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE,
ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER 
DEALINGS IN THE SOFTWARE.
*/

session_start();
include 'conf/app_config.php';
include 'conf/config.php';
$plugins_folder = dirname( dirname(__FILE__) ).'/wp-content/plugins/';
//error_log($plugins_folder, 0);
if(is_dir($plugins_folder)){
    $installed_plugins = scandir($plugins_folder);
    $installed_plugins = array_diff($installed_plugins, array('.', '..'));
}else{
    $error_message = 'Folder '.$plugins_folder.' does not exists. I must be in the root of your nuked Wordpress. Check out the <a target="_blank" href="'.$app_support_url.'">support page</a>';
}

//disable plugin $_GET
if(isset($_GET) && isset($_GET['id_folder']) && $_GET['id_folder'] !== ''){
    $id_folder = $_GET['id_folder'];
    if (!is_writable($plugins_folder.$installed_plugins[$id_folder + 1])) {
        $error_message = 'You can\'t disable a single plugin due to a permission issue.<br/> Click on <a href="">disable all plugins</a> in order to disable every plugins (I\'ll try to use the database details in wp-config.php).';
        $_get_options = _get_options();
        $option_value = $_get_options['option_value'];
        if(isset($option_value) && $option_value !== ''){
            $error_message .= 'I have tried to access the DB using the wp-config.php DB details and I have retrieved that you have these active plugins: <pre>'.$option_value.'</pre>';
            $_SESSION['option_value'] = $option_value;
            $error_message .= 'You can now store this option_value in a safe place (a local file) and click <a href="?disable_all_plugins=1">here</a> in order to disable all plugins';
        }
    }else{
        rename($plugins_folder.$installed_plugins[$id_folder + 1], $plugins_folder.$installed_plugins[$id_folder + 1].'-disabled');
        header('Location: '.strtok($_SERVER["REQUEST_URI"],'?'));
    }
}
if(isset($_GET['disable_all_plugins']) && $_GET['disable_all_plugins'] !== ''){
    $_get_options = _get_options();
    $option_value = $_get_options['option_value'];
    $option_id = $_get_options['option_id'];
    $_clear_plugins = _clear_plugins($option_id);
    $error_message = $_clear_plugins['error_message'].' If you still need the options_value: <pre>'.$option_value.'</pre>';
}
?><!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title><?php echo $app_title.$app_subtitle; ?></title>

    <!-- Bootstrap Core CSS -->
    <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <!-- Theme CSS -->
    <link href="css/clean-blog.css" rel="stylesheet">

    <!-- Custom Fonts -->
    <link href="vendor/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
    <link href='https://fonts.googleapis.com/css?family=Lora:400,700,400italic,700italic' rel='stylesheet' type='text/css'>
    <link href='https://fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,600italic,700italic,800italic,400,300,600,700,800' rel='stylesheet' type='text/css'>

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>

<body>

    <!-- Navigation -->
    <nav class="navbar navbar-default navbar-custom navbar-fixed-top">
        <div class="container-fluid">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header page-scroll">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                    <span class="sr-only">Toggle navigation</span>
                    Menu <i class="fa fa-bars"></i>
                </button>
                <a class="navbar-brand" href="index.html"><?php echo $app_title.$app_subtitle; ?></a>
            </div>

            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav navbar-right">
                    <li>
                        <a target="_blank" href="<?php echo $app_credits_url; ?>">Credits</a>
                    </li>
                    <li>
                        <a target="_blank" href="<?php echo $app_license_url; ?>">License</a>
                    </li>
                    <li>
                        <a target="_blank" href="<?php echo $app_support_url; ?>">Support</a>
                    </li>
                </ul>
            </div>
            <!-- /.navbar-collapse -->
        </div>
        <!-- /.container -->
    </nav>

    <!-- Page Header -->
    <!-- Set your background image for this header on the line below. -->
    <header class="intro-header" style="background-image: url('img/home-bg.jpg')">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 col-lg-offset-2 col-md-10 col-md-offset-1">
                    <div class="site-heading">
                        <h1><?php echo $app_title;?></h1>
                        <hr class="small">
                        <span class="subheading"><?php echo $app_subtitle;?></span>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <div class="container">
        <div class="row">
            <div class="col-lg-8 col-lg-offset-2 col-md-10 col-md-offset-1">
                <div class="row">
                    <div class="col-md-12">
                        <p>
                            <?php
                                if(isset($error_message) && $error_message !== ''){
                                    echo $error_message;
                                }else{
                            ?>
                            <b>Plugins in folder</b><br>
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead><th>Plugin name</th><th></th></thead>
                                    <tbody>
                                        <?php
                                        $id_folder = 1; //starts from 1
                                        if (isset($installed_plugins) && is_array($installed_plugins)){
                                            foreach ($installed_plugins as $ip){
                                                echo '<tr><td>'.$ip.'</td><td><i data-id_folder="'.$id_folder.'" class="fa fa-thumbs-down" aria-hidden="true"></i></td></tr>';
                                                $id_folder++;
                                            }
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                            <?php
                                }
                            ?>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <hr>

    <!-- Footer -->
    <footer>
        <div class="container">
            <div class="row">
                <div class="col-lg-8 col-lg-offset-2 col-md-10 col-md-offset-1">
                    <ul class="list-inline text-center">
                        <li>
                            <a target="_blank" href="<?php echo $app_twitter_url;?>">
                                <span class="fa-stack fa-lg">
                                    <i class="fa fa-circle fa-stack-2x"></i>
                                    <i class="fa fa-twitter fa-stack-1x fa-inverse"></i>
                                </span>
                            </a>
                        </li>
                        <li>
                            <a target="_blank" href="<?php echo $app_facebook_url;?>">
                                <span class="fa-stack fa-lg">
                                    <i class="fa fa-circle fa-stack-2x"></i>
                                    <i class="fa fa-facebook fa-stack-1x fa-inverse"></i>
                                </span>
                            </a>
                        </li>
                        <li>
                            <a target="_blank" href="<?php echo $app_github_url;?>">
                                <span class="fa-stack fa-lg">
                                    <i class="fa fa-circle fa-stack-2x"></i>
                                    <i class="fa fa-github fa-stack-1x fa-inverse"></i>
                                </span>
                            </a>
                        </li>
                    </ul>
                    <p class="copyright text-muted"><?php echo $app_copyright; ?></p>
                </div>
            </div>
        </div>
    </footer>

    <!-- jQuery -->
    <script src="vendor/jquery/jquery.min.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="vendor/bootstrap/js/bootstrap.min.js"></script>

    <script src="js/script.js"></script>

</body>

</html>
<?php

function _get_options(){
    include '../wp-config.php';        
    $db_host = DB_HOST;
    $db_user = DB_USER;
    $db_psw = DB_PASSWORD;
    $conn = new mysqli($db_host, $db_user, $db_psw);
    if ($conn->connect_error) { die("Connection failed: " . $conn->connect_error); } 
    mysqli_select_db ( $conn, DB_NAME );
    if ($result = mysqli_query($conn, "SELECT `option_id`, `option_value` FROM `wp_options` WHERE `option_name` = 'active_plugins'")) {
        $row = mysqli_fetch_row($result);
        if (!$row) { exit(); }
        $option_id = $row[0];
        $option_value = $row[1];
        mysqli_free_result($result);
    }
    return array(
        'option_id' => $option_id,
        'option_value' => $option_value
    );
}
function _clear_plugins($option_id){
    include '../wp-config.php';    
    $db_host = DB_HOST;
    $db_user = DB_USER;
    $db_psw = DB_PASSWORD;
    $conn = new mysqli($db_host, $db_user, $db_psw);
    if ($conn->connect_error) { die("Connection failed: " . $conn->connect_error); } 
    mysqli_select_db ( $conn, DB_NAME );
    if ($result = mysqli_query($conn, "UPDATE `wp_options` SET option_value = '' WHERE `option_id` = '$option_id'")) {
        if($result){
            $error_message = 'All plugins are disabled. Try to manage your WP again.';
        }
    }
    return array(
        'option_id' => $option_id,
        'error_message' => $error_message
    );
}
