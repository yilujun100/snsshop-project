<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Robot extends Admin_Base
{
    protected $relation_model = '';
	/**
	 * 构造函数
	 *
	 * Goods constructor
	 */
	public function __construct()
	{
		parent::__construct();

		//$this->load->model($this->relation_model);
	}

    /**
     * 机器人列表
     */
    public function index()
    {
        $page = $this->get('page', 1);

        $order_by = array(
            'iId' => 'DESC'
        );

        $robot_uin = trim($this->get('robot_uin', 0));
        $robot_nickname = trim($this->get('robot_nickname', ''));
//		$robot_gender = intval($this->get('robot_gender', -1));
        $robot_state = intval($this->get('robot_state', -1));

        $where = array();
        if ($robot_uin > 0) {
            $where['iUin'] = $robot_uin;
        }
        if ($robot_nickname) {
            $where['like']['sNickName'] = $robot_nickname;
        }
        /*if ($robot_gender > -1) {
            $where['iGender'] = $robot_gender;
        }*/
        if ($robot_state > -1) {
            $where['iState'] = $robot_state;
        }

        $this->load->model('robot_model');

        $result_list = $this->robot_model->row_list('*', $where, $order_by, $page);

        $viewData = array(
            'result_list' => $result_list,
            'robot_uin' => $robot_uin,
            'robot_nickname' => $robot_nickname,
//			'robot_gender' => $robot_gender,
            'robot_state' => $robot_state
        );

        $this->predefine_asset('validate');

        $this->render($viewData);
    }

    /**
     * 启用/禁用机器人
     */
    public function state()
    {
        $uin = $this->post('robot_uin');
        $state = $this->post('robot_state');

        if (! $uin || ! in_array($state, array_keys(Lib_Constants::$robot_states))) {
            $this->output_json(Lib_Errors::PARAMETER_ERR);
        }

        if (Lib_Constants::ROBOT_STATE_DISABLED == $state) { // 禁用
            $disable_type = intval($this->post('disable_type', 0));
            if (1 == $disable_type) { // 按天禁用
                $disable_day = intval($this->post('disable_day', 0));
                if ($disable_day < 1) {
                    $this->output_json(Lib_Errors::PARAMETER_ERR);
                }
                $data = array(
                    'iState' => $state,
                    'iDisableTime' => time() + 86400 * $disable_day
                );
            } else if (2 == $disable_type) { // 永久禁用
                $data = array(
                    'iState' => $state,
                    'iDisableTime' => -1
                );
            } else {
                $this->output_json(Lib_Errors::PARAMETER_ERR);
            }
        } else {
            $data = array(
                'iDisableTime' => 0,
                'iState' => $state
            );
        }

        $this->load->model('robot_model');

        if (! $this->robot_model->update_row($data, $uin)) {
            $this->output_json(Lib_Errors::ROBOT_STATE_FAILED);
        }
        $this->output_json(Lib_Errors::SUCC);
    }

    /**
     * 晒单
     */
    public function share()
    {
        $page = $this->get('page', 1);

		$order_by = array(
            'iLotTime' => 'DESC',
            'iUpdateTime' => 'DESC',
			'iCreateTime' => 'DESC'
		);

        /*$share_goods_id = intval($this->get('share_goods_id', ''));
        $share_act_id = intval($this->get('share_act_id', ''));*/

        $beginTime = trim($this->get('beginTime', ''));
        $endTime = trim($this->get('endTime', ''));
        $robotUin = trim($this->get('robotUin', ''));

        $where = array(
            'iIsRobot' => 1,
            'iLotState' => 2
        );
        if ($beginTime > 0) {
            $where['iLotTime >='] = strtotime($beginTime);
        }
        if ($endTime > 0) {
            $where['iLotTime <='] = strtotime($endTime);
        }
        if ($robotUin > 0) {
            $where['iWinnerUin'] = $robotUin;
        } else {
            $where['iWinnerUin >'] = 0;
        }

        $this->load->model('active_peroid_model');
        $this->load->model('share_model');

        $result_list = $this->active_peroid_model->row_list('*', $where, $order_by, $page);

        if ($result_list['count'] > 0) {
            foreach ($result_list['list'] as & $item) {
                $params = array(
                    'iUin' => $item['iWinnerUin']
                );
                if ($share = $this->share_model->get_row($params)) {
                    $item['iShared'] = 1;
                    $item['iShareId'] = $share['iShareId'];
                    $item['sShareContent'] = $share['sContent'];
                    if ($share['sImg'] && ($share_img = json_decode($share['sImg'], TRUE))) {
                        $item['shareImg'] = $share_img;
                    }
                } else {
                    $item['shared'] = 0;
                }
            }
        }
        $viewData = array(
            'result_list' => $result_list,
            'beginTime' => $beginTime,
            'endTime' => $endTime,
            'robotUin' => $robotUin,
        );

        $this->predefine_asset('datetimepicker');

        $this->render($viewData);
    }

    /**
     * 新增晒单
     */
    public function share_add()
    {
        if (! $this->input->is_ajax_request()) {
            if (! ($period = $this->check_period())) {
                show_404();
            }
            $this->share_edit_asset();
            $data = array(
                'period' => $period,
            );
            $this->render($data, 'robot/share_edit');
        } else {
            if (! ($period = $this->check_period())) {
                $this->output_json(Lib_Errors::PARAMETER_ERR);
            }
            $this->load->model('robot_model');
            if (! ($robot = $this->robot_model->get_robot($period['iWinnerUin']))) {
                $this->output_json(Lib_Errors::PARAMETER_ERR);
            }
            $this->load->library('form_validation');
            $this->set_form_validation();
            if (FALSE === $this->form_validation->run()) {
                $errors = $this->form_validation->error_array();
                $this->render_result(Lib_Errors::PARAMETER_ERR, array($errors), reset($errors));
            }

            $input = $this->post($this->get_share_field());
            if (1 == $input['onlineType']) {
                $online_time = time();
            } else {
                $online_time = strtotime($input['onlineTime']);
            }

            $data = array(
                'sContent' => $input['shareContent'],
                'iUin' => $period['iWinnerUin'],
                'sNickName' => $period['sWinnerNickname'],
                'sHeadImg' => $period['sWinnerHeadImg'],
                'iActId' => $period['iActId'],
                'iPeriod' => $period['iPeroid'],
                'sGoodsName' => $period['sGoodsName'],
                'iLuckyCode' => $period['sWinnerCode'],
                'iLotTime' => $period['iLotTime'],
                'iLotCount' => $period['iLotCount'],
                'iWinnerCount' => $period['iWinnerCount'],
                'iPlatForm' => Lib_Constants::PLATFORM_WX,
                'iIp' => empty($robot['sIp'])?0:ip2long($robot['sIp']),
                'iGoodsId' => $period['iGoodsId'],
                'sGoodsImg' => $period['sImg'],
                'iAudit' => 1,
                'iIsRobot' => 1,
                'iOnlineTime' => $online_time,
            );

            $img = array();
            for ($i = 1; $i < 6; $i ++) {
                $key = 'share_img' . $i;
                if (! empty($input[$key])) {
                    $img[strval($i)] = $input[$key];
                }
            }
            if (! empty($img)) {
                $data['sImg'] = json_encode($img);
            }

            $this->load->model('share_model');

            if (! $this->share_model->add_row($data)) {
                $this->log->error('admin | share_add', 'insert database failed', $data);
                $this->render_result(Lib_Errors::ROBOT_SHARE_MODIFY_FAILED);
            }

            $this->render_result(Lib_Errors::SUCC);
        }
    }

    /**
     * 编辑晒单
     */
    public function share_edit()
    {
        if (! $this->input->is_ajax_request()) {
            if (! ($share = $this->check_share())) {
                show_404();
            }
            $this->share_edit_asset();
            if ($share['sImg'] && ($share_img = json_decode($share['sImg'], TRUE))) {
                foreach ($share_img as $k => $item) {
                    $share['share_img'][$k] = $item;
                }
            }
            $data = array(
                'share_id' => $share['iShareId'],
                'item' => $share
            );
            $this->render($data, 'robot/share_edit');
        } else {
            if (! ($share = $this->check_share())) {
                $this->output_json(Lib_Errors::PARAMETER_ERR);
            }
            if (! ($period = $this->check_period($share['iActId'], $share['iPeriod']))) {
                $this->output_json(Lib_Errors::PARAMETER_ERR);
            }
            $this->load->library('form_validation');
            $this->set_form_validation();
            if (FALSE === $this->form_validation->run()) {
                $errors = $this->form_validation->error_array();
                $this->render_result(Lib_Errors::PARAMETER_ERR, array($errors), reset($errors));
            }

            $input = $this->post($this->get_share_field());
            if (1 == $input['onlineType']) {
                $online_time = time();
            } else {
                $online_time = strtotime($input['onlineTime']);
            }
            $data = array(
                'sContent' => $input['shareContent'],
                'iOnlineTime' => $online_time,
            );

            $img = array();
            for ($i = 1; $i < 6; $i ++) {
                $key = 'share_img' . $i;
                if (! empty($input[$key])) {
                    $img[strval($i)] = $input[$key];
                }
            }
            if (! empty($img)) {
                $data['sImg'] = json_encode($img);
            }

            $this->load->model('share_model');

            if (! $this->share_model->update_row($data, $share['iShareId'])) {
                $this->log->error('admin | share_edit', 'update database failed', $data);
                $this->render_result(Lib_Errors::ROBOT_SHARE_MODIFY_FAILED);
            }

            $this->render_result(Lib_Errors::SUCC);
        }
    }

    /**
     * 查看详情
     */
    public function share_detail()
    {
        if (! ($share = $this->check_share())) {
            show_404();
        }
        if (! ($period = $this->check_period($share['iActId'], $share['iPeriod']))) {
            show_404();
        }
        if ($share['sImg'] && ($share_img = json_decode($share['sImg'], TRUE))) {
            foreach ($share_img as $k => $item) {
                $share['share_img'][$k] = $item;
            }
        }
        $data = array(
            'share_id' => $share['iShareId'],
            'period' => $period,
            'item' => $share
        );
        $this->render($data, 'robot/share_detail');
    }

    /**
     * 获取机器人分享可编辑字段
     *
     * @return array
     */
    protected function get_share_field()
    {
        return array('onlineType','onlineTime','share_img1','share_img2','share_img3','share_img4','share_img5','shareContent');
    }

    /**
     * 检查晒单
     *
     * @return bool
     */
    protected function check_share()
    {
        $id = intval($this->get_post('share_id'), 0);
        if ($id < 1) {
            return FALSE;
        }
        $this->load->model('share_model');
        if (! ($share = $this->share_model->get_row($id)) ||
            $share['iIsRobot'] != 1) {
            return FALSE;
        }
        return $share;
    }

    /**
     * 检查中奖纪录
     *
     * @param int $act
     * @param int $period
     *
     * @return bool|int
     */
    protected function check_period($act = 0, $period = 0)
    {
        if ($act < 1 || $period < 1) {
            $act = intval($this->get_post('act'), 0);
            $period = intval($this->get_post('period'), 0);
        }
        if ($act < 1 || $period < 1) {
            return false;
        }
        $this->load->model('active_peroid_model');
        $where = array(
            'iActId' => $act,
            'iPeroid' => $period
        );
        if (! ($period = $this->active_peroid_model->get_row($where)) ||
            $period['iLotState'] != 2 ||
            $period['iWinnerUin'] < 1 ||
            $period['iIsRobot'] != 1) {
            return false;
        }
        return $period;
    }

    /**
     * 上传分享图
     */
    public function share_upload_img()
    {
        $config = array(
            'allowed_types' => 'jpg|png',
            /*'max_size' => 1024,
            'max_width' => 960,
            'max_height' => 960,
            'min_width' => 320,*/
        );
        $res = upload_files('upload_img', 'robot_share', $config);
        if (0 != $res['error']) {
            $this->output_json(Lib_Errors::SVR_ERR, $res, $res['msg']);
        } else {
            $this->output_json(Lib_Errors::SUCC, array('uri' => $res['url']));
        }
    }

    /**
     * 设置登录表单验证规则
     *
     */
    protected function set_form_validation()
    {
        $config = array(
            array(
                'field' => 'shareContent',
                'label' => '晒单内容',
                'rules' => array(
                    'required',
                    'min_length[10]',
                    'max_length[500]'
                )
            ),
            array(
                'field' => 'share_img1',
                'label' => '图一',
                'rules' => 'required'
            ),
            array(
                'field' => 'onlineType',
                'label' => '发布类型',
                'rules' => 'required'
            ),
            array(
                'field' => 'onlineTime',
                'label' => '上线时间',
                'rules' => array(
                    'required',
                    array('check_online_time', array($this, 'check_online_time'))
                ),
            )
        );
        $this->form_validation->set_rules($config);
    }

    /**
     * 上线时间检查
     *
     * @param $online_time
     *
     * @return bool
     */
    public function check_online_time($online_time)
    {
        if (1 == $this->post('onlineType')) {
            return TRUE;
        }
        if (strtotime($online_time) < time()) {
            $this->form_validation->set_message('check_online_time', '上线时间不能小于当前时间');
            return FALSE;
        }
        return TRUE;
    }

    /**
     * 添加编辑晒单需要用到的资源
     */
    protected function share_edit_asset()
    {
        $this->predefine_asset('validate');
        $this->predefine_asset('upload');
        $this->predefine_asset('datetimepicker');
    }

    /**
     * 机器人统计 - 实时
     */
    public function stat()
    {
        set_time_limit(0);
        date_default_timezone_set('PRC');
        $this->predefine_asset('validate');
        $this->predefine_asset('datetimepicker');

        $view_data = $this->get_robot_stat_realtime();

        $this->render($view_data);
    }

    /**
     * 机器人统计导出 - 实时
     */
    public function excel()
    {
        set_time_limit(0);
        date_default_timezone_set('PRC');

        $view_data = $this->get_robot_stat_realtime();

        $title = array( '日期','总开奖人数','总开奖金额(元)','用户参与次数','用户参与金额(元)','用户中奖次数','用户中奖金额(元)','用户盈亏(元)','用户奖品成本(元)','采购盈亏(元)', '平台盈亏(元)');
        $desc = array( '字段备注','*只针对当天(0点-24点)已开奖夺宝单统计','*当天已开奖夺宝单的累计价值，即每单的码数*每码价格','*当天已开奖夺宝单中用户实际参与的次数','当天已开奖夺宝单中用户实际参与的券数金额','*当天已开奖夺宝单中用户实际中奖的次数','*当天已开奖夺宝单中用户实际中奖的夺宝单累计价值','*用户中奖金额-用户参与券数金额','*对应商品的[最低售价]字段','*用户中奖金额-用户奖品成本', '*采购盈亏-用户盈亏');
        $result_list = array('title'=>$title,'date'=>array($desc),'file_title'=>'全盘统计');
        if (!empty($view_data['list_stat'])) {
            foreach ($view_data['list_stat'] as $item) {
                $result_list['date'][] = array(
                    date('Y-m-d', $item['iStatTime']),
                    $item['iOpenCount'],
                    price_format($item['iOpenAmount']),
                    $item['iJoinCoupon'],
                    price_format($item['iJoinAmount']),
                    $item['iWinCount'],
                    price_format($item['iWinAmount']),
                    price_format($item['iFloatAmount']),
                    price_format($item['iCostAmount']),
                    price_format($item['iFloatSourAmount']),
                    price_format($item['iFloatPlatAmount']),
                );
            }
        }

        if(empty($result_list)) {
            $this->log->error('robot_excel', 'result is null');
            $this->render_result(Lib_Errors::PARAMETER_ERR);
        }

        $this->log->error('robot_excel', 'result is not null');
        $excel = new Lib_Excel($result_list['title'] , $result_list['date']);
        $this->log->error('robot_excel', 'new Lib_Excel' . json_encode($result_list));
        $excel->download('百分好礼-' .$result_list['file_title'].date('Y-m-d',time()).'.xlsx');
        $this->log->error('robot_excel', ' Lib_Excel download');
    }

    /**
     * 机器人统计 - 延时10分钟
     */
    public function stat_delay()
    {
        set_time_limit(0);
        date_default_timezone_set('PRC');
        $this->predefine_asset('validate');
        $this->predefine_asset('datetimepicker');

        $view_data = $this->get_robot_stat_delay();

        $this->render($view_data);
    }

    /**
     * 机器人统计导出 - 延时10分钟
     */
    public function excel_delay()
    {
        date_default_timezone_set('PRC');

        $view_data = $this->get_robot_stat_delay();

        $title = array( '日期','总开奖人数','总开奖金额(元)','用户参与次数','用户参与金额(元)','用户中奖次数','用户中奖金额(元)','用户盈亏(元)','用户奖品成本(元)','采购盈亏(元)', '平台盈亏(元)');
        $desc = array( '字段备注','*只针对当天(0点-24点)已开奖夺宝单统计','*当天已开奖夺宝单的累计价值，即每单的码数*每码价格','*当天已开奖夺宝单中用户实际参与的次数','当天已开奖夺宝单中用户实际参与的券数金额','*当天已开奖夺宝单中用户实际中奖的次数','*当天已开奖夺宝单中用户实际中奖的夺宝单累计价值','*用户中奖金额-用户参与券数金额','*对应商品的[最低售价]字段','*用户中奖金额-用户奖品成本', '*采购盈亏-用户盈亏');
        $result_list = array('title'=>$title,'date'=>array($desc),'file_title'=>'全盘统计');
        if (!empty($view_data['list_stat'])) {
            foreach ($view_data['list_stat'] as $item) {
                $result_list['date'][] = array(
                    date('Y-m-d', $item['iStatTime']),
                    $item['iOpenCount'],
                    price_format($item['iOpenAmount']),
                    $item['iJoinCoupon'],
                    price_format($item['iJoinAmount']),
                    $item['iWinCount'],
                    price_format($item['iWinAmount']),
                    price_format($item['iFloatAmount']),
                    price_format($item['iCostAmount']),
                    price_format($item['iFloatSourAmount']),
                    price_format($item['iFloatPlatAmount']),
                );
            }
        }

        if(empty($result_list)) {
            $this->log->error('robot_excel', 'result is null');
            $this->render_result(Lib_Errors::PARAMETER_ERR);
        }

        $this->log->error('robot_excel', 'result is not null');
        $excel = new Lib_Excel($result_list['title'] , $result_list['date']);
        $this->log->error('robot_excel', 'new Lib_Excel' . json_encode($result_list));
        $excel->download('百分好礼-' .$result_list['file_title'].date('Y-m-d',time()).'.xlsx');
        $this->log->error('robot_excel', ' Lib_Excel download');
    }

    /**
     * 取统计数据 - 实时
     * @param $start_time
     * @param $end_time
     * @return array
     */
    private function get_robot_stat($start_time, $end_time)
    {
        $open_count = $open_amount = $join_count = $join_amount = $cost_amount = $win_count = $win_amount = 0;

        $this->load->model('active_peroid_model');
        $active_peroid_table = $this->active_peroid_model->get_cur_table();
        /*开奖总次数 开奖金额 成本*/
        $sql = 'select count(*) open_count, sum(iTotalPrice) open_amount from '.$active_peroid_table.' where iActType='.Lib_Constants::ACTIVE_TYPE_SYS.' and iLotState='.Lib_Constants::ACTIVE_LOT_STATE_OPENED.' and iLotTime >='.$start_time.' and iLotTime<'.$end_time.';';
        $res = $this->active_peroid_model->query($sql);
        if (!empty($res) && !empty($res[0])) {
            $open_count = intval($res[0]['open_count']); // 开奖总次数
            $open_amount = intval($res[0]['open_amount']);//开奖金额

        }

        /*用户中奖次数 用户中奖金额*/
        $sql = 'select count(a.iPeroidCode) win_count, sum(a.iTotalPrice) win_amount, sum(b.iLowestPrice) cost_amount from '.$active_peroid_table.' a left join t_goods_item b on a.iGoodsId = b.iGoodsId  where a.iIsRobot = 0 and  a.iActType='.Lib_Constants::ACTIVE_TYPE_SYS.' and a.iLotState='.Lib_Constants::ACTIVE_LOT_STATE_OPENED.' and a.iLotTime >='.$start_time.' and a.iLotTime<'.$end_time.';';
        //$sql = 'select count(*) win_count, sum(iTotalPrice) win_amount, sum(iLowestPrice) cost_amount from '.$active_peroid_table.' where iIsRobot = 0 and  iActType='.Lib_Constants::ACTIVE_TYPE_SYS.' and iLotState='.Lib_Constants::ACTIVE_LOT_STATE_OPENED.' and iLotTime >='.$start_time.' and iLotTime<'.$end_time.';';
        $res = $this->active_peroid_model->query($sql);
        if (!empty($res) && !empty($res[0])) {
            $win_count = intval($res[0]['win_count']);//用户中奖次数
            $win_amount = intval($res[0]['win_amount']);//用户中奖金额
            $cost_amount = intval($res[0]['cost_amount']);//奖品成本
        }

        /*用户参与次数 参与金额*/
        $table_name = 't_user_summary';
        $db_name = 'yydb_active';
        $active_table_name = $this->active_peroid_model->get_cur_database().'.'.$this->active_peroid_model->get_cur_table();
        $db_num = $table_num = 10;
        for ($i=0; $i<$db_num; $i++) {
            for ($j=0; $j<$table_num; $j++) {
                $tmp_table = $db_name.$i.'.'.$table_name.$j;
                $sql = 'select sum(a.iLotCount) join_count, sum(b.iCodePrice*a.iLotCount) join_amount from '.$tmp_table.' a left join '.$active_table_name.' b on a.iActId=b.iActId and a.iPeroid=b.iPeroid where a.iIsRobot = 0 and  b.iActType='.Lib_Constants::ACTIVE_TYPE_SYS.' and b.iLotState='.Lib_Constants::ACTIVE_LOT_STATE_OPENED.' and b.iLotTime >='.$start_time.' and b.iLotTime<'.$end_time.';';
                $res = $this->active_peroid_model->query($sql);
                if (!empty($res) && !empty($res[0])) {
                    $join_count += intval($res[0]['join_count']);//用户中奖次数
                    $join_amount += intval($res[0]['join_amount']);//用户中奖金额
                }
            }
        }
        return array(
            'iStatTime' => strtotime(date('Y-m-d', $start_time)),
            'iOpenCount' => $open_count,//开奖总次数
            'iOpenAmount' => $open_amount,//开奖总金额
            'iJoinCoupon' => $join_count,//用户参与次数
            'iJoinAmount' => $join_amount,//参与金额
            'iWinCount' => $win_count,//中奖次数
            'iWinAmount' => $win_amount,//中奖金额
            'iFloatAmount' => ($win_amount-$join_amount),//用户盈亏 = 中奖金额 - 用户参与金额
            'iCostAmount' => $cost_amount,//用户奖品成本
            'iFloatSourAmount' => ($win_amount - $cost_amount), //采购盈亏 = 用户中奖金额 - 用户奖品成本
            'iFloatPlatAmount' => (($win_amount - $cost_amount) - ($win_amount-$join_amount)),//平台盈亏 = 采购盈亏 - 用户盈亏
        );
    }

    /**
     * 取统计数据 - 实时
     * @return array
     */
    private function get_robot_stat_realtime()
    {
        $now = time();
        $begin_time = $this->get('beginTime', date('Y-m-d', strtotime('-15 days')));
        $end_time = $this->get('endTime', date('Y-m-d', $now));

        $view_data = array(
            'beginTime' => strtotime($begin_time),
            'endTime' => strtotime($end_time),
            'total' => array(
                'iOpenCount' => 0,//开奖总次数
                'iOpenAmount' => 0,//开奖总金额
                'iJoinCoupon' => 0,//参与次数
                'iJoinAmount' => 0,//参与金额
                'iWinCount' => 0,//中奖次数
                'iWinAmount' => 0,//中奖金额
                'iFloatAmount' => 0,//用户盈亏 = 中奖金额 - 用户参与金额
                'iCostAmount' =>  0,//用户奖品成本
                'iFloatSourAmount' => 0, //采购盈亏 = 用户中奖金额 - 用户奖品成本
                'iFloatPlatAmount' => 0,//平台盈亏 = 采购盈亏 - 用户盈亏
            ),
            'days' => array()
        );
        if ($view_data['beginTime'] > $view_data['endTime']) {
            return $view_data;
        }

        $start_time = strtotime(date('Y-m-d', $view_data['beginTime']));
        $end_time = strtotime(date('Y-m-d', $view_data['endTime'])) + 86400;

        $today_time = strtotime('today');
        if ($end_time > $today_time) {
            $end_time = $today_time+86400;
        }


        $view_data['total_stat'] = $this->get_robot_stat($start_time, $end_time);

        while ($start_time < $end_time) {
            $date = date('Y-m-d', $start_time);
            $tmp_s = $start_time;
            $start_time += 86400;
            $view_data['list_stat'][$date] = $this->get_robot_stat($tmp_s, ($tmp_s+86400));
        }

        return $view_data;
    }

    /**
     * 取统计数据 - 延时
     * @return array
     */
    private function get_robot_stat_delay()
    {
        $begin_time = $this->get('beginTime', date('Y-m-d', strtotime('-15 days')));
        $end_time = $this->get('endTime', date('Y-m-d',strtotime('-1 days')));

        $view_data = array(
            'beginTime' => strtotime($begin_time),
            'endTime' => strtotime($end_time),
        );

        $today_time = strtotime('today');
        if ($view_data['endTime'] > ($today_time)) {
            $view_data['endTime'] = $today_time;
        }

        /*汇总*/
        $this->load->model('active_daily_model');
        $sql = 'select
            iStatTime,
            sum(iOpenCount) iOpenCount,
            sum(iOpenAmount) iOpenAmount,
            sum(iJoinCoupon) iJoinCoupon,
            sum(iJoinAmount) iJoinAmount,
            sum(iWinCount) iWinCount,
            sum(iWinAmount) iWinAmount,
            sum(iFloatAmount) iFloatAmount,
            sum(iCostAmount) iCostAmount,
            sum(iFloatSourAmount) iFloatSourAmount,
            sum(iFloatPlatAmount) iFloatPlatAmount
            from '.$this->active_daily_model->get_cur_table().'
            where iStatTime >= '.$view_data['beginTime'].' and iStatTime <='.$view_data['endTime'];
        $row = $this->active_daily_model->query($sql);
        $total_stat = empty($row[0]) ? array() : $row[0];

        /*详情*/
        $sql = 'select
            iStatTime,
            sum(iOpenCount) iOpenCount,
            sum(iOpenAmount) iOpenAmount,
            sum(iJoinCoupon) iJoinCoupon,
            sum(iJoinAmount) iJoinAmount,
            sum(iWinCount) iWinCount,
            sum(iWinAmount) iWinAmount,
            sum(iFloatAmount) iFloatAmount,
            sum(iCostAmount) iCostAmount,
            sum(iFloatSourAmount) iFloatSourAmount,
            sum(iFloatPlatAmount) iFloatPlatAmount
            from '.$this->active_daily_model->get_cur_table().'
            where iStatTime >= '.$view_data['beginTime'].' and iStatTime <='.$view_data['endTime'].'
            group by iStatTime';

        $list_stat = $this->active_daily_model->query($sql);

        $view_data['total_stat'] = $total_stat;
        $view_data['list_stat'] = $list_stat;

        return $view_data;
    }
}