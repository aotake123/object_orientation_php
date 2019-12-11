<?php

ini_set('log_errors','on');
ini_set('error_log','php.log');
session_start();

//インスタンス格納用変数
    $stores = array();   //店舗一覧
    $homes = array();   //住居一覧
    $customers = array();    //住人一覧
    $areas = array();    //配送地域一覧

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
    const Positive_Deli1 = お疲れ様です！UBERです;
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

//配達員、お客さんの上位の抽象クラス
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
    public $spotName;    //場所の名前
    public $spotImg;    //場所の画像
    public $distance; //配達に必要な基本の距離(m)
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
    protected $itemName; //渡す商品の名前
    public function __construct($spotName, $spotImg, $distance, $itemName){
        $this->name = $spotName;
        $this->img = $spotImg;
        $this->distance = $distance;
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
    //時間毎に注文毎にかかる時間
    protected $orderTime_morning;
    protected $orderTime_evening;
    protected $orderTime_night;
    public function getAreaName(){
        return $this->areaName;
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
$deriver = new Driver('宇羽太郎', Sex::MAN,  30, 30, 30, 'img/driver01.jpg', 30, 30, 30, 30, 30);
//ショップ一覧
$shops[] = new Shop( 'マクドナルド', 'img/shop01.jpg', 10, Item::LIGHT);
$shops[] = new Shop( 'タピオカ屋', 'img/shop02.jpg', 15, Item::LIGHT);
$shops[] = new Shop( '吉野家', 'img/shop03.jpg', 15, Item::LIGHT);
$shops[] = new Shop( '筋肉食堂', 'img/shop04.jpg', 15, Item::MIDDLE);
$shops[] = new Shop( '松屋', 'img/shop05.jpg', 15, Item::MIDDLE);
$shops[] = new Shop( 'オリジン弁当', 'img/shop06.jpg', 15, Item::MIDDLE);
$shops[] = new Shop( 'ゴーゴーカレー', 'img/shop07.jpg', 15, Item::MIDDLE);
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



function createDriver(){
    global $deriver;
    $_SESSION['driver'] = $deriver;
}
function createShop(){
    global $shop;
    $shop = $shops[mt_rand(0,1)];
    History::set('アプリから注文が入りました！');
    $_SESSION['shop'] = $shop;
}
function createHome(){
    global $home;
    $home = $homes[mt_rand(0,1)];
    History::set('〜に配達に訪れました！！');
}
function createCustomer(){
    global $home;
    $home = $homes[mt_rand(0,1)];
    History::set('〜が玄関口から現れた！！');
}
function createArea(){
    global $home;
    $home = $homes[mt_rand(0,1)];
    History::set('〜に移動しました！！');
}

function init(){
    History::clear();
    History::set('初期化します！');
    $_SESSION['DeriveryCount'] = 0;
    createDriver();
    createShop();
}
function gameOver(){
    $_SESSION = array();
}

//1.post送信されていた場合
if(!empty($_POST)){
    $pickFlg = (!empty($_POST['pickup'])) ? true : false;
    $startFlg = (!empty($_POST['start'])) ? true : false;
    error_log('POSTされた！');

    if($startFlg){
        History::set('配達スタート！');
        init();
    }else{
        //集荷を押した場合
        if($pickFlg){
            //店に言って情報が読み込まれ、選択肢が変化する
        //配達を押した場合
            //配達員が消耗をする
            //売り上げ金額が上がる
         //条件を満たした場合（体力ゼロ）はゲームオーバーとする
            if($_SESSION['human']->getHp() <= 0){
                gameOver();
         //24時を回った場合は日付を翌日の8時まで進める
            }else{
                //配達が完了したら、また次の配送を発生させる
                if($_SESSION['human']->hp <= 0){
                    History::set($_SESSION['shop']->itemName.'の配送を完了した！');
                    createOrder();
                    $_SESSION['DeriveryCount'] = $_SESSION['DeriveryCount']+1;
                }
            }
        }else{
            History::set('配送拒否を行いました！');
            createOrder();
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
            <div class="header__clock">AM8時00分</div>
            <div class="header__date">2019年 12月05日(火)</div>
            <div class="header__physical">(^_^)</div>
        </header>
        <div class="infomation">
            <div class="infoation__town">新宿</div>
            <div class="infomation__wrap">
                <div class="informaiton__w-driver_picture window">
                    <div>
                        <img class="prof_image" src="<?php $_SESSION['driver']->getFaceImg(); ?>">
                    </div>
                </div>
                <div class="information__w-change_picture window"></div>
                <div class="information__w-status window">
                    <p class="status_sentence">本日の配達数：１２</p>
                    <p class="status_sentence">満腹度：４５／１００％</p>
                    <p class="status_sentence">トイレ危険度：３５／１００％</p>
                    <p class="status_sentence">自転車電池残量：／４７km</p>
                </div>
            </div>
        </div>
        <footer class="footer">
            <div class="footer__command">
                <form method="footer__command-post">
                    <table class="footer__command-table"><tbody>
                        <tr>
                            <td colspan="2"><input type="submit" name="pickup" value="▶集荷" class="cell"></td>
                            <td><input type="submit" name="cycle" value="▶駐輪所"></td>
                            <td><input type="submit" name="combi" value="▶コンビニ"></td>
                        </tr>
                        <tr>
                            <td class="cell"><input type="submit" name="park" value="▶公園"></td>
                            <td class="cell"><input type="submit" name="eat" value="▶飲食店"></td>
                            <td class="cell"><input type="submit" name="gohome" value="▶帰宅"></td>
                            <td class="cell"><input type="submit" name="start" value="▶リセット"></td>
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
