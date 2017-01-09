<?php
namespace Home\Controller;
class IndexController extends CommonController {
    public function index(){
        if(isset($_SESSION['uid'])) {
            $tq=C('DB_PREFIX');
            $uid=$_SESSION['uid'];
            //账号余额
            $userimg=M('userinfo')->where('uid='.$uid)->find();
            $price=M('accountinfo')->where('uid='.$uid)->find();
            //账号体验卷数
            $endtime=date(time());
            $exper=M('experienceinfo')->join($tq.'experience on '.$tq.'experienceinfo.eid='.$tq.'experience.eid')->where($tq.'experienceinfo.uid='.$uid.' and '.$endtime.' < '.$tq.'experienceinfo.endtime and '.$tq.'experienceinfo.getstyle=0')->count();     
            $user['price']=round($price['balance'],2);
            $user['exper']=$exper;
            $user['portrait']=$userimg['portrait'];
            $this->assign('user',$user);
        }
        $catgood=M('catproduct')->select();
    	$goods=M('productinfo')->select();
        $uorder=$this->userorder();
        $news=$this->information();
        $focus=$this->focus();
        $notices=$this->notice();
        $isopen=$this->isopen();
        $this->assign('isopen',$isopen);
        $this->assign('nolist',$uorder);
        $this->assign('news',$news);
        $this->assign('focus',$focus);
        $this->assign('notices',$notices);
    	$this->assign('catgood',$catgood);
    	$this->assign('goods',$goods);
		$this->display();
    }
    public function index2(){
    	$uorder=$this->userorder();
    	$tosn = array();
    	foreach($uorder as $k=>&$v){
    		if($v['cid'] == 1){
    			$s = file_get_contents("http://hq.gz.1251923837.clb.myqcloud.com/HQ/Goods2/FU?callback=JSON_CALLBACK&t=".time());
    			$s = str_replace("JSON_CALLBACK(", "", $s);
    			$s = str_replace(");", "", $s);
    		
    			$sf = json_decode($s,true);

    			$yincangyoujia = $sf['c'];
    		}else if($v['cid'] == 2){
    			$s = file_get_contents("http://hq.gz.1251923837.clb.myqcloud.com/HQ/Goods2/AG?callback=JSON_CALLBACK&t=".time());
    			
    			$s = str_replace("JSON_CALLBACK(", "", $s);
    			$s = str_replace(");", "", $s);
    			$sf = json_decode($s,true);
    			$yincangyoujia = $sf['c'];
    		}else{
    			$s = file_get_contents("http://hq.gz.1251923837.clb.myqcloud.com/HQ/Goods2/CU?callback=JSON_CALLBACK&t=".time());
    			$s = str_replace("JSON_CALLBACK(", "", $s);
    			$s = str_replace(");", "", $s);
    			$sf = json_decode($s,true);
    			$yincangyoujia = $sf['c'];
    		}
    		
    		if($v['eid'] == 0){
    			if($v['ostyle'] ==  0){
    				$newprice1 = ($yincangyoujia-$v['buyprice'])*$v['wave']*$v['onumber'];
    			}else{
    				$newprice1 = ($v['buyprice']-$yincangyoujia)*$v['wave']*$v['onumber'];
    			}
    			$newprice3 = $newprice1;
    		}else{
    			$newprice3 = 0;
    		}
    		if($yincangyoujia == ''){
    			$v['yj'] = 0;
    		}else{
    			$v['yj'] = $newprice3;
    		}
    		unset($s,$sf,$yincangyoujia,$newprice1,$newprice3);
    		array_push($tosn, $v['yj']);
    	}
    	$this->assign('tosn',array_sum($tosn));
    	$this->assign('nolist',$uorder);
    	$this->display();
    }
    public function home(){
    	$goods=M('productinfo')->select();
    	$you = array();
    	$tong = array();
    	$yin = array();
    	
    	foreach($goods as $k=>$v){
    		if($v['cid'] == 1){
    			array_push($you, $goods[$k]);
    		}
    		if($v['cid'] == 2){
    			array_push($yin, $goods[$k]);
    		}
    		if($v['cid'] == 3){
    			array_push($tong, $goods[$k]);
    		}
    	}
    	//unset($you['2'],$yin['2'],$tong['2']);
    	$you['0']['uprice'] > $you['1']['uprice'] ?$you1 = $you['0']['pid']:$you1 = $you['1']['pid'];
    	$tong['0']['uprice'] > $tong['1']['uprice'] ?$tong1 = $tong['0']['pid']:$tong1 = $tong['1']['pid'];
    	$yin['0']['uprice'] > $yin['1']['uprice'] ?$yin1 = $yin['0']['pid']:$yin1 = $yin['1']['pid'];
		
    	$this->assign('yin1',$yin1);
    	$this->assign('tong1',$tong1);
    	$this->assign('you1',$you1);
    	$this->assign('you',$you);
    	$this->assign('tong',$tong);
    	$this->assign('yin',$yin);
    	$this->display();
    }
   
    //查询网站是否关闭，关闭则不能购买，并且现价变成休市
    public function isopen(){
        $stye=M('webconfig')->select();
        return $stye[0]['isopen'];
    }
    //显示最新资讯
    public function information(){
        $news=M('newsinfo')->where('ncategory=1')->order('nid desc')->limit(3)->select();
        return $news;
    }
    //显示财经要闻Focus
    public function focus(){
    $news=M('newsinfo')->where('ncategory=2')->order('nid desc')->limit(3)->select();
    return $news;
    }
    //显示系统公告Notice
    public function notice(){
    $news=M('newsinfo')->where('ncategory=3')->order('nid desc')->limit(3)->select();
    return $news;
    }
    //获取动态油的价格，显示到页面
   public function price(){
         $source=file_get_contents("xh/you.txt");
         $msg = explode(',',$source);
         $myprice[0] = str_replace('price:', '',str_replace('"','',$msg[1]));//最新
         $myprice[8] = round(str_replace('jk:', '',str_replace('"','',$msg[4])));//今开
         $myprice[7] = round(str_replace('zk:', '',str_replace('"','',$msg[5])));//昨开
         $myprice[4] = round(str_replace('zg:', '',str_replace('"','',$msg[6])));//最高
         $myprice[5] = round(str_replace('zd:', '',str_replace('"','',$msg[7])));//最低
         $myprice[12] = $myprice[0]-$myprice[8];
         $this->ajaxReturn($myprice);
    }
    //获取动态白银的价格，显示到页面
    public function byprice(){
         $source=file_get_contents("xh/baiyin.txt");
         $msg = explode(',',$source);
         $myprice[0] = round(str_replace('price:', '',str_replace('"','',$msg[1])));//最新
         $myprice[8] = round(str_replace('jk:', '',str_replace('"','',$msg[4])));//今开
         $myprice[7] = round(str_replace('zk:', '',str_replace('"','',$msg[5])));//昨开
         $myprice[4] = round(str_replace('zg:', '',str_replace('"','',$msg[6])));//最高
         $myprice[5] = round(str_replace('zd:', '',str_replace('"','',$msg[7])));//最低
         $myprice[12] = $myprice[0]-$myprice[8];
         $this->ajaxReturn($myprice);
    }
    //获取动态铜的价格，显示到页面
    public function toprice(){
         $source=file_get_contents("xh/tong.txt");
         $msg = explode(',',$source);
         $myprice[0] = round(str_replace('price:', '',str_replace('"','',$msg[1])));//最新
         $myprice[8] = round(str_replace('jk:', '',str_replace('"','',$msg[4])));//今开
         $myprice[7] = round(str_replace('zk:', '',str_replace('"','',$msg[5])));//昨开
         $myprice[4] = round(str_replace('zg:', '',str_replace('"','',$msg[6])));//最高
         $myprice[5] = round(str_replace('zd:', '',str_replace('"','',$msg[7])));//最低
         $myprice[12] = $myprice[0]-$myprice[8];
         $this->ajaxReturn($myprice);
    }
    //根据传回来的id获取商品的信息
    public function selectid(){
        $tq=C('DB_PREFIX');
        $pid=I('post.pid');
        $uid=$_SESSION['uid'];
        //获取当前id对应的商品
        $good=M('productinfo')->where('pid='.$pid)->find();
        //查询用户时候有对应的体验卷
       $sum=M('experienceinfo')->join($tq.'experience on '.$tq.'experienceinfo.eid='.$tq.'experience.eid')->where($tq.'experienceinfo.uid='.$uid.' and '.date(time()).' < '.$tq.'experienceinfo.endtime and '.$tq.'experienceinfo.getstyle=0 and '.$tq.'experience.eprice='.$good['uprice'])->count();

        $good['sum']=$sum;
        $this->ajaxReturn($good);
    }

    //查询当前用户正在交易的订单
    public function userorder(){
    	
        $tq=C('DB_PREFIX');
        $uid = $_SESSION['uid'];
        $userorders=M('order')->join($tq.'productinfo on '.$tq.'order.pid='.$tq.'productinfo.pid')
        ->join($tq.'catproduct on '.$tq.'productinfo.cid='.$tq.'catproduct.cid')->where($tq.'order.uid='.$uid.' and ostaus=0')->select();
      
        // echo "<pre>";
        // var_dump($userorders);
        //  echo "</pre>";
    
        return $userorders;
    }
	//查询订单信息(接收修改后的订单，或者直接平仓，或者购买完后平仓，3中情况)
    public function orderid(){
        $tq=C('DB_PREFIX');
        $orderid=I('orderid');
        $order=M('order')->join($tq.'productinfo on '.$tq.'order.pid='.$tq.'productinfo.pid')
        ->join($tq.'catproduct on '.$tq.'productinfo.cid='.$tq.'catproduct.cid')->where('oid='.$orderid)->find();
    	$order['expend'] = $order['onumber']*$order['uprice'];	//支出
    	$order['paytime'] = date('Y-m-d',$order['buytime']);
		$this->ajaxReturn($order);
    }
	//修改订单的止盈和止亏
    public function edityk(){
        $order=M('order');
        $order->oid=I('post.oid');
        $order->endprofit=I('post.zy');
        $order->endloss=I('post.zk');
        $order->save();
        $this->redirect('Index/index');
    }

    

	
}