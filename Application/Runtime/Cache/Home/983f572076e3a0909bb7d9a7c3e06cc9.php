<?php if (!defined('THINK_PATH')) exit();?><script language="javascript" type="text/javascript" src="/Public/Home/js/jquery.min.js"></script>
                <!-- ngView: undefined --><div class="scrollable ng-scope" style="padding-top: 50px; ">
    <div class="scrollable-header section" style="padding:0;">
        

<div class="container" style="background-color:#f5f5f5;">
    <div class="row">
            <div class="col-xs-4" style="padding: 2px 1px 2px 0;">
                <div class="btn btn-block" style="padding:0; line-height:24px;">
                    <div style="font-size:12px;">纪念银币</div>
                    <div class="btn-hq btn-hq-down" style="font-size:18px;" goods-hq="AG">4244.00 <i class="fa fa-long-arrow-down"></i></div>
                </div>
            </div>
            <div class="col-xs-4" style="padding: 2px 1px 2px 1px;">
                <div class="btn btn-block" style="padding:0; line-height:24px;">
                    <div style="font-size:12px;">成品琥珀</div>
                    <div class="btn-hq btn-hq-up" style="font-size:18px;" goods-hq="FU">320.827 <i class="fa fa-long-arrow-up"></i></div>
                </div>
            </div>
            <div class="col-xs-4" style="padding: 2px 0 2px 1px;">
                <div class="btn btn-block" style="padding:0; line-height:24px;">
                    <div style="font-size:12px;">毛主席纪念章</div>
                    <div class="btn-hq btn-hq-up" style="font-size:18px;" goods-hq="CU">32481.00 <i class="fa fa-long-arrow-up"></i></div>
                </div>
            </div>

    </div>
</div>

    </div>

    <div class="scrollable-content section overthrow" style="padding:3px 8px">
        




<div class="panel">
    <div class="panel-heading">
        今日盈亏(元)
        <span  ng-style="vm.TodayProfitStyle1" class="ng-binding" style="color: rgb(205, 0, 0); font-size: 14px; "> <?php echo ($tosn); ?> <i class="fa fa-long-arrow-up"></i></span>
        <span  ng-style="vm.TodayProfitStyle2" class="ng-binding" style="color: green; display: none; font-size: 14px; "> <?php echo ($tosn); ?> <i class="fa fa-long-arrow-down"></i></span>
        <div class="pull-right">
            <!-- <button class="btn btn-primary btn-sm" ui-turn-on="modalSellAll">一键平仓</button> -->
        </div>
    </div>
    <div class="panel-body" style="padding:0">
        <div class="list-group" style="font-size:12px;">
        </div>
    </div>
</div>

<div get-my-buy-transaction="">
<?php if(is_array($nolist)): $i = 0; $__LIST__ = $nolist;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$on): $mod = ($i % 2 );++$i;?><div class='' style='width:100%;height:50px;border-bottom:1px solid #ccc;'>
		<div style='float:left;width:20%;height:50px;line-height:50px;font-size:16px;color:#cd0000'><div class=" ploss"><?php echo ($on["yj"]); ?></div></div>
		<div style='width:10%;float:left;'>
			<div style='height:25px;line-height:25px;text-align:center;font-size:16px;color:green;'>买<?php if($on["ostyle"] == 1): ?>跌<?php else: ?>涨<?php endif; ?></div>
			<div style='height:25px;line-height:25px;text-align:center;font-size:16px;'><?php echo ($on["onumber"]); ?>手</div>
		</div>
		<div style='width:35%;float:left;'>
			<div style='height:25px;line-height:25px;text-align:center;font-size:16px;'><?php echo ($on["cname"]); ?>(<?php echo ($on["company"]); ?>)</div>
			<div style='height:25px;line-height:25px;text-align:center;font-size:16px;'><?php echo ($on["buyprice"]); ?></div>
		</div>
		<div style='width:10%;float:left;line-height:50px;font-size:25px;text-align:center;'>&gt</div>
		<a href="<?php echo U('Detailed/orderid');?>?orderid=<?php echo ($on["oid"]); ?>"><div style='width:20%;float:left;margin-top:10px;'><button class="btn btn-primary btn-sm" style='float:right;'>转让</button></div></a>
	
	</div><?php endforeach; endif; else: echo "" ;endif; ?>

</div>


    </div>
<div ui-content-for="modals">
        

<div class="modal" ui-if='modalSell' ui-state='modalSell' style="padding-top: 90px;">
    <div class="modal-backdrop in"></div>
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button class="close fa fa-close" ui-turn-off="modalSell"></button>
                <h4 class="modal-title">确认转让</h4>
            </div>
            <div class="modal-body">
                <form name="SellTransactionForm" role="form">
                    <input name="BuyTransactionId" type="hidden" value="{{vm.Id}}" />
                    <div style="text-align:center;">
                        <div>{{vm.TransactionGoodsName}}</div>
                        <div>{{vm.BuyPrice}} {{vm.BuyNumber}}手 {{vm.BuyType == 1 ? "买涨" : "买跌"}}</div>
                    </div>
                    <div role="alert" class="alert alert-dismissible alert-{{alert.type}}" ng-repeat="alert in alerts">
<button aria-label="Close" data-dismiss="alert" ng-click="alert.close()" class="close" type="button"><span aria-hidden="true" >×</span></button>
    <span style="white-space: pre;" ng-bind= "alert.msg" ></span>
</div>
                </form>
            </div>
            <div class="modal-footer">
                <button class="btn btn-primary btn-block" ng-click="sell()" ng-disabled="btnSellDisabled" type="button">确定</button>
            </div>
        </div>
    </div>
</div>


<div class="modal" ui-if='modalSellAll' ui-state='modalSellAll' style="padding-top: 90px;">
    <div class="modal-backdrop in"></div>
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button class="close fa fa-close" ui-turn-off="modalSellAll"></button>
                <h4 class="modal-title">一键平仓</h4>
            </div>
            <div class="modal-body">
                <div style="text-align:center;">确定一键平仓吗？</div>
                <div role="alert" class="alert alert-dismissible alert-{{alert.type}}" ng-repeat="alert in alerts">
<button aria-label="Close" data-dismiss="alert" ng-click="alert.close()" class="close" type="button"><span aria-hidden="true" >×</span></button>
    <span style="white-space: pre;" ng-bind= "alert.msg" ></span>
</div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-primary btn-block" ng-click="sellAll()" ng-disabled="btnSellAllDisabled" type="button">确定</button>
            </div>
        </div>
    </div>
</div>   


    </div>
    
</div>
<script type="text/javascript">
setInterval('pcprice()', 1000);
function pcprice() 
    { 

      var yinprice1=0;
      var yinprice2=0;
      var yinprice3=0;
      var ykzf = $(".ykzf");
      var yincangyoujia=parseFloat($('.yincangyoujia').html()).toFixed(2);
      //console.log(yincangyoujia);
      ykzf.each(function(){
          var buyprice = parseFloat($(this).children(".buyprice").html()).toFixed(2);
          //状态0是涨，1是跌
          var ykzfostyle = $(this).children(".ykzfostyle").html();
          //是否体验卷0不是，1是
          var ykzfeid = $(this).children(".ykzfeid").html();
          //数量
          var onumber = $(this).children(".onumber").html();
					//波动
          var ykzfwave = $(this).children(".ykzfwave").html();
          if(ykzfeid==0){
              if (ykzfostyle==0) {
                   var newprice1 = (yincangyoujia-buyprice)*ykzfwave*onumber;
              }else{
                   var newprice1 = (buyprice-yincangyoujia)*ykzfwave*onumber;
              };
              yinprice1 = newprice1+yinprice1;
              var newprice3 =newprice1.toFixed(2);
              //var newprice4 =(yinprice1.toFixed(1))/2;
            }else{
                 var newprice3 =0;
                // var newprice4 =0;
            }
			if(yincangyoujia=="NaN"){
				$(this).children(".cash1").text("");
			}else{
				$(this).children(".cash1").html(newprice3);
				if(newprice3>=0){
						$(this).children(".cash1").css('color','#ed0000')
				}else{
						$(this).children(".cash1").css('color','#2fb44e')
				}
			}      
	
    //        $(this).children(".cash11").html(newprice4);
      });
      var ykzf1 = $(".ykzf1");
      var ycbaiyinjia=parseFloat($('.ycbaiyinjia').html()).toFixed(2);
      //console.log(ycbaiyinjia);
      ykzf1.each(function(){
          var buyprice1 = parseFloat($(this).children(".buyprice2").html()).toFixed(2);
          //状态0是涨，1是跌
          var ykzfostyle = $(this).children(".ykzfostyle").html();
           //是否体验卷0不是，1是
          var ykzfeid = $(this).children(".ykzfeid").html();
          //数量
          var onumber = $(this).children(".onumber").html();
        	//波动
          var ykzfwave = $(this).children(".ykzfwave").html();
          if(ykzfeid==0){
              if (ykzfostyle==0) {
                  var yinprice1 = (ycbaiyinjia-buyprice1)*ykzfwave*onumber;
              }else{
                  var yinprice1 = (buyprice1-ycbaiyinjia)*ykzfwave*onumber;
              };
                  yinprice2 = yinprice1+yinprice2;
                  var yinprice3 =yinprice1.toFixed(2);
              //    var yinprice4 =(yinprice2.toFixed(1))/2;
          }else{
                var yinprice3 =0;
             //   var yinprice4 =0;
          }
          if(ycbaiyinjia=="NaN"){
						$(this).children(".cash2").text("");
					}else{
						$(this).children(".cash2").html(yinprice3);
						if(yinprice3>=0){
								$(this).children(".cash2").css('color','#ed0000')
						}else{
								$(this).children(".cash2").css('color','#2fb44e')
						}
					}
           
      //     $(this).children(".cash22").html(yinprice4);
      });        
      var ykzf2 = $(".ykzf2");
      var yctojia=parseFloat($('.yctojia').html()).toFixed(2);
      ykzf2.each(function(){
          var buyprice2 = parseFloat($(this).children(".buyprice3").html()).toFixed(2);
           //状态0是涨，1是跌
          var ykzfostyle = $(this).children(".ykzfostyle").html();
          //是否体验卷0不是，1是
          var ykzfeid = $(this).children(".ykzfeid").html();
          //数量
          var onumber = $(this).children(".onumber").html();
          //波动
          var ykzfwave = $(this).children(".ykzfwave").html();
          if(ykzfeid==0){
               if (ykzfostyle==0) {
                    var newpr3 = (yctojia-buyprice2)*ykzfwave*onumber;
              }else{
                    var newpr3 = (buyprice2-yctojia)*ykzfwave*onumber;
              };
                  yinprice3=newpr3+yinprice3;
                  newpr4 = newpr3.toFixed(2);
               //   newpr5 = (yinprice3.toFixed(1))/2;
          }else{
                  newpr4 = 0;
              //    newpr5 = 0;
          }
          
          if(yctojia=="NaN"){
						$(this).children(".cash3").text("");	
					}else{
						$(this).children(".cash3").html(newpr4);
						if(newpr4>=0){
								$(this).children(".cash3").css('color','#ed0000')
						}else{
								$(this).children(".cash3").css('color','#2fb44e')
						}
					}
    //      $(this).children(".cash33").html(newpr5);
      });    
      var picsum=Number(yinprice1+yinprice2+yinprice3).toFixed(2);
      // picsum =picsum/2
      $('.ploss').each(function(){
      	if($(this).text()==""){
	      	$('.yk_con').html();
	      }else{
	      	$('.yk_con').html(picsum);
	      }	
      })
       
    } 

</script>