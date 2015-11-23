<?php

class ThemeHouse_SocialUGroups_Extend_ThemeHouse_SocialGroups_DataWriter_SocialForum extends XFCP_ThemeHouse_SocialUGroups_Extend_ThemeHouse_SocialGroups_DataWriter_SocialForum
{
    protected $_userGroupPermissions = null;

    protected function _getFields()
    {
        $fields = parent::_getFields();

        $fields['xf_social_forum']['user_group_permissions'] = array('type' => self::TYPE_SERIALIZED, 'default' => '');

        return $fields;
    } /* END _getFields */

    public function setSocialUserGroupPermissions($socialUserGroupId, array $permissions)
    {
        if (!$this->_userGroupPermissions) {
            if ($this->get('user_group_permissions') && !$this->_userGroupPermissions) {
                $this->_userGroupPermissions = unserialize($this->get('user_group_permissions'));
            } else {
                $this->_userGroupPermissions = array();
            }
        }

        $this->_userGroupPermissions[$socialUserGroupId] = $permissions;
    } /* END setSocialUserGroupPermissions */

    protected function _preSave()
    {
        parent::_preSave();

        $this->set('user_group_permissions', $this->_userGroupPermissions);
    } /* END _preSave */
}