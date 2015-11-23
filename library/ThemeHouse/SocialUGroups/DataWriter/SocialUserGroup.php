<?php

/**
 * Data writer for social user groups.
 */
class ThemeHouse_SocialUGroups_DataWriter_SocialUserGroup extends XenForo_DataWriter
{

    /**
     * Title of the phrase that will be created when a call to set the
     * existing data fails (when the data doesn't exist).
     *
     * @var string
     */
    protected $_existingDataErrorPhrase = 'th_requested_social_user_group_not_found_socialusergroups';

    /**
     * Gets the fields that are defined for the table.
     * See parent for explanation.
     *
     * @return array
     */
    protected function _getFields()
    {
        return array(
            'xf_social_user_group' => array(
                'social_user_group_id' => array(
                    'type' => self::TYPE_UINT,
                    'autoIncrement' => true
                ), /* END 'social_user_group_id' */
				'social_forum_id' => array(
                    'type' => self::TYPE_UINT,
                    'required' => true
                ), /* END 'social_forum_id' */
			    'title' => array(
                    'type' => self::TYPE_STRING,
                    'required' => true,
                    'verification' => array(
                        '$this',
                        '_verifyTitle'
                    )
                ), /* END 'title' */
                'style_id' => array(
                    'type' => self::TYPE_UINT,
                    'default' => 0
                ), /* END 'style_id' */
            )
            , /* END 'xf_social_user_group' */
		);
    } /* END _getFields */

    /**
     * Gets the actual existing data out of data that was passed in.
     * See parent for explanation.
     *
     * @param mixed
     *
     * @return array false
     */
    protected function _getExistingData($data)
    {
        if (!$socialUserGroupId = $this->_getExistingPrimaryKey($data, 'social_user_group_id')) {
            return false;
        }

        if (ThemeHouse_SocialGroups_SocialForum::hasInstance()) {
            $socialForum = ThemeHouse_SocialGroups_SocialForum::getInstance()->toArray();
        } else {
            return false;
        }

        $socialUserGroup = $this->_getSocialUserGroupModel()->getSocialUserGroupById($socialUserGroupId, $socialForum['social_forum_id']);
        if (!$socialUserGroup) {
            return false;
        }

        return $this->getTablesDataFromArray($socialUserGroup);
    } /* END _getExistingData */

    /**
     * Gets SQL condition to update the existing record.
     *
     * @return string
     */
    protected function _getUpdateCondition($tableName)
    {
        return 'social_user_group_id = ' . $this->_db->quote($this->getExisting('social_user_group_id'));
    } /* END _getUpdateCondition */

    /**
     * Verification callback to check that a social user group title is valid
     *
     * @param string Title
     *
     * @return bool
     */
    protected function _verifyTitle(&$title)
    {
        if ($this->isUpdate() && $title === $this->getExisting('title')) {
            return true; // unchanged, always pass
        }

        // standardize white space in titles
        $title = trim(preg_replace('/\s+/', ' ', $title));

        $existingUserGroup = $this->_getSocialUserGroupModel()->getSocialUserGroup(
            array(
                'social_user_group_id' => $this->get('social_user_group_id'),
                'title' => $title
            ));
        if ($existingUserGroup && $existingUserGroup['social_user_group_id'] != $this->get('social_user_group_id')) {
            $this->error(new XenForo_Phrase('th_social_user_group_titles_must_be_unique_socialusergroups'),
                'title');
            return false;
        }

        return true;
    } /* END _verifyTitle */

    /**
     * Get the social user groups model.
     *
     * @return ThemeHouse_SocialUGroups_Model_SocialUserGroup
     */
    protected function _getSocialUserGroupModel()
    {
        return $this->getModelFromCache('ThemeHouse_SocialUGroups_Model_SocialUserGroup');
    } /* END _getSocialUserGroupModel */
}