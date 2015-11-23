<?php

class ThemeHouse_SocialUGroups_Listener_TemplateHook extends ThemeHouse_Listener_TemplateHook
{
    protected function _getHooks()
    {
        return array(
            'th_social_forum_tools_socialgroups',
            'th_social_user_group_popup_socialperms',
        );
    } /* END _getHooks */

    public static function templateHook($hookName, &$contents, array $hookParams, XenForo_Template_Abstract $template)
    {
        $templateHook = new ThemeHouse_SocialUGroups_Listener_TemplateHook($hookName, $contents, $hookParams, $template);
        $contents = $templateHook->run();
    } /* END templateHook */

	protected function _thSocialForumToolsSocialGroups()
	{
		$this->_appendTemplate('th_social_forum_tools_socialusergroups');
	} /* END _thSocialForumToolsSocialGroups */

	protected function _thSocialUserGroupPopupSocialperms()
	{
	    $this->_appendTemplate('th_social_user_group_popup_socialusergroups');
	} /* END _thSocialUserGroupPopupSocialperms */
}