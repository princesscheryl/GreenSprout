<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GREEN SPROUT</title>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.3.0/fonts/remixicon.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <style>
    * {
    padding: 0;
    margin: 0;
    box-sizing: border-box ;
    list-style: none;
    text-decoration: none;
    }
    header{
        position: fixed;
        right: 0;
        top: 0;
        z-index: 1000;
        width: 100%;
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 33px 9%;
        background: transparent;
    }
    .logo{
        font-family: 'Helvetica', sans-serif;
        font-size: 40px;
        font-weight: bold;
        margin-bottom: 10px;
        color: #465644;
        text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.4); 
    }
    .navlist{
        display: flex;
    }
    .navlist a{
        color: white;
        margin-left: 60px;
        font-size: 21px;
        font-weight: 600;
        border-bottom: 2px solid transparent;
        transition: all .55s ease;
    }
    .navlist a:hover{
        border-bottom: 2px solid white;
    }
    #menu-icon{
        color: white;
        font-size: 30px;
        z-index: 10001;
        cursor: pointer;
        display: none;
    }
    .intro{
        height: 100%;
        width: 100%;
        min-height: 100vh;
        background: rgb(235, 229, 197);
        position: relative;
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        align-items: center;
        gap: 2rem;
    } 
    .intro-img img{
        width: 130%; 
        max-width: 670px; 
        height: auto; 
    }
    section{
        padding: 0 19%;
    }
    @keyframes fadeIn{
        from {opacity: 0; }
        to {opacity: 1; }
    }
    .intro-text h4{
        font-family: "Lato", sans-serif;
        font-size: px;
        font-weight: 400;
        color: white;
        margin-bottom: 40px;
        animation: fadeIn 1.5s ease-in-out 0.5s;
        animation-fill-mode: both;
    }
    .intro-text h3{
        font-family: "Montserrat", sans-serif;
        font-size: 74px;
        line-height: 1;
        color: white;
        margin: 0 0 45px;
        animation: fadeIn 1.5s ease-in-out;

    }
    .intro-text a{
        display: inline-block;
        color: white;
        background: rgb(235, 229, 197);
        border: 1px solid transparent;
        padding: 12px 30px;
        line-height: 1.4;
        font-size: 16px;
        font-weight: 500;
        border-radius: 30px;
        text-transform: uppercase;
        transition: all .55s ease;
        animation: fadeIn 1.5s ease-in-out 1s;
        animation-fill-mode: both;
    }
    .intro-text a:hover{
        background: transparent;
        border: 1px solid white;
        transform: translateX(8px);
    }
    .intro-text a.ctaa{
        background: transparent;
        border: 1px solid white;
        margin-left: 20px;
    }
    .intro-text a.ctaa i{
        vertical-align: middle;
        margin-right: 5px;
    }
    .navlist a{
        margin-left: 0;
        display: block;
        margin: 7px;
    }
    .nav-button .btn{
        width: 130px;
        height: 40px;
        font-weight: 500;
        background: rgb(255, 241, 239);
        border: none;
        border-radius: 30px;
        cursor: pointer;
        transition: .3s ease;
    }
    .nav-button .btn a {
        color: #465644;
        text-decoration: none;
        display: block;
        width: 100%;
        height: 100%;
        line-height: 40px;
    }
    #sign-btn{
        margin-left: 15px;
    }
    .btn:hover{
        background:rgba(255, 241, 239, 0.3) ;
    }
    </style>
</head>
<body>
    <header>
        <a href="index2.php" class="logo">GREEN SPROUT</a>
        <ul class="navlist">
            <li><a href="index2.php">Home</a></li>
            <li><a href="seed_feed.php">Seeds</a></li>
            <Li><a href="user_register.php?action=login">Community</a></Li>
        </ul>
        <div class="nav-button">
            <button class="btn" id="login-btn"><a href="user_register.php?action=login">LOG IN</a></button>
            <button class="btn" id="sign-btn"><a href="user_register.php?action=signup">SIGN UP</a></button>
        </div>
        
        <div class="bx bx-menu" id="menu-icon"></div>

    </header>
    <section class="intro">
        <div class="intro-text">
            <h3>WELCOME TO GREEN SPROUT</h3>
            <h4>YOUR COMPANION FOR SMARTER, GREENER GARDENING.</h4>
            <a href="user_register.php?action=login" class="ctaa"> get started</a>
        </div>
        <div class="intro-img">
            <img src="../assets/images/potted1.png" alt="Monstera Plant">
        </div>
    </section>
</body>
</html>