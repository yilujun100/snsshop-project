<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Advert extends API_Base
{
    public function __construct()
    {
        parent::__construct(true);
        $this->load->model('advert_item_model');
    }

    /**
     * 广告列表
     */
    public function ad_list()
    {
        extract($this->cdata);
        $this->load->model('advert_position_model');

        if (empty($position_id) ||
            ! ($position = $this->advert_position_model->get_row($position_id))) {
            $this->log->error('Advert','params['.json_encode($this->cdata).'] | '.__METHOD__);
            $this->render_result(Lib_Errors::PARAMETER_ERR);
        }

        $where = array(
            'iPositionId' => (int)$position_id,
            'iState' => Lib_Constants::PUBLISH_STATE_ONLINE
        );

        if (! isset($closed) || true !== $closed) {
            $time = time();
            $where['iBeginTime <='] = $time;
            $where['iEndTime >='] = $time;
        }

        $order_by = array('iSort'=>'DESC', 'iCreateTime'=>'DESC');
        $limit = $position['iAdCount'];

        $result = $this->advert_item_model->row_list('iAdId, sTitle, sDesc, sImg, sTarget,iBeginTime,iEndTime', $where, $order_by, 1, $limit);

        $this->output_json(Lib_Errors::SUCC, $result['list']);
    }
}