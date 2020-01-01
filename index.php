<?php

ini_set('log_errors','on');
ini_set('error_log','php.log');
session_start();

require('function.php');

//年月日表示用関数
if(!empty($_SESSION)){
    $firstTime = '20191201080000';  //初期設定値 文字列型
}else{
}
$tmp = strtotime($firstTime);
$week = array('日','月','火','水','木','金','土');
$weekNumber = date('w');

//インスタンス格納用変数
    $shops = array();   //店舗一覧
    $homes = array();   //住居一覧
    $customers = array();    //住人一覧
    $areas = array();    //配送地域一覧

    global $rightImg;
    global $leftImg;


class Sex{
    const MAN = 1;
    const WOMAN = 2;
}
class Weather{
    const HARE = 1;
    const KUMORI = 2;
    const AME = 3;
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
     protected $power;    //筋力
     protected $faceimg;  //配達員画像
     protected $hungry;   //満腹度
     protected $water;    //喉の乾き
     protected $toilet;   //トイレ危険度
     protected $money;    //所持金
     protected $passion;  //やる気
     public function SayMessage(){
     }
     public function __construct($name, $sex, $clock, $hp, $power, $faceimg, $hungry, $water, $toilet, $money, $passion){
        $this->name = $name;
        $this->sex = $sex;
        $this->clock = $clock;
        $this->hp  = $hp;
        $this->power = $power;
        $this->faceimg  = $faceimg;
        $this->hungry = $hungry;
        $this->water  = $water;
        $this->toilet  = $toilet;
        $this->money  = $money;
        $this->passion = $passion;
     }
     public function setClock($num2){
         $this->clock = $num2;
     }
     public function getClock($num2){
         return $this->clock;
     }
     public function setHp($num3){
         $this->hp = $num3;
     }
     public function getHp(){
         return $this->hp;
     }
     public function setPower($num4){
         $this->power = $num4;
     }
     public function getPower(){
         return $this->power;
     }
     public function getFaceImg(){
         return $this->faceimg;
     }
     public function setHungry($num5){
         $this->hungry = $num5;
     }
     public function getHungry(){
         return $this->hungry;
     }
     public function setWater($num6){
         $this->water = $num6;
     }
     public function getWater(){
         return $this->water;
     }
     public function setToilet($num7){
         $this->toilet = $num7;
     }
     public function getToilet(){
         return $this->toilet;
     }
     public function setmoney($num8){
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
        //セッションhistoryが作られていなければ作る
        if(empty($_SESSION['history'])) $_SESSION['history'] = '';
        //文字列をセッションhistoryへ格納
        $_SESSION['history'] .= $str.'<br>';
    }
    public static function clear(){
        unset($_SESSION['history']);
    }
}

//インスタンス生成
//配達員
$driver = new Driver('宇羽太郎', Sex::MAN,  30, 30, 100, 'img/driver01.png', 30, 30, 100, 30, 100);
debug('配達員データ：'.print_r($driver,true));


//ショップ一覧
$shops[] = new Shop( 'マクドナルド', 'img/shop01.jpeg', 10, Item::LIGHT, 'ハンバーガー');
$shops[] = new Shop( 'タピオカ屋', 'img/shop02.jpeg', 15, Item::LIGHT, 'タピオカミルクティー');
$shops[] = new Shop( '吉野家', 'img/shop03.jpg', 15, Item::LIGHT, '牛丼');
$shops[] = new Shop( '筋肉食堂', 'img/shop04.jpg', 15, Item::MIDDLE, '日替わり弁当');
$shops[] = new Shop( '松屋', 'img/shop05.jpg', 15, Item::MIDDLE, '牛めし');
$shops[] = new Shop( 'オリジン弁当', 'img/shop06.jpg', 15, Item::MIDDLE, '幕内弁当');
$shops[] = new Shop( 'ゴーゴーカレー', 'img/shop07.jpg', 15, Item::MIDDLE, 'メジャーカレー');

//住宅一覧
$homes[] = new Home( '木造住宅', 'img/home01.jpg', 400, HOUSE::WOOD);
$homes[] = new Home( '鉄骨住宅', 'img/home02.jpg', 400, HOUSE::RC);
$homes[] = new Home( '高級住宅', 'img/home03.jpg', 400, HOUSE::SRC);
$homes[] = new Home( 'タワーマンション', 'img/home04.jpg', 400, HOUSE::TOWER);
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

function movingPoint(){
    //移動距離をランダムに決定
    //距離に応じて減る体力を決定
    //距離に応じて経過する時間を決定
}

function createDriver(){
    global $driver;
    $_SESSION['driver'] = $driver;
    debug('配達員データ：'.print_r($_SESSION['driver'],true));

}
function createShop(){
    global $shops;
    $shop = $shops[mt_rand(0,1)];
    $transFlg = "";
    History::set('アプリから注文が入りました！');
    $_SESSION['shop'] = $shop;
}
function createHome(){
    global $homes;
    $home = $homes[mt_rand(0,1)];
    History::set('〜に配達に訪れました！！');
}
function createCustomer(){
    global $customers;
    $customer = $customers[mt_rand(0,1)];
    History::set('〜が玄関口から現れた！！');
    $_SESSION['customer'] = $customer;
}
function createArea(){
    global $areas;
    $area = $areas[mt_rand(0,1)];
    History::set('〜に移動しました！！');
    $_SESSION['area'] = $area;
}

function init(){
    History::clear();
    History::set('配達の仕事を開始します！');
    $_SESSION['DriveryCount'] = 0;
    createDriver();
    createShop();
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
            //集荷を発生させる
            //店に言って情報が読み込まれ、選択肢が変化する
        }else if($transFlg){
        //配達を押した場合
            //配達員が消耗をする
            //売り上げ金額が上がる
            History::set($_SESSION['shop']->getItemName().'の配送を完了した！');
            createShop();
            $_SESSION['DriveryCount'] = $_SESSION['DriveryCount']+1;

         //条件を満たした場合（体力ゼロ）はゲームオーバーとする
         //24時を回った場合は日付を翌日の8時まで進める

        }else if($cycleFlg){
            History::set('駐輪所に到着した！');
            $_SESSION['MINUTE'];     //時間を加算し、体力を低下させる
            //一定確率で自転車の電池残量を回復し、一定確率で分岐させる
        }else if($combiFlg){
            History::set('コンビニに寄った！');
            //時間を加算し、体力を低下させる
            //トイレを借りて、トイレとやる気を回復させる
            //買うものをランダムに選択して、お金を失わせ、空腹を回復させる
        }else if($parkFlg){
            History::set('公園に到着した！');
            //時間を加算し、体力を低下させる
            //一定確率で自転車の電池残量を回復し、一定確率で分岐させる
        }else if($eatFlg){
            History::set('飲食店（要編集）に到着した！');
            //時間を加算し、体力を低下させる
            //トイレを借りて、トイレとやる気を回復させる
            //水分と空腹を回復させる、やる気も急増する
            //食べる店舗はランダムで選択する
        }else if($homeFlg){
            History::set('今日は早めに帰って寝よう');
            //体力を大幅に回復し、やる気、空腹、トイレ、自転車も全回復
            //日付を進め、時間も開始時間に変更する
            //新たな集荷を発生させる
        }else{
            //リセットを押してゲーム初期化
            init();
        }
    } 
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
      <?php if(empty($_SESSION) || !empty($_SESSION['start'])){ ?>
      <div class="top__image">
        <h1 class="subject">配達シュミレータ</h1>
        <h2>GAME START ?</h2>
        <div class="top__button">
            <form method="post">
            <input type="submit" name="start" value="▶ゲームスタート">
            </form>
        </div>
      </div>
      <?php }else{ ?>
        <header class="header">
            <div class="subject"><h1>配達シュミレータ</h1></div>
            <div class="header__clock"><?php echo date('H時i分',$tmp); ?></div>
            <div class="header__date">
                <?php echo date('Y年m月d日',$tmp); ?>(<?php echo $week[$weekNumber]; ?>)
            </div>
            <div class="header__physical">
                <?php
                if($_SESSION['driver']->getPassion() >= 80){
                    echo '絶好調';
                }else if($_SESSION['driver']->getPassion() < 80 && $_SESSION['driver']->getPassion() >= 60){
                    echo '好調';
                }else if($_SESSION['driver']->getPassion() < 60 && $_SESSION['driver']->getPassion() >= 40){
                    echo '普通';
                }else if($_SESSION['driver']->getPassion() < 40 && $_SESSION['driver']->getPassion() >= 20){
                    echo '不調';
                }else{
                    echo '絶不調';
                }
                ?>
            </div>
        </header>
        <div class="infomation">
            <div class="infoation__town">
                <div class="information__town-name">現在地 >> 新宿</div>
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
                    <p class="status_sentence">配達数：<?php echo $_SESSION['DeriveryCount']; ?></p>
                    <p class="status_sentence">体力：<?php echo $_SESSION['driver']->getHp(); ?>/100</p>
                    <p class="status_sentence">満腹度：<?php echo $_SESSION['driver']->getHungry(); ?>/100%</p>
                    <p class="status_sentence">トイレ：<?php echo $_SESSION['driver']->getWater(); ?>/100%</p>
                    <p class="status_sentence">自転車：34/47km</p>
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
