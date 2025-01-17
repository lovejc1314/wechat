<?php
/**
 * Created by PhpStorm.
 * User: 67066
 * Date: 2020/11/25
 * Time: 11:21
 */

namespace EasySwoole\WeChat\OfficialAccount\Menu;


use EasySwoole\WeChat\Kernel\BaseClient;
use EasySwoole\WeChat\Kernel\Exceptions\HttpException;
use EasySwoole\WeChat\Kernel\ServiceProviders;

/**
 * 自定义菜单
 * 包括:创建接口,查询接口,删除接口,个性化菜单接口,获取自定义菜单配置实现
 * 参考微信文档 https://developers.weixin.qq.com/doc/offiaccount/Custom_Menus/Querying_Custom_Menus.html
 * */
class Client extends BaseClient
{

    /**
     * 获取自定义菜单配置
     * @return mixed
     * @throws HttpException
     */
    public function list()
    {
        $response = $this->getClient()
            ->setMethod('GET')
            ->send($this->buildUrl('/cgi-bin/menu/get', [
                'access_token' => $this->app[ServiceProviders::AccessToken]->getToken()
            ])
            );

        $this->checkResponse($response, $parseData);
        return $parseData;
    }

    /**
     * 获取当前菜单配置
     * @return mixed
     * @throws HttpException
     */
    public function current()
    {
        $response = $this->getClient()
            ->setMethod('GET')
            ->send($this->buildUrl('/cgi-bin/get_current_selfmenu_info', [
                'access_token' => $this->app[ServiceProviders::AccessToken]->getToken()
            ])
            );

        $this->checkResponse($response, $parseData);
        return $parseData;
    }

    /**
     * 创建菜单或个性化菜单
     * @param array $buttons
     * @param array $matchRule
     * @return bool
     * @throws HttpException
     */
    public function create(array $buttons, array $matchRule = [])
    {
        if (!empty($matchRule)) {
            // 创建个性化菜单
            $response = $this->getClient()
                ->setMethod('POST')
                ->setBody($this->jsonDataToStream([
                    'button' => $buttons,
                    'matchrule' => $matchRule,
                ]))
                ->send($this->buildUrl('/cgi-bin/menu/addconditional', [
                    'access_token' => $this->app[ServiceProviders::AccessToken]->getToken()
                ])
                );
            return $this->checkResponse($response);
        }

        // 创建菜单
        $response = $this->getClient()
            ->setMethod('POST')
            ->setBody($this->jsonDataToStream(['button' => $buttons]))
            ->send($this->buildUrl('/cgi-bin/menu/create', [
                'access_token' => $this->app[ServiceProviders::AccessToken]->getToken()
            ])
            );
        return $this->checkResponse($response);
    }

    /**
     * 删除菜单或个性化菜单
     * @param int|null $menuId
     * @return bool
     * @throws HttpException
     */
    public function delete(int $menuId = null)
    {
        if (is_null($menuId)) {
            // 删除所有菜单
            $response = $this->getClient()
                ->setMethod('GET')
                ->send($this->buildUrl('/cgi-bin/menu/delete', [
                    'access_token' => $this->app[ServiceProviders::AccessToken]->getToken()
                ])
                );
            return $this->checkResponse($response);
        }

        // 删除个性化菜单
        $response = $this->getClient()
            ->setMethod('POST')
            ->setBody($this->jsonDataToStream(['menuid' => $menuId]))
            ->send($this->buildUrl('/cgi-bin/menu/delconditional', [
                'access_token' => $this->app[ServiceProviders::AccessToken]->getToken()
            ])
            );
        return $this->checkResponse($response);
    }

    /**
     * 测试个性化菜单匹配结果
     * @param string $userId
     * @return bool
     * @throws HttpException
     */
    public function match(string $userId)
    {
        $response = $this->getClient()
            ->setMethod('POST')
            ->setBody($this->jsonDataToStream(['user_id' => $userId]))
            ->send($this->buildUrl('/cgi-bin/menu/trymatch', [
                'access_token' => $this->app[ServiceProviders::AccessToken]->getToken()
            ])
            );
        $this->checkResponse($response, $parseData);
        return $parseData;
    }
}