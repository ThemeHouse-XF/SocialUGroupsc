<?php

/**
 *
 * @see ThemeHouse_SocialGroups_DataWriter_SocialForumMember
 */
class ThemeHouse_SocialUGroups_Extend_ThemeHouse_SocialGroups_DataWriter_SocialForumMember extends XFCP_ThemeHouse_SocialUGroups_Extend_ThemeHouse_SocialGroups_DataWriter_SocialForumMember
{

    /**
     *
     * @see ThemeHouse_SocialGroups_DataWriter_SocialForumMember::_getFields()
     */
    protected function _getFields()
    {
        $fields = parent::_getFields();

        $fields['xf_social_forum_member']['social_user_group_id'] = array(
            'type' => self::TYPE_UINT,
            'default' => '0',
        );

        return $fields;
    } /* END _getFields */

    /**
     * Pre-save handling.
     */
    protected function _preSave()
    {
        parent::_preSave();

        if (isset($GLOBALS['ThemeHouse_SocialGroups_ControllerPublic_SocialForum'])) {
            $this->_processSocialUserGroupChange($GLOBALS['ThemeHouse_SocialGroups_ControllerPublic_SocialForum']);
        }
    } /* END _preSave */

    /**
     *
     * @param ThemeHouse_SocialGroups_ControllerPublic_SocialForum $controller
     */
    protected function _processSocialUserGroupChange(ThemeHouse_SocialGroups_ControllerPublic_SocialForum $controller)
    {
        $socialUserGroupId = $controller->getInput()->filterSingle('social_user_group_id', XenForo_Input::UINT);

        $socialForumModel = $this->getModelFromCache('ThemeHouse_SocialGroups_Model_SocialForum');

        if (ThemeHouse_SocialGroups_SocialForum::hasInstance()) {
            $socialForum = ThemeHouse_SocialGroups_SocialForum::getInstance()->toArray();
        } else {
            return $this->responseError('th_requested_social_forum_not_found_socialgroups');
        }
        if ($socialForumModel->canManageSocialUserGroups($socialForum)) {
            $this->set('social_user_group_id', $socialUserGroupId);
        }
    } /* END _processSocialUserGroupChange */
}