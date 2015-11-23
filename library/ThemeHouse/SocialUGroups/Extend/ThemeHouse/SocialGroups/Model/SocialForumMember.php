<?php

class ThemeHouse_SocialUGroups_Extend_ThemeHouse_SocialGroups_Model_SocialForumMember extends XFCP_ThemeHouse_SocialUGroups_Extend_ThemeHouse_SocialGroups_Model_SocialForumMember
{

    const FETCH_SOCIAL_USER_GROUP = 0x01;

    public function prepareSocialForumMemberFetchOptions(array $fetchOptions)
    {
        $socialForumMemberFetchOptions = parent::prepareSocialForumMemberFetchOptions($fetchOptions);
        $selectFields = $socialForumMemberFetchOptions['selectFields'];
        $joinTables = $socialForumMemberFetchOptions['joinTables'];
        $orderClause = $socialForumMemberFetchOptions['orderClause'];

        if (!empty($fetchOptions['th_socialusergroups_join'])) {
            if ($fetchOptions['th_socialusergroups_join'] & self::FETCH_SOCIAL_USER_GROUP) {
                $selectFields .= ',
					social_user_group.title AS social_user_group_title, social_user_group.style_id';
                $joinTables .= '
					LEFT JOIN xf_social_user_group AS social_user_group ON
						(social_user_group.social_user_group_id = social_forum_member.social_user_group_id)';
                $orderClause .= ', user.username ASC';
            }
        }

        return array(
            'selectFields' => $selectFields,
            'joinTables' => $joinTables,
            'orderClause' => $orderClause
        );
    } /* END prepareSocialForumMemberFetchOptions */

    public function getSocialForumUsers(array $conditions = array(), array $fetchOptions = array())
    {
        $fetchOptions['th_socialusergroups_join'] = self::FETCH_SOCIAL_USER_GROUP;

        return parent::getSocialForumUsers($conditions, $fetchOptions);
    } /* END getSocialForumUsers */
}