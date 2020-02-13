<?php

ini_set('log_errors','on');
ini_set('error_log','php.log');
session_start();

require('function.php');

//初期状態の年月日表示用関数
if((!empty($_SESSION)) && empty($_SESSION['tmp'])){
    $firstTime = '20191201080000';  //初期設定値 文字列型
    $_SESSION['tmp'] = strtotime($firstTime);
}
$week = array('日','月','火','水','木','金','土');
$weekNumber = date('w', $_SESSION['tmp']);

//インスタンス格納用配列
    $shops = array();   //店舗一覧
    $homes = array();   //住居一覧
    $customers = array();    //住人一覧
    $areas = array();    //配送地域一覧

//フラグ変数
    $pickFlg = "";
    $transFlg = "";

class Sex{
    const MAN = 1;
    const WOMAN = 2;
}
class Item{
    const LIGHT = 1;
    const MIDDLE = 2;
    const HEAVY = 3;
}
class MESSAGE{
    const Positive_Deli1 = こんにちは！UBERです;
    const Positive_Deli2 = どうもです！UBERです;
    const Positive_Deli3 = 毎度です！UBERです！;
    const Positive_shop1 = 配達お願いします！;
    const Positive_shop2 = よろしくお願いします！;
    const Positive_cust1 = ありがとう！;
    const Positive_cust2 = おおきにっ！;
    const Positive_cust3 = 配送頑張って！;
}
class HOUSE{
    const WOOD = 1;
    const RC = 2;
    const SRC = 3;
    const TOWER = 4;
}

abstract Class Human{ 
    abstract public function SayMessage();
    protected $name;
    protected $sex;
    public function setName($str){
        $this->name = $str;
    }
    public function getName(){
        return $this->name;
    }
    public function setSex($num){
        $this->sex = $num;
    }
    public function getSex(){
        return $this->sex;
    }
}

Class Driver extends Human{ 
     protected $clock;  //現在時刻
     protected $hp;   //体力
     protected $faceimg;  //配達員画像
     protected $hungry;   //満腹度
     protected $toilet;   //トイレ危険度
     protected $money;    //所持金
     protected $passion;  //やる気
     protected $bike;   //自転車電池残量
     public function SayMessage(){
     }
     public function __construct($name, $sex, $clock, $hp, $faceimg, $hungry, $toilet, $money, $passion, $bike){
        $this->name = $name;
        $this->sex = $sex;
        $this->clock = $clock;
        $this->hp  = $hp;
        $this->faceimg  = $faceimg;
        $this->hungry = $hungry;
        $this->toilet  = $toilet;
        $this->money  = $money;
        $this->passion = $passion;
        $this->bike = $bike;
     }
     public function setClock($num2){
         $this->clock = $num2;
     }
     public function getClock($num2){
         return $this->clock;
     }
     public function setHp($num3){
        if($num3 < 0){ $num3 = 0;}
        if($num3 > 100){ $num3 = 100; }
         $this->hp = $num3;
     }
     public function getHp(){
         return $this->hp;
     }
     public function getFaceImg(){
         return $this->faceimg;
     }
     public function setHungry($num5){
        if($num5 < 0){ $num5 = 0;}
        if($num5 > 100){ $num5 = 100; }
         $this->hungry = $num5;
     }
     public function getHungry(){
         return $this->hungry;
     }
     public function setToilet($num7){
        if($num7 < 0){
            $num7 = 0;
        }
        $this->toilet = $num7;
     }
     public function getToilet(){
         return $this->toilet;
     }
     public function setMoney($num8){
         $this->toilet = $num8;
     }
     public function getMoney(){
         return $this->money;
     }
     public function setPassion($num9){
        if($num9 < 0){ $num9 = 0;}
        if($num9 > 100){ $num9 = 100; }
         $this->passion = $num9;
     }
     public function getPassion(){
         return $this->passion;
     }
     public function setBike($num10){
         if($num10 <0){
             $num10 = 0;
         }
         $this->bike = $num10;
     }
     public function getBike(){
         return $this->bike;
     }
 }

Class Customer extends Human{
    protected $img;
    public function SayMessage(){
    }
    public function __construct($name, $sex, $img){
        $this->name = $name;
        $this->sex = $sex;
        $this->img = $img;
    }
    public function getImg(){
        return $this->img;
    }
}

abstract class Building{
    public $spotName; 
    public $spotImg;
    public $distance;   //到達までに必要な基本距離(m)
    public function setSpotName($num11){
        $this->spotName = $num11;
    }
    public function getSpotName(){
        return $this->spotName;
    }
    public function getSpotImg(){
        return $this->spotImg;
    }
    public function getDistance(){
        return $this->distance;
    }
}

Class Shop extends Building{
    protected $itemWeight;
    protected $itemName; //渡す商品の名前
    public function __construct($spotName, $spotImg, $distance, $itemWeight, $itemName){
        $this->spotName = $spotName;
        $this->spotImg = $spotImg;
        $this->distance = $distance;
        $this->itemWeight = $itemWeight;
        $this->itemName = $itemName;
        }
    public function getItemName(){
        return $this->itemName;
    }
}

Class Home extends Building{
    protected $howHouse; //住宅の種類
    public function getHowHouse(){
        return $this->howHouse;
    }
}

Class Area{
    protected $areaName;
    protected $areaImg;
    //時間毎に注文毎にかかる時間
    protected $orderTime_morning;
    protected $orderTime_evening;
    protected $orderTime_night;
    public function __construct($areaName, $areaImg, $orderTime_morning, $orderTime_evening, $orderTime_night){
        $this->areaName = $areaName;
        $this->areaImg = $areaImg;
        $this->morning = $orderTime_morning;
        $this->evening = $orderTime_evening;
        $this->night = $orderTime_night;
    }
    public function getAreaName(){
        return $this->areaName;
    }
    public function getAreaImg(){
        return $this->areaImg;
    }
    public function getMorning(){
        return $this->orderTime_morning;
    }
    public function getEvening(){
        return $this->orderTime_evening;
    }
    public function getNight(){
        return $this->orderTime_night;
    }

}

//履歴管理クラス
class History{
    public static function set($str){
        if(empty($_SESSION['history'])) $_SESSION['history'] = '';
        $_SESSION['history'] .= $str.'<br>';
    }
    public static function setNotBr($str){
        if(empty($_SESSION['history'])) $_SESSION['history'] = '';
        $_SESSION['history'] .= $str;
    }
    public static function clear(){
        unset($_SESSION['history']);
    }
}

//インスタンス生成
//配達員
$driver = new Driver('宇羽太郎', Sex::MAN, 30, 100, 'img/driver01.png', 100, 100, 30, 100, 50);
//ショップ一覧
$shops[] = new Shop( 'マクドナルド', 'img/shop01.jpeg', 1000, Item::LIGHT, 'ハンバーガー');
$shops[] = new Shop( 'タピオカ屋', 'img/shop02.jpeg', 1500, Item::LIGHT, 'タピオカ茶');
$shops[] = new Shop( '吉野家', 'img/shop03.jpg', 1200, Item::LIGHT, '牛丼');
$shops[] = new Shop( '筋肉食堂', 'img/shop04.jpg', 1500, Item::MIDDLE, '日替わり弁当');
$shops[] = new Shop( '松屋', 'img/shop05.jpg', 1200, Item::MIDDLE, '牛めし');
$shops[] = new Shop( 'オリジン弁当', 'img/shop06.jpg', 1800, Item::MIDDLE, '幕ノ内弁当');
$shops[] = new Shop( 'ゴーゴーカレー', 'img/shop07.jpg', 1800, Item::MIDDLE, 'メジャーカレー');
//住宅一覧
$homes[] = new Home( '木造住宅', 'img/home01.jpg', 1050, HOUSE::WOOD);
$homes[] = new Home( '鉄骨住宅', 'img/home02.jpg', 1550, HOUSE::RC);
$homes[] = new Home( '高級住宅', 'img/home03.jpg', 1850, HOUSE::SRC);
$homes[] = new Home( 'タワマン', 'img/home04.jpg', 2550, HOUSE::TOWER);
//住人一覧
$customers[] = new Customer( '20代の男性', Sex::MAN, 'img/customer01.png');
$customers[] = new Customer( '20代の女性', Sex::WOMAN, 'img/customer02.png');
$customers[] = new Customer( '大学生', Sex::MAN, 'img/customer03.png');
$customers[] = new Customer( '女子大生', Sex::WOMAN, 'img/customer04.png');
$customers[] = new Customer( '30代の男性', Sex::MAN, 'img/customer05.png');
$customers[] = new Customer( '30代の女性', Sex::WOMAN, 'img/customer06.png');
$customers[] = new Customer( '40代の男性', Sex::MAN, 'img/customer07.png');
$customers[] = new Customer( '40代の女性', Sex::WOMAN, 'img/customer08.png');
//エリア一覧
$areas[] = new Area( '新宿', 'img/area01.jpg', 1, 0.9, 1);
$areas[] = new Area( '表参道', 'img/area02.jpg', 1.1, 1, 1.1);
$areas[] = new Area( '渋谷', 'img/area03.jpg', 1, 0.9, 1);
$areas[] = new Area( '恵比寿', 'img/area04.jpg', 1, 0.9, 1);
$areas[] = new Area( '中目黒', 'img/area05.jpg', 1.1, 0.9, 1.1);
$areas[] = new Area( '五反田', 'img/area06.jpg', 1.1, 0.9, 1);
$areas[] = new Area( '品川', 'img/area07.jpg', 1.2, 0.9, 1.1);
$areas[] = new Area( '田町', 'img/area08.jpg', 1.2, 0.9, 1.1);
$areas[] = new Area( '新橋', 'img/area09.jpg', 1.1, 1, 1.1);
$areas[] = new Area( '赤坂', 'img/area10.jpg', 0.85, 1, 1);
$areas[] = new Area( '六本木', 'img/area11.jpg', 1, 0.9, 1);
$areas[] = new Area( '麻布十番', 'img/area12.jpg', 1, 0.9, 1);
$areas[] = new Area( '青山一丁目', 'img/area13.jpg', 1.1, 1, 1.2);
$areas[] = new Area( '神宮前', 'img/area14.jpg', 1.1, 1.1, 1.1);
$areas[] = new Area( '代々木', 'img/area15.jpg', 1, 0.9, 1);


function moving(){  //店舗係数、建物係数、荷物係数、エリア係数
    //移動距離の設計
    if(!empty($pickFlg)){
        createArea();
        $_SESSION['distance'] = $_SESSION['shop']->getDistance();   //集荷
        debug('現在の距離データ（集荷）：'.print_r($_SESSION['distance'],true));
    }else if(!empty($transFlg)){
        createArea();
        $_SESSION['distance'] = $_SESSION['home']->getDistance();  //配送
        debug('現在の距離データ（配送）：'.print_r($_SESSION['distance'],true));
    }
    //やる気の値によって計算距離を上下させる
    if($_SESSION['driver']->getPassion() >= 80){
        $_SESSION['distance'] = $_SESSION['distance'] * 0.9;
    }else if($_SESSION['driver']->getPassion() < 80 && $_SESSION['driver']->getPassion() >= 60){
        $_SESSION['distance'] = $_SESSION['distance'] * 0.95;
    }else if($_SESSION['driver']->getPassion() < 60 && $_SESSION['driver']->getPassion() >= 40){
        $_SESSION['distance'] = $_SESSION['distance'] * 1;
    }else if($_SESSION['driver']->getPassion() < 40 && $_SESSION['driver']->getPassion() >= 20){
        $_SESSION['distance'] = $_SESSION['distance'] * 1.05;
    }else{
        $_SESSION['distance'] = $_SESSION['distance'] * 1.1;
    }
    //体力低下(40配送で死亡、1配送で2.5P、1移動で1.25P減)
    $_SESSION['driver']->setHp($_SESSION['driver']->getHp() - $_SESSION['distance']/1500 * 1.25); //移動距離*2.5ポイント
        //満腹度がゼロの場合、追加で15Pの体力を消費
        if($_SESSION['driver']->getHungry() === 0){ 
            $_SESSION['driver']->setHp($_SESSION['driver']->getHp() - 15);
        }
    //時間経過（1配送で20分、1移動毎に平均10分経過）
    $_SESSION['tmp'] += $_SESSION['distance']/1500 * 10 * 60;
        //満腹度がゼロの場合、所用時間を1.5倍に増加させる
        if($_SESSION['driver']->getHungry() === 0){ 
            $_SESSION['tmp'] += $_SESSION['distance']/1500 * 10 * 60 * 0.5;
        }
        //トイレ係数がゼロの場合、所用時間を2倍に増加させる
        if($_SESSION['driver']->getToilet() === 0){ 
            $_SESSION['tmp'] += $_SESSION['distance']/1500 * 10 * 60;
        }
    //やる気DOWN（40配送でやる気ゼロ、1配送毎に2.5%、1移動毎に1.25%低下）
    $_SESSION['driver']->setPassion($_SESSION['driver']->getPassion() - $_SESSION['distance']/1500 * 1.25);
    //満腹度DOWN（20配送で空腹、1配送毎に平均5%、1移動毎に2.5%低下）
    $_SESSION['driver']->setHungry($_SESSION['driver']->getHungry() - $_SESSION['distance']/1500 * 2.5);
    //トイレ係数DOWN(36km12配送で臨界点、1配送毎に平均6%、1移動毎に3%低下)
    $_SESSION['driver']->setToilet($_SESSION['driver']->getToilet() - $_SESSION['distance']/1500 * 3);
    //自転車電池残量DOWN(1配送で3km、1移動毎に1.5km低下)
    $_SESSION['driver']->setBike($_SESSION['driver']->getBike() - $_SESSION['distance']/1500 * 1.5);
}

function createDriver(){
    global $driver;
    $_SESSION['driver'] = $driver;
}
function createShop(){
    global $shops;
    $shop = $shops[mt_rand(0,6)];
    $transFlg = "";
    History::set('アプリから注文が入りました！');
    $_SESSION['shop'] = $shop;
    $_SESSION['distance'] = $_SESSION['shop']->getDistance();
}
function createHome(){
    global $homes;
    $home = $homes[mt_rand(0,1)];
    $_SESSION['home'] = $home;
}

function createCustomer(){
    global $customers;
    $customer = $customers[mt_rand(0,7)];
    $_SESSION['customer'] = $customer;
    History::set($_SESSION['customer']->getName().'が玄関口から現れた！！');
}
function createArea(){
    global $areas;
    $area = $areas[mt_rand(0,14)];
    $_SESSION['area'] = $area;
}
function getMoney(){
    $_SESSION['yen'] = $_SESSION['yen'] + $_SESSION['distance'] / 1500 * 500;
}
function SosComment(){
    if($_SESSION['driver']->getHungry() === 0){
        History::set('空腹で倒れそうだ！何か食べないと死んでしまう！');
    }else if($_SESSION['driver']->getHungry() < 10){
        History::set('すごくお腹が空いてきた・・・。');
    }else if($_SESSION['driver']->getHungry() < 50){
        History::set('お腹が空いてきた・・・。');
    }else if($_SESSION['driver']->getToilet() === 0){
        History::set('まずい、もう漏れてしまいそうだ！');
        History::set('配送に大幅な遅れが発生する状態です！');
    }else if($_SESSION['driver']->getToilet() < 10){
        History::set('すごくトイレに行きたくなってきた・・・。');
    }else if($_SESSION['driver']->getToilet() < 50){
        History::set('トイレに行きたくなってきた・・・。');
    }
}

function nextDay(){
    $_SESSION['tmp'] = strtotime('+1 day', $_SESSION['tmp']);
    $time_target = '08:00:00';
    $_SESSION['tmp'] = strtotime($time_target);
}

function init(){
    History::clear();
    History::set('配達の仕事を開始します！');
    createDriver();
    createShop();
    createArea();
    $_SESSION['DriveryCount'] = 0;  //配達回数
    $_SESSION['yen'] = 0;   //本日の売上
    $_SESSION['driver']->setPassion(100);    //やる気初期化
    $_SESSION['driver']->setHp(100);    //HP初期化
    $_SESSION['driver']->setHungry(100);    //満腹度初期化
    $_SESSION['driver']->setToilet(100);    //トイレ危険度初期化
    $_SESSION['driver']->setBike(50);      //バイク残量初期化
    $firstTime = '20191201080000';  //初期設定値 文字列型
    $_SESSION['tmp'] = strtotime($firstTime);
}

function preGameOver(){
    $_SESSION['gameover'] = "";
    History::clear();
    History::set('体力が尽き、宇羽太郎は救急車で搬送された！');
    History::set('その後、彼の行方を知る者は、誰もいなかった…。');
}

function gameOver(){
    $_SESSION = array();
}

History::clear();


//post送信されていた場合
if(!empty($_POST)){
    $startFlg = (!empty($_POST['start'])) ? true : false;   //初回スタート
    $pickFlg = (!empty($_POST['pick'])) ? true : false;   //集荷
    $transFlg = (!empty($_POST['transport'])) ? true : false;   //配達
    $cycleFlg = (!empty($_POST['cycle'])) ? true : false;   //駐輪場
    $combiFlg = (!empty($_POST['combi'])) ? true : false;   //コンビニ
    $parkFlg = (!empty($_POST['park'])) ? true : false; //公園
    $eatFlg = (!empty($_POST['eat'])) ? true : false;   //飲食店
    $homeFlg = (!empty($_POST['home'])) ? true :false;  //帰宅
    $moveFlg = (!empty($_POST['move'])) ? true :false;  //エリアの移動
    $resetFlg = (!empty($_POST['reset'])) ? true :false;  //リセットボタン
    error_log('POSTされた！');
    debug('$startFlg情報：'.print_r($startFlg,true));
    debug('$pickFlg情報：'.print_r($pickFlg,true));
    debug('$transFlg情報：'.print_r($transFlg,true));

    if($startFlg){
        History::set('配達をスタートします！');
        init();
    }else{
        //集荷を押した場合
        if($pickFlg){
            History::set($_SESSION['shop']->getSpotName().'の集荷に訪れた！');
            moving();
            SosComment();
        //配達を押した場合
        }else if($transFlg){
            createHome();
            createCustomer();
            moving();
            getMoney();
            History::set($_SESSION['shop']->getItemName().'の配送を完了した！(報酬'.ceil($_SESSION['distance'] / 1500 * 500).'円)');
            createShop();
            $_SESSION['DriveryCount'] = $_SESSION['DriveryCount']+1;

            //条件を満たした場合（体力ゼロ）はゲームオーバーとする
            if($_SESSION['driver']->getHp() <= 0){
                preGameOver();
            }
            //12時間経過した場合は日付を翌日の朝8時まで進める

        }else if($cycleFlg){
            History::set('駐輪所に到着した！');
            moving();
            $_SESSION['driver']->setBike(50);
            History::set('自転車を交換し、電池の残量が満タンになった！');

        }else if($combiFlg){
            History::set('コンビニに寄った！');
            moving();
            History::set('トイレを借りて用を足した！');
            $_SESSION['driver']->setToilet(100);
        }else if($parkFlg){
            History::set('公園に到着した！');
            moving(); 
            History::set('休憩して体力とやる気が回復した！');
            History::set('トイレも借りて用を足した！');
            $_SESSION['driver']->setToilet(100);
            $_SESSION['driver']->setHp($_SESSION['driver']->getHp() + 25);
            $_SESSION['driver']->setPassion($_SESSION['driver']->getPassion() + 25);
        }else if($eatFlg){
            moving();
            History::set($shops[mt_rand(0,6)]->getSpotName().'に到着し、ご飯を食べた！');
            History::set('宇羽太郎の体力とやる気が回復した！');
            History::set('トイレも借りて用を足した！');
            $_SESSION['driver']->setToilet(100);
            $_SESSION['driver']->setHungry($_SESSION['driver']->getHungry() + 50);
            $_SESSION['driver']->setPassion($_SESSION['driver']->getPassion() + 50);
        }else if($homeFlg){
            History::set('「今日は早めに帰って寝よう!」');
            History::set('宇羽太郎の体力とやる気が回復した！');
            History::set('夜が明け、新しい朝がやってきた！');
            $_SESSION['driver']->setHp($_SESSION['driver']->getHp() + 50);
            $_SESSION['driver']->setToilet(100);
            $_SESSION['driver']->setBike(50);
            $_SESSION['driver']->setHungry(100);
            $_SESSION['driver']->setPassion(100);
            $_SESSION['driver']->setHp(50);
            //日付を進め、時間も開始時間に変更する
            //新たな集荷を発生させる init関数に引数を入れて条件分岐させる
        }else if($moveFlg){
            History::set('エリアを移動した！');
            moving();
            createArea();
            History::set($_SESSION['area']->getAreaName().'に到着した！');
        }else if($resetFlg){
            //リセットを押してゲーム初期化
            init();
        }

        if($_SESSION['driver']->getHp() <= 0){
            preGameOver();
        }

    } 
    //トップ画面に戻す
    $_POST = array();
}

?>

<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <link rel="stylesheet" type="text/css" href="style.css">
    <title>配達員シュミレーター</title>
  </head>

  <body>
    <div id="l-main">
      <?php if(empty($_SESSION)){ ?>
        <header id="l-header">
            <div class="l-header__topImage">
                <form method="post">
                    <input type="submit" name="start" class="p-header-btn">
                </form>
            </div>
        </header>
      <?php }else{ ?>
        <header id="l-header">
            <div class="subject"><h1>配達シュミレータ</h1></div>
            <div class="header__clock"><?php echo date('H時i分',$_SESSION['tmp']); ?></div>
            <div class="header__date">
                <?php echo date('Y年m月d日',$_SESSION['tmp']); ?>(<?php echo $week[$weekNumber]; ?>)
            </div>
            <div class="header__physical"><span class="header__physical--name">やる気 </span>
                <?php
                if($_SESSION['driver']->getPassion() >= 80){
                    echo '<img src="img/yaruki01.gif" class="yaruki_gif">';
                }else if($_SESSION['driver']->getPassion() < 80 && $_SESSION['driver']->getPassion() >= 60){
                    echo '<img src="img/yaruki02.gif" class="yaruki_gif">';
                }else if($_SESSION['driver']->getPassion() < 60 && $_SESSION['driver']->getPassion() >= 40){
                    echo '<img src="img/yaruki03.gif" class="yaruki_gif">';
                }else if($_SESSION['driver']->getPassion() < 40 && $_SESSION['driver']->getPassion() >= 20){
                    echo '<img src="img/yaruki04.gif" class="yaruki_gif">';
                }else{
                    echo '<img src="img/yaruki05.gif" class="yaruki_gif">';
                }
                ?>
            </div>
        </header>
        <div class="infomation" style="
                background-image: url(<?php echo $_SESSION['area']->getAreaImg(); ?>);
                background-size: cover;
               ">
            <div class="infoation__town">
                <div class="information__town-name">
                    現在地 >> <?php echo $_SESSION['area']->getAreaName(); ?>
                　　　　本日の売上金：<?php echo ceil($_SESSION['yen']); ?>円</div>
            </div>
            <div class="infomation__wrap">
                <div class="informaiton__w-driver_picture">
                    <div>
                       <img class="prof_image" src="
                       <?php 
                       if ($_SESSION['driver']->getHp() === 0){ echo 'img/hansou.jpg';
                       }else if($_SESSION['driver']->getHungry() === 0){ echo 'img/driver03.png';
                        }else if(!empty($pickFlg)){ echo $_SESSION['driver']->getFaceImg();
                       }else if(!empty($transFlg)){ echo 'img/driver02.png'; 
                       }else if(!empty($homeFlg)){ echo 'img/home.jpg';
                       }else{ echo $_SESSION['driver']->getFaceImg(); } ?>">
                    </div>
                </div>
                <div class="information__w-change_picture">
                    <div>
                        <img class="prof_image" src="
                        <?php
                        if($_SESSION['driver']->getHp() === 0){ echo 'img/lost.jpg';
                        }else if(!empty($pickFlg)){ echo $_SESSION['shop']->getSpotImg();
                        }else if(!empty($startFlg)){echo $_SESSION['shop']->getSpotImg(); 
                        }else if(!empty($transFlg)){ echo $_SESSION['customer']->getImg();
                        }else if(!empty($parkFlg)){ echo 'img/park.jpg';
                        }else if(!empty($eatFlg)){ echo 'img/eat.jpg';
                        }else if(!empty($cycleFlg)){ echo 'img/bike.jpg';
                        }else if(!empty($combiFlg)){ echo 'img/combi.jpg';
                        }else if(!empty($homeFlg)){ echo 'img/sun.jpg';
                        } 
                        ?>
                        ">
                    </div>
                </div>
                <div class="information__w-status window">
                    <p class="status_sentence">配達数：<?php echo $_SESSION['DriveryCount']; ?></p>
                    <p class="status_sentence">体力：<?php echo ceil($_SESSION['driver']->getHp()); ?>/100</p>
                    <p class="status_sentence">満腹度：<?php echo ceil($_SESSION['driver']->getHungry()); ?>/100%</p>
                    <p class="status_sentence">トイレ：<?php echo ceil($_SESSION['driver']->getToilet()); ?>/100%</p>
                    <p class="status_sentence">自転車：<?php echo ceil($_SESSION['driver']->getBike()); ?>/50km</p>
                </div>
            </div>
            <div class="information__history window">
                <?php echo (!empty($_SESSION['history'])) ? $_SESSION['history'] : ''; ?>
            </div>
        </div>
        <footer id="l-footer">
            <div class="footer__command">
                <form method="post" class="footer__command-post">
                    <table class="footer__command-table"><tbody>
                        <?php
                        if($_SESSION['driver']->getHp() > 0){
                        ?>
                        <tr>
                            <?php
                            if(empty($pickFlg)){
                            ?>
                            <td><input type="submit" name="pick" value="集荷" class="cell"></td>
                            <?php
                            }else{
                            ?>
                            <td><input type="submit" name="transport" value="配達" class="cell"></td>
                            <?php
                            } 
                            ?>
                            <td><input type="submit" name="cycle" value="駐輪所" class="cell"></td>
                            <td><input type="submit" name="combi" value="コンビニ" class="cell"></td>
                            <td><input type="submit" name="park" value="公園" class="cell"></td>
                        </tr>
                        <tr>
                            <td><input type="submit" name="eat" value="飲食店" class="cell"></td>
                            <td><input type="submit" name="home" value="帰宅" class="cell"></td>
                            <td><input type="submit" name="move" value="移動" class="cell"></td>
                            <td><input type="submit" name="reset" value="リセット" class="cell"></td>
                        </tr>
                        <?php
                        }else{
                        ?>
                        <tr>
                            <td><input type="submit" name="reset" value="BadEnd" class="cell"></td>
                            <td><input type="submit" name="reset" value="BadEnd" class="cell"></td>
                            <td><input type="submit" name="reset" value="BadEnd" class="cell"></td>
                            <td><input type="submit" name="reset" value="BadEnd" class="cell"></td>
                        </tr>
                        <tr>
                            <td><input type="submit" name="reset" value="BadEnd" class="cell"></td>
                            <td><input type="submit" name="reset" value="BadEnd" class="cell"></td>
                            <td><input type="submit" name="reset" value="BadEnd" class="cell"></td>
                            <td><input type="submit" name="reset" value="BadEnd" class="cell"></td>
                        </tr>
                        <?php
                        }
                        ?>
                    </tbody></table>
                </form>
            </div>
            <div class="footer__history">

            </div>
      </footer>
      <?php } ?>
    </div>
  </body>
</html>
