<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 数据库配置分组 【用于主从和简单负载均衡】
 * 配置规则：
 *  调用格式: $this->load->database('yydb_admin_read', true); 会随机选择一个配置
 *  分组名称对应数据库名
 */
$a = array();
$multi_db = array(DATABASE_YYDB_ACTIVE => array('master'=>1,'slave'=>2));
for($i=0; $i<10; $i++) {
    foreach ($multi_db as $db => $conf) {
        $b = array();
        foreach($conf as $key=>$val) {
            switch ($key) {
                case 'master':
                    $b[0] = $db.$i.'_m1';
                    break;
                case 'slave':
                    for ($j=1; $j<=$val; $j++) {
                        $b[] = $db.$i.'_s'.$j;
                    }
                    break;
            }
        }
        $a[$db.$i] = $b;
    }
}
$config['map_reduce'] = array_merge(array(
    DATABASE_YYDB => array('yydb_m1', 'yydb_s1', 'yydb_s2'),   //读库  默认第一个为写库
    DATABASE_MPTOOLS => array('mptools_m1'),   //读库  默认第一个为写库
    DATABASE_YYDB.LOGIC_GROUP_USER => array('yydb_m1', 'yydb_s1', 'yydb_s2'),   //读库  默认第一个为写库
    DATABASE_YYDB.LOGIC_GROUP_USER => array('yydb_m1', 'yydb_s1', 'yydb_s2'),   //读库  默认第一个为写库
    DATABASE_YYDB_USER => array('yydb_user_m1', 'yydb_user_s1', 'yydb_user_s2'),   //读库  默认第一个为写库
    DATABASE_YYDB_USER.LOGIC_GROUP_USER => array('yydb_user_m1', 'yydb_user_s1', 'yydb_user_s2'),   //读库  默认第一个为写库
    DATABASE_YYDB_ACTIVE => array('yydb_active_m1', 'yydb_active_s1', 'yydb_active_s2'),   //读库  默认第一个为写库
    DATABASE_YYDB_STATISTICS => array('yydb_statistics_m1', 'yydb_statistics_s1', 'yydb_statistics_s2'),   //读库  默认第一个为写库
    DATABASE_YYDB_TMP => array('yydb_tmp_m1'),   //读库  默认第一个为写库
//  DATABASE_WTG_WKD => array('wtg_wkd_m1'),   //读库  默认第一个为写库
), $a);