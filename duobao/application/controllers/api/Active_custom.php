<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 *  私人定制
 *
 * Class Active_custom
 */
class Active_custom extends API_Base
{
    /**
     * 列表分页大小
     *
     * @var int
     */
    public static $PAGE_SIZE = 6;

    /**
     * 最大活动名称长度
     */
    const ACTIVE_NAME_LENGTH_MAX = 25;

    /**
     * 单个用户每天最多发起的自定义活动数量
     */
    const DAILY_MAX = 5;

    /**
     * Active_custom constructor.
     */
    public function __construct()
    {
        parent::__construct(true);
    }

    /**
     * 生成自定义夺宝单
     */
    public function generate()
    {
        extract($this->cdata);

        if (empty($uin) || ! $this->check_uin($uin)) {
            $this->output_json(Lib_Errors::UIN_ERROR);
        }

        if (empty($lot_count)) {
            $this->output_json(Lib_Errors::CUSTOM_PEOPLE_ERROR);
        }

        if (empty($active_name) || utf8_strlen($active_name) > self::ACTIVE_NAME_LENGTH_MAX) {
            $this->output_json(Lib_Errors::CUSTOM_ACTIVE_NAME_ERROR);
        }

        if (empty($goods_id)) {
            $this->output_json(Lib_Errors::CUSTOM_GOODS_NOT);
        }
        $this->load->model('goods_item_model');
        if (! ($goods = $this->goods_item_model->get_row($goods_id))) {
            $this->log->error('Active_item','goods get_row failed | params['.json_encode($this->cdata).'] | '.__METHOD__);
            $this->output_json(Lib_Errors::CUSTOM_GOODS_NOT);
        }

        if ($lot_count > ceil($goods['iLowestPrice'] / 100)) {
            $this->output_json(Lib_Errors::CUSTOM_PEOPLE_ERROR);
        }

        $this->load->model('active_config_model');
        $datStart = mktime(0, 0, 0);
        $dayEnd = mktime(23, 59, 59);
        $sql = "SELECT COUNT(*) as total FROM `t_active_config` WHERE iInitiator='{$uin}' AND iCreateTime BETWEEN {$datStart} AND {$dayEnd}";
        $res = $this->active_config_model->query($sql);

        if ($res && $res[0]['total'] >= self::DAILY_MAX) {
            $this->output_json(Lib_Errors::CUSTOM_DAILY_MAX);
        }

        $time = time();
        $data = array(
            'iGoodsId' => $goods_id,
            'iCateId' => $goods['iCateId'],
            'iCateId_1' => $goods['iCateId_1'],
            'iCateId_2' => $goods['iCateId_2'],
            'iCateId_3' => $goods['iCateId_3'],
            'sGoodsName' => $goods['sName'],
            'iGoodsType' => $goods['iType'],
            'iCostPrice' => $goods['iCostPrice'],
            'iLowestPrice' => $goods['iLowestPrice'],
            'sImg' => $goods['sImg'],
            'sImgExt' => $goods['sImgExt'],
            'sSearchKey' => $active_name . '::' . $goods['sName'],
            'iActType' => Lib_Constants::ACTIVE_TYPE_CUSTOM,
            'iIsOpen' => isset($is_open) ? $is_open : 1,
            'iInitiator' => $uin,
            'sActName' => $active_name,
            'iCodePrice' => ceil($goods['iLowestPrice'] / $lot_count / 100) * 100,
            'iLotCount' => $lot_count,
            'iTotalPrice' => $goods['iLowestPrice'],
            'iBuyCount' => $lot_count,
            'iPeroidBuyCount' => $lot_count,
            'iPeroidCount' => 1,
            'iState' => 1,
            'iBeginTime' => $time,
            'iEndTime' => $time + 86400 * 365,
        );

        if ($actId = $this->active_config_model->add_row($data)) {
            if ($this->active_config_model->generate_period($actId)) {
                $this->output_json(Lib_Errors::SUCC, array('actId'=>$actId));
            } else {
                $this->log->error('Active_item','active_config_model generate_period failed | params['.json_encode($data).'] | '.__METHOD__);
                $this->output_json(Lib_Errors::CUSTOM_ADD_PERIOD_FAILED);
            }
        } else {
            $this->log->error('Active_item','active_config_model add_row failed | params['.json_encode($data).'] | '.__METHOD__);
            $this->output_json(Lib_Errors::CUSTOM_ADD_FAILED);
        }
    }

    /**
     * 列表
     */
    public function fetch_list()
    {
        extract($this->cdata);
        if (empty($uin)) {
            $this->log->error('Active_Custom','params['.json_encode($this->cdata).'] | '.__METHOD__);
            $this->output_json(Lib_Errors::PARAMETER_ERR);
        }
        $where = array(
            'iActType' => Lib_Constants::ACTIVE_TYPE_CUSTOM,
            'iInitiator' => $uin,
        );
        if (! empty($win)) {
            $where['iWinnerUin'] = $uin;
            $where['iLotState'] = Lib_Constants::ACTIVE_LOT_DONE;
        }
        $order_by = array(
            'iCreateTime' => 'DESC'
        );
        $page_index = ! empty($p_index) ? intval($p_index) : 1;
        $page_size = ! empty($p_size) ? intval($p_size) : self::$PAGE_SIZE;

        $this->load->model('active_peroid_model');

        $fields = array('iActId,iPeroid,sGoodsName,iGoodsId,sImg,iCodePrice,iProcess,iSoldCount,iIsCurrent,iCornerMark,iLotState,iLotTime,sWinnerCode,iWinnerUin,sWinnerNickname,iWinnerCount,sWinnerOrder');

        if($list = $this->active_peroid_model->row_list($fields, $where, $order_by, $page_index, $page_size)) {
//            $list['sql'] = $this->active_peroid_model->db->last_query();
            $list['where'] = $where;
            if ($list['count'] > 0) {
                $this->load->model('order_summary_model');
                foreach ($list['list'] as & $v) {
                    $v['joinCount'] = $this->order_summary_model->get_join_count($uin, $v['iActId'], $v['iPeroid']);
                }
            }
            $this->output_json(Lib_Errors::SUCC, $list);
        }
        $this->output_json(Lib_Errors::SVR_ERR);
    }

    /**
     * 公开列表
     */
    public function custom_list()
    {
        extract($this->cdata);

        $where = array(
            'iActType' => Lib_Constants::ACTIVE_TYPE_CUSTOM,
            'iIsOpen' => 1
        );
        $order_by = isset($order_by) && $order_by == 'hot' ? array('iProcess'=>'DESC') : array('iCreateTime'=>'DESC');
        $page_index = ! empty($p_index) ? intval($p_index) : 1;
        $page_size = ! empty($p_size) ? intval($p_size) : self::$PAGE_SIZE;

        $this->load->model('active_peroid_model');

        $fields = array('iPeroidCode,iActId,iPeroid,sGoodsName,iGoodsId,sImg,iCodePrice,iProcess,iSoldCount,iIsCurrent,iCornerMark,iLotState,sActName');

        if($list = $this->active_peroid_model->row_list($fields, $where, $order_by, $page_index, $page_size)) {
            $list['where'] = $where;
            $this->render_result(Lib_Errors::SUCC, $list);
        }
        $this->render_result(Lib_Errors::SVR_ERR);
    }
}