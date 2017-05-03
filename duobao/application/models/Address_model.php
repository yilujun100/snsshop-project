<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 购物车MODEL
 */
class Address_model extends MY_Model {
    protected $db_group_name = DATABASE_YYDB_USER;          //分组名
    protected $table_name = 't_address';                //表名
    protected $table_primary = 'iAddressID';                   //主键
    protected $cache_row_key_column = 'iAddressID';               //缓存key字段  可自定义
    protected $table_num= 10;
    protected $db_map_column = 'iUin';  //分表字段
    protected $need_cache_row = false;

    public function __construct() {
        parent::__construct();
    }


    /**
     * 获取用户地址详细
     * @param $id
     * @param $uin
     * @return bool
     */
    public function get_address_info_by_id($id,$uin)
    {
        if(empty($id) || !is_numeric($id)) return false;

        return $address = $this->get_row(array('iAddressID'=>$id,'iUin'=>$uin));
    }


    /**
     * 获取用户地址列表
     * @param $uin
     * @param array $where
     * @param array $order_by
     * @param int $page_index
     * @param int $page_size
     * @return bool
     */
    public function get_user_address_list($uin,$where=array(), $order_by=array(), $page_index = 1, $page_size = self::PAGE_SIZE)
    {
        if(empty($uin)) return false;

        $where = array_merge($where,array('iUin' => $uin));
        return $list = $this->row_list('*',$where, $order_by, $page_index, $page_size);
    }


    /**
     * 新增用户地址
     * eg:$insert = $this->address_model->add_user_address('2147483640','leo','广东省','深圳市','南山区','比克大厦','18927498947','公司地址',1);
     * @param $uin
     * @param $name
     * @param $province
     * @param $city
     * @param $district
     * @param $address
     * @param $mobile
     * @param string $remark
     * @param int $default
     * @param string $zipcode
     * @return bool
     */
    public function add_user_address($uin,$name,$province,$city,$district,$address,$mobile, $remark = '',$default = 0,$zipcode = '')
    {
        if(empty($uin) || empty($name) || empty($province) || empty($city) || empty($district) || empty($address) || empty($mobile)) return false;

        $data = array(
            'iUin' => $uin,
            'sName' => $name,
            'sProvince' => $province,
            'sCity' => $city,
            'sDistrict' => $district,
            'sAddress' => $address,
            'sZipcode' => $zipcode,
            'sMobile' => $mobile,
            'sRemark' => $remark,
            'iIsDefault' => $default,
            'iCreateTime' => time(),
            'iLastModTime' => time()
        );

        if($default == 1){
            $this->update_rows(array('iIsDefault'=>0),array('iUin'=>$uin));
            if(!$result = $this->add_row($data)){
                //$this->log->error('address_model',$this->db->error().' | '.$this->db->last_query());
                return false;
            }
        }else{
            if(!$result = $this->add_row($data)){
                //$this->log->error('address_model',$this->db->error().' | '.$this->db->last_query());
                return false;
            }
        }

        return $result;
    }

    /**
     * 设置默认地址
     * @param $uin
     * @param $address_id
     * @return bool
     */
    public function set_address_default($uin,$address_id)
    {
        if(empty($uin) || empty($address_id)) return false;

        if(!$result = $this->update_rows(array('iIsDefault'=>0),array('iUin'=>$uin))){
            return false;
        }else{
            return true;
        }
    }


    /**
     * 删除用户地址
     * @param $uin
     * @param $address_id
     * @return bool
     */
    public function del_address($uin,$address_id)
    {
        if(empty($uin) || empty($address_id)) return false;

        if($result = $this->delete_rows(array('iUin'=>$uin,'iAddressID'=>$address_id))){
            return true;
        }else{
            return false;
        }
    }


    /**
     * 更新用户地址
     * @param $data
     * @param $uin
     * @param $address_id
     */
    public function update_address($data,$uin,$address_id)
    {
        if(empty($uin) || empty($address_id) || empty($data)) return false;

        if($result = $this->update_row($data,array('iUin'=>$uin,'iAddressID'=>$address_id))){
            return true;
        }else{
            return false;
        }
    }
}