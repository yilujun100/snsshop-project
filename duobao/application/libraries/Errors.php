<?php

// 本类处于过渡状态，有新增的错误码和提示请用const和$ERR_MSG_MAP，其余两块逐步废弃

class Lib_Errors
{
    const SUCC									= 0;			//成功
    const UNKONWN_ERR							= -100000;		//未知错误
    const PARAMETER_ERR							= -100001;		//参数错误
    const NOT_LOGIN								= -100002;		//未登陆
    const USER_NOT_EXISTS						= -100003;		//用户不存在
    const SVR_ERR								= -100005;		//操作后台失败
    const INVALID_REQUEST						= -100006;		//无效请求,cdata解板出错
    const UNKNOW_CLIENT							= -100007;		//未知客户端
    const CLIENT_VERSION_ERROR					= -100008;		//客户端版本错误
    const EXCEPTION_REQUEST                     = -100009;      //异常请求
    const CONFIG_ERR                            = -100010;      //配置错误
    const API_URL_MAP                           = -100011;      //API 接口不存在
    const UIN_ERROR                             = -100012;      //uin 参数错误
    const REQUEST_ERROR                         = -100013;      // 网络请求失败，请稍后重试
    const NOT_SUBSCRIBE                         = -100014;      // 未关注公众号

    // 后台错误
    const HANDLE_FAILED                         = -120000;      //操作失败
    const USER_EXISTS							= -120001;		//用户名已存在
    const USER_PASSWORD_ERROR   				= -120002;		//用户名或密码错误
    const USER_IS_INVALID       				= -120003;		//抱歉，您的账号已被禁用，请联系管理员
    const ROLE_ID_INVALID       				= -120004;		//抱歉，您的账号对应角色不存在，请联系管理员
    const PERMISSION_DENIED       				= -120005;		//抱歉，您暂时没有进行该操作的权限
    const HAS_OL_AWARDS_ACTIVITY                = -121005;      //存在已上线的活动，请先下线活动
    const AUDIT_ONLINE_ERR                      = -120006;      //上线状态只能进行下线操作
    const AUDIT_OFFLINE_ERR                     = -120007;      //下线状态只能进行上线/删除/编辑操作
    const AUDIT_READY_ERR                       = -120008;      //确认状态只能进行上线/删除/编辑操作
    const NO_CHILDREN_CATE                      = -120009;      //下级类目不存在
    const CATE_NAME_ERROR                       = -120010;      //类目名称错误
    const CATE_REMARK_ERROR                     = -120011;      //类目名称错误
    const CATE_SORT_ERROR                       = -120012;      //类目显示顺序错误
    const CATE_NOT_EXIST                        = -120013;      //类目类目不存在
    const GOODS_IMG_UPLOAD_FAILED               = -120014;      //商品图片上传失败
    const GOODS_MODIFY_FAILED                   = -120015;      //添加/编辑商品失败
    const GOODS_ID_NOT_EXIST                    = -120016;      //商品ID不存在
    const GOODS_NOT_ONLINE                      = -120017;      //商品未上线
    const ACTIVE_MODIFY_FAILED                  = -120018;      //添加/编辑夺宝单失败
    const USER_MODIFY_FAILED                    = -120019;      //添加/编辑用户失败
    const USER_DELETE_FAILED                    = -120020;      //删除用户失败
    const USER_STATE_FAILED                     = -120021;      //启用/禁用用户失败
    const ROLE_EXISTS                           = -120022;      //启用/角色名已存在
    const ROLE_MODIFY_FAILED                    = -120023;      //添加/编辑角色失败
    const ROLE_DELETE_FAILED                    = -120024;      //删除角色失败
    const ADVERT_IMG_UPLOAD_FAILED              = -120025;      //广告图片上传失败
    const ADVERT_MODIFY_FAILED                  = -120026;      //添加/编辑广告失败
    const ROLE_PURVIEW_FAILED                   = -120027;      //授权失败
    const RECOMMEND_MODIFY_FAILED               = -120028;      //添加/编辑推荐失败
    const ACTIVE_ID_NOT_EXISTS                  = -120029;      //夺宝单不存在
    const ACTIVE_RECOMMEND                      = -120030;      //夺宝单已推荐
    const ACTIVE_STATE_ERROR                    = -120031;      //夺宝单已结束/下线
    const ONLINE_CAN_NOT_DELETE                 = -120032;      //上线状态无法删除
    const DELIVER_FAILED                        = -120033;      //发货失败
    const DELIVER_ID_NOT_EXIST                  = -120034;      //发货ID不存在
    const DELIVER_USER_INCOMPLETE               = -120035;      //收货人信息不完整
    const DELIVER_TRACE_INCOMPLETE              = -120036;      //发货轨迹不完整
    const AWARDS_TYPE_NOT_ONLINE                = -120037;      //奖励类型未上线
    const HAVE_ONLINE_ACTIVITY                  = -120038;      //存在已上线的活动
    const ADVERT_POS_REQUIRED                   = -120039;      //请选择广告位
    const GOODS_UNDONE_ACTIVE                   = -120040;      //该商品存在未完成的夺宝单
    const GOODS_UNDONE_ACTIVITY                 = -120041;      //该商品存在未完成的积分兑换活动
    const GOODS_EDIT_ONLINE_FAILED              = -120042;      //线上编辑商品失败
    const ACTIVE_NOT_BEGIN                      = -120043;      //活动未开始
    const ACTIVE_ENDED                          = -120044;      //活动已结束
    const ACTIVE_PERIOD_LIMIT                   = -120045;      //活动已满期
    const ACTIVE_GENERATE_FAILED                = -120046;      //生成新一期活动失败
    const ACTIVE_UPDATE_STATE_FAILED            = -120047;      //更新活动状态失败
    const ACTIVE_ONLINE_FAILED                  = -120048;      //更新活动为上线状态失败
    const ACTIVE_END_FAILED                     = -120049;      //更新活动为结束状态失败
    const VARIABLE_NOT_EXISTS                   = -120050;      //该变量不存在
    const VARIABLE_MODIFY_FAILED                = -120051;      //添加/修改变量失败
    const VARIABLE_DELETE_FAILED                = -120052;      //删除变量失败
    const ACTIVE_HAS_SOLD                       = -120053;      //活动销量不为0
    const ACTIVE_TERMINATE_FAILED               = -120054;      //结单失败
    const ROBOT_STATE_FAILED                    = -120055;      // 启用/禁用机器人失败
    const ROBOT_SHARE_MODIFY_FAILED             = -120056;      // 添加/编辑机器人晒单失败
    const ACTIVE_EDIT_ONLINE_FAILED             = -120057;      // 线上编辑活动失败
    const ACTIVE_EDIT_ONLINE_PERIOD_FAILED      = -120058;      // 同步更新当前期失败
    const DELIVER_BATCH_FAILED                  = -120059;      // 批量发货失败
    const DELIVER_BATCH_EXCEL_EMPTY             = -120060;      // 上传的表格没有数据
    const DELIVER_ID_ERROR                      = -120061;      // 发货ID错误
    const DELIVER_DONE                          = -120062;      // 已确认收货
    const DELIVER_PURCHASE_PRICE_ERROR          = -120063;      // 发货成本错误
    const DELIVER_PURCHASE_PAY_STATE            = -120064;      // 采购付款错误
    const DELIVER_PURCHASE_PAY_TYPE             = -120065;      // 采购支付方式错误
    const DELIVER_FEE_ERROR                     = -120066;      // 运费错误
    const DELIVER_EXPRESS_ERROR                 = -120066;      // 快递公司错误
    const DELIVER_EXPRESS_ID                    = -120067;      // 快递单号错误


    //数据库错误
    const DB_CONN                               = -130001;      //数据库连接错误
    const DB_EXEC                               = -130002;      //SQL执行错误
    const DB_NOT_MAP                            = -130003;      //未指定分库分表字段值

    //业务错误
    const OVERTIME								= -210013;		//已经过期
    const PAYED									= -210014;		//订单已支付
    const REFUNDED								= -210015;		//已经退款
    const TOO_MANY_WORD							= -210024;		//字数超过限制
    const TOKEN_ERROR							= -210025;		//token错误，防止CSRF
    const ADDRESS_NOT_FOUND						= -210032;		//收货地址不存在
    const MOBILE_ERROR                          = -210034;      //手机号有误
    const CART_NOT_FOUND                        = -210035;      //购物车不存在
    const ORDER_CLEARING_AMOUNT                 = -210040;      //订单结算金额有误
    const ORDER_NOT_FOUND                       = -210041;      //订单不存在
    const ORDER_TYPE_NOT_FOUND                  = -210042;      //订单类型不存在
    const ORDER_CASHIER_UNDEFINED               = -210043;      //该类型订单未定义收银员
    const PAY_AGENT_ERROR                       = -210044;      //请选择正确的支付方式
    const UPDATE_ORDER_ADDRESS                  = -210045;      //更新订单收货地址出错
    const ORDER_VALID                           = -210046;      //订单还未失效
    const DELIVER_CONFIRMED                     = -210047;      //已确认发货
    const ORDER_INVALID                         = -210048;      //订单已失效
    const ORDER_UNDELIVERED                     = -210049;      //订单未发货
    const ADD_DELIVER_FAILED                    = -210050;      //添加发货记录失败

    //夺宝活动相关
    const ACTIVE_NOT_FOUND                      = -220000;      //活动不存在
    const ACTIVE_OVER_TIME                      = -220001;      //活动当期已开奖或不存在
    const ACTIVE_OUTLINE                        = -220002;      //活动已下线
    const ACTIVE_IS_NOT_LOTTEY                  = -220003;      //活动不是开奖状态
    const ACTIVE_NOT_STOCK                      = -220004;      //当期夺宝码数已经售完
    const ACTIVE_COUPON_NOT_STOCK               = -220005;      //夺宝券数量不足
    const ACTIVE_USER_TIME_UPPER                = -220006;      //用户单次购买上限
    const ACTIVE_USER_PEROID_UPPER              = -220007;      //用户单期购买上限

    //用户信息相关
    const OPENID_IS_EMPTY                       = -230001;       //微信用户OPENID为空
    const USER_INFO_FAILED                      = -230002;       //获取用户信息失败

    //用户奖励相关
    const NO_ONLINE_AWARDS                      = -240001;       //没有在线奖励活动

    //用户夺宝相关
    const NOT_ATTEND_ACTIVE                     = -250001;      //用户未参与夺宝
    const ORDER_NOT_PAIED                       = -250002;      //订单未支付
    const GOODS_NOT_DELIVER                     = -250003;      //商品未发货
    const DELIVER_NOT_CONFIRM                   = -250004;      //未确认收货
    const NOT_WINNER_USER                       = -250005;      //不是中奖者
    const REDUCE_COUPON_FAILED                  = -250006;      //扣券失败
    const REDUCE_FREE_COUPON_FAILED             = -250007;      //参数次数不够
    const SIGN_ERROR                            = -250010;      //sign签名失败

    // 消息中心相关
    const MESSAGE_MAX_LENGTH                    = -260000;      //消息内容超出系统允许的最大长度
    const MESSAGE_EXPIRE_ERROR                  = -260001;      //消息过期时间不能小于当前时间
    const MESSAGE_ID_ERROR                      = -260002;      //消息 msg_id 参数错误
    const MESSAGE_READ_FAILED                   = -260003;      //消息标记为已读失败
    const MESSAGE_TEM_DATA_ERROR                = -260004;      //模板数据错误
    const MESSAGE_NOTIFY_TYPE_INVALID           = -260005;      //无效通知类型
    const MESSAGE_SEND_MY_FAILED                = -260006;      //发送个人中心通知失败
    const MESSAGE_TO_UIN_ERROR                  = -260007;      //收信人Uin错误
    const MESSAGE_SEND_WX_FAILED                = -260008;      //发送微信通知失败
    const MESSAGE_BUSINESS_TYPE_FAILED          = -260009;      //消息业务类型失败
    const MESSAGE_FOREIGN_KEY_ERROR             = -260010;      //消息外键错误或不能为空

	//积分
    const SCORE_NOT_ENOUGH                      = -270001;      //积分不足
    const SCORE_ACTIVITY_TIMEOUT                = -270002;      //积分活动已过期
    const SCORE_ACTIVITY_OFFLINE                = -270003;      //积分活动已下线
    const SCORE_ACTIVITY_NOT_BEGIN              = -270004;      //积分活动未开始
    const SCORE_LARGE_THAN_SINGLE               = -270005;      //大于单次限购数
    const SCORE_LARGE_THAN_TOTAL                = -270006;      //大于总限购数
    const SCORE_STOCK_NOT_ENOUGH                = -270007;      //库存不足

	// 私人定制
    const CUSTOM_GOODS_NOT                      = -280000;      // 商品不存在
    const CUSTOM_ACTIVE_NAME_ERROR              = -280001;      // 活动名称错误
    const CUSTOM_PEOPLE_ERROR                   = -280002;      // 参与人数错误
    const CUSTOM_ADD_FAILED                     = -280003;      // 添加“私人定制”失败
    const CUSTOM_ADD_PERIOD_FAILED              = -280004;      // 生成一期“私人定制”失败
    const CUSTOM_DAILY_MAX                      = -280005;      // 请明天再试

    //购物车
    const CART_SVR_ERR                          = -290001; //购物车操作失败

    //签到
    const USER_SIGNED                           = -300001; //已签到

    //点赞
    const USER_LIKED                            = -300002; //已点赞

    //福袋
    const BAG_HAVE_GET                          = -310001; //已领取
    const BAG_NOT_PAID                          = -310002; //福袋未支付
    const BAG_NOT_ACTIVE                        = -310003; //福袋未激活
    const BAG_HAVE_DONE                         = -310004; //福袋已领完

    // 拼团
    const GROUPON_NOT_EXISTS                    = -320000; // 团购不存在
    const GROUPON_NOT_ONLINE                    = -320001; // 团购不在线
    const GROUPON_NOT_START                     = -320002; // 团购未开始
    const GROUPON_ENDED                         = -320003; // 团购已结束
    const GROUPON_SOLD_OUT                      = -320004; // 团购已售罄
    const GROUPON_TYPE_ERROR                    = -320005; // 团购类型错误
    const GROUPON_SPEC_NOT_EXISTS               = -320006; // 团购规格不存在
    const GROUPON_SPEC_NOT_MATCH                = -320007; // 团购规格不匹配
    const GROUPON_DIY_NOT_EXISTS                = -320008; // 拼团不存在
    const GROUPON_DIY_NOT_START                 = -320009; // 拼团未开始
    const GROUPON_DIY_ENDED                     = -320010; // 拼团已结束
    const GROUPON_DIY_PEOPLE_MAX                = -320011; // 拼团已达到开团人数
    const GROUPON_ORDER_NOT_EXISTS              = -320012; // 团购订单不存在
    const GROUPON_ORDER_USER_ERROR              = -320013; // 团购订单用户异常
    const GROUPON_ORDER_UNPAID                  = -320014; // 团购订单未支付
    const GROUPON_ORDER_USED                    = -320015; // 团购订单已使用
    const GROUPON_ORDER_CREATE_FAILED           = -320016; // 团购订单创建失败
    const GROUPON_ORDER_TYPE_ERROR              = -320017; // 团购订单类型错误
    const GROUPON_ORDER_PAID_ERROR              = -320018; // 设置团购订单为「已支付」时出错
    const GROUPON_JOINED                        = -320019; // 已经参与过该团了
    const GROUPON_JOIN_FAILED                   = -320020; // 参团失败
    const GROUPON_HAS_ONGOING_DIY               = -320021; // 有正在进行中的拼团
    const GROUPON_NOT_DIY_ORDER                 = -320022; // 不是开团订单

    const SHARE_INVITE_ALREADY_GET              = -330001; //分享有礼 - 已领取过夺宝券



    public static $ERR_CODE_MAP = array(
        self::SUCC									=>'成功',			//成功
        self::UNKONWN_ERR							=>'未知错误',		//如果没有配置对应的code，则默认用这个
        self::PARAMETER_ERR							=>'参数错误',		//参数错误
        self::NOT_LOGIN								=>'未登陆',		//未登陆
        self::USER_NOT_EXISTS						=>'用户不存在',		//用户不存在
        self::SVR_ERR								=>'操作后台失败',		//操作后台失败
        self::INVALID_REQUEST						=>'无效请求',		//无效请求,cdata解板出错
        self::UNKNOW_CLIENT							=>'未知客户端',		//未知客户端
        self::CLIENT_VERSION_ERROR					=>'客户端版本错误',		//客户端版本错误
        self::EXCEPTION_REQUEST					    =>'异常请求',		//客户端版本错误
        self::CONFIG_ERR                            =>'配置错误',
        self::API_URL_MAP                           =>'接口不存在',
        self::UIN_ERROR                             =>'uin 参数错误',
        self::REQUEST_ERROR                         =>'网络请求失败，请稍后重试',
        self::NOT_SUBSCRIBE                         =>'未关注微信公众号',

        //数据库错误
        self::DB_CONN								=>'数据库连接错误',
        self::DB_EXEC								=>'数据库执行错误',
        self::DB_NOT_MAP						    =>'数据库映射错误',		//未指定分库分表字段值

        // 后台错误
        self::HANDLE_FAILED                         => '操作失败',
        self::USER_EXISTS							=> '用户名已存在',
        self::USER_PASSWORD_ERROR   				=> '用户名或密码错误',
        self::USER_IS_INVALID       				=> '抱歉，您的账号已被禁用，请联系管理员',
        self::ROLE_ID_INVALID       				=> '抱歉，您的账号对应角色不存在，请联系管理员',
        self::PERMISSION_DENIED       				=> '抱歉，您暂时没有进行该操作的权限',
        self::HAS_OL_AWARDS_ACTIVITY                => '存在已上线的活动，请先下线活动',
        self::AUDIT_ONLINE_ERR                      => '上线状态只能进行下线操作',
        self::AUDIT_OFFLINE_ERR                     => '下线状态只能进行上线/删除/编辑操作',
        self::AUDIT_READY_ERR                       => '确认状态只能进行上线/删除/编辑操作',
        self::NO_CHILDREN_CATE                      => '下级类目不存在',
        self::CATE_NAME_ERROR                       => '类目名称错误',
        self::CATE_REMARK_ERROR                     => '类目备注错误',
        self::CATE_SORT_ERROR                       => '类目显示顺序错误',
        self::CATE_NOT_EXIST                        => '类目类目不存在',
        self::GOODS_IMG_UPLOAD_FAILED               => '商品图片上传失败',
        self::GOODS_MODIFY_FAILED                   => '添加/编辑商品失败',
        self::GOODS_ID_NOT_EXIST                    => '商品ID不存在',
        self::GOODS_NOT_ONLINE                      => '商品未上线',
        self::ACTIVE_MODIFY_FAILED                  => '添加/编辑夺宝单失败',
        self::USER_MODIFY_FAILED                    => '添加/编辑用户失败',
        self::USER_DELETE_FAILED                    => '删除用户失败',
        self::USER_STATE_FAILED                     => '启用/禁用用户失败',
        self::ROLE_EXISTS                           => '角色名已存在',
        self::ROLE_MODIFY_FAILED                    => '添加/编辑角色失败',
        self::ROLE_DELETE_FAILED                    => '删除色失败',
        self::ADVERT_IMG_UPLOAD_FAILED              => '广告图片上传失败',
        self::ADVERT_MODIFY_FAILED                  => '添加/编辑广告失败',
        self::ROLE_PURVIEW_FAILED                   => '授权失败',
        self::RECOMMEND_MODIFY_FAILED               => '添加/编辑推荐失败',
        self::ACTIVE_ID_NOT_EXISTS                  => '夺宝单不存在',
        self::ACTIVE_RECOMMEND                      => '夺宝单已推荐',
        self::ACTIVE_STATE_ERROR                    => '夺宝单已结束/下线',
        self::ONLINE_CAN_NOT_DELETE                 => '上线记录不能删除,先下线',
        self::DELIVER_FAILED                        => '发货失败',
        self::DELIVER_ID_NOT_EXIST                  => '发货ID不存在',
        self::DELIVER_USER_INCOMPLETE               => '收货人信息不完整',
        self::DELIVER_TRACE_INCOMPLETE              => '发货轨迹不完整',
        self::AWARDS_TYPE_NOT_ONLINE                => '奖励类型未上线',
        self::HAVE_ONLINE_ACTIVITY                  => '设置时间段存在已上线的活动',
        self::ADVERT_POS_REQUIRED                   => '请选择广告位',
        self::GOODS_UNDONE_ACTIVE                   => '该商品存在未完成的夺宝单',
        self::GOODS_UNDONE_ACTIVITY                 => '该商品存在未完成的积分兑换活动',
        self::GOODS_EDIT_ONLINE_FAILED              => '线上编辑商品失败',
        self::ACTIVE_NOT_BEGIN                      => '活动未开始',
        self::ACTIVE_ENDED                          => '活动已结束',
        self::ACTIVE_PERIOD_LIMIT                   => '活动已满期',
        self::ACTIVE_GENERATE_FAILED                => '生成新一期活动失败',
        self::ACTIVE_UPDATE_STATE_FAILED            => '更新活动状态失败',
        self::ACTIVE_ONLINE_FAILED                  => '更新活动为上线状态失败',
        self::ACTIVE_END_FAILED                     => '更新活动为结束状态失败',
        self::VARIABLE_NOT_EXISTS                   => '该变量不存在',
        self::VARIABLE_MODIFY_FAILED                => '添加/修改变量失败',
        self::VARIABLE_DELETE_FAILED                => '删除变量失败',
        self::ACTIVE_HAS_SOLD                       => '活动销量不为0',
        self::ACTIVE_TERMINATE_FAILED               => '结单失败',
        self::ROBOT_STATE_FAILED                    => '启用/禁用机器人失败',
        self::ROBOT_SHARE_MODIFY_FAILED             => '添加/编辑机器人晒单失败',
        self::ACTIVE_EDIT_ONLINE_FAILED             => '线上编辑活动失败',
        self::ACTIVE_EDIT_ONLINE_PERIOD_FAILED      => '同步更新当前期失败',
        self::DELIVER_BATCH_FAILED                  => '批量发货失败',
        self::DELIVER_BATCH_EXCEL_EMPTY             => '上传的表格没有数据',
        self::DELIVER_ID_ERROR                      => '发货ID错误',
        self::DELIVER_DONE                          => '已确认收货',
        self::DELIVER_PURCHASE_PRICE_ERROR          => '发货成本错误',
        self::DELIVER_PURCHASE_PAY_STATE            => '采购付款错误',
        self::DELIVER_PURCHASE_PAY_TYPE             => '采购支付方式错误',
        self::DELIVER_FEE_ERROR                     => '运费错误',
        self::DELIVER_EXPRESS_ERROR                 => '快递公司错误',
        self::DELIVER_EXPRESS_ID                    => '快递单号错误',


        //业务错误
        self::OVERTIME								=>'已经过期',		//已经过期
        self::PAYED									=>'订单已支付',		//订单已支付
        self::REFUNDED								=>'已经退款',		//已经退款
        self::TOO_MANY_WORD							=>'字数超过限制',		//字数超过限制
        self::TOKEN_ERROR							=>'token错误',		//token错误，防止CSRF
        self::ADDRESS_NOT_FOUND						=>'收货地址不存在',		//收货地址不存在
        self::MOBILE_ERROR                          => '手机号有误',
        self::CART_NOT_FOUND                        => '购物车不存在',
        self::ORDER_CLEARING_AMOUNT                 => '订单结算金额有误',
        self::ORDER_NOT_FOUND                       => '订单不存在',
        self::ORDER_TYPE_NOT_FOUND                  => '订单类型不存在',
        self::ORDER_CASHIER_UNDEFINED               => '该类型订单未定义收银员',
        self::PAY_AGENT_ERROR                       => '请选择正确的支付方式',
        self::UPDATE_ORDER_ADDRESS                  => '更新订单收货地址出错',
        self::ORDER_VALID                           => '订单还未失效',
        self::DELIVER_CONFIRMED                     => '已确认发货',
        self::ORDER_INVALID                         => '订单已失效',
        self::ORDER_UNDELIVERED                     => '订单未发货',
        self::ADD_DELIVER_FAILED                    => '添加发货记录失败',

        //夺宝活动相关
        self::ACTIVE_NOT_FOUND                      => '活动不存在',
        self::ACTIVE_OVER_TIME                      => '活动当期已开奖或不存在',
        self::ACTIVE_OUTLINE                        => '活动已下线',
        self::ACTIVE_IS_NOT_LOTTEY                  => '活动不是开奖状态',
        self::ACTIVE_NOT_STOCK                      => '当期夺宝码数库存不足',
        self::ACTIVE_COUPON_NOT_STOCK               => '夺宝券数量不足',
        self::ACTIVE_USER_TIME_UPPER                => '用户单次购买上限',
        self::ACTIVE_USER_PEROID_UPPER              => '用户单期购买上限',

        //用户相关
        self::OPENID_IS_EMPTY                       => 'openid为空',
        self::USER_INFO_FAILED                      => '获取用户信息失败,请刷新重试',

        //用户夺宝相关
        self::NOT_ATTEND_ACTIVE                     => '用户未参与夺宝',
        self::ORDER_NOT_PAIED                       => '订单未支付',
        self::GOODS_NOT_DELIVER                     => '商品未发货',
        self::DELIVER_NOT_CONFIRM                   => '未确认发货',
        self::NOT_WINNER_USER                       => '不是改期中奖者',

		//福袋相关
        self::REDUCE_COUPON_FAILED                  => '扣券失败',
        self::REDUCE_FREE_COUPON_FAILED            => '没有更多的参与次数哦',
        self::SIGN_ERROR                            => 'sign校验失败',
        self::BAG_HAVE_GET                          => '已领取',
        self::BAG_NOT_PAID                          => '福袋未支付',
        self::BAG_NOT_ACTIVE                        => '福袋未激活',
        self::BAG_HAVE_DONE                         => '福袋已领完',

		// 消息中心相关
        self::MESSAGE_MAX_LENGTH                    => '消息内容超出系统允许的最大长度',
        self::MESSAGE_EXPIRE_ERROR                  => '消息过期时间不能小于当前时间',
        self::MESSAGE_ID_ERROR                      => '消息 msg_id 参数错误',
        self::MESSAGE_READ_FAILED                   => '消息标记为已读失败',
        self::MESSAGE_TEM_DATA_ERROR                => '模板数据错误',
        self::MESSAGE_NOTIFY_TYPE_INVALID           => '无效通知类型',
        self::MESSAGE_SEND_MY_FAILED                => '发送个人中心通知失败',
        self::MESSAGE_TO_UIN_ERROR                  => '收信人Uin错误',
        self::MESSAGE_SEND_WX_FAILED                => '发送微信通知失败',
        self::MESSAGE_BUSINESS_TYPE_FAILED          => '消息业务类型错误',
        self::MESSAGE_FOREIGN_KEY_ERROR             => '消息外键错误或不能为空',

		//积分
        self::SCORE_NOT_ENOUGH                      => '您的积分不足,继续参与赚积分吧',
        self::SCORE_ACTIVITY_TIMEOUT                => '积分活动已过期',
        self::SCORE_ACTIVITY_OFFLINE                => '积分活动已下线',
        self::SCORE_LARGE_THAN_TOTAL                => '已达到本礼品兑换次数上限，换个礼品兑换吧！',
        self::SCORE_LARGE_THAN_SINGLE               => '超过单次兑换数量',
        self::SCORE_STOCK_NOT_ENOUGH                => '对不起,库存不足',

		// 私人定制
        self::CUSTOM_GOODS_NOT                      => '该商品不存在',
        self::CUSTOM_ACTIVE_NAME_ERROR              => '活动名称错误',
        self::CUSTOM_PEOPLE_ERROR                   => '参与人数错误',
        self::CUSTOM_ADD_FAILED                     => '添加“私人定制”失败',
        self::CUSTOM_ADD_PERIOD_FAILED              => '生成一期“私人定制”失败',
        self::CUSTOM_DAILY_MAX                      => '请明天再试',

        //购物车
        self::CART_SVR_ERR                          => '购物车操作失败',

        //签到
        self::USER_SIGNED                           => '今天已签到',
        self::USER_LIKED                            => '已点过赞哦',

        // 拼团
        self::GROUPON_NOT_EXISTS                    => '团购不存在',
        self::GROUPON_NOT_ONLINE                    => '团购不在线',
        self::GROUPON_NOT_START                     => '团购未开始',
        self::GROUPON_ENDED                         => '团购已结束',
        self::GROUPON_SOLD_OUT                      => '团购已售罄',
        self::GROUPON_TYPE_ERROR                    => '团购类型错误',
        self::GROUPON_SPEC_NOT_EXISTS               => '团购规格不存在',
        self::GROUPON_SPEC_NOT_MATCH                => '团购规格不匹配',
        self::GROUPON_DIY_NOT_EXISTS                => '拼团不存在',
        self::GROUPON_DIY_NOT_START                 => '拼团未开始',
        self::GROUPON_DIY_ENDED                     => '拼团已结束',
        self::GROUPON_DIY_PEOPLE_MAX                => '拼团已达到开团人数',
        self::GROUPON_ORDER_NOT_EXISTS              => '团购订单不存在',
        self::GROUPON_ORDER_USER_ERROR              => '团购订单用户异常',
        self::GROUPON_ORDER_UNPAID                  => '团购订单未支付',
        self::GROUPON_ORDER_USED                    => '团购订单已使用',
        self::GROUPON_ORDER_CREATE_FAILED           => '团购订单创建失败',
        self::GROUPON_ORDER_TYPE_ERROR              => '团购订单类型错误',
        self::GROUPON_ORDER_PAID_ERROR              => '设置团购订单为「已支付」时出错',
        self::GROUPON_JOINED                        => '已经参与过该团了',
        self::GROUPON_JOIN_FAILED                   => '参团失败',
        self::GROUPON_HAS_ONGOING_DIY               => '有正在进行中的拼团',
        self::GROUPON_NOT_DIY_ORDER                 => '不是开团订单',

        self::SHARE_INVITE_ALREADY_GET              => '已领取过夺宝券',

    );

    /**
     * 获取对应的错误信息
     * refer:config/errors.php
     * @param $code
     */
    public static function get_error($code){
        return isset(self::$ERR_CODE_MAP[$code]) ? self::$ERR_CODE_MAP[$code] : self::$ERR_CODE_MAP[self::UNKONWN_ERR];
    }
}