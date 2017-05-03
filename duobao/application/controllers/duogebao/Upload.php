<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 上传类
 * Class Upload
 */
class Upload extends Duogebao_Base {
    /**
     * Index::upload()
     * 异步上传图片
     *
     * @return void
     */
    public function share_img() {
        $FILES = array_keys($_FILES);
        $fileInput = $FILES[0];
        $path = $this->input->get_post('path', true) ? $this->input->get_post('path', true) : 'other';
        $return = upload_files($fileInput, $path);
        if(isset($return['url'])) {
            $return['small_url'] = get_img_resize_url($return['url'], Lib_Constants::SHARE_IMG_SMALL,Lib_Constants::SHARE_IMG_SMALL);
            $return['mid_url'] = get_img_resize_url($return['url'], Lib_Constants::SHARE_IMG_MIDDLE,Lib_Constants::SHARE_IMG_MIDDLE);
        }
        exit(json_encode($return));
    }
}