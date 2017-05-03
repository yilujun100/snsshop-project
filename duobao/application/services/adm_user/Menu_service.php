<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 后台菜单相关功能
 *
 * 依赖 adm_user/User_service
 *
 * Class User_service
 */
class Menu_service extends MY_Service
{
    /**
     * 当前角色权限
     *
     * @var
     */
    private $role_purview;

    /**
     * 权限白名单
     *
     * @var
     */
    private $white_list;

    /**
     * 当前角色菜单
     *
     * @var array
     */
    private $role_menu;

    /**
     * 当前节点
     *
     * @var array
     */
    private $node;

    /**
     * 当前位置
     *
     * @var array
     */
    private $position;


    /**
     * 运行服务
     *
     * @param $node
     * @param $menu
     * @param $white_list
     */
    public function run($node, $menu, $white_list = array())
    {
        $this->node = $node;

        $this->role_purview = $this->user_service->get_role_purview();

        $this->white_list = $white_list;

        $this->role_menu = $this->role_menu($menu);

        $this->current_menu();
    }

    /**
     * 获取当前角色菜单
     *
     * @return array
     */
    public function get_role_menu()
    {
        return $this->role_menu;
    }

    /**
     * 获取当前位置信息
     *
     * @return array
     */
    public function get_position()
    {
        return $this->position;
    }

    /**
     * 处理菜单当前位置
     */
    private function current_menu()
    {
        $position = array();
        foreach ($this->role_menu as & $menu1) {
            foreach ($menu1['sub'] as & $menu2) {
                foreach ($menu2['sub'] as & $menu3) {
                    if (! empty($menu3['sub'])) {
                        foreach ($menu3['sub'] as & $menu4) {
                            if ($this->node == $menu4['node']) {
                                $position[] = $menu4;
                                $position[] = $menu3;
                                $position[] = $menu2;
                                $position[] = $menu1;
                                $menu4['current'] = 1;
                                $menu3['current'] = 1;
                                $menu2['current'] = 1;
                                $menu1['current'] = 1;
                                break;
                            }
                        }
                    } else {
                        if ($this->node == $menu3['node']) {
                            $position[] = $menu3;
                            $position[] = $menu2;
                            $position[] = $menu1;
                            $menu3['current'] = 1;
                            $menu2['current'] = 1;
                            $menu1['current'] = 1;
                            break;
                        }
                    }
                }
            }
        }
        $this->position = array_reverse($position);
    }

    /**
     * 处理菜单
     *
     * @param $menus
     *
     * @return array
     */
    private function role_menu($menus)
    {
        $role_menu = array();
        foreach ($menus as $k => $menu) {
            $role_menu_sub = $this->role_menu_recursion($menu['sub']);
            if ($role_menu_sub) {
                $role_menu[] = array(
                    'name' => $menu['name'],
                    'node' => $menu['node'],
                    'sub' => $role_menu_sub
                );
            }
        }
        return $role_menu;
    }

    /**
     * 递归处理菜单
     *
     * @param $menus
     *
     * @return array
     */
    private function role_menu_recursion($menus)
    {
        $role_menu = array();
        foreach ($menus as $menu) {
            if (! empty($menu['sub'])) {
                $menu['sub'] = call_user_func(array($this, 'role_menu_recursion'), $menu['sub']);
                if ($menu['sub']) {
                    $role_menu[] = $menu;
                }
            } else {
                if (isset($menu['node']) && (in_array($menu['node'], $this->role_purview) || in_array($menu['node'], $this->white_list))) {
                    $role_menu[] = $menu;
                }
            }
        }
        return $role_menu;
    }
}