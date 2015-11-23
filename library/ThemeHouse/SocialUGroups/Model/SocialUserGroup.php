<?php

/**
 * Model for social user groups.
 */
class ThemeHouse_SocialUGroups_Model_SocialUserGroup extends XenForo_Model
{

    /**
     * Gets all social user groups in the specified social forum, ordered by
     * title.
     *
     * @return array Format: [] => (array) user group info
     */
    public function getAllUserGroupsInSocialForum($socialForumId)
    {
        return $this->fetchAllKeyed(
            '
            SELECT *
            FROM xf_social_user_group
            WHERE social_forum_id = ?
            ORDER BY title
        ', 'social_user_group_id', $socialForumId);
    } /* END getAllUserGroupsInSocialForum */

    /**
     * Gets social user groups that match the specified criteria.
     *
     * @param array $conditions List of conditions.
     * @param array $fetchOptions
     *
     * @return array [social user group id] => info.
     */
    public function getSocialUserGroups(array $conditions = array(), array $fetchOptions = array())
    {
        $whereClause = $this->prepareSocialUserGroupConditions($conditions, $fetchOptions);

        $sqlClauses = $this->prepareSocialUserGroupFetchOptions($fetchOptions);
        $limitOptions = $this->prepareLimitFetchOptions($fetchOptions);

        return $this->fetchAllKeyed(
            $this->limitQueryResults(
                '
                SELECT social_user_group.*
                    ' . $sqlClauses['selectFields'] . '
                FROM xf_social_user_group AS social_user_group
                ' . $sqlClauses['joinTables'] . '
                WHERE ' . $whereClause . '
                ' . $sqlClauses['orderClause'] . '
            ', $limitOptions['limit'], $limitOptions['offset']),
            'social_user_group_id');
    } /* END getSocialUserGroups */

    /**
     * Gets the social user group that matches the specified criteria.
     *
     * @param array $conditions List of conditions.
     * @param array $fetchOptions Options that affect what is fetched.
     *
     * @return array false
     */
    public function getSocialUserGroup(array $conditions = array(), array $fetchOptions = array())
    {
        $socialUserGroups = $this->getSocialUserGroups($conditions, $fetchOptions);

        return reset($socialUserGroups);
    } /* END getSocialUserGroup */

    /**
     * Gets a social user group by ID.
     *
     * @param integer $socialUserGroupId
     * @param array $fetchOptions Options that affect what is fetched.
     *
     * @return array false
     */
    public function getSocialUserGroupById($socialUserGroupId, $socialForumId, array $fetchOptions = array())
    {
        $conditions = array(
            'social_user_group_id' => $socialUserGroupId,
            'social_forum_id' => $socialForumId,
        );

        return $this->getSocialUserGroup($conditions, $fetchOptions);
    } /* END getSocialUserGroupById */

    /**
     * Gets the total number of a social user group that match the specified
     * criteria.
     *
     * @param array $conditions List of conditions.
     *
     * @return integer
     */
    public function countSocialUserGroups(array $conditions = array())
    {
        $fetchOptions = array();

        $whereClause = $this->prepareSocialUserGroupConditions($conditions, $fetchOptions);
        $joinOptions = $this->prepareSocialUserGroupFetchOptions($fetchOptions);

        $limitOptions = $this->prepareLimitFetchOptions($fetchOptions);

        return $this->_getDb()->fetchOne(
            '
            SELECT COUNT(*)
            FROM xf_social_user_group AS social_user_group
            ' . $joinOptions['joinTables'] . '
            WHERE ' . $whereClause . '
        ');
    } /* END countSocialUserGroups */

    /**
     * Gets all social user groups titles.
     *
     * @return array [social user group id] => title.
     */
    public static function getSocialUserGroupTitles()
    {
        $socialUserGroups = XenForo_Model::create(__CLASS__)->getSocialUserGroups();
        $titles = array();
        foreach ($socialUserGroups as $socialUserGroupId => $socialUserGroup) {
            $titles[$socialUserGroupId] = $socialUserGroup['title'];
        }
        return $titles;
    } /* END getSocialUserGroupTitles */

    /**
     * Gets the default social user group record.
     *
     * @return array
     */
    public function getDefaultSocialUserGroup()
    {
        return array(
            'social_user_group_id' => '', /* END 'social_user_group_id' */
        );
    } /* END getDefaultSocialUserGroup */

    /**
     * Prepares a set of conditions to select social user groups against.
     *
     * @param array $conditions List of conditions.
     * @param array $fetchOptions The fetch options that have been provided. May
     * be edited if criteria requires.
     *
     * @return string Criteria as SQL for where clause
     */
    public function prepareSocialUserGroupConditions(array $conditions, array &$fetchOptions)
    {
        $db = $this->_getDb();
        $sqlConditions = array();

        if (isset($conditions['social_user_group_ids']) && !empty($conditions['social_user_group_ids'])) {
            $sqlConditions[] = 'social_user_group.social_user_group_id IN (' .
                 $db->quote($conditions['social_user_group_ids']) . ')';
        } else
            if (isset($conditions['social_user_group_id'])) {
                $sqlConditions[] = 'social_user_group.social_user_group_id = ' .
                     $db->quote($conditions['social_user_group_id']);
            }

        if (isset($conditions['title'])) {
            $sqlConditions[] = 'social_user_group.title = ' . $db->quote($conditions['title']);
        }

        if (isset($conditions['social_forum_ids']) && !empty($conditions['social_forum_ids'])) {
            $sqlConditions[] = 'social_user_group.social_forum_id IN (' . $db->quote($conditions['social_forum_ids']) .
                 ')';
        } else
            if (isset($conditions['social_forum_id'])) {
                $sqlConditions[] = 'social_user_group.social_forum_id = ' . $db->quote($conditions['social_forum_id']);
            }

        $this->_prepareSocialUserGroupConditions($conditions, $fetchOptions, $sqlConditions);

        return $this->getConditionsForClause($sqlConditions);
    } /* END prepareSocialUserGroupConditions */

    /**
     * Method designed to be overridden by child classes to add to set of
     * conditions.
     *
     * @param array $conditions List of conditions.
     * @param array $fetchOptions The fetch options that have been provided. May
     * be edited if criteria requires.
     * @param array $sqlConditions List of conditions as SQL snippets. May be
     * edited if criteria requires.
     */
    protected function _prepareSocialUserGroupConditions(array $conditions, array &$fetchOptions, array &$sqlConditions)
    {
    } /* END _prepareSocialUserGroupConditions */

    /**
     * Checks the 'join' key of the incoming array for the presence of the
     * FETCH_x bitfields in this class
     * and returns SQL snippets to join the specified tables if required.
     *
     * @param array $fetchOptions containing a 'join' integer key built from
     * this class's FETCH_x bitfields.
     *
     * @return string containing selectFields, joinTables, orderClause keys.
     * Example: selectFields = ', user.*, foo.title'; joinTables = ' INNER JOIN
     * foo ON (foo.id = other.id) '; orderClause = 'ORDER BY x.y'
     */
    public function prepareSocialUserGroupFetchOptions(array &$fetchOptions)
    {
        $selectFields = '';
        $joinTables = '';
        $orderBy = '';

        $this->_prepareSocialUserGroupFetchOptions($fetchOptions, $selectFields, $joinTables, $orderBy);

        return array(
            'selectFields' => $selectFields,
            'joinTables' => $joinTables,
            'orderClause' => ($orderBy ? "ORDER BY $orderBy" : '')
        );
    } /* END prepareSocialUserGroupFetchOptions */

    /**
     * Method designed to be overridden by child classes to add to SQL snippets.
     *
     * @param array $fetchOptions containing a 'join' integer key built from
     * this class's FETCH_x bitfields.
     * @param string $selectFields = ', user.*, foo.title'
     * @param string $joinTables = ' INNER JOIN foo ON (foo.id = other.id) '
     * @param string $orderBy = 'x.y ASC, x.z DESC'
     */
    protected function _prepareSocialUserGroupFetchOptions(array &$fetchOptions, &$selectFields, &$joinTables, &$orderBy)
    {
    } /* END _prepareSocialUserGroupFetchOptions */
}