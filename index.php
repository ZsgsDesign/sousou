<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>首页 - 飕搜</title>
        <link type="text/css" rel="stylesheet" href="css/MDI.css" media="screen,projection"/>
        <link type="text/css" rel="stylesheet" href="css/materialize.css" media="screen,projection"/>
        <link type="text/css" rel="stylesheet" href="css/sousou.css" media="screen,projection"/>
        <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no">

        <!-- The following statement avoid some misunderstanding from 360, Baidu and Safari browser -->

        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="format-detection" content="telephone=no">
        <meta name="renderer" content="webkit">
        <meta http-equiv="Cache-Control" content="no-siteapp">
        <style>
        .container {
            margin-top: 20vh;
            text-align: center;
        }
        @media only screen and (min-width: 993px) {
            .container{
                text-align:left;
            }
        }
        </style>
    </head>
    <body>
        <div id="bg"><img src="img/bg.jpg" alt=""></div>
        <div class="container">
            <div class="col s12">
                <h1 class="ss-brand">飕搜&trade;</h1>
                <form action="search.php" method="get">
                    <input autocomplete="off" class="ss-search-container" name="q" placeholder="飕一下，都知道！" type="text">
                </form>
            </div>
        </div>
        <script type="text/javascript" src="js/materialize.js"></script>
    </body>
</html>