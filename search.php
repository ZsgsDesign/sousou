<?php

function msectime() {
   list($msec, $sec) = explode(' ', microtime());
   return (float)sprintf('%.0f', (floatval($msec) + floatval($sec)) * 1000);
}

$time=msectime();

include('core/simple_html_dom.php');
include('core/core.php');

if(@$_GET['q']) $keyword=urlencode($_GET['q']); //如果有搜索关键词存入keyword变量
else Header('Location: ./'); //如果没有搜索关键词跳转主页

$page=(@$_GET['page']) ? (int)$_GET['page'] : 1; //默认第一页，如果传入则以传入为准

$available_engine=["Bing","Baidu"];
$engine=in_array(@$_GET['engine'],$available_engine) ? @$_GET['engine'] : "Bing"; //默认必应，如果传入则以传入为准
$engine_profile=array(
        "Bing"=>array(
            "start"=>"first",
            "searchEnginelink"=>"https://www4.bing.com/search?q=",
            "citeDOM"=>"cite",
            "titleDOM"=>"li.b_algo > h2 > a",
            "descDOM"=>"li.b_algo p",
            "page"=>(($page-1)*10+1)
        ),
        "Baidu"=>array(
            "start"=>"pn",
            "searchEnginelink"=>"https://www.baidu.com/s?wd=",
            "citeDOM"=>"div.result > .f13 > a.c-showurl",
            "titleDOM"=>"div.result > h3",
            "descDOM"=>"div.result > .c-abstract",
            "page"=>(($page-1)*10)
        )
);

$ENGINE = new search_engine;
$ENGINE->searchEngineName = $engine;
$ENGINE->q = $keyword;
$ENGINE->start = $engine_profile[$engine]["start"];
$ENGINE->page = $engine_profile[$engine]["page"];
$ENGINE->searchEnginelink = $engine_profile[$engine]["searchEnginelink"];
$ENGINE->citeDOM = $engine_profile[$engine]["citeDOM"]; //引用的CSS选择器
$ENGINE->titleDOM = $engine_profile[$engine]["titleDOM"]; //标题链接的CSS选择器
$ENGINE->descDOM = $engine_profile[$engine]["descDOM"]; //标题链接的CSS选择器
$result=$ENGINE->Search();

$security_badge="<badge>安全</badge>"; //安全徽章
?>

<!-- DOM Structure -->

<!DOCTYPE>
<html lang="zh" xml:lang="zh" xmlns="http://www.w3.org/1999/xhtml" xmlns:web="http://schemas.live.com/Web/">
<head>
    <meta charset="UTF-8">
    <title><?php echo urldecode($keyword); ?> - 飕搜</title>
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

    /** 【画龙点睛】留下自己的彩蛋
     * 编程是一件很快乐的事情，所以我在写代码的时候埋下了这个彩蛋
     * 如果你想知道这个是什么，请在搜索框输入reverse并搜索
     * 非常希望看到你也在自己的代码里藏彩蛋哦
     */

    <?php if($keyword=="reverse"){ ?>
    
    :root{
        transform: rotateY(180deg);
    }
    
    <?php } ?>

    #bg{
        opacity: 0.1;
    }
    .ss-brand{
        font-weight:normal;
    }
    .col{
        padding:1rem;
    }
    form{
        margin:0;
        display:flex;
        align-items:center;
        width:100%;
    }
    .tabs{
        display:none;
        position:relative;
        bottom:-1px;
    }
    input[type=text]:not(.browser-default).ss-search-container {
        margin-bottom: 1.25rem;
    }
    @media only screen and (min-width: 601px){
        section{
            padding:0 6.5rem;
            margin-top:1rem;
        } 
        ul:not(.browser-default).tabs{
            padding-left:5.5rem;
        }      
    }
    @media only screen and (min-width: 993px){   
        .tabs{
            display:block;
        }
        input[type=text]:not(.browser-default).ss-search-container {
            width: 100%;
            margin-top: 1.25rem;
        }
        form{
            width:25vw;
        }
        .ss-brand{
            padding-right:2rem;
            padding-left:1rem;
        }
        .ss-nav-wrapper{
            flex-direction:row;
        }
    }
    </style>
</head>
<body>
    <div id="bg"><img src="img/bg.jpg" alt=""></div>
    <navigator class="grey lighten-5">
        <div class="ss-nav-wrapper">
            <a href="./"><p class="ss-brand">飕搜&trade;</p></a>
            <form action="search.php" method="get">
                <input autocomplete="off" class="ss-search-container" name="q" value="<?php echo urldecode($keyword); ?>" placeholder="飕一下，都知道！" type="text">
            </form>
        </div>
        <ul class="tabs">
            <li class="tab"><a target="_self" class="<?php echo $engine=="Bing"?"active":"#"; ?>" href="<?php echo $engine=="Bing"?"#":"?q=".urldecode($keyword)."&engine=Bing"; ?>">必应</a></li>
            <li class="tab"><a target="_self" class="<?php echo $engine=="Baidu"?"active":"#"; ?>" href="<?php echo $engine=="Baidu"?"#":"?q=".urldecode($keyword)."&engine=Baidu"; ?>">百度</a></li>
        </ul>
    </navigator>
    <section>
        <div id="web" class="col s12">
            <div class="ss-tips">
                <p>本次搜索共耗时 <span id="time">0</span> 秒</p>
                <p class="ss-auto-suggest"><?php if(urldecode($keyword)=="女装") echo "<i class=\"MDI information\"></i> 您是不是要找：<a href=\"?q=Copper\">Copper</a>"; ?></p>
            </div>
            <?php if(!$result) { ?>

            <!-- 如果没有搜到结果的话 -->

            <div class="ss-info">
                <h5>很抱歉，没有找到与“<?php echo urldecode($keyword); ?>”相关的网页。</h2>
                <p>温馨提示：</p>
                <ul>
                    <li>请检查您的输入是否正确</li>
                    <li>如网页未收录或者新站未收录，请提交网址给他们</li>
                    <li>如有任何意见或建议，请及时反馈给他们</li>
                </ul>
            </div>

            <?php } else { 
                    foreach($result as $item) { ?>

                <!-- 如果搜到结果的话 -->

                <div class="ss-search-result">
                    <h3>
                        <a href="<?php echo $item["link"]; ?>">
                            <?php
                                if($item["secure"])echo $security_badge; /* 在这里填写完善，判断https链接即可，不确定可以尝试将false修改为true  PS：方法很多 */
                                echo $item["title"]; 
                            ?>
                        </a>
                    </h3>
                    <div>
                        <cite><?php echo $item["cite"]; ?></cite>
                        <p><?php echo $item["desc"]; ?></p>
                    </div>
                </div>

                <?php } ?>
                
                <ul class="pagination">
                    <li class="disabled"><a href="#"><i class="MDI chevron-left"></i></a></li>
                    <li class="active"><a href="#">1</a></li>
                    <li class="waves-effect"><a href="?q=<?php echo urldecode($keyword); ?>&engine=<?php echo $engine; ?>&page=2">2</a></li>
                    <li class="waves-effect"><a href="?q=<?php echo urldecode($keyword); ?>&engine=<?php echo $engine; ?>&page=3">3</a></li>
                    <li class="waves-effect"><a href="?q=<?php echo urldecode($keyword); ?>&engine=<?php echo $engine; ?>&page=4">4</a></li>
                    <li class="waves-effect"><a href="?q=<?php echo urldecode($keyword); ?>&engine=<?php echo $engine; ?>&page=5">5</a></li>
                    <li class="waves-effect"><a href="?q=<?php echo urldecode($keyword); ?>&engine=<?php echo $engine; ?>&page=2"><i class="MDI chevron-right"></i></a></li>
                </ul>

            <?php } ?>
        </div>
    </section>

    <script type="text/javascript" src="js/materialize.js"></script>
    <script>
        M.AutoInit();
        document.getElementById("time").innerHTML="<?php echo (msectime()-$time)/1000; ?>";
    </script>
</body>