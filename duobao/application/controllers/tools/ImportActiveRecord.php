<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 导入微团购魔法森林参与记录
 *
 * Class ImportActiveRecord
 */
class ImportActiveRecord extends Script_Base
{
    /**
     * 单次处理的记录数
     */
    const LIMIT = 2000;

    /**
     * 脚本最大执行时间
     */
    const MAX_RUN_TIME = 18000;

    /**
     * @var string
     */
    protected $log_type = 'ImportActiveRecord';

    public function __construct()
    {
        parent::__construct();

        $this->load->model('active_luckycode_record_model');

        $this->load->model('active_config_model');
        $this->load->model('active_peroid_model');
        $this->load->model('luckycode_record_model');
        $this->load->model('luckycode_summary_model');
        $this->load->model('user_model');
    }

    /**
     * 修复伪造订单
     */
    public function repair_fake_order()
    {
        $this->load->model('active_merage_order_model');
        $this->load->model('active_order_model');
        $this->load->model('order_summary_model');

        $user_exception = array();

        foreach (array(0, 4) as $v) {
            $table_name = 't_luckycode_summary' . $v;
            $sql = "SELECT * FROM `{$table_name}` WHERE sOrderId LIKE '44%';";
            $fake_list = $this->luckycode_summary_model->query($sql);

            $merage = $active = $order_summary = $luckycode_summary = array();

            foreach ($fake_list as $fake) {
                $user = $this->user_model->get_user_by_uin($fake['iUin']);
                if(empty($user['sOpenId'])) {
                    $user_exception[] = array('fake'=>$fake, 'user'=>$user);
                    continue;
                }

                $table = $this->user_model->map($user['iUin'])->get_cur_table();
                $table_ext = substr($table, -1);

                $merageOrderId = $this->setOrderId(Lib_Constants::PLATFORM_WTG, Lib_Constants::ORDER_TYPE_WTG_FAKE, $fake['iUin']);
                $merage['t_active_merage_order'.$table_ext][] = array(
                    'sMergeOrderId' => '"'.$merageOrderId.'"',
                    'iUin' => '"'.$user['iUin'].'"',
                    'iTotalPrice' => $fake['iLotCount'] * 100,
                    'iCoupon' => 0,
                    'iAmount' => $fake['iLotCount'] * 100,
                    'iPayAgentType' => Lib_Constants::ORDER_PAY_TYPE_WX,
                    'iPayTime' => $fake['iLastModTime'] - 86400 * 10,
                    'iPayStatus' => Lib_Constants::PAY_STATUS_PAID,
                    'sTransId' => '"'.$merageOrderId.'"',
                    'iPlatformId' => Lib_Constants::PLATFORM_WTG,
                    'iIP' => '"'.'127.0.0.1'.'"',
                    'iLocation' => '""',
                    'iCreateTime' => $fake['iLastModTime'] - 86400 * 10 - 10,
                    'iLastModTime' => time()
                );

                $active_order = $this->setOrderId(Lib_Constants::PLATFORM_WTG, Lib_Constants::ORDER_TYPE_WTG_FAKE, $fake['iUin']);
                $active['t_active_order'.$table_ext][] = array(
                    'sOrderId' => '"'.$active_order.'"',
                    'sMergeOrderId' =>'"'.$merageOrderId.'"',
                    'iUin' => '"'.$user['iUin'].'"',
                    'iGoodsId' => $fake['iGoodsId'],
                    'iActId' => $fake['iActId'],
                    'iPeroid' => $fake['iPeroid'],
                    'iBuyType' => Lib_Constants::ORDER_TYPE_ACTIVE,
                    'iCount' => $fake['iLotCount'],
                    'iUnitPrice' => Lib_Constants::COUPON_UNIT_PRICE,
                    'iTotalPrice' => $fake['iLotCount'] * Lib_Constants::COUPON_UNIT_PRICE,
                    'iAmount' => $fake['iLotCount'] * Lib_Constants::COUPON_UNIT_PRICE,
                    'iPayAgentType' => Lib_Constants::ORDER_PAY_TYPE_WX,
                    'iPayTime' => $fake['iLastModTime'] - 86400 * 10,
                    'sTransId' => '"'.$active_order.'"',
                    'iPayStatus' => Lib_Constants::PAY_STATUS_PAID,
                    'iCreateTime' => $fake['iCreateTime'],
                    'iLastModTime' => time()
                );

                $peroid = $this->active_peroid_model->get_row(array('sWinnerOrder'=>$fake['sOrderId']));
                if(! empty($peroid)) {
                    $is_win = 1;
                } else {
                    $is_win = 0;
                }

                $order_summary['t_order_summary'.$table_ext][] = array(
                    'iActId' => $fake['iActId'],
                    'iPeroid' => $fake['iPeroid'],
                    'iGoodsId' => $fake['iGoodsId'],
                    'sGoodsName' => '"'.$fake['sGoodsName'].'"',
                    'sOrderId' => '"'.$active_order.'"',
                    'iUin' => $user['iUin'],
                    'sNickName' => '"'.addslashes($user['sNickName']).'"',
                    'sHeadImg' => '"'.$user['sHeadImg'].'"',
                    'iLotCount' => $fake['iLotCount'],
                    'iLotTime' => $fake['iLotTime'],
                    'iLotState' => $fake['iLotState'],
                    'iIsWin' => $is_win,
                    'sLuckyCodes' => "'".$fake['sLuckyCodes']."'",
                    'iIP' => '"'.'127.0.0.1'.'"',
                    'iLocation' => '""',
                    'iCreateTime' => $fake['iCreateTime'],
                    'iLastModTime' => time(),
                );

                //更新订单号
                $this->active_peroid_model->update_row(array('sWinnerOrder'=>$active_order), array('sWinnerOrder'=>$fake['sOrderId']));
                $this->luckycode_summary_model->update_rows(array('sOrderId'=>$active_order), array('sOrderId'=>$fake['sOrderId'],'iActId'=>$fake['iActId']));
            }

            $this->add_merage_order($merage);
            $this->add_active_order($active);
            $this->add_order_summary($order_summary);
        }
    }

    /**
     * 加大订单
     *
     * @param $data
     *
     * @return bool
     */
    private function add_merage_order($data){
        foreach($data as $table =>$val){
            $count = count($val)-1;
            $inert_str = $ext_str = "";
            foreach($val as $k => $v){
                $inert = implode(',',$v);
                $inert = '('.$inert.')';
                $inert_str .= $k == $count ? $inert : ($inert.',');
            }
            $inert_str = trim($inert_str,',');
            $sql = "insert into `".$table."` (`sMergeOrderId`,`iUin`,`iTotalPrice`,`iCoupon`,`iAmount`,`iPayAgentType`,`iPayTime`,`iPayStatus`,`sTransId`,`iPlatformId`,`iIP`,`iLocation`,`iCreateTime`,`iLastModTime`) values ".$inert_str;
            if(!$this->active_merage_order_model->query($sql)){
                pr($this->active_merage_order_model->db->lasst_query());
                return false;
            }
        }

        return true;
    }

    /**
     * 小订单
     *
     * @param $data
     *
     * @return bool
     */
    private function add_active_order($data){
        foreach($data as $table =>$val){
            $count = count($val)-1;
            $inert_str = $ext_str = "";
            foreach($val as $k => $v){
                $inert = implode(',',$v);
                $inert = '('.$inert.')';
                $inert_str .= $k == $count ? $inert : ($inert.',');
            }
            $inert_str = trim($inert_str,',');
            $sql = "insert into `".$table."` (`sOrderId`,`sMergeOrderId`,`iUin`,`iGoodsId`,`iActId`,`iPeroid`,`iBuyType`,`iCount`,`iUnitPrice`,`iTotalPrice`,`iAmount`,`iPayAgentType`,`iPayTime`,`sTransId`,`iPayStatus`,`iCreateTime`,`iLastModTime`) values ".$inert_str;
            if(!$this->active_order_model->query($sql)){
                pr($this->active_order_model->db->lasst_query());
                return false;
            }
        }

        return true;
    }

    /**
     * 加用户订单汇总
     *
     * @param $data
     *
     * @return bool
     */
    private function add_order_summary($data)
    {
        foreach($data as $table =>$val){
            $count = count($val)-1;
            $inert_str = $ext_str = "";
            foreach($val as $k => $v){
                $inert = implode(',',$v);
                $inert = '('.$inert.')';
                $inert_str .= $k == $count ? $inert : ($inert.',');
            }
            $inert_str = trim($inert_str,',');
            $sql = "insert into `".$table."` (`iActId`,`iPeroid`,`iGoodsId`,`sGoodsName`,`sOrderId`,`iUin`,`sNickName`,`sHeadImg`,`iLotCount`,`iLotTime`,`iLotState`,`iIsWin`,`sLuckyCodes`,`iIP`,`iLocation`,`iCreateTime`,`iLastModTime`) values ".$inert_str;
            if(!$this->order_summary_model->query($sql)){
                pr($this->order_summary_model->db->lasst_query());
                return false;
            }
        }
        return true;
    }

    /**
     * 统一返回指定格式的订单号
     * 格式：平台类型+订单类型+年月日时分秒+微秒+随机数+uin后两位
     * @param $plat_from
     * @param $uin
     * @param $type
     * @return string
     */
    private function setOrderId($plat_from, $type, $uin = null)
    {
        list($usec, $sec) = explode(" ", microtime());
        $usec = substr($usec,2,4);
        if($uin == null){
            return $plat_from.$type.date('YmdHis').$usec.substr(implode(NULL, array_map('ord', str_split(substr(uniqid(), 7, 13), 1))), 0, 4);
        }else{
            $suffix = substr($uin,-2);
            return $plat_from.$type.date('YmdHis').$usec.substr(implode(NULL, array_map('ord', str_split(substr(uniqid(), 7, 13), 1))), 0, 2).$suffix;
        }
    }

    /**
     * 运行脚本
     */
    public function run()
    {
        set_time_limit(0);

        $page_size = self::LIMIT;

        $begin = time();

        $page_index = $this->get_post('page_index', 1);

        $this->log("====================================BEGIN(".date('Y-m-d H:i:s').")=============================================\n\n");

        $where = array(
            'iActId' => 10048
        );

        $order_by = array(
            'iGrouponId' => 'ASC',
            'iPeriods' => 'DESC',
            'iAutoId' => 'DESC',
        );

        $alc_record = $this->active_luckycode_record_model->row_list('*', $where, $order_by, $page_index, $page_size);

        if (! isset($alc_record['count'])  || $alc_record['count'] < 1) {
            return;
        }

        echo ("=== row[".$alc_record['count']."] === page_size[".$page_size."] === page_count[".$alc_record['page_count']."] === page_index[".$page_index."] ===</br>");

        $alc_summary = array();

        foreach ($alc_record['list'] as $v) {

            $active = $this->get_active_groupon_id($v['iGrouponId']);

            $record = $this->import_luckycode_record($v, $active);

            $this->combine_luckycode_summary($v, $active, $record, $alc_summary);
        }

        $this->import_luckycode_summary($alc_summary);

        if ($page_index >= $alc_record['page_count'] || time() - $begin > self::MAX_RUN_TIME) {
            return;
        }

        echo $url = 'http://dev.vikduo.com/tools/ImportActiveRecord/run?page_index=' . (++ $page_index);
        echo '<script type="text/javascript">location.href="' . $url . '"</script>';

        $this->log("====================================END(".date('Y-m-d H:i:s').")=============================================\n\n");
    }

    /**
     * 导入参与记录汇总
     *
     * @param $alc_summary
     */
    private function import_luckycode_summary($alc_summary)
    {
        foreach ($alc_summary as $v) {
            $where_summary = array(
                'iActId' => $v['iActId'],
                'iPeroid' => $v['iPeroid'],
                'sOrderId' => $v['sOrderId'],
            );
            $summary_row = $this->luckycode_summary_model->get_row($where_summary);
            if ($summary_row) {
                $lucky_code = array_unique(array_merge(json_decode($summary_row['sLuckyCodes'], true), json_decode($v['sLuckyCodes'], true)));
                sort($lucky_code);
                $summary_update = array(
                    'sLuckyCodes' => json_encode($lucky_code)
                );
                $this->luckycode_summary_model->update_row($summary_update, $where_summary);
            } else {
                $this->luckycode_summary_model->add_row($v);
            }
        }
    }

    /**
     * 参与记录汇总
     *
     * @param $record_raw
     * @param $active
     * @param $record
     * @param $alc_summary
     */
    private function combine_luckycode_summary($record_raw, $active, $record, & $alc_summary)
    {
        if (! isset($alc_summary[$record['sOrderId']])) {

            $alc_summary[$record['sOrderId']] = array(
                'iActId' => $record['iActId'],
                'iPeroid' => $record['iPeroid'],
                'iGoodsId' => $record['iGoodsId'],
                'sGoodsName' => $active['sGoodsName'],
                'iUin' => $record['iUin'],
                'sOrderId' => $record['sOrderId'],
                'sNickName' => $record['sNickName'],
                'sLuckyCodes' => json_encode(array($record_raw['sLuckyCode'])),
                'iNotifyStatus' => 1,
                'iCreateTime' => $record_raw['iLotTime'],
                'iLastModTime' => $record_raw['iLotTime'],
            );

            $summary = & $alc_summary[$record['sOrderId']];

            $period = $this->get_active_period($record['iActId'], $record['iPeroid']);
            if (empty($period)) {
                $summary['iLotTime'] = 0;
                $summary['iLotState'] = -1;
            } else {
                $summary['iLotTime'] = $period['iLotTime'];
                $summary['iLotState'] = $period['iLotState'];
            }
            if (-1 == $summary['iLotState']) {
                $summary['iSoonStatus'] = 1;
                $summary['iResultStatus'] = 1;
            } else if (0 == $summary['iLotState']) {
                $summary['iSoonStatus'] = 0;
                $summary['iResultStatus'] = 0;
            } else if (1 == $summary['iLotState']) {
                $summary['iSoonStatus'] = 1;
                $summary['iResultStatus'] = 0;
            } else {
                $summary['iSoonStatus'] = 1;
                $summary['iResultStatus'] = 1;
            }

            $user = $this->user_model->get_wx_user_by_openid($record_raw['sOpenId']);
            if (empty($user)) { // 导入用户失败
                $record['iUin'] = -$record_raw['iUin']; // 数据异常需手动更正
                $record['sNickName'] = $record_raw['sNickName'];
            } else {
                $record['iUin'] = $user['iUin'];
                $record['sNickName'] = $user['sNickName'];
            }
            if (empty($user)) { // 导入用户失败
                $summary['sHeadImg'] = $record_raw['sIconLinkUrl'];
            } else {
                $summary['sHeadImg'] = $user['sHeadImg'];
            }

            $iLotCount = 1;
        } else {

            $summary = & $alc_summary[$record['sOrderId']];

            $lucky_code = json_decode($summary['sLuckyCodes'], true);
            if (! in_array($record_raw['sLuckyCode'], $lucky_code)) {
                $lucky_code[] = $record_raw['sLuckyCode'];
            }
            sort($lucky_code);

            $summary['sLuckyCodes'] = json_encode($lucky_code);
            $iLotCount = count($lucky_code);
        }
        $summary['iLotCount'] = $iLotCount;
    }

    /**
     * 导入参与记录
     *
     * @param $record_raw
     * @param $active
     *
     * @return array
     */
    private function import_luckycode_record($record_raw, $active)
    {
        $record = array(
            'iGoodsId' => $active['iGoodsId'],
            'iActId' => $active['iActId'],
            'iPeroid' => $record_raw['iPeriods'],
            'sOrderId' => $record_raw['sOrderId'], // @todo
            'sLuckyCode' => $record_raw['sLuckyCode'],
            'iCreateTime' => $record_raw['iLotTime'],
            'iMsecTime' => $record_raw['iMLotTime'],
        );

        $user = $this->user_model->get_wx_user_by_openid($record_raw['sOpenId']);
        if (empty($user)) { // 导入用户失败
            $record['iUin'] = -$record_raw['iUin']; // 数据异常需手动更正
            $record['sNickName'] = $record_raw['sNickName'];
        } else {
            $record['iUin'] = $user['iUin'];
            $record['sNickName'] = $user['sNickName'];
        }

        $record['iRid'] = $this->luckycode_record_model->add_row($record);

        return $record;
    }

    /**
     * 获取某期夺宝单数据
     *
     * @param $act_id
     * @param $period
     *
     * @return mixed
     */
    private function get_active_period($act_id, $period)
    {
        static $_cache = array();
        $key = period_code_encode($act_id, $period);
        if (! isset($_cache[$key])) {
            $where_period = array(
                'iActId' => $act_id,
                'iPeroid' => $period,
            );
            $_cache[$key] = $this->active_peroid_model->get_row($where_period);
        }
        return $_cache[$key];
    }

    /**
     * 获取夺宝单数据
     *
     * @param $iGrouponId
     *
     * @return mixed
     */
    private function get_active_groupon_id($iGrouponId)
    {
        static $_cache = array();
        if (! isset($_cache[$iGrouponId])) {
            $where_active = array(
                'iGrouponId' => $iGrouponId
            );
            $_cache[$iGrouponId] = $this->active_config_model->get_row($where_active);
        }
        return $_cache[$iGrouponId];
    }
}