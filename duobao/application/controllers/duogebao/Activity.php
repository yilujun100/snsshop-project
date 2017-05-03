<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 活动
 *
 * Class Activity
 */
class Activity extends Duogebao_Base
{

    const ACTIVITY_AD_POSITION = 2;

    /**
     * 是否需要验证登陆
     *
     * @var array
     */
    protected $need_login_methods = array('qualification_recharge');

    /**
     * Activity constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->assign('menus_active_index', 3);
    }

    /**
     * 列表
     */
    public function index()
    {
        $this->set_wx_share('activity');
        $params = array(
            'position_id' => self::ACTIVITY_AD_POSITION,
            'closed' => true,
        );
        $activity_advert = $this->get_api('ad_list', $params);
        $this->assign('activity_advert',empty($activity_advert['retData']) ? array() : $activity_advert['retData']);
        $this->render();
    }


    /**
     * 充值活动说明
     */
    public function recharge()
    {
        $this->render();
    }

    /**
     * 儿童节限时充值
     */
    public function qualification_recharge()
    {

        $this->layout_name = null;
        $this->set_wx_share('qualification_recharge');
        $forgery_list_v1 = array(
            array('sNickName'=>'小雅','count'=>100,'largess'=>18),
            array('sNickName'=>'夺宝狗大爷','count'=>50,'largess'=>8),
            array('sNickName'=>'小豆丁','count'=>30,'largess'=>5),
            array('sNickName'=>'二胖','count'=>20,'largess'=>3),
            array('sNickName'=>'竹竿','count'=>100,'largess'=>18),
            array('sNickName'=>'刘一丝汗毛','count'=>10,'largess'=>1),
            array('sNickName'=>'狐狸猫*—*虾米','count'=>20,'largess'=>3),
            array('sNickName'=>'花泽类','count'=>100,'largess'=>18),
            array('sNickName'=>'zero','count'=>10,'largess'=>1),
            array('sNickName'=>'小钢铁','count'=>100,'largess'=>18),
            array('sNickName'=>'土豆小姐','count'=>50,'largess'=>8),

            array('sNickName'=>'此夏l','count'=>100,'largess'=>18),
            array('sNickName'=>'新城邓宏','count'=>50,'largess'=>8),
            array('sNickName'=>'剑雨江湖','count'=>30,'largess'=>5),
            array('sNickName'=>'Wilsen','count'=>20,'largess'=>3),
            array('sNickName'=>'945725885','count'=>100,'largess'=>18),
            array('sNickName'=>'纯情小王爷','count'=>10,'largess'=>1),
            array('sNickName'=>'浅浅的丶吟唱','count'=>20,'largess'=>3),
            array('sNickName'=>'柳下惠的忧伤','count'=>100,'largess'=>18),
            array('sNickName'=>'任志久','count'=>10,'largess'=>1),
            array('sNickName'=>'草木深情','count'=>100,'largess'=>18),
            array('sNickName'=>'龚小包','count'=>50,'largess'=>8),

            array('sNickName'=>'狮子','count'=>100,'largess'=>18),
            array('sNickName'=>'sunboys在路','count'=>50,'largess'=>8),
            array('sNickName'=>'浅笑云灬','count'=>30,'largess'=>5),
            array('sNickName'=>'未见绉颜','count'=>20,'largess'=>3),
            array('sNickName'=>'Story故事','count'=>100,'largess'=>18),
            array('sNickName'=>'睿了睿','count'=>10,'largess'=>1),
            array('sNickName'=>'此人未被包养','count'=>20,'largess'=>3),
            array('sNickName'=>'never','count'=>100,'largess'=>18),
            array('sNickName'=>'还没睡的。吼起','count'=>10,'largess'=>1),
            array('sNickName'=>'不份手dě恋爱','count'=>100,'largess'=>18),
            array('sNickName'=>'丢三落四ぅ','count'=>50,'largess'=>8),

            array('sNickName'=>'表白.','count'=>100,'largess'=>18),
            array('sNickName'=>'难瘦°','count'=>50,'largess'=>8),
            array('sNickName'=>'你看起来很下饭','count'=>30,'largess'=>5),
            array('sNickName'=>'人生多忐忑','count'=>20,'largess'=>3),
            array('sNickName'=>'姐特坏','count'=>100,'largess'=>18),
            array('sNickName'=>'心不动，则不痛','count'=>10,'largess'=>1),
            array('sNickName'=>'咆哮。','count'=>20,'largess'=>3),
            array('sNickName'=>'厌学的小骚年','count'=>100,'largess'=>18),
            array('sNickName'=>'女汉子，怕谁','count'=>10,'largess'=>1),
            array('sNickName'=>'怀念。','count'=>100,'largess'=>18),
            array('sNickName'=>'我喜欢帅哥','count'=>50,'largess'=>8)
        );
        $forgery_list_v2 = array(
            array('sNickName'=>'不份手dě恋爱','count'=>10,'largess'=>1),
            array('sNickName'=>'真心难瘦ぅ','count'=>20,'largess'=>3),
            array('sNickName'=>'你看起来很下饭','count'=>10,'largess'=>1),
            array('sNickName'=>'厌学的小骚年','count'=>10,'largess'=>1),
            array('sNickName'=>'怀念7-11','count'=>30,'largess'=>5),
            array('sNickName'=>'冷暖自知','count'=>20,'largess'=>3),
            array('sNickName'=>'小温馨','count'=>50,'largess'=>8),
            array('sNickName'=>'哇咔咔','count'=>30,'largess'=>5),
            array('sNickName'=>'你好逗!','count'=>50,'largess'=>8),
            array('sNickName'=>'鲜花配绿叶','count'=>30,'largess'=>5),
            array('sNickName'=>'牛逼人物','count'=>20,'largess'=>3),

            array('sNickName'=>'冷暖自知','count'=>10,'largess'=>1),
            array('sNickName'=>'小温馨','count'=>20,'largess'=>3),
            array('sNickName'=>'叫我糊涂','count'=>10,'largess'=>1),
            array('sNickName'=>'哇咔咔','count'=>10,'largess'=>1),
            array('sNickName'=>'你好逗!','count'=>30,'largess'=>5),
            array('sNickName'=>'牛逼人物','count'=>20,'largess'=>3),
            array('sNickName'=>'粑粑去哪儿','count'=>50,'largess'=>8),
            array('sNickName'=>'我还好！','count'=>30,'largess'=>5),
            array('sNickName'=>'超人丶','count'=>50,'largess'=>8),
            array('sNickName'=>'当垃圾丢','count'=>30,'largess'=>5),
            array('sNickName'=>'百年茶业','count'=>20,'largess'=>3),

            array('sNickName'=>'爱吃麻辣烫','count'=>10,'largess'=>1),
            array('sNickName'=>'不吃香菜的猴子','count'=>20,'largess'=>3),
            array('sNickName'=>'不吃兔兔','count'=>10,'largess'=>1),
            array('sNickName'=>'猫爷','count'=>10,'largess'=>1),
            array('sNickName'=>'悉数沉淀','count'=>30,'largess'=>5),
            array('sNickName'=>'暖寄归人','count'=>20,'largess'=>3),
            array('sNickName'=>'瞎闹腾i','count'=>50,'largess'=>8),
            array('sNickName'=>'独美i ','count'=>30,'largess'=>5),
            array('sNickName'=>'厌世症i','count'=>50,'largess'=>8),
            array('sNickName'=>'人心可畏','count'=>30,'largess'=>5),
            array('sNickName'=>'你真逗比','count'=>20,'largess'=>3),

            array('sNickName'=>'前凸后翘','count'=>10,'largess'=>1),
            array('sNickName'=>'可喜可乐','count'=>20,'largess'=>3),
            array('sNickName'=>'以心换心','count'=>10,'largess'=>1),
            array('sNickName'=>'或许','count'=>10,'largess'=>1),
            array('sNickName'=>'渣中王','count'=>30,'largess'=>5),
            array('sNickName'=>'一干为尽','count'=>20,'largess'=>3),
            array('sNickName'=>'你的愚忠','count'=>50,'largess'=>8),
            array('sNickName'=>'就是任性','count'=>30,'largess'=>5),
            array('sNickName'=>'缺氧患人！','count'=>50,'largess'=>8),
            array('sNickName'=>'住进时光里','count'=>30,'largess'=>5),
            array('sNickName'=>'难免心酸°','count'=>20,'largess'=>3)
        );
        $forgery_list_v3 = array(
            array('sNickName'=>'牛逼人物','count'=>20,'largess'=>3),
            array('sNickName'=>'为什么超人不是我','count'=>30,'largess'=>5),
            array('sNickName'=>'安笙凉城','count'=>100,'largess'=>18),
            array('sNickName'=>'荒城旧日','count'=>100,'largess'=>18),
            array('sNickName'=>'哈哈哈哈哈','count'=>20,'largess'=>3),
            array('sNickName'=>'百年茶业','count'=>50,'largess'=>8),
            array('sNickName'=>'Married ?','count'=>20,'largess'=>3),
            array('sNickName'=>'wolf','count'=>50,'largess'=>8),
            array('sNickName'=>'旧同桌的你','count'=>20,'largess'=>3),
            array('sNickName'=>'逗比女王','count'=>20,'largess'=>3),
            array('sNickName'=>'six','count'=>100,'largess'=>18),

            array('sNickName'=>'只为你生！','count'=>20,'largess'=>3),
            array('sNickName'=>'前后都是你','count'=>30,'largess'=>5),
            array('sNickName'=>'陌离女王','count'=>100,'largess'=>18),
            array('sNickName'=>'缺我也没差','count'=>100,'largess'=>18),
            array('sNickName'=>'十年温如初','count'=>20,'largess'=>3),
            array('sNickName'=>'闹够了就滚','count'=>50,'largess'=>8),
            array('sNickName'=>'单身女王','count'=>20,'largess'=>3),
            array('sNickName'=>'我心透心凉','count'=>50,'largess'=>8),
            array('sNickName'=>'有钱就是任性','count'=>20,'largess'=>3),
            array('sNickName'=>'爱情就是难题','count'=>20,'largess'=>3),
            array('sNickName'=>'沁月沫璇55','count'=>100,'largess'=>18),

            array('sNickName'=>'谢希比','count'=>20,'largess'=>3),
            array('sNickName'=>'给个响亮名字','count'=>30,'largess'=>5),
            array('sNickName'=>'欢呼声天枰','count'=>100,'largess'=>18),
            array('sNickName'=>'小悠悠','count'=>100,'largess'=>18),
            array('sNickName'=>'贫道随云子','count'=>20,'largess'=>3),
            array('sNickName'=>'huangiroi','count'=>50,'largess'=>8),
            array('sNickName'=>'1罗船长1','count'=>20,'largess'=>3),
            array('sNickName'=>'a939824956','count'=>50,'largess'=>8),
            array('sNickName'=>'夜小贱520','count'=>20,'largess'=>3),
            array('sNickName'=>'我最love萝莉','count'=>20,'largess'=>3),
            array('sNickName'=>'简爱依楠','count'=>100,'largess'=>18),

            array('sNickName'=>'mask0809','count'=>20,'largess'=>3),
            array('sNickName'=>'卡牌大湿','count'=>30,'largess'=>5),
            array('sNickName'=>'捂住灵魂傲娇','count'=>100,'largess'=>18),
            array('sNickName'=>'失心的坏小孩','count'=>100,'largess'=>18),
            array('sNickName'=>'wing孤独的总和','count'=>20,'largess'=>3),
            array('sNickName'=>'苦涩虾米','count'=>50,'largess'=>8),
            array('sNickName'=>'水瓶奋斗的前程','count'=>20,'largess'=>3),
            array('sNickName'=>'合衬欧尼','count'=>50,'largess'=>8),
            array('sNickName'=>'习惯沉默65','count'=>20,'largess'=>3),
            array('sNickName'=>'星际GM','count'=>20,'largess'=>3),
            array('sNickName'=>'you诚','count'=>100,'largess'=>18)
        );
        $forgery_list_v4 = array(
            array('sNickName'=>'龙在天涯','count'=>20,'largess'=>3),
            array('sNickName'=>'老猫','count'=>100,'largess'=>18),
            array('sNickName'=>'风澈','count'=>100,'largess'=>18),
            array('sNickName'=>'Ken','count'=>20,'largess'=>3),
            array('sNickName'=>'让我好好的','count'=>20,'largess'=>3),
            array('sNickName'=>'Freedom','count'=>100,'largess'=>18),
            array('sNickName'=>'X.O','count'=>100,'largess'=>18),
            array('sNickName'=>'草木兰不随风','count'=>20,'largess'=>3),
            array('sNickName'=>'橘子miumiu','count'=>20,'largess'=>3),
            array('sNickName'=>'蔷薇花上有只猫','count'=>30,'largess'=>5),
            array('sNickName'=>'我一切只靠手气','count'=>30,'largess'=>5),

            array('sNickName'=>'velin2013','count'=>20,'largess'=>3),
            array('sNickName'=>'美美名品店','count'=>100,'largess'=>18),
            array('sNickName'=>'鱼粑粑打鱼','count'=>100,'largess'=>18),
            array('sNickName'=>'孙氏家族','count'=>20,'largess'=>3),
            array('sNickName'=>'不ai笑的鱼','count'=>20,'largess'=>3),
            array('sNickName'=>'萝卜辉辉','count'=>100,'largess'=>18),
            array('sNickName'=>'我是大馒头c','count'=>100,'largess'=>18),
            array('sNickName'=>'小草帽咯咯','count'=>20,'largess'=>3),
            array('sNickName'=>'漠河以北','count'=>20,'largess'=>3),
            array('sNickName'=>'为你折的纸玫瑰','count'=>30,'largess'=>5),
            array('sNickName'=>'宫野沫欣','count'=>30,'largess'=>5),

            array('sNickName'=>'火柴214','count'=>20,'largess'=>3),
            array('sNickName'=>'付忻人','count'=>100,'largess'=>18),
            array('sNickName'=>'夕微娅','count'=>100,'largess'=>18),
            array('sNickName'=>'旧梦乱人心','count'=>20,'largess'=>3),
            array('sNickName'=>'反正你那么爱她','count'=>20,'largess'=>3),
            array('sNickName'=>'大皇帝','count'=>100,'largess'=>18),
            array('sNickName'=>'高美麒','count'=>100,'largess'=>18),
            array('sNickName'=>'陌怨人殇','count'=>20,'largess'=>3),
            array('sNickName'=>'暗殘殤','count'=>20,'largess'=>3),
            array('sNickName'=>'huangiroi','count'=>30,'largess'=>5),
            array('sNickName'=>'习惯沉默65','count'=>30,'largess'=>5),

            array('sNickName'=>'程翰文','count'=>20,'largess'=>3),
            array('sNickName'=>'宫野沫欣','count'=>100,'largess'=>18),
            array('sNickName'=>'爸','count'=>100,'largess'=>18),
            array('sNickName'=>'猩猩嘿嘿嘿','count'=>20,'largess'=>3),
            array('sNickName'=>'我最love萝莉','count'=>20,'largess'=>3),
            array('sNickName'=>'不ai笑的鱼','count'=>100,'largess'=>18),
            array('sNickName'=>'花花花、小伙','count'=>100,'largess'=>18),
            array('sNickName'=>'蛋疼先森','count'=>20,'largess'=>3),
            array('sNickName'=>'絕蝂de愛','count'=>20,'largess'=>3),
            array('sNickName'=>'紅塵殇雪','count'=>30,'largess'=>5),
            array('sNickName'=>'浅夏初凉','count'=>30,'largess'=>5),
        );
        shuffle($forgery_list_v1);
        shuffle($forgery_list_v2);
        shuffle($forgery_list_v3);
        shuffle($forgery_list_v4);
        $time_now = date('Y/m/d H:i:s');

        $this->assign('time_now', $time_now);
        $this->assign('forgery_list_v1', $forgery_list_v1);
        $this->assign('forgery_list_v2', $forgery_list_v2);
        $this->assign('forgery_list_v3', $forgery_list_v3);
        $this->assign('forgery_list_v4', $forgery_list_v4);
        $this->render();
    }
}