<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 导入微团购魔法森林活动数据
 *
 * Class ImportActiveConfig
 */
class ImportActiveConfig extends Script_Base
{
    /**
     * 单次处理的记录数
     */
    const LIMIT = 2000;

    /**
     * 脚本最大执行时间
     */
    const MAX_RUN_TIME = 3600;

    /**
     * 默认商品分类ID
     */
    const DEFAULT_CATE_ID = 1;

    /**
     * 默认最多开奖期数
     */
    const DEFAULT_PERIOD = 999999;

    /**
     * @var string
     */
    protected $log_type = 'ImportActiveConfig';

    public function __construct()
    {
        parent::__construct();

        $this->load->model('active_luckycode_config_model');
        $this->load->model('active_luckycode_record_model');

        $this->load->model('goods_item_model');
        $this->load->model('active_config_model');
        $this->load->model('active_peroid_model');
        $this->load->model('user_model');
    }

    public function test_type()
    {
        $period_data = array(
            'iActId' => 1,
            'iPeroid' => 1,
            'iIsCurrent' => 1,
            'iGoodsId' => 1,
            'iCateId' => 1,
            'sGoodsName' => 1,
            'iGoodsType' => 1,
            'iCostPrice' => 1,
            'iLowestPrice' => 1,
            'sImg' => '',
            'sImgExt' => '',
            'sSearchKey' => '',
            'iActType' => 1,
            'iCodePrice' => 1,
            'iLotCount' => 1,
            'iTotalPrice' => 1,
            'iBuyCount' => 1,
            'iPeroidBuyCount' => 1,
            'iPeroidCount' => 1,
            'iBeginTime' => 1,
            'iEndTime' => 1,
            'iSoldCount' => 1,
            'iProcess' => 1,
            'iTotalSoldCount' => 1,
            'iLotTime' => 1,
            'sWinnerCode' => 1,
            'iWinnerCount' => 1,
            'iLotNumA' => 1,
            'iLotNumB' => 1,
            'sLotBasis' => 1,
            'iWinnerUin' => -2141071174268234057,
        );
        $this->active_peroid_model->add_row($period_data);
    }

    public function order_deliver()
    {
        set_time_limit(0);

        $this->load->model('order_deliver_model');

        header("Content-type: text/html; charset=utf-8");
        require_once('common/Log.php');
        require_once('common/Common.php');
        require_once('common/Base.php');

        $user_exception = $address_exception = array();

        $sql = 'SELECT * FROM `t_active_peroid` WHERE iLotState=2 AND iWinnerUin>0;';
        $period_list = $this->active_peroid_model->query($sql);

        foreach ($period_list as $period) {

            $user = $this->user_model->get_user_by_uin($period['iWinnerUin']);
            if (empty($user) || empty($user['iWuin'])) {
                $user_exception[] = array('period'=>$period, 'user'=>$user);
                continue;
            }

            $user_address = WTG_BModel_Base::getDataByMapi('http://dev.mapi.gaopeng.com/user/address/list',array('uin'=>$user['iWuin'], 'type'=>'dev'));
            if(is_array($user_address) && $user_address['retData']) {
                $address = json_decode($user_address['retData'], true);
                if (empty($address[0])) {
                    $address_exception[] = array('period'=>$period, 'user_address'=>$user_address);
                    continue;
                }
                $address = $address[0];
            } else {
                $address_exception[] = array('period'=>$period, 'user_address'=>$user_address);
                continue;
            }

            $ext = array();
            for ($i = 1; $i < 6; $i ++) {
                $ext[$i] = $period['iLotTime'] + 864000;
            }
            $deliver = array(
                'iGoodsId' => $period['iGoodsId'],
                'iUin' => $period['iWinnerUin'],
                'iType' => 1,
                'sOrderId' => $period['sWinnerOrder'],
                'sExpressId' => '15403947305',
                'sExpressName' => '圆通快递',
                'sName' => $address['name'],
                'sMobile' => $address['mobile'],
                'sAddress' => $address['province'] . ' ' . $address['city'] . ' ' . $address['district'] . ' ' . $address['address'],
                'iDeliverStatus' => 1,
                'iConfirmStatus' => 1,
                'sExtField' => json_encode($ext),
            );
            $this->order_deliver_model->add_row($deliver);
        }

        pr("====================== 用户信息异常 =============================\n");
        pr($user_exception);

        pr("\n\n====================== 收货地址异常 =============================\n");
        pr($address_exception);
    }

    /**
     * 检查开奖状态
     */
    public function check_lot_state()
    {
        $sql = <<<'Q'
SELECT
  b.`iGrouponId`,
  a.`iPeroid`,
  a.`iLotState`,
  a.`iLotTime`,
  a.`sWinnerCode`
FROM
  `t_active_peroid` a
  LEFT JOIN `t_active_config` b
    ON a.`iActId` = b.`iActId`
WHERE a.`iActId` <= 122
ORDER BY b.`iGrouponId`,
  a.`iPeroid` ;
Q;
        $period_list = $this->active_peroid_model->query($sql);

        $state_exception = array();

        foreach ($period_list as $period) {
            $where = array(
                'iGrouponId' => $period['iGrouponId'],
                'iPeriods' => $period['iPeroid'],
            );
            $luckycode_config = $this->active_luckycode_config_model->get_row($where);

            if (0 == $period['iLotState'] && 0 != $luckycode_config['iStatus'] && 1 != $luckycode_config['iStatus']) {
                $state_exception[] = $period;
            } else if ($period['iLotState'] != $luckycode_config['iStatus'] - 1) {
                $state_exception[] = $period;
            }
        }

        pr("====================== 开奖状态异常 =============================\n");
        pr($state_exception);
    }

    public function run()
    {
        set_time_limit(0);

        $page_index = 1;

        $page_size = self::LIMIT;

        $begin = time();

        $this->log("====================================BEGIN(".date('Y-m-d H:i:s').")=============================================\n\n");

        while (true) {

            echo "process... page_size: " . $page_index;

            $where = array(
                'iActId' => 10048
            );

            $order_by = array(
                'iGrouponId' => 'ASC',
                'iPeriods' => 'DESC',
                'iAutoId' => 'DESC',
            );

            $alc_config = $this->active_luckycode_config_model->row_list('*', $where, $order_by, $page_index, $page_size);

            if (! isset($alc_config['count'])  || $alc_config['count'] < 1) {
                continue;
            }

            foreach ($alc_config['list'] as $v) {

                $where_goods = array(
                    'iGrouponId' => $v['iGrouponId']
                );
                $goods = $this->goods_item_model->get_row($where_goods);

                $is_current = 0;

                if (empty($goods)) {
                    // 导入商品
                    $goods = $this->import_goods($v);

                    // 导入夺宝单
                    $active = $this->import_config($v, $goods);

                    if ($v['iFinishPeriods'] != $v['iPeriods'] || $v['iStatus'] < 2) {
                        $is_current = 1;
                    }
                } else {
                    $where_active = array(
                        'iGrouponId' => $goods['iGrouponId']
                    );
                    $active = $this->active_config_model->get_row($where_active);
                }
                unset($goods);

                $where_period = array(
                    'iActId' => $active['iActId'],
                    'iPeroid' => $v['iPeriods'],
                );

                $period = $this->active_peroid_model->get_row($where_period);
                if (empty($period)) {
                    $period = $this->import_period($v, $active, $is_current);
                }
                unset($active, $period);
            }

            if ($page_index >= $alc_config['page_count'] || time() - $begin > self::MAX_RUN_TIME) {
                break;
            }

            $page_index++;
        }

        $this->log("====================================END(".date('Y-m-d H:i:s').")=============================================\n\n");
    }

    /**
     * 导入商品
     *
     * @param $period
     *
     * @return array
     */
    private function import_goods($period)
    {
        $price_total = $period['iLimitCount'] * 100;

        $goods = array(
            'iCateId' => self::DEFAULT_CATE_ID,
            'iCateId_1' => self::DEFAULT_CATE_ID,
            'iType' => 0,
            'sName' => $period['sGrouponName'],
            'iCostPrice' => $price_total,
            'iLowestPrice' => $price_total,
            'sIntro' => $period['sGrouponDesc'],
            'sContent' => $period['sGrouponDesc'],
            'sImg' => $period['sGrouponBImgNew'],
            'sImgExt' => json_encode(array(1=>$period['sGrouponBImgExtNew'])),
            'iState' => Lib_Constants::PUBLISH_STATE_ONLINE,
            'iMerchant' => 2,
            'iGrouponId' => $period['iGrouponId']
        );
        $goods['iGoodsId'] = $this->goods_item_model->add_row($goods);

        $this->log('import_goods:');
        $this->log(print_r($goods, true));

        return $goods;
    }

    /**
     * 导入夺宝单
     *
     * @param $period
     * @param $goods
     *
     * @return array
     */
    private function import_config($period, $goods)
    {
        $config = array(
            'iGoodsId' => $goods['iGoodsId'],
            'iCateId' => $goods['iCateId'],
            'sGoodsName' => $goods['sName'],
            'iGoodsType' => $goods['iType'],
            'iCostPrice' => $goods['iCostPrice'],
            'iLowestPrice' => $goods['iLowestPrice'],
            'sImg' => $goods['sImg'],
            'sImgExt' => $goods['sImgExt'],
            'sSearchKey' => $goods['sName'],
            'iActType' => Lib_Constants::ACTIVE_TYPE_SYS,
            'iCodePrice' => 100,
            'iLotCount' => $period['iLimitCount'],
            'iTotalPrice' => $goods['iLowestPrice'],
            'iBuyCount' => $period['iLimitCount'],
            'iPeroidBuyCount' => $period['iLimitCount'],
            'iPeroidCount' => $period['iLimitCount'],
            'iBeginTime' => $period['iBeginTime'],
            'iEndTime' => $period['iEndTime'],
            'iGrouponId' => $goods['iGrouponId'],
        );
        if (empty($period['iFinishPeriods'])) {
            $config['iPeroidCount'] = self::DEFAULT_PERIOD;
        } else {
            $config['iPeroidCount'] = $period['iFinishPeriods'];
        }
        if ($period['iFinishPeriods'] == $period['iPeriods'] && $period['iStatus'] >= 2) { // 已结束
            $config['iState'] = Lib_Constants::PUBLISH_STATE_END;
        } else {
            $config['iState'] = Lib_Constants::PUBLISH_STATE_ONLINE;
        }
        $config['iActId'] = $this->active_config_model->add_row($config);

        $this->log('import_config:');
        $this->log(print_r($goods, true));

        return $config;
    }

    /**
     * 导入某期夺宝单数据
     *
     * @param $period
     * @param $active
     * @param $is_current
     *
     * @return array
     */
    private function import_period($period, $active, $is_current)
    {
        $period_data = array(
            'iActId' => $active['iActId'],
            'iPeroid' => $period['iPeriods'],
            'iIsCurrent' => $is_current,
            'iGoodsId' => $active['iGoodsId'],
            'iCateId' => $active['iCateId'],
            'sGoodsName' => $active['sGoodsName'],
            'iGoodsType' => $active['iGoodsType'],
            'iCostPrice' => $active['iCostPrice'],
            'iLowestPrice' => $active['iLowestPrice'],
            'sImg' => $active['sImg'],
            'sImgExt' => $active['sImgExt'],
            'sSearchKey' => $active['sGoodsName'],
            'iActType' => $active['iActType'],
            'iCodePrice' => $active['iCodePrice'],
            'iLotCount' => $active['iLotCount'],
            'iTotalPrice' => $active['iTotalPrice'],
            'iBuyCount' => $active['iBuyCount'],
            'iPeroidBuyCount' => $active['iPeroidBuyCount'],
            'iPeroidCount' => $active['iPeroidCount'],
            'iBeginTime' => $active['iBeginTime'],
            'iEndTime' => $active['iEndTime'],
            'iSoldCount' => $period['iGetCount'],
            'iProcess' => round($period['iGetCount'] / $active['iLotCount'] * 100),
            'iTotalSoldCount' => ($period['iPeriods'] - 1) * $active['iLotCount'] + $period['iGetCount'],
            'iLotTime' => $period['iLotTime'],
            'sWinnerCode' => $period['sLuckyCode'],
            'iWinnerCount' => $period['iTimes'],
            'iLotNumA' => $period['iNumberA'],
            'iLotNumB' => $period['iNumberB'],
            'sLotBasis' => $period['sProof'],
        );

        if ($period['iStatus'] < 2) {
            $period_data['iLotState'] = 0;
        } else {
            $period_data['iLotState'] = $period['iStatus'] - 1;
        }
        if (in_array($period['iStatus'], array(2, 3))) { // 有人中奖了
            $where_record = array(
                'iGrouponId' => $period['iGrouponId'],
                'iPeriods' => $period['iPeriods'],
                'sLuckyCode' => $period['sLuckyCode'],
            );
            $winner = $this->active_luckycode_record_model->get_row($where_record);
            $user = $this->user_model->get_wx_user_by_openid($winner['sOpenId']);
            if (empty($user)) { // 导入用户失败
                $period_data['iWinnerUin'] = -$winner['iUin']; // 数据异常需手动更正
                $period_data['sWinnerNickname'] = $winner['sNickName'];
                $period_data['sWinnerHeadImg'] = $winner['sIconLinkUrl'];
                $period_data['sWinnerOrder'] = $winner['sOrderId']; // @todo
            } else {
                $period_data['iWinnerUin'] = $user['iUin'];
                $period_data['sWinnerNickname'] = $user['sNickName'];
                $period_data['sWinnerHeadImg'] = $user['sHeadImg'];
                $period_data['sWinnerOrder'] = $winner['sOrderId']; // @todo
            }
        }

        $this->active_peroid_model->add_row($period_data);

        return $period_data;
    }
}