<?php

class ThemeHouse_SocialUGroups_Install_Controller extends ThemeHouse_Install
{

    protected $_resourceManagerUrl = 'http://xenforo.com/community/resources/social-user-groups.1879/';

    /**
     *
     * @see ThemeHouse_Install::_getPrerequisites()
     */
    protected function _getPrerequisites()
    {
        return array(
            'ThemeHouse_SocialGroups' => '1370009392'
        );
    } /* END _getPrerequisites */

    /**
     *
     * @see ThemeHouse_Install::_getTables()
     */
    protected function _getTables()
    {
        return array(
            'xf_social_user_group' => array(
                'social_user_group_id' => 'INT(10) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY',
                'social_forum_id' => 'INT(10) UNSIGNED NOT NULL',
                'title' => 'VARCHAR(50) NOT NULL',
                'style_id' => 'INT(10) UNSIGNED NOT NULL DEFAULT \'0\' COMMENT \'Style override for specific social user group\'',
            ), /* 'xf_social_user_group' */
        );
    } /* END _getTables */

    protected function _getTableChanges()
    {
        return array(
            'xf_social_forum_member' => array(
                'social_user_group_id' => 'INT(10) UNSIGNED NOT NULL DEFAULT 0',
            ), /* 'xf_social_forum_member' */
            'xf_social_forum' => array(
                'user_group_permissions' => 'TEXT',
            ), /* 'user_group_permissions' */
        );
    } /* END _getTableChanges */

    protected function _getUniqueKeys()
    {
        return array(
            'xf_social_user_group' => array(
                'social_forum_id_title' => array(
                    'social_forum_id',
                    'title'
                ), /* END 'social_forum_id_title' */
            ) /* 'xf_social_user_group' */
        );
    } /* END _getUniqueKeys */

    /**
     *
     * @see ThemeHouse_Install::_getPermissionEntries()
     */
    protected function _getPermissionEntries()
    {
        return array(
            'forum' => array(
                'manageSocialUserGroups' => array(
                    'permission_group_id' => 'forum', /* 'permission_group_id' */
                    'permission_id' => 'editSocialForum', /* 'permission_id' */
                ), /* 'manageSocialUserGroups' */
            ), /* 'forum' */
        );
    } /* END _getPermissionEntries */
}