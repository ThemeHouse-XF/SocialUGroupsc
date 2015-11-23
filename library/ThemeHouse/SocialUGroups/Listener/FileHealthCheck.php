<?php

class ThemeHouse_SocialUGroups_Listener_FileHealthCheck
{

    public static function fileHealthCheck(XenForo_ControllerAdmin_Abstract $controller, array &$hashes)
    {
        $hashes = array_merge($hashes,
            array(
                'library/ThemeHouse/SocialUGroups/DataWriter/SocialUserGroup.php' => '3156d33126be4b7fe36d0615f6019d97',
                'library/ThemeHouse/SocialUGroups/Extend/ThemeHouse/SocialGroups/ControllerPublic/SocialForum.php' => '77f5ce21487a233fab4390dafaa09b34',
                'library/ThemeHouse/SocialUGroups/Extend/ThemeHouse/SocialGroups/DataWriter/SocialForum.php' => 'f95ec6e5d9157f953c4a55b910aa8c24',
                'library/ThemeHouse/SocialUGroups/Extend/ThemeHouse/SocialGroups/DataWriter/SocialForumMember.php' => '1a045d337bf2b60f9945e521771b127e',
                'library/ThemeHouse/SocialUGroups/Extend/ThemeHouse/SocialGroups/Model/SocialForum.php' => '913faa333211d49e48434904ec8a672a',
                'library/ThemeHouse/SocialUGroups/Extend/ThemeHouse/SocialGroups/Model/SocialForumMember.php' => 'c293d6cf663dfe78ab70297a3a5aa253',
                'library/ThemeHouse/SocialUGroups/Extend/XenForo/ControllerPublic/Thread.php' => '6ebce4d741a62b8559ad3553e37cf0eb',
                'library/ThemeHouse/SocialUGroups/Install/Controller.php' => 'dc26a080cfa0e4d1c968fcde5106bc89',
                'library/ThemeHouse/SocialUGroups/Listener/LoadClass.php' => '5d0bc11294a54d862d9034471596e834',
                'library/ThemeHouse/SocialUGroups/Listener/TemplateCreate.php' => '731204f1c8c810ed90d7fc16ffd06f1a',
                'library/ThemeHouse/SocialUGroups/Listener/TemplateHook.php' => 'b340d91572a693cfdd8cefbd8ed7ef91',
                'library/ThemeHouse/SocialUGroups/Listener/TemplatePostRender.php' => '0fc40b58721cb3ec85bffd7b961fc44d',
                'library/ThemeHouse/SocialUGroups/Model/SocialUserGroup.php' => 'e94b3e9f9d3c3c7634e978bbeb4411c1',
                'library/ThemeHouse/SocialUGroups/Route/Prefix/SocialUserGroups.php' => '6cd72c823ec3b6abd33ca046884cbd65',
                'library/ThemeHouse/Install.php' => '18f1441e00e3742460174ab197bec0b7',
                'library/ThemeHouse/Install/20151109.php' => '2e3f16d685652ea2fa82ba11b69204f4',
                'library/ThemeHouse/Deferred.php' => 'ebab3e432fe2f42520de0e36f7f45d88',
                'library/ThemeHouse/Deferred/20150106.php' => 'a311d9aa6f9a0412eeba878417ba7ede',
                'library/ThemeHouse/Listener/ControllerPreDispatch.php' => 'fdebb2d5347398d3974a6f27eb11a3cd',
                'library/ThemeHouse/Listener/ControllerPreDispatch/20150911.php' => 'f2aadc0bd188ad127e363f417b4d23a9',
                'library/ThemeHouse/Listener/InitDependencies.php' => '8f59aaa8ffe56231c4aa47cf2c65f2b0',
                'library/ThemeHouse/Listener/InitDependencies/20150212.php' => 'f04c9dc8fa289895c06c1bcba5d27293',
                'library/ThemeHouse/Listener/LoadClass.php' => '5cad77e1862641ddc2dd693b1aa68a50',
                'library/ThemeHouse/Listener/LoadClass/20150518.php' => 'f4d0d30ba5e5dc51cda07141c39939e3',
                'library/ThemeHouse/Listener/Template.php' => '0aa5e8aabb255d39cf01d671f9df0091',
                'library/ThemeHouse/Listener/Template/20150106.php' => '8d42b3b2d856af9e33b69a2ce1034442',
                'library/ThemeHouse/Listener/TemplateCreate.php' => '6bdeb679af2ea41579efde3e41e65cc7',
                'library/ThemeHouse/Listener/TemplateCreate/20150106.php' => 'c253a7a2d3a893525acf6070e9afe0dd',
                'library/ThemeHouse/Listener/TemplateHook.php' => 'a767a03baad0ca958d19577200262d50',
                'library/ThemeHouse/Listener/TemplateHook/20150106.php' => '71c539920a651eef3106e19504048756',
                'library/ThemeHouse/Listener/TemplatePostRender.php' => 'b6da98a55074e4cde833abf576bc7b5d',
                'library/ThemeHouse/Listener/TemplatePostRender/20150106.php' => 'efccbb2b2340656d1776af01c25d9382',
            ));
    }
}