<?php

ini_set('log_errors','on');
ini_set('error_log','php.log');
session_start();

require('function.php');

//年月日表示用関数
if(empty($_SESSION) || empty($firstTime)){
    $firstTime = '20191201080000';  //初期設定値 文字列型
}
$tmp = strtotime($firstTime);
$week = array('日','月','火','水','木','金','土');
$weekNumber = date('w');

//インスタンス格納用変数
    $shops = array();   //店舗一覧
    $homes = array();   //住居一覧
    $customers = array();    //住人一覧
    $areas = array();    //配送地域一覧

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
    const Positive_cust2 = おおきに！;
    const Positive_cust3 = お疲れ様。配送頑張ってね！;
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
         if($num3 < 0){
             $num3 = 0;
         }
         $this->hp = $num3;
     }
     public function getHp(){
         return $this->hp;
     }
     public function getFaceImg(){
         return $this->faceimg;
     }
     public function setHungry($num5){
        if($num5 < 0){
            $num5 = 0;
        }
         $this->hungry = $num5;
     }
     public function getHungry(){
         return $this->hungry;
     }
     public function setToilet($num7){
        if($num7 < 0){
            $num7 = 0;
        }
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
         $this->passion = $num9;
     }
     public function getPassion(){
         return $this->passion;
     }
     public function setBike($num10){
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
$shops[] = new Shop( 'タピオカ屋', 'img/shop02.jpeg', 1500, Item::LIGHT, 'タピオカミルクティー');
$shops[] = new Shop( '吉野家', 'img/shop03.jpg', 1200, Item::LIGHT, '牛丼');
$shops[] = new Shop( '筋肉食堂', 'img/shop04.jpg', 1500, Item::MIDDLE, '日替わり弁当');
$shops[] = new Shop( '松屋', 'img/shop05.jpg', 1200, Item::MIDDLE, '牛めし');
$shops[] = new Shop( 'オリジン弁当', 'img/shop06.jpg', 1800, Item::MIDDLE, '幕内弁当');
$shops[] = new Shop( 'ゴーゴーカレー', 'img/shop07.jpg', 1800, Item::MIDDLE, 'メジャーカレー');
//住宅一覧
$homes[] = new Home( '木造住宅', 'img/home01.jpg', 1000, HOUSE::WOOD);
$homes[] = new Home( '鉄骨住宅', 'img/home02.jpg', 1500, HOUSE::RC);
$homes[] = new Home( '高級住宅', 'img/home03.jpg', 1800, HOUSE::SRC);
$homes[] = new Home( 'タワーマンション', 'img/home04.jpg', 2500, HOUSE::TOWER);
//住人一覧
$customers[] = new Customer( '20代社会人', Sex::MAN, 'img/customer01.jpg');
$customers[] = new Customer( '20代社会人', Sex::WOMAN, 'img/customer02.png');
$customers[] = new Customer( '大学生', Sex::MAN, 'img/customer03.jpg');
$customers[] = new Customer( '女子大生', Sex::WOMAN, 'img/customer04.jpg');
$customers[] = new Customer( '30代社会人', Sex::MAN, 'img/customer05.jpg');
$customers[] = new Customer( '30代社会人', Sex::WOMAN, 'img/customer06.jpg');
$customers[] = new Customer( '40代社会人', Sex::MAN, 'img/customer07.jpg');
$customers[] = new Customer( '40代社会人', Sex::WOMAN, 'img/customer08.jpg');
//エリア一覧
$areas[] = new Area( '新宿', 'img/area01.jpg', 15, 10, 15);
$areas[] = new Area( '表参道', 'img/area02.jpg', 20, 15, 20);
$areas[] = new Area( '渋谷', 'img/area03.jpg', 15, 10, 15);
$areas[] = new Area( '恵比寿', 'img/area04.jpg', 15, 10, 15);
$areas[] = new Area( '中目黒', 'img/area05.jpg', 20, 10, 20);
$areas[] = new Area( '五反田', 'img/area06.jpg', 20, 10, 15);
$areas[] = new Area( '品川', 'img/area07.jpg', 25, 10, 20);
$areas[] = new Area( '田町', 'img/area08.jpg', 25, 10, 20);
$areas[] = new Area( '新橋', 'img/area09.jpg', 20, 15, 20);
$areas[] = new Area( '赤坂', 'img/area10.jpg', 10, 15, 15);
$areas[] = new Area( '六本木', 'img/area11.jpg', 15, 10, 15);
$areas[] = new Area( '麻布十番', 'img/area12.jpg', 15, 10, 15);
$areas[] = new Area( '青山一丁目', 'img/area13.jpg', 20, 15, 25);
$areas[] = new Area( '神宮前', 'img/area14.jpg', 20, 20, 20);
$areas[] = new Area( '代々木', 'img/area15.jpg', 15, 10, 15);


function moving(){  //店舗係数、建物係数、荷物係数、エリア係数
    //移動距離の設計
    if(!empty($pickFlg)){
        createArea();
        //$_SESSION['distance'] = $_SESSION['shop']->getDistance();   //集荷
        $_SESSION['distance'] = 1500;
    }else{
        createArea();
        //$_SESSION['distance'] = $_SESSION['homes']->getDistance();  //配送
        $_SESSION['distance'] = 1500;
    }
    //移動距離をランダムに増減させて合計から差し引く
    //体力低下(40配送で死亡、1配送で2.5P、1移動で1.25P減る)
    $_SESSION['driver']->setHp($_SESSION['driver']->getHp() - $_SESSION['distance']/1500 * 1.25); //移動距離*2.5ポイント
    //時間経過（1配送で20分、1移動毎に平均10分経過する）
        //編集中
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
    //debug('配達員データ：'.print_r($_SESSION['driver'],true));

}
function createShop(){
    global $shops;
    //debug('$shopsデータ：'.print_r($shops,true));
    $shop = $shops[mt_rand(0,1)];
    //debug('$shopデータ：'.print_r($shop,true));
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
    $customer = $customers[mt_rand(0,1)];
    $_SESSION['customer'] = $customer;
    History::set($_SESSION['customer']->getName().'が玄関口から現れた！！');
}
function createArea(){
    global $areas;
    $area = $areas[mt_rand(0,14)];
    $_SESSION['area'] = $area;
}
function getMoney(){
    $_SESSION['yen'] = $_SESSION['yen'] + 500;
}

function init(){
    History::clear();
    History::set('配達の仕事を開始します！');
    createDriver();
    createShop();
    createArea();
    $_SESSION['DriveryCount'] = 0;  //配達回数
    $_SESSION['yen'] = 0;   //本日の売上
    $_SESSION['bike'] = 50; //自転車電池残量
    $_SESSION['manpuku'] = 100;     //満腹度
    $_SESSION['toilet'] = 100;  //トイレ安全度
    $_SESSION['driver']->setPassion(100);    //やる気初期化
    $_SESSION['driver']->setHp(100);    //HP初期化
    $_SESSION['driver']->setHungry(100);    //満腹度初期化
    $_SESSION['driver']->setToilet(100);    //トイレ危険度初期化
    $_SESSION['driver']->setBike(50);      //バイク残量初期化
}
function gameOver(){
    $_SESSION = array();
}
  

//1.post送信されていた場合
History::clear();
if(!empty($_POST)){
    $startFlg = (!empty($_POST['start'])) ? true : false;   //初回スタート
    $pickFlg = (!empty($_POST['pick'])) ? true : false;   //集荷
    $transFlg = (!empty($_POST['transport'])) ? true : false;   //配達
    $cycleFlg = (!empty($_POST['cycle'])) ? true : false;   //駐輪場
    $combiFlg = (!empty($_POST['combi'])) ? true : false;   //コンビニ
    $parkFlg = (!empty($_POST['park'])) ? true : false; //公園
    $eatFlg = (!empty($_POST['eat'])) ? true : false;   //飲食店
    $homeFlg = (!empty($_POST['home'])) ? true :false;  //帰宅
    error_log('POSTされた！');

    if($startFlg){
        History::set('配達をスタートします！');
        init();
    }else{
        //集荷を押した場合
        if($pickFlg){
            History::set($_SESSION['shop']->getSpotName().'の集荷に訪れた！');
            moving();   //移動
        }else if($transFlg){
        //配達を押した場合
            createHome();   //建物決定
            createCustomer();   //お客さん属性決定
            moving();    //移動
            getMoney(); //売上金額加算
            History::set($_SESSION['shop']->getItemName().'の配送を完了した！');
            createShop();
            $_SESSION['DriveryCount'] = $_SESSION['DriveryCount']+1;

         //条件を満たした場合（体力ゼロ）はゲームオーバーとする
         //24時を回った場合は日付を翌日の朝8時まで進める

        }else if($cycleFlg){
            History::set('駐輪所に到着した！');
            //配達員が消耗をする（体力、やる気、空腹、トイレ、電池残量悪化）
            //一定確率で自転車の電池残量を回復し、一定確率で分岐させる
        }else if($combiFlg){
            History::set('コンビニに寄った！');
            //配達員が消耗をする（体力、やる気、空腹、トイレ、電池残量悪化）
            //トイレを借りて、トイレとやる気を回復させる
            //買うものをランダムに選択して、お金を失わせ、空腹を回復させる
        }else if($parkFlg){
            History::set('公園に到着した！');
            //配達員が消耗をする（体力、やる気、空腹、トイレ、電池残量悪化）
            //一定確率で自転車の電池残量を回復し、一定確率で分岐させる
        }else if($eatFlg){
            History::set($shops[mt_rand(0,6)]->getSpotName().'に到着し、ご飯を食べた！');
            History::set('宇羽太郎の体力とやる気が回復した！');
            History::set('トイレも借りて用を足した！');
            $_SESSION['toilet'] = 100;
            //配達員が消耗をする（体力、やる気、空腹、トイレ、電池残量悪化）
            //水分と空腹を回復させる、やる気も急増する
        }else if($homeFlg){
            History::set('今日は早めに帰って寝よう');
            //体力を大幅に回復し、やる気、空腹、トイレ、自転車も全回復
            //日付を進め、時間も開始時間に変更する
            //新たな集荷を発生させる init関数に引数を入れて条件分岐させる
        }else{
            //リセットを押してゲーム初期化
            init();
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
    <div class="main">
      <?php if(empty($_SESSION)){ ?>
      <div class="top__image">
        <header>
            <h1 class="subject">配達シュミレータ</h1>
            <h2>GAME START ?</h2>
            <div class="top__button">
                <form method="post">
                <input type="submit" name="start" value="▶ゲームスタート">
                </form>
            </div>
        </header>
      </div>
      <?php }else{ ?>
        <header class="header">
            <div class="subject"><h1>配達シュミレータ</h1></div>
            <div class="header__clock"><?php echo date('H時i分',$tmp); ?></div>
            <div class="header__date">
                <?php echo date('Y年m月d日',$tmp); ?>(<?php echo $week[$weekNumber]; ?>)
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
                　　　　本日の売上金：<?php echo $_SESSION['yen']?>円</div>
            </div>
            <div class="infomation__wrap">
                <div class="informaiton__w-driver_picture">
                    <div>
                        <img class="prof_image" src="<?php echo $_SESSION['driver']->getFaceImg(); ?>">
                    </div>
                </div>
                <div class="information__w-change_picture">
                    <div>
                        <img class="prof_image" src="<?php echo $_SESSION['shop']->getSpotImg(); ?>">
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
        <footer class="footer">
            <div class="footer__command">
                <form method="post" class="footer__command-post">
                    <table class="footer__command-table"><tbody>
                        <tr>
                            <?php
                            if(empty($pickFlg)){
                            ?>
                                <td class="cell"><input type="submit" name="pick" value="集荷" class="cell"></td>
                            <?php
                            }else{
                            ?>
                                <td class="cell"><input type="submit" name="transport" value="配達" class="cell"></td>
                            <?php
                            } 
                            ?>
                            <td class="cell"><input type="submit" name="cycle" value="駐輪所"></td>
                            <td class="cell"><input type="submit" name="combi" value="コンビニ"></td>
                            <td class="cell"><input type="submit" name="park" value="公園"></td>
                        </tr>
                        <tr>
                            <td class="cell"><input type="submit" name="eat" value="飲食店"></td>
                            <td class="cell"><input type="submit" name="home" value="帰宅"></td>
                            <td class="cell"><input type="submit" name="move" value="移動"></td>
                            <td class="cell"><input type="submit" name="reset" value="リセット"></td>
                        </tr>
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
