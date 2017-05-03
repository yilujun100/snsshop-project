<?php $this->widget('weixin_share', array()) ?>
<?php if(defined('ENVIRONMENT') && ENVIRONMENT === 'production'){ ?>
<script type="text/javascript" src="http://tajs.qq.com/stats?sId=55791706" charset="UTF-8"></script>
<script type="text/javascript" src="http://ta.nexto2o.com/js/ta.js?id=1&siteid=683&key=<?=get_nex_to_key()?>"></script>
<?php } ?>