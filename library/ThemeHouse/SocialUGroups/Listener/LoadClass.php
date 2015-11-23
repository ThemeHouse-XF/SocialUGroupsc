<?php

class ThemeHouse_SocialUGroups_Listener_LoadClass extends ThemeHouse_Listener_LoadClass
{
    /**
     * Gets the classes that are extended for this add-on. See parent for explanation.
     *
     * @return array
     */
    protected function _getExtendedClasses()
    {
        return array(
            'ThemeHouse_SocialUGroups' => array(
                'controller' => array(
                    'ThemeHouse_SocialGroups_ControllerPublic_SocialForum',
                    'XenForo_ControllerPublic_Thread',
                ), /* END 'controller' */
                'datawriter' => array(
                    'ThemeHouse_SocialGroups_DataWriter_SocialForum',
                    'ThemeHouse_SocialGroups_DataWriter_SocialForumMember',
                ), /* END 'datawriter' */
                'model' => array(
                    'ThemeHouse_SocialGroups_Model_SocialForum',
                    'ThemeHouse_SocialGroups_Model_SocialForumMember',
                ), /* END 'model' */
            ), /* END 'ThemeHouse_SocialUGroups' */
        );
    } /* END _getExtendedClasses */

    public static function loadClassController($class, array &$extend)
    {
        $loadClassController = new ThemeHouse_SocialUGroups_Listener_LoadClass($class, $extend, 'controller');
        $extend = $loadClassController->run();
    } /* END loadClassController */

    public static function loadClassDataWriter($class, array &$extend)
    {
        $loadClassDataWriter = new ThemeHouse_SocialUGroups_Listener_LoadClass($class, $extend, 'datawriter');
        $extend = $loadClassDataWriter->run();
    } /* END loadClassDataWriter */

    public static function loadClassModel($class, array &$extend)
    {
        $loadClassModel = new ThemeHouse_SocialUGroups_Listener_LoadClass($class, $extend, 'model');
        $extend = $loadClassModel->run();
    } /* END loadClassModel */
}