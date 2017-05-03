<?php
defined('BASEPATH') OR exit('No direct script access allowed');



class News extends API_Base
{
    public function __construct()
    {
        parent::__construct(true);
    }


    //获取列表
    /**
     *
     */
    public function get_list()
    {
        extract($this->cdata);

        $this->load->model('news_model');
        if( $list =$this->news_model->get_news('iNewsId,sTitle,sContent,sImg,iCreateTime', array('iState'=>1,'iType'=>$type), array(), $p_cur,$p_size) ){
            $this->render_result(Lib_Errors::SUCC,$list);
        }else{
            $this->log->error('News','select news list fail | sql['.$this->news_model->db->last_query().')] | params['.json_encode($this->cdata).'] | '.__METHOD__);
            $this->render_result(Lib_Errors::SVR_ERR);
        }
    }
    //获取详情
    /**
     *
     */
    public function get_detail()
    {
        extract($this->cdata);
        if(empty($news_id)){
            $this->log->error('News','params['.json_encode($this->cdata).'] | '.__METHOD__);
            $this->render_result(Lib_Errors::PARAMETER_ERR);
        }
        $this->load->model('news_model');
        if( $rs =$this->news_model->get_row(array('iGoodsId'=>$news_id)) ){
            $this->render_result(Lib_Errors::SUCC,$rs);
        }else{
            $this->log->error('News','select news list fail | sql['.$this->news_model->db->last_query().')] | params['.json_encode($this->cdata).'] | '.__METHOD__);
            $this->render_result(Lib_Errors::SVR_ERR);
        }
    }
}