<?php
    require_once '../conf/Config.php';
    /**
     * 
     * @author Jupiter
     * 
     * 处理核心类
     * 
     * 用于为服务类提供工具
     */
    class Core{
        /**
         * 签名时过滤字段
         * @param Array $req
         * @return Array $result
         */
        public static function paraFilter(Array $req){
            $result=array();
            foreach ($req as $key => $value){
                if(!($key==Config::SIGNATURE_KEY||$key==Config::SIGNTYPE_KEY
                    ||$key==Config::MHT_SIGN_TYPE_KEY||$key==Config::MHT_SIGNATURE_KEY)){
                    $result[$key]=$value;
                }
            }
            return $result;
        }
        /**
         * 签名生成
         * 
         * @param Array $req
         * @return string
         */
        public static function buildSignature(Array $req){
            $prestr=self::createLinkString($req, true, false);
            $prestr.=Config::QSTRING_SPLIT.md5(Config::$secure_key);
            return md5($prestr);
        }
        
        /**
         * 拼接报文
         * 
         * @param Array $para
         * @param Boolean $sort
         * @param Boolean $encode
         * @return string
         */
        public static function createLinkString(Array $para,$sort,$encode) {
            if($sort){
                $para= self::argSort($para);
            }
            foreach($para as $key => $value){
                if($encode){
                    $value=  urlencode($value);
                }
                $linkString.=$key.Config::QSTRING_EQUAL.$value.Config::QSTRING_SPLIT;
            }
            $linkString=  substr($linkString, 0, count($linkString)-2);
            return $linkString;
        }
        
        private static function argSort($para){
            ksort($para);
            reset($para);
            return $para;
        }
    }