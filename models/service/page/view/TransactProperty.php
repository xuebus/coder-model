<?php

/**
 * TransactProperty.php
 *
 * @description : 资产交易记录及查询接口Page层
 *
 * @author : zhaoxichao
 * @since : 11/06/2018
 */
class Service_Page_View_TransactProperty extends Base_Page {
    /**
     * @var null|Service_Data_TransactProperty Data层对象实例
     */
    private $objDataService = null;

    /**
     * Service_Page_View_TransactProperty constructor.
     */
    public function __construct() {

        //开启签名校验并初始化参数校验规则
        parent::__construct();

        //重写参数校验规则
        $this->arrValidate = array(
            'action' => array('notEmpty'),
            'sign'   => array('notEmpty'),
            'name'   => array('notEmpty'),
        );

        //初始化参数过滤规则
        $this->filterRule = array(
            'bduss' => 's',
            'name' => 's',
            'trans_type' => 'i',
            'time_start' => 'i',
            'time_end' => 'i',
            'ps' => 'i',
            'pn' => 'i',
        );

        //实例化Page层
        $this->objDataService = new Service_Data_TransactProperty();
    }

    /**
     * call
     * @description : 资产交易记录及查询入口
     *
     * @throws Utils_Exception
     * @author zhaoxichao
     * @date 14/06/2018
     */
    public function  call() {
        $arrRet = array();

        if (!$this->useInfo['isLogin']) {
            //用户未登录百度账号
            throw new Utils_Exception(
                Const_Error::$COMMON_ERROR_MSG[Const_Error::ERROR_BUDSS_NO_LOGIN_NO],
                Const_Error::ERROR_BUDSS_NO_LOGIN_NO
            );
        }

        $uid = intval($this->useInfo['uid']);

        //判断用户是否登录区块链账号
        $chainInfo = $this->objDataService->isChainUserExists($uid);
        if (!$chainInfo['address']) {
            //用户未登录区块链账号
            throw new Utils_Exception(
                Const_Error::$EXCEPTION_MSG[Const_Error::ERROR_CHAIN_NO_LOGIN_NO],
                Const_Error::ERROR_CHAIN_NO_LOGIN_NO
            );
        }

        //根据接口要求预处理参数
        $this->handleParams($this->arrInput);

        //查询交易记录数据
        $arrRet = $this->objDataService->getTransactPropertyData($uid, $this->arrInput);

        $this->arrOutput = $arrRet;
    }

    /**
     * handleParams
     * @description : 根据数据端接口要求预处理参数
     *
     * @param array $arrInput 请求参数
     * @throws Utils_Exception
     * @author zhaoxichao
     * @date 14/06/2018
     */
    private function handleParams($arrInput = array()) {

        //产生交易的时间范围--结束时间,默认接口访问的当前时间
        $arrInput['time_end'] = (isset($arrInput['time_end']) && $arrInput['time_end'] > 0)? $arrInput['time_end'] : time();

        //分页大小，取值范围大于等于1且小于等于50,默认取20
        $arrInput['ps'] = (isset($arrInput['ps']) && $arrInput['ps'] >= 1 && $arrInput['ps'] <= 50) ? $arrInput['ps'] : Const_Common::DEFAULT_PAGE_SIZE;

        //默认页码为1
        $arrInput['pn'] = (isset($arrInput['pn']) && $arrInput['pn'] >= 1) ? $arrInput['pn'] : Const_Common::DEFAULT_PAGE_NUM;

        //比较起止时间
        if ($arrInput['time_start'] >= $arrInput['time_end']) {
            //查询交易起始时间大于交易结束时间
            throw new Utils_Exception(
                Const_Error::$EXCEPTION_MSG[Const_Error::ERROR_QUERY_TIME_RANGE],
                Const_Error::ERROR_QUERY_TIME_RANGE
            );
        }

        $this->arrInput = $arrInput;
    }

    /**
     * afterCall
     * @description : 返回层处理(日志打印,返回结果格式化)
     *
     * @throws Utils_Exception
     * @author zhaoxichao
     * @date 14/06/2018
     */
    public function afterCall() {
        if (empty($this->arrOutput)) {
            throw new Utils_Exception(
                As_Const_Error::$ERROR_MSG[As_Const_Error::ERROR_CANT_FIND_DATA],
                As_Const_Error::ERROR_CANT_FIND_DATA
            );
        }

        $this->arrOutput = Utils_Output::SuccessArray($this->arrOutput);
    }
}