<?php
namespace Home\Controller;

use Think\Controller;

class PayController extends Controller
{

    //生成订单
    public function  createorder($no)
    {

        $data['uid'] = session('uid');
        $data['balanceno'] = $no;
        $result = D('balance')->add($data);

    }
    public function zhongyunpay($data)
    {
        $payconfig = C('zhongyun_pay');
        $pay_memberid = $payconfig['pay_memberid'];   //商户ID
        $pay_orderid = $data['pay_orderid'];    //订单号
        $pay_amount = $data['pay_amount'];    //交易金额
        $pay_applydate = date("Y-m-d H:i:s");  //订单时间
        $pay_bankcode = $payconfig['pay_bankcode'];   //银行编码
        $pay_notifyurl = $payconfig['pay_notifyurl'];   //服务端返回地址
        $pay_callbackurl = $payconfig['pay_callbackurl'];  //页面跳转返回地址
        $Md5key = $payconfig['Md5key'];   //密钥
        $tjurl = $payconfig['tjurl'];   //提交地址
        
        $requestarray = array(
                "pay_memberid" => $pay_memberid,
                "pay_orderid" => $pay_orderid,
                "pay_amount" => $pay_amount,
                "pay_applydate" => $pay_applydate,
                "pay_bankcode" => $pay_bankcode,
                "pay_notifyurl" => $pay_notifyurl,
                "pay_callbackurl" => $pay_callbackurl
            );
            
            ksort($requestarray);
            reset($requestarray);
            $md5str = "";
            foreach ($requestarray as $key => $val) {
                $md5str = $md5str . $key . "=>" . $val . "&";
            }
            // echo($md5str . "key=" . $Md5key."<br>");
            $sign = strtoupper(md5($md5str . "key=" . $Md5key)); 
            $requestarray["pay_md5sign"] = $sign;
            
            $str = '<form id="Form1" name="Form1" method="post" action="' . $tjurl . '">';
            foreach ($requestarray as $key => $val) {
                $str = $str . '<input type="hidden" name="' . $key . '" value="' . $val . '">';
            }
            $str = $str . '<input type="submit" value="">';
            $str = $str . '</form>';
            $str = $str . '<script>';
            $str = $str . 'document.Form1.submit();';
            $str = $str . '</script>';
            exit($str);
    }
    public function notify_url()
    {
        $payconfig = C('zhongyun_pay');
        $ReturnArray = array( // 返回字段
            "memberid" => $_REQUEST["memberid"], // 商户ID
            "orderid" =>  $_REQUEST["orderid"], // 订单号
            "amount" =>  $_REQUEST["amount"], // 交易金额
            "datetime" =>  $_REQUEST["datetime"], // 交易时间
            "returncode" => $_REQUEST["returncode"]
        );
      
        $Md5key = $payconfig['Md5key'];
        //$sign = $this->md5sign($Md5key, $ReturnArray);
        
        ///////////////////////////////////////////////////////
        ksort($ReturnArray);
        reset($ReturnArray);
        $md5str = "";
        foreach ($ReturnArray as $key => $val) {
            $md5str = $md5str . $key . "=>" . $val . "&";
        }
        $sign = strtoupper(md5($md5str . "key=" . $Md5key)); 
        ///////////////////////////////////////////////////////
        // if ($sign == $_REQUEST["sign"]) {
        if ($sign == $_REQUEST["sign"]) {
            if ($_REQUEST["returncode"] == "00") {
                $parameter = array(
                    "order_no"     => $_REQUEST["orderid"], //商户订单编号；
                    "order_amount"     => $_REQUEST["amount"],    //交易金额；
                    "trade_status"     => $_REQUEST["returncode"], //交易状态
                );
                if(!checkorderstatus($order_no)){
                    orderhandle($parameter);
                    //进行订单处理，并传送从支付宝返回的参数；
                }
                //支付成功跳转个人中心
               $str = "交易成功！订单号：".$_REQUEST["orderid"];
               file_put_contents("success.txt",$str."\n", FILE_APPEND);
               exit("ok");
            }
        }   
    }
    public function return_url()
    {
        $payconfig = C('zhongyun_pay');
        $ReturnArray = array( // 返回字段
            "memberid" => $_REQUEST["memberid"], // 商户ID
            "orderid" =>  $_REQUEST["orderid"], // 订单号
            "amount" =>  $_REQUEST["amount"], // 交易金额
            "datetime" =>  $_REQUEST["datetime"], // 交易时间
            "returncode" => $_REQUEST["returncode"]
        );
      
        $Md5key = $payconfig['Md5key'];
        //$sign = $this->md5sign($Md5key, $ReturnArray);
        
        ///////////////////////////////////////////////////////
        ksort($ReturnArray);
        reset($ReturnArray);
        $md5str = "";
        foreach ($ReturnArray as $key => $val) {
            $md5str = $md5str . $key . "=>" . $val . "&";
        }
        $sign = strtoupper(md5($md5str . "key=" . $Md5key)); 
        ///////////////////////////////////////////////////////
        if ($sign == $_REQUEST["sign"]) {
            if ($_REQUEST["returncode"] == "00") {
                redirect(U('Index/index'), 2, '页面跳转中...');
                   // $this->success('请等待...', U('Index/index'));
            }
        }
    }
    //提交类
    public function dopay()
    {

        /////////////////////////////////接收表单提交参数//////////////////////////////////////
        ////////////////////////To receive the parameter form HTML form//////////////////////
        $pay_config = C('pay_config');
        $merchant_code = $pay_config['merchant_code'];
        $service_type = $pay_config['service_type'];
        $interface_version = $pay_config['interface_version'];
        $sign_type = $pay_config['sign_type'];
        $input_charset = $pay_config['input_charset'];
        $notify_url = $pay_config['notify_url'];
        $order_no = time();
        $order_time = date("Y-m-d H:i:s", time());
        $order_amount = I('post.amount');
        $product_name = '充值';
        $product_code = $_POST['product_code'];
        $product_desc = $_POST['product_desc'];
        $product_num = $_POST['product_num'];
        $show_url = $pay_config['show_url'];
        $client_ip = $_POST['client_ip'];
        $bank_code = $_POST['bank_code'];
        $redo_flag = $_POST['redo_flag'];
        $extend_param = $_POST['extend_param'];
        $extra_return_param = $_POST['extra_return_param'];
        $return_url = $pay_config['return_url'];
        $this->createorder( $order_no);
        //////////////////////////////////// 字符编码转换 /////////////////////////////////
        /////////////////////////////// Character transcoding ////////////////////////////


        if($product_name != ""){
            $product_name = mb_convert_encoding($product_name, "UTF-8", "UTF-8");
        }
        if($product_code != ""){
            $product_code = mb_convert_encoding($product_code, "UTF-8", "UTF-8");
        }
        if($product_desc != ""){
            $product_desc = mb_convert_encoding($product_desc, "UTF-8", "UTF-8");
        }
        if($order_no != ""){
            $order_no = mb_convert_encoding($order_no, "UTF-8", "UTF-8");
        }
        if($extend_param != ""){
            $extend_param = mb_convert_encoding($extend_param, "UTF-8", "UTF-8");
        }
        if($extra_return_param != ""){
            $extra_return_param = mb_convert_encoding($extra_return_param, "UTF-8", "UTF-8");
        }
        if($notify_url != ""){
            $notify_url = mb_convert_encoding($notify_url, "UTF-8", "UTF-8");
        }
        if($return_url != ""){
            $return_url = mb_convert_encoding($return_url, "UTF-8", "UTF-8");
        }
        if($show_url != ""){
            $show_url = mb_convert_encoding($show_url, "UTF-8", "UTF-8");
        }

        /////////////////////////////   数据签名  /////////////////////////////////
        ////////////////////////////  Data signature  ////////////////////////////

        /**
        签名规则定义如下：
        （1）参数列表中，除去sign_type、sign两个参数外，其它所有非空的参数都要参与签名，值为空的参数不用参与签名；
        （2）签名顺序按照参数名a到z的顺序排序，若遇到相同首字母，则看第二个字母，以此类推，同时将商家支付密钥key放在最后参与签名，组成规则如下：
        参数名1=参数值1&参数名2=参数值2&……&参数名n=参数值n&key=key值
         */

        /**
        The definition of signature rule is as follows :
        （1） In the list of parameters, except the two parameters of sign_type and sign, all the other parameters that are not in blank shall be signed, the parameter with value as blank doesn’t need to be signed;
        （2） The sequence of signature shall be in the sequence of parameter name from a to z, in case of same first letter, then in accordance with the second letter, so on so forth, meanwhile, the merchant payment key shall be put at last for signature, and the composition rule is as follows :
        Parameter name 1 = parameter value 1& parameter name 2 = parameter value 2& ......& parameter name N = parameter value N & key = key value
         */

        $signStr= "";

        if($bank_code != ""){
            $signStr = $signStr."bank_code=".$bank_code."&";
        }
        if($client_ip != ""){
            $signStr = $signStr."client_ip=".$client_ip."&";
        }
        if($extend_param != ""){
            $signStr = $signStr."extend_param=".$extend_param."&";
        }
        if($extra_return_param != ""){
            $signStr = $signStr."extra_return_param=".$extra_return_param."&";
        }

        $signStr = $signStr."input_charset=".$input_charset."&";
        $signStr = $signStr."interface_version=".$interface_version."&";
        $signStr = $signStr."merchant_code=".$merchant_code."&";
        $signStr = $signStr."notify_url=".$notify_url."&";
        $signStr = $signStr."order_amount=".$order_amount."&";
        $signStr = $signStr."order_no=".$order_no."&";
        $signStr = $signStr."order_time=".$order_time."&";

        if($product_code != ""){
            $signStr = $signStr."product_code=".$product_code."&";
        }
        if($product_desc != ""){
            $signStr = $signStr."product_desc=".$product_desc."&";
        }

        $signStr = $signStr."product_name=".$product_name."&";

        if($product_num != ""){
            $signStr = $signStr."product_num=".$product_num."&";
        }
        if($redo_flag != ""){
            $signStr = $signStr."redo_flag=".$redo_flag."&";
        }
        if($return_url != ""){
            $signStr = $signStr."return_url=".$return_url."&";
        }

        $signStr = $signStr."service_type=".$service_type."&";

        if($show_url != ""){
            $signStr = $signStr."show_url=".$show_url."&";
        }

        //注：以下的key值必须与商家后台设置的支付密钥保持一致
        //Note：The key value must be consistent with which you had set on Dinpay's Merchant System.

        $key = "os29EkDKntXQh3yXRdnlVfaMKcyPXk";

        $signStr = $signStr."key=".$key;
        $sign = md5($signStr);

        $form = "";
         $form .= '<input type="hidden" name="sign"		  value="'.$sign. '" />
			<input type="hidden" name="merchant_code" value="'.$merchant_code. '" />
			<input type="hidden" name="bank_code"     value="'.$bank_code. '"/>
			<input type="hidden" name="order_no"      value="'.$order_no. '"/>
			<input type="hidden" name="order_amount"  value="'.$order_amount. '"/>
			<input type="hidden" name="service_type"  value="'.$service_type. '"/>
			<input type="hidden" name="input_charset" value="'.$input_charset. '"/>
			<input type="hidden" name="notify_url"    value="'.$notify_url. '">
			<input type="hidden" name="interface_version" value="'.$interface_version. '"/>
			<input type="hidden" name="sign_type"     value="'.$sign_type. '"/>
			<input type="hidden" name="order_time"    value="'.$order_time. '"/>
			<input type="hidden" name="product_name"  value="'.$product_name. '"/>
			<input Type="hidden" Name="client_ip"     value="'.$client_ip. '"/>
			<input Type="hidden" Name="extend_param"  value="'.$extend_param. '"/>
			<input Type="hidden" Name="extra_return_param" value="'.$extra_return_param. '"/>
			<input Type="hidden" Name="product_code"  value="'.$product_code. '"/>
			<input Type="hidden" Name="product_desc"  value="'.$product_desc. '"/>
			<input Type="hidden" Name="product_num"   value="'.$product_num. '"/>
			<input Type="hidden" Name="return_url"    value="'.$return_url. '"/>
			<input Type="hidden" Name="show_url"      value="'.$show_url. '"/>
			<input Type="hidden" Name="redo_flag"     value="'.$redo_flag. '"/>';
        $this->assign('form', $form);
        $this->display();

    }

    public function notifyurl(){
        //////////////////////////		接收智付返回通知数据  /////////////////////////////////
        ////////////////////////// To receive notification data from Dinpay ////////////////////


        $merchant_code	= $_POST["merchant_code"];
        $interface_version = $_POST["interface_version"];
        $sign_type = $_POST["sign_type"];
        $dinpaySign = $_POST["sign"];
        $notify_type = $_POST["notify_type"];
        $notify_id = $_POST["notify_id"];
        $order_no = $_POST["order_no"];
        $order_time = $_POST["order_time"];
        $order_amount = $_POST["order_amount"];
        $trade_status = $_POST["trade_status"];
        $trade_time = $_POST["trade_time"];
        $trade_no = $_POST["trade_no"];
        $bank_seq_no = $_POST["bank_seq_no"];
        $extra_return_param = $_POST["extra_return_param"];




        /////////////////////////////   数据签名  /////////////////////////////////
        ////////////////////////////  Data signature  ////////////////////////////

        /**
        签名规则定义如下：
        （1）参数列表中，除去sign_type、sign两个参数外，其它所有非空的参数都要参与签名，值为空的参数不用参与签名；
        （2）签名顺序按照参数名a到z的顺序排序，若遇到相同首字母，则看第二个字母，以此类推，同时将商家支付密钥key放在最后参与签名，组成规则如下：
        参数名1=参数值1&参数名2=参数值2&……&参数名n=参数值n&key=key值
         */

        /**
        The definition of signature rule is as follows :
        （1） In the list of parameters, except the two parameters of sign_type and sign, all the other parameters that are not in blank shall be signed, the parameter with value as blank doesn’t need to be signed;
        （2） The sequence of signature shall be in the sequence of parameter name from a to z, in case of same first letter, then in accordance with the second letter, so on so forth, meanwhile, the merchant payment key shall be put at last for signature, and the composition rule is as follows :
        Parameter name 1 = parameter value 1& parameter name 2 = parameter value 2& ......& parameter name N = parameter value N & key = key value
         */


        $signStr = "";

        if($bank_seq_no != ""){
            $signStr = $signStr."bank_seq_no=".$bank_seq_no."&";
        }
        if($extra_return_param != ""){
            $signStr = $signStr."extra_return_param=".$extra_return_param."&";
        }

        $signStr = $signStr."interface_version=".$interface_version."&";
        $signStr = $signStr."merchant_code=".$merchant_code."&";
        $signStr = $signStr."notify_id=".$notify_id."&";
        $signStr = $signStr."notify_type=".$notify_type."&";
        $signStr = $signStr."order_amount=".$order_amount."&";
        $signStr = $signStr."order_no=".$order_no."&";
        $signStr = $signStr."order_time=".$order_time."&";
        $signStr = $signStr."trade_no=".$trade_no."&";
        $signStr = $signStr."trade_status=".$trade_status."&";

        if($trade_time != ""){
            $signStr = $signStr."trade_time=".$trade_time."&";
        }

        //注：以下的key值必须与商家后台设置的支付密钥保持一致
        //Note：The key value must be consistent with which you had set on Dinpay's Merchant System.

        $key="os29EkDKntXQh3yXRdnlVfaMKcyPXk";

        $signStr = $signStr."key=".$key;
        $sign = md5($signStr);

        ////////////////////////////////////异步通知必须响应“SUCCESS”///////////////////////////////
        /**
        When the notification method is service asynchronous notification, after receiving backstage notification and complete processing, the merchant system must printout the following seven characters "SUCCESS "which can't be change,including the space between the characters, otherwise, the Dinpay payment system will, during the subsequent period, send such notification for 5 times with increasing time interval.
         */



        if($dinpaySign==$sign){		//验签成功（Signature correct）

            echo "SUCCESS";
            /**
            1.响应 SUCCESS
            1.response to SUCCESS

            2.判断商家号、商家订单号、订单金额、交易状态
            2.To check parameter value including merchant_code、order_no、order_amount and trade_status

            3.校验是否重复通知
            3.To check whether it's repeated notice.
             */
            //判断交易状态

            $parameter = array(
                "order_no"     => $order_no, //商户订单编号；
                "order_amount"     => $order_amount,    //交易金额；
                "trade_status"     => $trade_status, //交易状态
            );
            if($_POST['trade_status'] =='SUCCESS'){
                if(!checkorderstatus($order_no)){
                    orderhandle($parameter);
                    //进行订单处理，并传送从支付宝返回的参数；
                }
                //支付成功跳转个人中心
            } else {

            }


        }else{

            echo "Signature Error";  //验签失败，业务结束（End of the business）
        }


    }


    public function returnurl() {
        //////////////////////////		接收智付返回通知数据  /////////////////////////////////
        ////////////////////////// To receive notification data from Dinpay ////////////////////

        $merchant_code	= $_POST["merchant_code"];
        $interface_version = $_POST["interface_version"];
        $sign_type = $_POST["sign_type"];
        $dinpaySign = $_POST["sign"];
        $notify_type = $_POST["notify_type"];
        $notify_id = $_POST["notify_id"];
        $order_no = $_POST["order_no"];
        $order_time = $_POST["order_time"];
        $order_amount = $_POST["order_amount"];
        $trade_status = $_POST["trade_status"];
        $trade_time = $_POST["trade_time"];
        $trade_no = $_POST["trade_no"];
        $bank_seq_no = $_POST["bank_seq_no"];
        $extra_return_param = $_POST["extra_return_param"];

        /////////////////////////////   数据签名  /////////////////////////////////
        ////////////////////////////  Data signature  ////////////////////////////

        /**
        签名规则定义如下：
        （1）参数列表中，除去sign_type、sign两个参数外，其它所有非空的参数都要参与签名，值为空的参数不用参与签名；
        （2）签名顺序按照参数名a到z的顺序排序，若遇到相同首字母，则看第二个字母，以此类推，同时将商家支付密钥key放在最后参与签名，组成规则如下：
        参数名1=参数值1&参数名2=参数值2&……&参数名n=参数值n&key=key值
         */

        /**
        The definition of signature rule is as follows :
        （1） In the list of parameters, except the two parameters of sign_type and sign, all the other parameters that are not in blank shall be signed, the parameter with value as blank doesn’t need to be signed;
        （2） The sequence of signature shall be in the sequence of parameter name from a to z, in case of same first letter, then in accordance with the second letter, so on so forth, meanwhile, the merchant payment key shall be put at last for signature, and the composition rule is as follows :
        Parameter name 1 = parameter value 1& parameter name 2 = parameter value 2& ......& parameter name N = parameter value N & key = key value
         */


        $signStr = "";

        if($bank_seq_no != ""){
            $signStr = $signStr."bank_seq_no=".$bank_seq_no."&";
        }
        if($extra_return_param != ""){
            $signStr = $signStr."extra_return_param=".$extra_return_param."&";
        }

        $signStr = $signStr."interface_version=".$interface_version."&";
        $signStr = $signStr."merchant_code=".$merchant_code."&";
        $signStr = $signStr."notify_id=".$notify_id."&";
        $signStr = $signStr."notify_type=".$notify_type."&";
        $signStr = $signStr."order_amount=".$order_amount."&";
        $signStr = $signStr."order_no=".$order_no."&";
        $signStr = $signStr."order_time=".$order_time."&";
        $signStr = $signStr."trade_no=".$trade_no."&";
        $signStr = $signStr."trade_status=".$trade_status."&";

        if($trade_time != ""){
            $signStr = $signStr."trade_time=".$trade_time."&";
        }

        //注：以下的key值必须与商家后台设置的支付密钥保持一致
        //Note：The key value must be consistent with which you had set on Dinpay's Merchant System.

        $key="os29EkDKntXQh3yXRdnlVfaMKcyPXk";

        $signStr = $signStr."key=".$key;
        $sign = md5($signStr);

        if($dinpaySign==$sign){

            //验签成功（Signature correct）

            if($_POST['trade_status'] =='SUCCESS'){
                $this->redirect('Index/index');
                //支付成功跳转个人中心
            }

        }else{

            //验签失败，业务结束（End of the business）
            echo "Signature Error";
        }

    }


}