<?php

/**
 * Class ProductController
 * User:  fomo3d.wiki
 * Email: fomo3d.wiki@gmail.com
 * Date: 2020/5/10
 */
class ProductController extends BaseController
{
    /**
     * 开启auto API Document
     */
    use Auto\Api;

    /**
     * 出售商品
     * @GET array('name'=>$data::name())
     * User:  fomo3d.wiki
     * Email: fomo3d.wiki@gmail.com
     * Date: 2020/5/10
     */
    public function sellAction()
    {
        $name = $this->get('name', 'this is sell name');
        $this->getResponse()->setBody(success(['str'=>$name]));
    }

    /**
     * 统计商品
     * @GET array('email'=>$data::email())
     * User:  fomo3d.wiki
     * Email: fomo3d.wiki@gmail.com
     * Date: 2020/5/11
     */
    public function countAction()
    {
        $name = $this->get('email', 'this is count email');
        $this->getResponse()->setBody(success(['str'=>$name]));
    }

    /**
     * 买进商品
     * @GET array('num'=>$data::bankAccountNumber())
     * User:  fomo3d.wiki
     * Email: fomo3d.wiki@gmail.com
     * Date: 2020/5/11
     */
    public function buyAction()
    {
        $name = $this->get('num', 'this is buy num');
        $this->getResponse()->setBody(success(['str'=>$name]));
    }
}