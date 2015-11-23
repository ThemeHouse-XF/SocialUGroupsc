<?php

class ThemeHouse_SocialUGroups_Extend_ThemeHouse_SocialGroups_Model_SocialForum extends XFCP_ThemeHouse_SocialUGroups_Extend_ThemeHouse_SocialGroups_Model_SocialForum
{

    public function getSocialForumPermissions(array $user, $permissions)
    {
        $socialForum = array();
        if (!isset($user['user_group_permissions'])) {
            if (ThemeHouse_SocialGroups_SocialForum::hasInstance()) {
                $socialForum = ThemeHouse_SocialGroups_SocialForum::getInstance()->toArray();
            }
        } else {
            $socialForum = $user;
        }

        $socialForumMember = array();
        if (!isset($user['social_user_group_id'])) {
            if (ThemeHouse_SocialGroups_SocialForum::hasInstance()) {
                $socialForumMember = ThemeHouse_SocialGroups_SocialForum::getInstance()->getMember();
            }
        } else {
            $socialForumMember = $user;
        }
        $nodePermissions = array();
        if (isset($socialForumMember['is_approved']) && isset($socialForumMember['is_invited']) && !empty($socialForumMember) && $socialForumMember['is_approved'] &&
            !$socialForumMember['is_invited']) {
            if (isset($socialForum['user_group_permissions']) && $socialForum['user_group_permissions']) {
                $userGroupPermissions = unserialize($socialForum['user_group_permissions']);
                if (isset($userGroupPermissions[$socialForumMember['social_user_group_id']])) {
                    $nodePermissions = $userGroupPermissions[$socialForumMember['social_user_group_id']];
                }
            }
        }
        foreach ($nodePermissions as $nodePermission => $permissionValue) {
            if ($permissionValue) {
                $permissions[$nodePermission] = 1;
            }
        }
        return parent::getSocialForumPermissions($user, $permissions);
    } /* END getSocialForumPermissions */

    /**
     * Determines if the specified user can manage social user groups.
     *
     * @param array $socialForum
     * @param string $errorPhraseKey By ref. More specific error, if available.
     * @param array|null $viewingUser Viewing user reference
     *
     * @return boolean
     */
    public function canManageSocialUserGroups(array $socialForum, &$errorPhraseKey = '', array $nodePermissions = null,
        array $viewingUser = null)
    {
        $this->standardizeViewingUserReferenceForNode($socialForum['node_id'], $viewingUser, $nodePermissions);

        return XenForo_Permission::hasContentPermission($nodePermissions, 'manageSocialUserGroups');
    } /* END canManageSocialUserGroups */
}