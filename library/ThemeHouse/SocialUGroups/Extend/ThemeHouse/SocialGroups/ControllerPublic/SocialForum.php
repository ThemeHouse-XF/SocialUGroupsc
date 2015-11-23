<?php

/**
 *
 * @see ThemeHouse_SocialGroups_ControllerPublic_SocialForum
 */
class ThemeHouse_SocialUGroups_Extend_ThemeHouse_SocialGroups_ControllerPublic_SocialForum extends XFCP_ThemeHouse_SocialUGroups_Extend_ThemeHouse_SocialGroups_ControllerPublic_SocialForum
{

    protected function _preDispatch($action)
    {
        parent::_preDispatch($action);

        $socialForum = ThemeHouse_SocialGroups_SocialForum::getInstance();
        if ($socialForum) {
            $socialForumMember = $socialForum->getMember();
            if (isset($socialForumMember['style_id']) && $socialForumMember['style_id']) {
                $this->setViewStateChange('styleId', $socialForumMember['style_id']);
            }
        }
    } /* END _preDispatch */

    /**
     *
     * @see XenForo_ControllerPublic_Forum::actionIndex()
     */
    public function actionIndex()
    {
        $response = parent::actionIndex();

        return $this->_getSocialGroupsResponse($response);
    } /* END actionIndex */

    /**
     *
     * @see XenForo_ControllerPublic_Forum::actionForum()
     */
    public function actionForum()
    {
        $response = parent::actionForum();

        return $this->_getSocialGroupsResponse($response);
    } /* END actionForum */

    /**
     *
     * @param XenForo_ControllerResponse_Abstract $response
     * @return XenForo_ControllerResponse_Abstract
     */
    protected function _getSocialGroupsResponse(XenForo_ControllerResponse_Abstract $response)
    {
        if ($this->_routeMatch->getResponseType() == 'rss') {
            return $response;
        }

        if ($response instanceof XenForo_ControllerResponse_View) {
            $socialForum = ThemeHouse_SocialGroups_SocialForum::getInstance()->toArray();

            $viewParams = array(
                'canManageSocialUserGroups' => $this->_getForumModel()->canManageSocialUserGroups($socialForum)
            );
            if ($response->subView) {
                $response->subView->params = array_merge($response->subView->params, $viewParams);
            }
            $response->params = array_merge($response->params, $viewParams);
        }

        return $response;
    } /* END _getSocialGroupsResponse */ /* END _getSocialForumResponse */

    /**
     *
     * @see ThemeHouse_SocialGroups_ControllerPublic_SocialForum::_getCanEditInline()
     */
    protected function _getCanEditInline(array $socialForum)
    {
        if ($this->_getForumModel()->canManageSocialUserGroups($socialForum)) {
            return true;
        }
        return parent::_getCanEditInline($socialForum);
    } /* END _getCanEditInline */

    public function actionMemberSave()
    {
        $GLOBALS['ThemeHouse_SocialGroups_ControllerPublic_SocialForum'] = $this;

        return parent::actionMemberSave();
    } /* END actionMemberSave */

    public function actionMemberListItemEdit()
    {
        $response = parent::actionMemberListItemEdit();

        if ($response instanceof XenForo_ControllerResponse_View) {
            $socialUserGroupModel = $this->_getSocialUserGroupModel();

            $response->params['socialUserGroups'] = $socialUserGroupModel->getAllUserGroupsInSocialForum($response->params['socialForum']['social_forum_id']);
        }

        return $response;
    } /* END actionMemberListItemEdit */

    public function actionUserGroups()
    {
        if (ThemeHouse_SocialGroups_SocialForum::hasInstance()) {
            $socialForum = ThemeHouse_SocialGroups_SocialForum::getInstance()->toArray();
        } else {
            return $this->responseError('th_requested_social_forum_not_found_socialgroups');
        }

        $this->_assertCanManageSocialUserGroups($socialForum);

        $socialUserGroupModel = XenForo_Model::create('ThemeHouse_SocialUGroups_Model_SocialUserGroup');

        $socialUserGroups = $socialUserGroupModel->getAllUserGroupsInSocialForum($socialForum['social_forum_id']);

        $viewParams = array(
            'socialUserGroups' => $socialUserGroups,
            'socialForum' => $socialForum
        );

        $subView = $this->responseView('ThemeHouse_SocialUGroups_ViewPublic_SocialUserGroup_List',
            'th_social_user_groups_socialusergroups', $viewParams);
        return $this->_getWrapper($subView);
    } /* END actionUserGroups */

    /**
     * Helper to get the social user group add/edit form controller response.
     *
     * @param array $socialUserGroup
     *
     * @return XenForo_ControllerResponse_View
     */
    protected function _getSocialUserGroupAddEditResponse(array $socialUserGroup)
    {
        if (ThemeHouse_SocialGroups_SocialForum::hasInstance()) {
            $socialForum = ThemeHouse_SocialGroups_SocialForum::getInstance()->toArray();
        } else {
            return $this->responseError('th_requested_social_forum_not_found_socialgroups');
        }

        $this->_assertCanManageSocialUserGroups($socialForum);

        $viewParams = array(
            'socialUserGroup' => $socialUserGroup,
            'socialForum' => $socialForum,
            'styles' => $this->getModelFromCache('XenForo_Model_Style')->getAllStylesAsFlattenedTree()
        );

        $subView = $this->responseView('ThemeHouse_SocialUGroups_ViewPublic_SocialUserGroup_Edit',
            'th_social_user_group_edit_socialusergroups', $viewParams);
        return $this->_getWrapper($subView);
    } /* END _getSocialUserGroupAddEditResponse */

    /**
     * Displays a form to add a new social user group.
     *
     * @return XenForo_ControllerResponse_View
     */
    public function actionUserGroupsAdd()
    {
        return $this->_getSocialUserGroupAddEditResponse(
            $this->_getSocialUserGroupModel()
                ->getDefaultSocialUserGroup());
    } /* END actionUserGroupsAdd */

    /**
     * Displays a form to edit an existing social user group.
     *
     * @return XenForo_ControllerResponse_Abstract
     */
    public function actionUserGroupsEdit()
    {
        if (ThemeHouse_SocialGroups_SocialForum::hasInstance()) {
            $socialForum = ThemeHouse_SocialGroups_SocialForum::getInstance()->toArray();
        } else {
            return $this->responseError('th_requested_social_forum_not_found_socialgroups');
        }

        $socialUserGroupId = $this->_input->filterSingle('social_user_group_id', XenForo_Input::STRING);
        $socialUserGroup = $this->_getSocialUserGroupOrError($socialUserGroupId, $socialForum['social_forum_id']);

        if ($socialForum['social_forum_id'] != $socialUserGroup['social_forum_id']) {
            return $this->responseNoPermission();
        }

        return $this->_getSocialUserGroupAddEditResponse($socialUserGroup);
    } /* END actionUserGroupsEdit */

    /**
     * Inserts a new social user group or updates an existing one.
     *
     * @return XenForo_ControllerResponse_Abstract
     */
    public function actionUserGroupsSave()
    {
        $this->_assertPostOnly();

        if (ThemeHouse_SocialGroups_SocialForum::hasInstance()) {
            $socialForum = ThemeHouse_SocialGroups_SocialForum::getInstance()->toArray();
        } else {
            return $this->responseError('th_requested_social_forum_not_found_socialgroups');
        }

        $this->_assertCanManageSocialUserGroups($socialForum);

        $socialUserGroupId = $this->_input->filterSingle('social_user_group_id', XenForo_Input::STRING);

        $input = $this->_input->filter(
            array(
                'title' => XenForo_Input::STRING,
                'style_id' => XenForo_Input::UINT
            ));

        if (!$this->_input->filterSingle('style_override', XenForo_Input::UINT)) {
            $input['style_id'] = 0;
        }

        $writer = XenForo_DataWriter::create('ThemeHouse_SocialUGroups_DataWriter_SocialUserGroup');
        if ($socialUserGroupId) {
            $socialUserGroup = $this->_getSocialUserGroupOrError($socialUserGroupId, $socialForum['social_forum_id']);
            $writer->setExistingData($socialUserGroup);
            if ($socialForum['social_forum_id'] != $writer->get('social_forum_id')) {
                return $this->responseNoPermission();
            }
        } else {
            $writer->set('social_forum_id', $socialForum['social_forum_id']);
        }
        $writer->bulkSet($input);
        $writer->save();

        $socialUserGroup = $writer->getMergedData();

        return $this->responseRedirect(XenForo_ControllerResponse_Redirect::SUCCESS,
            XenForo_Link::buildPublicLink('social-forums/user-groups', $socialForum));
    } /* END actionUserGroupsSave */

    /**
     * Deletes a social user group.
     *
     * @return XenForo_ControllerResponse_Abstract
     */
    public function actionUserGroupsDelete()
    {
        if (ThemeHouse_SocialGroups_SocialForum::hasInstance()) {
            $socialForum = ThemeHouse_SocialGroups_SocialForum::getInstance()->toArray();
        } else {
            return $this->responseError('th_requested_social_forum_not_found_socialgroups');
        }

        $socialUserGroupId = $this->_input->filterSingle('social_user_group_id', XenForo_Input::STRING);
        $socialUserGroup = $this->_getSocialUserGroupOrError($socialUserGroupId, $socialForum['social_forum_id']);

        $this->_assertCanManageSocialUserGroups($socialForum);

        $writer = XenForo_DataWriter::create('ThemeHouse_SocialUGroups_DataWriter_SocialUserGroup');
        $writer->setExistingData($socialUserGroup);

        if ($this->isConfirmedPost()) { // delete social user group
            $writer->delete();

            return $this->responseRedirect(XenForo_ControllerResponse_Redirect::SUCCESS,
                XenForo_Link::buildPublicLink('social-forums/user-groups'));
        } else { // show delete confirmation prompt
            $writer->preDelete();
            $errors = $writer->getErrors();
            if ($errors) {
                return $this->responseError($errors);
            }

            $viewParams = array(
                'socialUserGroup' => $socialUserGroup
            );

            $subView = $this->responseView('ThemeHouse_SocialUGroups_ViewPublic_SocialUserGroup_Delete',
                'th_social_user_group_delete_socialusergroups', $viewParams);
            return $this->_getWrapper($subView);
        }
    } /* END actionUserGroupsDelete */

    /**
     *
     * @see ThemeHouse_SocialPerms_Extend_ThemeHouse_SocialGroups_ControllerPublic_SocialForum::actionPermissions()
     */
    public function actionPermissions()
    {
        $response = parent::actionPermissions();

        if ($response instanceof XenForo_ControllerResponse_View) {
            $response = $this->_getSocialGroupsPermissionsResponse($response);
        }

        return $response;
    }

    protected function _getSocialGroupsPermissionsResponse(XenForo_ControllerResponse_View $response)
    {
        $socialUserGroupModel = $this->_getSocialUserGroupModel();

        $socialForumId = $response->params['socialForum']['social_forum_id'];

        $socialUserGroups = $socialUserGroupModel->getAllUserGroupsInSocialForum($socialForumId);
        if ($response->subView) {
            $response->subView->params['socialUserGroups'] = $socialUserGroups;
        } else {
            $response->params['socialUserGroups'] = $socialUserGroups;
        }

        return $response;
    } /* END _getSocialGroupsPermissionsResponse */

    /**
     *
     * @see ThemeHouse_SocialPerms_Extend_ThemeHouse_SocialGroups_ControllerPublic_SocialForum::actionPermissionsGuest()
     */
    public function actionPermissionsGuest()
    {
        $response = parent::actionPermissionsGuest();

        if ($response instanceof XenForo_ControllerResponse_Reroute) {
            $response->controllerName = __CLASS__;
        }

        return $response;
    } /* END actionPermissionsGuest */

    /**
     *
     * @see ThemeHouse_SocialPerms_Extend_ThemeHouse_SocialGroups_ControllerPublic_SocialForum::actionPermissionsMember()
     */
    public function actionPermissionsMember()
    {
        $response = parent::actionPermissionsMember();

        if ($response instanceof XenForo_ControllerResponse_Reroute) {
            $response->controllerName = __CLASS__;
        }

        return $response;
    } /* END actionPermissionsMember */

    /**
     *
     * @see ThemeHouse_SocialPerms_Extend_ThemeHouse_SocialGroups_ControllerPublic_SocialForum::actionPermissionsModerator()
     */
    public function actionPermissionsModerator()
    {
        $response = parent::actionPermissionsModerator();

        if ($response instanceof XenForo_ControllerResponse_Reroute) {
            $response->controllerName = __CLASS__;
        }

        return $response;
    } /* END actionPermissionsModerator */

    public function actionPermissionsUserGroup()
    {
        if (ThemeHouse_SocialGroups_SocialForum::hasInstance()) {
            $socialForum = ThemeHouse_SocialGroups_SocialForum::getInstance()->toArray();
        } else {
            return $this->responseError('th_requested_social_forum_not_found_socialgroups');
        }

        // TODO: return error if social permissions add-on is disabled or not installed


        $this->_assertCanManageSocialPermissions($socialForum);
        $this->_assertCanManageSocialUserGroups($socialForum);

        $socialUserGroupId = $this->_input->filterSingle('social_user_group_id', XenForo_Input::UINT);

        $socialUserGroup = $this->_getSocialUserGroupOrError($socialUserGroupId, $socialForum['social_forum_id']);

        $selectedUserGroup = $socialUserGroup['title'];

        $permissionModel = XenForo_Model::create('XenForo_Model_Permission');

        $preparedOption = $permissionModel->getSocialGroupsPreparedOption(array());

        $selectedPermissions = array();
        if ($socialForum['user_group_permissions']) {
            $userGroupPermissions = unserialize($socialForum['user_group_permissions']);
            if (isset($userGroupPermissions[$socialUserGroupId])) {
                $selectedPermissions = $userGroupPermissions[$socialUserGroupId];
            }
        }

        $permissions = array();
        foreach ($preparedOption['permissions'] as $permissionId => $permissionName) {
            if (isset(XenForo_Application::get('options')->th_socialGroups_permissions[3][$permissionId])) {
                $permissions[$permissionId] = array(
                    'value' => $permissionId,
                    'label' => $permissionName,
                    'checked' => (array_key_exists($permissionId, $selectedPermissions))
                );
            }
        }

        $viewParams = array(
            'socialForum' => $socialForum,
            'permissions' => $permissions,
            'selectedUserGroup' => $selectedUserGroup,
            'socialUserGroup' => 'user-group',
            'socialUserGroupId' => $socialUserGroupId,
        );

        $subView = $this->responseView('ThemeHouse_SocialPerms_ViewPublic_SocialForum_Permissions',
            'th_social_permissions_socialperms', $viewParams);
        return $this->_getWrapper($subView);
    } /* END actionPermissionsUserGroup */ /* END actionPermissionsUserGroup */ /* END actionPermissionsUserGroup */ /* END actionPermissionsUserGroup */ /* END actionPermissionsUserGroup */

    public function actionPermissionsUserGroupSave()
    {
        $this->_assertPostOnly();

        if (ThemeHouse_SocialGroups_SocialForum::hasInstance()) {
            $socialForum = ThemeHouse_SocialGroups_SocialForum::getInstance()->toArray();
        } else {
            return $this->responseError('th_requested_social_forum_not_found_socialgroups');
        }

        // TODO: return error if social permissions add-on is disabled or not installed


        $this->_assertCanManageSocialPermissions($socialForum);
        $this->_assertCanManageSocialUserGroups($socialForum);

        $data = $this->_input->filter(
            array(
                'permissions' => XenForo_Input::ARRAY_SIMPLE,
                'social_user_group_id' => XenForo_Input::UINT
            ));

        foreach ($data['permissions'] as $permissionId => $permissionSet) {
            if (!isset(XenForo_Application::get('options')->th_socialGroups_permissions[3][$permissionId])) {
                unset($data['permissions'][$permissionId]);
            }
        }

        $dw = XenForo_DataWriter::create('ThemeHouse_SocialGroups_DataWriter_SocialForum');
        $dw->setExistingData($socialForum);
        $dw->setSocialUserGroupPermissions($data['social_user_group_id'], $data['permissions']);
        $dw->save();

        return $this->responseRedirect(XenForo_ControllerResponse_Redirect::SUCCESS,
            XenForo_Link::buildPublicLink('social-forums/permissions/user-groups', $socialForum,
                array(
                    'social_user_group_id' => $data['social_user_group_id']
                )));
    } /* END actionPermissionsUserGroupSave */

    /**
     * Asserts that the currently browsing user can manage social user groups.
     */
    protected function _assertCanManageSocialUserGroups(array $socialForum)
    {
        if (!$this->_getForumModel()->canManageSocialUserGroups($socialForum, $errorPhraseKey)) {
            throw $this->getNoPermissionResponseException();
        }
    } /* END _assertCanManageSocialUserGroups */

    /**
     * Gets a valid social user group or throws an exception.
     *
     * @param string $socialUserGroupId
     *
     * @return array
     */
    protected function _getSocialUserGroupOrError($socialUserGroupId, $socialForumId)
    {
        $socialUserGroup = $this->_getSocialUserGroupModel()->getSocialUserGroupById($socialUserGroupId, $socialForumId);
        if (!$socialUserGroup) {
            throw $this->responseException(
                $this->responseError(
                    new XenForo_Phrase('th_requested_social_user_group_not_found_socialusergroups'), 404));
        }

        return $socialUserGroup;
    } /* END _getSocialUserGroupOrError */

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