<!DOCTYPE html>
<html lang="en">
<head>
    <base href="<?=BASE_URL?>" />
    <meta charset="UTF-8">
    <title><?=$title?></title>
    <link rel="stylesheet" type="text/css" href="/style.css" >
    <link href="https://fonts.googleapis.com/css?family=Roboto&display=swap" rel="stylesheet"> 
    <!--<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">-->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
</head>
<body>
    <div class="wrapper">   
        <div class = "container">
            <header>
                <div class="logo"><a href="/"><img src="/src/logo.png" alt="" height="120px"></a></div>
                <? require_once(BASE_PATH."/v/v_sub_auth.php"); ?>
                <!--<div class="welcome_block">
                </div>-->
                <div class="icons">
                    <ul>
                        <li><a href="http://www.fb.com" target="_blank"><img src="/src/facebook_icon.png" alt="no"></a></li>
                        <li><a href="http://www.twitter.com" target="_blank"><img src="/src/twitter_icon.png" alt="no"></a></li>
                        <li><a href="http://www.youtube.com" target="_blank"><img src="/src/youtube_icon.png" alt="no"></a></li>
                        <li><a href="http://www.pinterest.com" target="_blank"><img src="/src/pinterest_icon.png" alt="no"></a></li>
                        <li><a href="http://www.instagram.com" target="_blank"><img src="/src/insta_icon.png" alt="no"></a></li>
                    </ul>
                </div>
            </header>
            <main>
               <div class="content_first_column">
                   <nav>
                       <h3>Меню</h3>
                       <ul class="main_menu">
                         <?=$menu?>  
                       </ul>
                   </nav>
                   <!--<div class="adv_picture">
                       <h3>Мы ждём вас!</h3>
                   </div>-->
               </div>       
               <?=$content?>       
            </main>
        </div>
        <footer>
                <a href="/" class="footer_column">Главная</a>
                <a href="/auth/login" class="footer_column">Регистрация</a>
                <a href="/contacts/index" class="footer_column">Контакты</a>
        </footer>
    </div>
    <script>
        var idUser = <?php if($this->muser->id_user) {echo $this->muser->id_user;} else { echo('0');}?>;
        var userRole = <?php if($this->muser->user_role) {echo ($this->muser->user_role);} else { echo('0');}?>;
    </script>    
    <script type="text/javascript" src="/script.js"></script>
    <script type="text/javascript" src="/editscript.js"></script>
</body>
</html>