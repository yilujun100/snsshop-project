<?php defined('BASEPATH') OR exit('No direct script access allowed');?>

<?php
if (is_granted($node)) {
    if (isset($button)) {
        $type = Lib_Constants::$btn_states[$button]['type'];
        $text = Lib_Constants::$btn_states[$button]['text'];
    }
?>
    <a href="javascript:;"
       data-node="<?=$node?>"
       class="btn-opera btn-opera-<?=empty($type)?'default':$type?><?=empty($class)?'':' '.(is_array($class)?implode(' ', $class):$class)?>"
        <?php
        if (! empty($id)) {
            echo 'id="' . $id . '"';
        }
        if (! empty($attr) && is_array($attr)) {
            foreach ($attr as $k => $v) {
                echo $k . '="' . $v . '"';
            }
        }
        ?>
    ><?=$text?></a>
<?php }?>