<?php

/**
 *
 * @see XenForo_ControllerPublic_Thread
 */
class ThemeHouse_SocialUGroups_Extend_XenForo_ControllerPublic_Thread extends XFCP_ThemeHouse_SocialUGroups_Extend_XenForo_ControllerPublic_Thread
{

    /**
     *
     * @see ThemeHouse_SocialGroups_Extend_XenForo_ControllerPublic_Thread::_overrideSocialForumStyle()
     */
    protected function _overrideSocialForumStyle(array $thread)
    {
        if (isset($thread['social_forum_id']) && $thread['social_forum_id']) {
            if (ThemeHouse_SocialGroups_SocialForum::hasInstance()) {
                $socialForumMember = ThemeHouse_SocialGroups_SocialForum::getInstance()->getMember();
                if (isset($socialForumMember['style_id']) && $socialForumMember['style_id']) {
                    $this->setViewStateChange('styleId', $socialForumMember['style_id']);
                    return;
                }
            }
        }

        return parent::_overrideSocialForumStyle($thread);
    } /* END _overrideSocialForumStyle */
}