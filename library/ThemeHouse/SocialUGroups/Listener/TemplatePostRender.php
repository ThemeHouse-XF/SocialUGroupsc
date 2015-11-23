<?php

class ThemeHouse_SocialUGroups_Listener_TemplatePostRender extends ThemeHouse_Listener_TemplatePostRender
{
    protected function _getTemplates()
    {
        return array(
            'th_member_list_item_edit_socialgroups',
            'th_social_permissions_socialperms',
        );
    } /* END ThemeHouse_SocialUGroups_Listener_TemplatePostRender::_getTemplates() */

    public static function templatePostRender($templateName, &$content, array &$containerData, XenForo_Template_Abstract $template)
    {
        $templatePostRender = new ThemeHouse_SocialUGroups_Listener_TemplatePostRender($templateName, $content, $containerData, $template);
        list($content, $containerData) = $templatePostRender->run();
    } /* END templatePostRender */

    protected function _thMemberListItemEditSocialgroups()
    {
        $codeSnippet = '<div class="memberListItemEdit inlineCtrlGroup">';
        $this->_appendTemplateAtCodeSnippet($codeSnippet, 'th_member_edit_user_group_socialusergroups');
    } /* END _thMemberListItemEditSocialgroups */ /* END ThemeHouse_SocialUGroups_Listener_TemplatePostRender::_thMemberListEditSocialgroups */

    protected function _thSocialPermissionsSocialperms()
    {
        $codeSnippet = '<table class="dataTable socialPermissionsTable">';
        $this->_appendTemplateBeforeCodeSnippet($codeSnippet, 'th_social_permissions_socialusergroups');
    } /* END _thSocialPermissionsSocialperms */
}