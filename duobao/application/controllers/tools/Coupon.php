<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Coupon extends Tools_Base
{
    public function top100()
    {
        $sql =<<<q
SELECT * FROM (SELECT iUin,(iHisCoupon-iHisGiftCoupon) AS iHisBuyCoupon FROM yydb_user.t_user_ext0 ORDER BY (iHisCoupon - iHisGiftCoupon) DESC LIMIT 100) t0
UNION ALL
SELECT * FROM (SELECT iUin,(iHisCoupon-iHisGiftCoupon) AS iHisBuyCoupon FROM yydb_user.t_user_ext1 ORDER BY (iHisCoupon - iHisGiftCoupon) DESC LIMIT 100) t1
UNION ALL
SELECT * FROM (SELECT iUin,(iHisCoupon-iHisGiftCoupon) AS iHisBuyCoupon FROM yydb_user.t_user_ext2 ORDER BY (iHisCoupon - iHisGiftCoupon) DESC LIMIT 100) t2
UNION ALL
SELECT * FROM (SELECT iUin,(iHisCoupon-iHisGiftCoupon) AS iHisBuyCoupon FROM yydb_user.t_user_ext3 ORDER BY (iHisCoupon - iHisGiftCoupon) DESC LIMIT 100) t3
UNION ALL
SELECT * FROM (SELECT iUin,(iHisCoupon-iHisGiftCoupon) AS iHisBuyCoupon FROM yydb_user.t_user_ext4 ORDER BY (iHisCoupon - iHisGiftCoupon) DESC LIMIT 100) t4
UNION ALL
SELECT * FROM (SELECT iUin,(iHisCoupon-iHisGiftCoupon) AS iHisBuyCoupon FROM yydb_user.t_user_ext5 ORDER BY (iHisCoupon - iHisGiftCoupon) DESC LIMIT 100) t5
UNION ALL
SELECT * FROM (SELECT iUin,(iHisCoupon-iHisGiftCoupon) AS iHisBuyCoupon FROM yydb_user.t_user_ext6 ORDER BY (iHisCoupon - iHisGiftCoupon) DESC LIMIT 100) t6
UNION ALL
SELECT * FROM (SELECT iUin,(iHisCoupon-iHisGiftCoupon) AS iHisBuyCoupon FROM yydb_user.t_user_ext7 ORDER BY (iHisCoupon - iHisGiftCoupon) DESC LIMIT 100) t7
UNION ALL
SELECT * FROM (SELECT iUin,(iHisCoupon-iHisGiftCoupon) AS iHisBuyCoupon FROM yydb_user.t_user_ext8 ORDER BY (iHisCoupon - iHisGiftCoupon) DESC LIMIT 100) t8
UNION ALL
SELECT * FROM (SELECT iUin,(iHisCoupon-iHisGiftCoupon) AS iHisBuyCoupon FROM yydb_user.t_user_ext9 ORDER BY (iHisCoupon - iHisGiftCoupon) DESC LIMIT 100) t9
ORDER BY iHisBuyCoupon DESC LIMIT 100;
q;
        $this->load->model('user_model');
        $this->load->model('user_ext_model');
        $this->load->model('user_summary_model');

        $data = array();
        $users = $this->user_ext_model->query($sql);
        $ip_arr = array();
        foreach ($users as $user) {
            $uin = $user['iUin'];
            $where = array('iUin' => $uin);
            $summary = $this->user_summary_model->get_rows($where);
            if (! isset($data[$uin])) {
                $u = $this->user_model->get_user_by_uin($uin);
                $data[$uin] = array(
                    'nickName' => $u['sNickName'],
                    'iHisBuyCoupon' => $user['iHisBuyCoupon'],
                    'goods' => array(),
                    'address' => array(),
                );
            }
            foreach ($summary as $v) {
                $goods_id = $v['iGoodsId'];
                if (! isset($data[$uin]['goods'][$goods_id])) {
                    $data[$uin]['goods'][$goods_id] = array(
                        'goodsName' => $v['sGoodsName'],
                        'codeCount' => count(explode(',', $v['sLuckyCodes']))
                    );
                } else {
                    $data[$uin]['goods'][$goods_id]['codeCount'] += count(explode(',', $v['sLuckyCodes']));
                }
                if ($ip = $v['iIP']) {
                    if (false === strpos($ip, '.')) {
                        $ip = long2ip($ip);
                    }
                    if ($ip) {
                        if (isset($ip_arr[$ip])) {
                            $address = $ip_arr[$ip];
                        } else if ($location = $this->ip_location($ip)) {
                            $address = $location['region'] . ' ' . $location['city'];
                            $ip_arr[$ip] = $address;
                        } else {
                            $address = '';
                            $ip_arr[$ip] = $address;
                        }
                        if ($address && ! in_array($address, $data[$uin]['address'])) {
                            $data[$uin]['address'][] = $address;
                        }
                    }
                }
            }
        }

        $tr = '';
        foreach ($data as $k => $v) {
            $goods_count = count($v['goods']);
            $tr .= '<tr>';

            $tr .= '<td rowspan="'.$goods_count.'">';
            $tr .= $v['nickName'];
            $tr .= '</td>';

            $tr .= '<td rowspan="'.$goods_count.'">';
            $tr .= $k;
            $tr .= '</td>';

            $tr .= '<td rowspan="'.$goods_count.'">';
            $tr .= $v['iHisBuyCoupon'];
            $tr .= '</td>';

            $tr .= '<td rowspan="'.$goods_count.'">';
            $tr .= implode('<br>', $v['address']);
            $tr .= '</td>';

            reset($v['goods']);
            $id = key($v['goods']);
            $good = current($v['goods']);

            $tr .= '<td>';
            $tr .= $id;
            $tr .= '</td>';
            $tr .= '<td>';
            $tr .= $good['goodsName'];
            $tr .= '</td>';
            $tr .= '<td>';
            $tr .= $good['codeCount'];
            $tr .= '</td>';

            $tr .= '</tr>';

            unset($v['goods'][$id]);

            foreach ($v['goods'] as $id => $good) {
                $tr .= '<tr>';
                $tr .= '<td>';
                $tr .= $id;
                $tr .= '</td>';
                $tr .= '<td>';
                $tr .= $good['goodsName'];
                $tr .= '</td>';
                $tr .= '<td>';
                $tr .= $good['codeCount'];
                $tr .= '</td>';
                $tr .= '</tr>';
            }
        }
        echo '<!DOCTYPE html><html lang="en"><body><table border="1">'.$tr.'</table></body></html>';
    }

    /**
     * ip定位
     *
     * @param $ip
     *
     * @return string
     */
    private function ip_location($ip)
    {
        static $ipObj;
        if (! is_object($ipObj)) {
            include APPPATH . 'libraries/ip/Ip.class.php';
            $dbFile = APPPATH . 'libraries/ip/ip.db';
            $ipObj = new Ip($dbFile);
        }
        if ($ip_data = $ipObj->binarySearch($ip)) {
            $location = explode('|', $ip_data['region']);
            return array(
                'region' => $location[2],
                'city' => $location[3],
            );
        }
    }
}