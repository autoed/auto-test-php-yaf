<?php

/**
 * Class MeetingController
 * User:  fomo3d.wiki
 * Email: fomo3d.wiki@gmail.com
 * Date: 2020/5/11
 */
class MeetingController extends BaseController
{
    /**
     * 开启auto API Document
     */
    use Auto\Api;

    /**
     * 发起会议
     * @GET array('email'=>$data::email())
     * User:  fomo3d.wiki
     * Email: fomo3d.wiki@gmail.com
     * Date: 2020/5/10
     */
    public function startAction()
    {
        $name = $this->get('email', 'this is start email');
        $this->getResponse()->setBody(success(['str'=>$name]));
    }

    /**
     * 离开会议
     * @GET array('name'=>$data::name())
     * User:  fomo3d.wiki
     * Email: fomo3d.wiki@gmail.com
     * Date: 2020/5/11
     */
    public function leaveAction()
    {
        $name = $this->get('name', 'this is leave name');
        $this->getResponse()->setBody(success(['str'=>$name]));
    }

    /**
     * 加入会议
     * @GET array('num'=>$data::bankAccountNumber())
     * User:  fomo3d.wiki
     * Email: fomo3d.wiki@gmail.com
     * Date: 2020/5/11
     */
    public function joinAction()
    {
        $name = $this->get('num', 'this is join num');
        $this->getResponse()->setBody(success(['str'=>$name]));
    }
}