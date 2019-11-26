<?php

ini_set('log_errors','on');
ini_set('error_log','php.log');
session_start();

$players = array();     //プレイヤー達格納用
$monsters = array();    //モンスター達格納用

//性別クラス
class Sex{
    const MAN = 1;
    const WOMAN = 2;
}

//抽象クラス（生き物クラス）
abstract class Creature{
    protected $name;
    protected $hp;
    protected $mp;
    protected $img;
    protected $attackMin;   //表示すら不要な場合（計算に使う設定値の場合）は、ゲッターすら作らない
    protected $attackMax;
    protected $defence;
    protected $speed;
    abstract public function sayCry();

    public function getName(){     //表示のみで書き換えは無い為、セッターは作らない
        return $this->name;
    }
    public function setHp($num){
        $this->hp = $num;
    }
    public function getHp(){
        return $this->hp;
    }
    public function setMp($num){
        $this->hp = $num;
    }
    public function getMp(){
        return $this->mp;
    }
    public function getImg(){
        return $this->img;
    }
}

//魔法クラス
class Magic{
    protected $name;
    protected $lostmp;
    protected $pointMin;
    protected $pointMax;
    protected $status;
    protected $percent;
    //コンストラクタ
    public function __construct($name,$lostmp,$pointMin,$pointMax,$status,$percent){
        $this->name = $name;
        $this->lostmp = $lostmp;
        $this->pointMin = $pointMin;
        $this->pointMax = $pointMax;
        $this->status = $status;
        $this->percent = $percent;
    }
     //魔法攻撃
     public function magicAttack($targetObj){
        $attackPoint = mt_rand($this->pointMin,$this->pointMax);
        $attackPoint = (int)$attackPoint;
        History::set(Human::getName().'は'.$this->name.'をとなえた！');
        $targetObj->setHp($targetObj->getHp()-$attackPoint);
        History::set($targetObj->getName().'に'.$attackPoint.'ポイントのダメージ！');
    }
}

//状態以上クラス
class Status{
    const SLEEP = 1;    //ねむり状態
    const SYLES = 1;    //魔法使用不可状態
}
//人クラス
class Human extends Creature{
    //プロパティ
    protected $sex;
    //コンストラクタ
    public function __construct($name, $sex, $hp, $mp, $img, $attackMin, $attackMax, $defence, $speed){
        $this->name = $name;
        $this->sex = $sex;
        $this->hp = $hp;
        $this->mp = $mp;
        $this->img = $img;
        $this->attackMin = $attackMin;
        $this->attackMax = $attackMax;
        $this->defence = $defence;
        $this->speed = $speed;
    }

    public function setSex($num){
        $this->sex = $num;
    }
    public function getSex(){
        return $this->sex;
    }
    public function attack($targetObj){
        $attackPoint = mt_rand($this->attackMin,$this->attackMax);
        if(!mt_rand(0,19)){  //5%の確率でクリティカル
            $attackPoint = $attackPoint * 1.5;
            $attackPoint = (int)$attackPoint;
            History::set('かいしんのいちげき！');      //表記上の違いから敵味方で別内容をオーバーライド
        }
        $targetObj->setHp($targetObj->getHp()-$attackPoint);
        History::set($targetObj->getName().'に'.$attackPoint.'ポイントのダメージ！');
    }

    public function sayCry(){
        History::set($this->name);
        switch($this->sex){
            case Sex::MAN :
                History::set('「ぐふっ！」');
                break;
            case Sex::WOMAN :
                History::set('「いやっ！」');
                break;
        }
    }
}

//モンスタークラス
class Monster extends Creature{
    //プロパティ
    //コンストラクタ
    public function __construct($name, $hp, $mp, $img, $attackMin, $attackMax, $defence, $speed){
        $this->name = $name;
        $this->hp = $hp;

        $this->mp = $mp;
        $this->img = $img;
        $this->attackMin = $attackMin;
        $this->attackMax = $attackMax;
        $this->defence = $defence;
        $this->speed = $speed;
    }
    public function attack($targetObj){
        $attackPoint = mt_rand($this->attackMin,$this->attackMax);
        if(!mt_rand(0,19)){  //5%の確率でクリティカル
            $attackPoint = $attackPoint * 1.5;
            $attackPoint = (int)$attackPoint;
            History::set('つうこんのいちげき！');
        }
        $targetObj->setHp($targetObj->getHp()-$attackPoint);
        History::set($attackPoint.'ポイントのダメージ！');
    }
    public function sayCry(){
        History::set($this->name.'「はうっ！」');
 
    }
}

//履歴管理クラス（インスタンス化して複数に増殖させる必要性が無いクラスなのでstatic化）
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
$players1 = new Human('アオタケ', SEX::MAN, 166, 51, "img/noimage.jpg", 43,52,85,60);
$monsters[] = new Monster('ス〇イム', 100, 4000, "img/monster01.jpg",15,21,30,50);

$magic[] = new Magic('ホ◯ミ', 3, 25, 35, 0, 0);
$magic[] = new Magic('ギ〇', 2, 9, 15, 0, 0);
$magic[] = new Magic('ラ〇ホー', 18, 150, 185, 0, 0);
$magic[] = new Magic('マ〇トーン', 5, 18, 27, 0, 0);
$magic[] = new Magic('ベホ◯ミ', 5, 75, 85, 0, 0);
$magic[] = new Magic('ベ〇ラマ', 4, 75, 91, 0, 0);



function createMonster(){
    global $monsters;
    $monster = $monsters[0];    //現状では単体テストの為、モンスター固定
    History::set($monster->getName().'が現れた');
    $_SESSION['monster'] = $monster;
}
function createHuman(){
    global $players1;
    $_SESSION['player1'] = $players1;
}

function init(){
    History::clear();
    History::set('初期化します！');
    $_SESSION['knockDownCount'] = 0;
    createHuman();
    createMonster();
}
function gameOver(){
    $_SESSION = array();
}

//1.post送信されていた場合
if(!empty($_POST)){
    $attackFlg = (!empty($_POST['attack'])) ? true : false;
    $startFlg = (!empty($_POST['start'])) ? true : false;
    $magicAttackFlg = (!empty($_POST['magic'])) ? true : false;
    $defenceFlg = (!empty($_POST['difence'])) ? true : false;
    $escapeFlg = (!empty($_POST['escape'])) ? true : false;
    error_log('POSTされた！');

    if($startFlg){
        History::set('ゲームスタート！');
        init();
    }else{
        //前の処理が残っていると最新の履歴が不明な為、戦闘開始以降はログを遷移毎に毎回消す
        History::clear();
        //攻撃するを押した場合
        if($attackFlg){
            //モンスターに攻撃を与える
            History::set($_SESSION['player1']->getName().'のこうげき！');
            $_SESSION['player1']->attack($_SESSION['monster']);
            $_SESSION['monster']->sayCry();

            //モンスターが攻撃をする
            History::set($_SESSION['monster']->getName().'のこうげき！');
            $_SESSION['monster']->attack($_SESSION['player1']);
            $_SESSION['player1']->sayCry();
      
            //自分のhpが0以下になったらゲームオーバー
            if($_SESSION['player1']->getHp() <= 0){
                gameOver();
            }else{
                // hpが0以下になったら、別のモンスターを出現させる
                if($_SESSION['monster']->getHp() <= 0){
                    History::set($_SESSION['monster']->getName().'をやっつけた！');
                    createMonster();
                    $_SESSION['knockDownCount'] = $_SESSION['knockDownCount']+1;
                }
            }
        }else{ //逃げるを押した場合
            History::set('逃げた！');
            createMonster();
        }
    }
    $_POST = array();
}

?>

<!DOCTYPE html>
<html>
<head>
<title>OBUKATU QUEST オブジェクト指向の動作確認</title>
<link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
    <main class="main">
        <div class="background">

        <?php if(empty($_SESSION)){ ?>
        <h2 style="margin-top:60px;">GAME START ?</h2>
        <form method="post">
          <input type="submit" name="start" value="▶ゲームスタート">
        </form>
        <?php }else{ ?>

        <div class="main_left">

            <div class="status">
                <ul class="window__player">
                    <li class="window__item"><?php echo  $_SESSION['player1']->getName(); ?></li>
                    <li class="window__item">HP <?php echo $_SESSION['player1']->getHp(); ?></li>
                    <li class="window__item">MP <?php echo $_SESSION['player1']->getMp(); ?></li>
                </ul>
            </div>

            <div class="target">
                <ul class="window">
                    <li class="window__target">
                        <div class="wrap_img">
                            <img class="img_monster" src="<?php echo $_SESSION['monster']->getImg(); ?>" width="100px" height="100px;">
                        </div>
                        <!-- <img src="<?php echo $_SESSION['monster']->getImg(); ?>" style="width:120px; height:auto; margin:40px auto 0 auto; display:block;"> -->

                    </li>
                <ul>
            </div>

            <div class="command">
                <div class="command__left">
                    <ul class="window">
                        <form method="post">
                        <li class="window__item"><input type="submit" name="attack" value="▶︎ たたかう"></li>
                        <li class="window__item"><input type="submit" name="magicAttack" value="▶︎ まほう"></li>
                        <li class="window__item"><input type="submit" name="difence" value="▶︎ ぼうぎょ"></li>
                        <li class="window__item"><input type="submit" name="escape" value="▶︎ にげる"></li>
                        </form>
                    </ul>
                </div>
                <div class="command__right">
                     <ul class="window">
                        <li class="window__item"><?php echo '▶ '.$_SESSION['monster']->getName(); ?></li>
                    </ul>
                </div>
            </div>

        </div>
        <div class="main_right">
            <div class="window window__history">
            <p><?php echo (!empty($_SESSION['history'])) ? $_SESSION['history'] : ''; ?></p>
            </div>
        </div>

       <?php } ?>

        </div>
    </main>
</body>
</html>

