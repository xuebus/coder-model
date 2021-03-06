<?php

class Const_Error extends As_Const_Exception{

    // 错误码前缀 --- xexplorer使用该前缀
    const EXCEPTION_CODE_PREFIX = 200;

    // 用户未登录
    const ERROR_USER_NOT_LOGIN   = 2000;

    // 未注册区块链账户
    const ERROR_USER_NOT_CHAIN_USER = 2001;

    // 调用区块链服务判断登录状态异常
    const ERROR_USER_CHECK_CHAIN_USER = 2002;

    // 查询交易记录失败
    const ERROR_QUERY_USER_CHAIN_LIST = 2003;

    // 区块链账户注册失败
    const ERROR_USER_REGISTER_ERROR = 2006;

    // 查询交易起始时间大于交易结束时间
    const ERROR_QUERY_TIME_RANGE = 2005;

    // 获取资产信息异常
    const ERROR_GET_MYASSET_ERROR = 2007;


    //异常提示信息
    public static $EXCEPTION_MSG = array(

       self::ERROR_USER_NOT_LOGIN => '用户未登录',

       self::ERROR_USER_NOT_CHAIN_USER => '用户未注册区块链账号',

       self::ERROR_USER_CHECK_CHAIN_USER => '查询区块链账户信息异常',

       self::ERROR_QUERY_USER_CHAIN_LIST => '查询用户资产交易记录失败',

       self::ERROR_USER_REGISTER_ERROR => '区块链账户注册失败',

       self::ERROR_QUERY_TIME_RANGE => '查询交易起始时间大于交易结束时间',

       self::ERROR_GET_MYASSET_ERROR => '获取我的资产信息异常',

    );

    /**
     * 获取错误码对应异常信息
     * @param $code
     * @return array
     */
    public static function getErrorInfo($code) {
        $errorInfo = array();
        $errorInfo['code'] = self::getErrCode($code);
        $errorInfo['msg']  = self::getCodeMsg($code);
        return $errorInfo;
    }

    /**
     * 获取异常码对应的提示信息
     * @param $code
     * @return mixed|string
     */
    public static function getCodeMsg($code) {

        // 获取通用异常错误码对应msg信息
        $message = self::getCommonCodeMsg($code);
        if(!empty($message)){
            return $message;
        }

        // 获取对应模块错误码对应msg信息
        if(isset(self::$EXCEPTION_MSG[$code])) {
            return self::$EXCEPTION_MSG[$code];
        }
        return '';
    }

    /**
     * 返回统一的错误码
     * @param $code
     * @return string
     */
    public static function getErrCode($code) {

        return self::EXCEPTION_CODE_PREFIX . $code;

    }

}