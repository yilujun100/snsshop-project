$(function(){fnInit();fnChangeExchangeNum();fnChangeStampsNum();fnStatusUseStamps()});var fnChangeExchangeNum=function(){var needStampsOne=luckyBag.stampsOne;var cost;$('#exchangeIncrease, #exchangeDecrease').on('click',function(){var num=$('#exchangeNum').text();var needStampsNum=$('#totalStamps').text();var stampsUse=$('#remain-stamps-use').text();if($(this).attr('id')=='exchangeIncrease'){num++}if($(this).attr('id')=='exchangeDecrease'){num--;if(num<=luckyBag.qtyMin){num=1}}$('#exchangeNum, #exchangeCase').text(num);$('#totalStamps').text(needStampsOne*num);cost=luckyBag.isUseStamps?($('#totalStamps').text()-$('#remain-stamps-use').text())*1:$('#totalStamps').text()*1;if(luckyBag.isUseStamps&&parseInt($('#totalStamps').text())<parseInt($('#remain-stamps-use').text())){cost=0}$('#needPayAmount, #totalPay').text('￥'+cost)})};var fnChangeStampsNum=function(){var cost;$('#stampsIncrease, #stampsDecrease').on('click',function(){if(!luckyBag.isUseStamps){return}else{var needStampsNum=$('#totalStamps').text();var num=$('#remain-stamps-use').text();if($(this).attr('id')=='stampsIncrease'){num++;if(num>=luckyBag.remainStamps){num=luckyBag.remainStamps}}if($(this).attr('id')=='stampsDecrease'){num--;if(num<=luckyBag.qtyMin){num=1}}cost=(needStampsNum-num)*1;if(num>needStampsNum){cost=0}$('#remain-stamps-use').text(num);$('#needPayAmount, #totalPay').text('￥'+cost)}})};var fnStatusUseStamps=function(){var useStamps=$('#use-stamps');var cost;useStamps.on('click',function(){var needStampsNum=$('#totalStamps').text();var useStampsNum=$('#remain-stamps-use').text();if(!$(this).is(':checked')){cost=needStampsNum;$(this).prop('checked',false);luckyBag.isUseStamps=false;$('#needPayAmount, #totalPay').text('￥'+cost)}else{cost=(needStampsNum-useStampsNum)*1;if(parseInt($('#totalStamps').text())<parseInt($('#remain-stamps-use').text())){cost=0}luckyBag.isUseStamps=true;$(this).prop('checked',true);$('#needPayAmount, #totalPay').text('￥'+cost)}})};var fnInit=function(){$('#totalStamps').text(luckyBag.stampsOne);$('.use-stamps strong').text(luckyBag.remainStamps);$('#remain-stamps-use').text(luckyBag.defaultCoupon);var needStamps=$('#totalStamps').text();var useStamps=luckyBag.defaultCoupon;var totalCost=needStamps-useStamps;$('#use-stamps').prop('checked',true);$('#remain-stamps').text(luckyBag.defaultCoupon);$('#needPayAmount, #totalPay').text('￥'+totalCost)};