<?php

use EvolutionCMS\Models\SiteTmplvarAccess;
use EvolutionCMS\Models\SiteTmplvarTemplate;

if(!function_exists('duplicateDocument')) {
    /**
     * @param int $docid
     * @param null|int $parent
     * @param int $_toplevel
     * @return int
     */
    function duplicateDocument($docid, $parent = null, $_toplevel = 0)
    {
        $modx = evolutionCMS();
        global $_lang;

        // invoke OnBeforeDocDuplicate event
        $evtOut = $modx->invokeEvent('OnBeforeDocDuplicate', array(
            'id' => $docid
        ));

        // if( !in_array( 'false', array_values( $evtOut ) ) ){}
        // TODO: Determine necessary handling for duplicateDocument "return $newparent" if OnBeforeDocDuplicate were able to conditially control duplication
        // [DISABLED]: Proceed with duplicateDocument if OnBeforeDocDuplicate did not return false via: $event->output('false');

        $userID = $modx->getLoginUserID();

        // Grab the original document
        $rs = $modx->getDatabase()->select(
            '*'
            , $modx->getDatabase()->getFullTableName('site_content')
            , sprintf('id=%d', (int)$docid)
        );
        $content = $modx->getDatabase()->getRow($rs);

        // Handle incremental ID
        switch ($modx->getConfig('docid_incrmnt_method')) {
            case '1':
                $rs = $modx->getDatabase()->select(
                    'MIN(T0.id)+1'
                    , sprintf(
                        '%s AS T0 LEFT JOIN %s AS T1 ON T0.id + 1 = T1.id'
                        , $modx->getDatabase()->getFullTableName('site_content')
                        , $modx->getDatabase()->getFullTableName('site_content')
                    )
                    , 'T1.id IS NULL'
                );
                $content['id'] = $modx->getDatabase()->getValue($rs);
                break;
            case '2':
                $rs = $modx->getDatabase()->select(
                    'MAX(id)+1'
                    , $modx->getDatabase()->getFullTableName('site_content')
                );
                $content['id'] = $modx->getDatabase()->getValue($rs);
                break;

            default:
                unset($content['id']); // remove the current id.
        }

        // Once we've grabbed the document object, start doing some modifications
        if ($_toplevel == 0) {
            // count duplicates
            $pagetitle = $modx->getDatabase()->getValue(
                $modx->getDatabase()->select(
                    'pagetitle'
                    , $modx->getDatabase()->getFullTableName('site_content')
                    , sprintf('id=%d', (int)$docid)
                )
            );
            $pagetitle = $modx->getDatabase()->escape($pagetitle);
            $count = $modx->getDatabase()->getRecordCount(
                $modx->getDatabase()->select(
                    'pagetitle'
                    , $modx->getDatabase()->getFullTableName('site_content')
                    , sprintf("pagetitle LIKE '%s Duplicate%%'", $pagetitle)
                )
            );
            if ($count >= 1) {
                $count = ' ' . ($count + 1);
            } else {
                $count = '';
            }

            $content['pagetitle'] = sprintf(
                '%s%s %s'
                , $_lang['duplicated_el_suffix']
                , $count, $content['pagetitle']
            );
            $content['alias'] = null;
        } elseif ($modx->getConfig('friendly_urls') == 0 || $modx->getConfig('allow_duplicate_alias') == 0) {
            $content['alias'] = null;
        }

        // change the parent accordingly
        if ($parent !== null) {
            $content['parent'] = $parent;
        }

        // Change the author
        $content['createdby'] = $userID;
        $content['createdon'] = time();
        // Remove other modification times
        $content['editedby'] = $content['editedon'] = $content['deleted'] = $content['deletedby'] = $content['deletedon'] = 0;

        // Set the published status to unpublished by default (see above ... commit #3388)
        $content['published'] = $content['pub_date'] = 0;

        // Escape the proper strings
        $content = $modx->getDatabase()->escape($content);

        // Duplicate the Document
        $newparent = $modx->getDatabase()->insert(
            $content
            , $modx->getDatabase()->getFullTableName('site_content')
        );

        // duplicate document's TVs
        duplicateTVs($docid, $newparent);
        duplicateAccess($docid, $newparent);

        // invoke OnDocDuplicate event
        $evtOut = $modx->invokeEvent('OnDocDuplicate', array(
            'id'     => $docid,
            'new_id' => $newparent
        ));

        // Start duplicating all the child documents that aren't deleted.
        $_toplevel++;
        $rs = $modx->getDatabase()->select(
            'id'
            , $modx->getDatabase()->getFullTableName('site_content')
            , sprintf('parent=%d AND deleted=0', (int)$docid)
            , 'id ASC'
        );
        while ($row = $modx->getDatabase()->getRow($rs)) {
            duplicateDocument($row['id'], $newparent, $_toplevel);
        }

        // return the new doc id
        return $newparent;
    }
}

if(!function_exists('duplicateTVs')) {
    /**
     * Duplicate Document TVs
     *
     * @param int $oldid
     * @param int $newid
     */
    function duplicateTVs($oldid, $newid)
    {
        $modx = evolutionCMS();

        $modx->getDatabase()->insert(
            array(
                'contentid' => '',
                'tmplvarid' => '',
                'value' => ''
            )
            , $modx->getDatabase()->getFullTableName('site_tmplvar_contentvalues')
            , sprintf('%d, tmplvarid, value', (int)$newid)
            , $modx->getDatabase()->getFullTableName('site_tmplvar_contentvalues')
            , sprintf('contentid=%d', (int)$oldid) // Copy from
        );
    }
}

if(!function_exists('duplicateAccess')) {
    /**
     * Duplicate Document Access Permissions
     *
     * @param int $oldid
     * @param int $newid
     */
    function duplicateAccess($oldid, $newid)
    {
        $modx = evolutionCMS();

        $modx->getDatabase()->insert(
            array(
                'document'       => '',
                'document_group' => ''
            )
            , $modx->getDatabase()->getFullTableName('document_groups')
            , sprintf('%d, document_group', (int)$newid)
            , $modx->getDatabase()->getFullTableName('document_groups')
            , sprintf('document=%d', (int)$oldid) // Copy from
        );
    }
}

if(!function_exists('evalModule')) {
    /**
     * evalModule
     *
     * @param string $moduleCode
     * @param array $params
     * @return string
     */
    function evalModule($moduleCode, $params)
    {
        $modx = evolutionCMS();
        $modx->event->params = &$params; // store params inside event object
        if (is_array($params)) {
            extract($params, EXTR_SKIP);
        }
        ob_start();
        $mod = eval($moduleCode);
        $msg = ob_get_contents();
        ob_end_clean();
        if (isset($php_errormsg)) {
            $error_info = error_get_last();
            switch ($error_info['type']) {
                case E_NOTICE :
                    $error_level = 1;
                case E_USER_NOTICE :
                    break;
                case E_DEPRECATED :
                case E_USER_DEPRECATED :
                case E_STRICT :
                    $error_level = 2;
                    break;
                default:
                    $error_level = 99;
            }
            if ($modx->getConfig('error_reporting') >= 99 || 2 < $error_level) {
                $modx->messageQuit(
                    'PHP Parse Error'
                    , ''
                    , true
                    , $error_info['type']
                    , $error_info['file']
                    , sprintf('%s - Module', $_SESSION['itemname'])
                    , $error_info['message']
                    , $error_info['line']
                    , $msg
                );
                $modx->event->alert(
                    sprintf(
                        'An error occurred while loading. Please see the event log for more information<p>%s</p>'
                        , $msg
                    )
                );
            }
        }
        unset($modx->event->params);

        return $mod . $msg;
    }
}

if(!function_exists('allChildren')) {
    /**
     * @param int $currDocID
     * @return array
     */
    function allChildren($currDocID)
    {
        $children = array();
        $found = collect();

        $docs = EvolutionCMS\Models\SiteContent::withTrashed()
            ->where('parent', '=', $currDocID)
            ->pluck('id')
            ->toArray();

        foreach ($docs as $id) {
            $children[] = $id;
            $found->push(allChildren($id));
        }

        return array_merge($children, $found->collapse()->toArray());
    }
}

if(!function_exists('jsAlert')) {
    /**
     * show javascript alert
     *
     * @param string $msg
     */
    function jsAlert($msg)
    {
        $modx = evolutionCMS();
        if ((int)get_by_key($_POST, 'ajax', 0) !== 1) {
            echo sprintf(
                '<script>window.setTimeout("alert(\'%s\')",10);history.go(-1)</script>'
                , addslashes($modx->getDatabase()->escape($msg)
                )
            );
        } else {
            echo $msg . "\n";
        }
    }
}

if(!function_exists('login')) {
    /**
     * @param string $username
     * @param string $givenPassword
     * @param string $dbasePassword
     * @return bool
     */
    function login($username, $givenPassword, $dbasePassword)
    {
        $modx = evolutionCMS();

        return $modx->getPasswordHash()->CheckPassword($givenPassword, $dbasePassword);
    }
}

if(!function_exists('loginV1')) {
    /**
     * @param int $internalKey
     * @param string $givenPassword
     * @param string $dbasePassword
     * @param string $username
     * @return bool
     */
    function loginV1($internalKey, $givenPassword, $dbasePassword, $username)
    {
        $modx = evolutionCMS();

        $user_algo = $modx->getManagerApi()->getV1UserHashAlgorithm($internalKey);

        if (!isset($modx->config['pwd_hash_algo']) || empty($modx->config['pwd_hash_algo'])) {
            $modx->setConfig('pwd_hash_algo', 'UNCRYPT');
        }

        if ($user_algo !== $modx->getConfig('pwd_hash_algo')) {
            $bk_pwd_hash_algo = $modx->getConfig('pwd_hash_algo');
            $modx->setConfig('pwd_hash_algo', $user_algo);
        }

        if ($dbasePassword != $modx->getManagerApi()->genV1Hash($givenPassword, $internalKey)) {
            return false;
        }

        updateNewHash($username, $givenPassword);

        return true;
    }
}

if(!function_exists('loginMD5')) {
    /**
     * @param int $internalKey
     * @param string $givenPassword
     * @param string $dbasePassword
     * @param string $username
     * @return bool
     */
    function loginMD5($internalKey, $givenPassword, $dbasePassword, $username)
    {
        $modx = evolutionCMS();

        if ($dbasePassword != md5($givenPassword)) {
            return false;
        }
        updateNewHash($username, $givenPassword);

        return true;
    }
}

if(!function_exists('updateNewHash')) {
    /**
     * @param string $username
     * @param string $password
     */
    function updateNewHash($username, $password)
    {
        $modx = evolutionCMS();

        $field = array();
        $field['password'] = $modx->getPasswordHash()->HashPassword($password);
        $modx->getDatabase()->update(
            $field,
            $modx->getDatabase()->getFullTableName('manager_users'),
            sprintf("username='%s'", $username)
        );
    }
}

if(!function_exists('incrementFailedLoginCount')) {
    /**
     * @param int $internalKey
     * @param int $failedlogins
     * @param int $failed_allowed
     * @param int $blocked_minutes
     */
    function incrementFailedLoginCount($internalKey, $failedlogins, $failed_allowed, $blocked_minutes)
    {
        $modx = evolutionCMS();

        $failedlogins += 1;

        $fields = array('failedlogincount' => $failedlogins);
        if ($failedlogins >= $failed_allowed) //block user for too many fail attempts
        {
            $fields['blockeduntil'] = time() + ($blocked_minutes * 60);
        }

        $modx->getDatabase()->update(
            $fields,
            $modx->getDatabase()->getFullTableName('user_attributes'),
            sprintf("internalKey='%d'", (int)$internalKey)
        );

        if ($failedlogins < $failed_allowed) {
            //sleep to help prevent brute force attacks
            $sleep = (int)$failedlogins / 2;
            if ($sleep > 5) {
                $sleep = 5;
            }
            sleep($sleep);
        }
        @session_destroy();
        session_unset();

        return;
    }
}

if(!function_exists('saveUserGroupAccessPermissons')) {
    /**
     * saves module user group access
     */
    function saveUserGroupAccessPermissons()
    {
        $modx = evolutionCMS();
        global $id, $newid;
        global $use_udperms;

        if ($newid) {
            $id = $newid;
        }
        $usrgroups = $_POST['usrgroups'];

        // check for permission update access
        if ($use_udperms == 1) {
            // delete old permissions on the module
            $modx->getDatabase()->delete(
                $modx->getDatabase()->getFullTableName('site_module_access')
                , sprintf('module=%d', (int)$id)
            );
            if (is_array($usrgroups)) {
                foreach ($usrgroups as $value) {
                    $modx->getDatabase()->insert(
                        array(
                            'module'    => (int)$id,
                            'usergroup' => stripslashes($value),
                        )
                        , $modx->getDatabase()->getFullTableName('site_module_access'));
                }
            }
        }
    }
}

if(!function_exists('saveEventListeners')) {
# Save Plugin Event Listeners
    function saveEventListeners($id, $sysevents, $mode)
    {
        $modx = evolutionCMS();
        // save selected system events
        $formEventList = array();
        foreach ($sysevents as $evtId) {
            if (!preg_match('@^[1-9][0-9]*$@', $evtId)) {
                $evtId = getEventIdByName($evtId);
            }
            if ($mode == '101') {
                $rs = $modx->getDatabase()->select(
                    'max(priority) as priority',
                    $modx->getDatabase()->getFullTableName('site_plugin_events'),
                    sprintf("evtid='%d'", (int)$evtId)
                );
            } else {
                $rs = $modx->getDatabase()->select(
                    'priority',
                    $modx->getDatabase()->getFullTableName('site_plugin_events'),
                    sprintf("evtid='%d' and pluginid='%d'", (int)$evtId, (int)$id)
                );
            }
            $prevPriority = $modx->getDatabase()->getValue($rs);
            if ($mode == '101') {
                $priority = isset($prevPriority) ? $prevPriority + 1 : 1;
            } else {
                $priority = isset($prevPriority) ? $prevPriority : 1;
            }
            $priority = (int)$priority;
            $formEventList[] = array('pluginid' => $id, 'evtid' => $evtId, 'priority' => $priority);
        }

        $evtids = array();
        foreach ($formEventList as $eventInfo) {
            $where = vsprintf("pluginid='%s' AND evtid='%s'", $eventInfo);
            $modx->getDatabase()->save(
                $eventInfo,
                $modx->getDatabase()->getFullTableName('site_plugin_events'),
                $where
            );
            $evtids[] = $eventInfo['evtid'];
        }

        $rs = $modx->getDatabase()->select(
            '*',
            $modx->getDatabase()->getFullTableName('site_plugin_events'),
            sprintf("pluginid='%d'", (int)$id)
        );
        $dbEventList = array();
        $del = array();
        while ($row = $modx->getDatabase()->getRow($rs)) {
            if (!in_array($row['evtid'], $evtids)) {
                $del[] = $row['evtid'];
            }
        }

        if (empty($del)) {
            return;
        }

        foreach ($del as $delid) {
            $modx->getDatabase()->delete(
                $modx->getDatabase()->getFullTableName('site_plugin_events'),
                sprintf("evtid='%d' AND pluginid='%d'", (int)$delid, (int)$id)
            );
        }
    }
}

if(!function_exists('getEventIdByName')) {
    /**
     * @param string $name
     * @return string|int
     */
    function getEventIdByName($name)
    {
        $modx = evolutionCMS();
        static $eventIds = array();

        if (isset($eventIds[$name])) {
            return $eventIds[$name];
        }

        $rs = $modx->getDatabase()->select(
            'id, name',
            $modx->getDatabase()->getFullTableName('system_eventnames')
        );
        while ($row = $modx->getDatabase()->getRow($rs)) {
            $eventIds[$row['name']] = $row['id'];
        }

        return $eventIds[$name];
    }
}

if(!function_exists('saveTemplateAccess')) {
    /**
     * @param int $id
     */
    function saveTemplateAccess($id)
    {
        $modx = evolutionCMS();
        if ($_POST['tvsDirty'] == 1) {
            $newAssignedTvs = isset($_POST['assignedTv']) ? $_POST['assignedTv'] : '';

            // Preserve rankings of already assigned TVs
            $rs = $modx->getDatabase()->select(
                '`tmplvarid`, `rank`'
                , $modx->getDatabase()->getFullTableName('site_tmplvar_templates')
                , sprintf("templateid='%d'", (int)$id)
                , ''
            );

            $ranksArr = array();
            $highest = 0;
            while ($row = $modx->getDatabase()->getRow($rs)) {
                $ranksArr[$row['tmplvarid']] = $row['rank'];
                $highest = $highest < $row['rank'] ? $row['rank'] : $highest;
            };

            $modx->getDatabase()->delete(
                $modx->getDatabase()->getFullTableName('site_tmplvar_templates')
                , sprintf("templateid='%d'", (int)$id)
            );
            if (empty($newAssignedTvs)) {
                return;
            }
            foreach ($newAssignedTvs as $tvid) {
                if (!$id || !$tvid) {
                    continue;
                }    // Dont link zeros
                $modx->getDatabase()->insert(
                    array(
                        'templateid' => $id,
                        'tmplvarid'  => $tvid,
                        'rank'       => isset($ranksArr[$tvid]) ? $ranksArr[$tvid] : $highest += 1 // append TVs to rank
                    )
                    , $modx->getDatabase()->getFullTableName('site_tmplvar_templates')
                );
            }
        }
    }
}

if(!function_exists('saveTemplateVarAccess')) {
    /**
     * @return void
     */
    function saveTemplateVarAccess($id)
    {
        $modx = evolutionCMS();
        $templates = isset($_POST['template']) ? $_POST['template'] : []; // get muli-templates based on S.BRENNAN mod

        $siteTmlvarTemplates = EvolutionCMS\Models\SiteTmplvarTemplate::where('tmplvarid', '=', $id)->get();

        $getRankArray = $siteTmlvarTemplates->pluck('rank', 'templateid')->toArray();
        /*foreach ($siteTmlvarTemplates as $siteTmlvarTemplate) {
            $getRankArray[$siteTmlvarTemplate->templateid] = $siteTmlvarTemplate->rank;
        }*/

        EvolutionCMS\Models\SiteTmplvarTemplate::where('tmplvarid', '=', $id)->delete();
        if (!$templates) {
            return;
        }
        foreach ($templates as $i => $iValue) {
            $field = [
                'tmplvarid'  => $id,
                'templateid' => $templates[$i],
                'rank'       => get_by_key($getRankArray, $iValue, 0)
            ];
            EvolutionCMS\Models\SiteTmplvarTemplate::create($field);
        }
    }
}

if(!function_exists('saveDocumentAccessPermissons')) {
    function saveDocumentAccessPermissons($id)
    {
        $modx = evolutionCMS();

        $docgroups = isset($_POST['docgroups']) ? $_POST['docgroups'] : '';

        // check for permission update access
        if($modx->getConfig('use_udperms') != 1) {
            return;
        }

        // delete old permissions on the tv
        EvolutionCMS\Models\SiteTmplvarAccess::where('tmplvarid', '=', $id)->delete();
        if (is_array($docgroups)) {
            foreach ($docgroups as $value) {
                $field = ['tmplvarid' => $id, 'documentgroup' => stripslashes($value)];
                EvolutionCMS\Models\SiteTmplvarAccess::create($field);
            }
        }
    }
}

if (!function_exists('sendMailMessageForUser')) {
    /**
     * Send an email to the user
     *
     * @param string $email
     * @param string $uid
     * @param string $pwd
     * @param string $ufn
     */
    function sendMailMessageForUser($email, $uid, $pwd, $ufn, $message, $url)
    {
        $modx = evolutionCMS();
        global $_lang;
        global $emailsubject, $emailsender;
        $message = sprintf($message, $uid, $pwd); // use old method
        // replace placeholders
        $message = str_replace(
            array('[+uid+]', '[+pwd+]', '[+ufn+]', '[+sname+]', '[+saddr+]', '[+semail+]', '[+surl+]')
            , array($uid, $pwd, $ufn, $modx->getPhpCompat()->entities($modx->getConfig('site_name')), $emailsender, $emailsender, $url)
            , $message
        );

        $param = array();
        $param['from'] = sprintf('%s<%s>', $modx->getConfig('site_name'), $emailsender);
        $param['subject'] = $emailsubject;
        $param['body'] = $message;
        $param['to'] = $email;
        $param['type'] = 'text';
        if ($modx->sendmail($param)) {
            return;
        }
        $modx->getManagerApi()->saveFormValues();
        $modx->messageQuit(sprintf('%s - %s', $email, $_lang['error_sending_email']));
    }
}

if (!function_exists('saveWebUserSettings')) {
// Save User Settings
    function saveWebUserSettings($id)
    {
        $modx = evolutionCMS();
        $tbl_web_user_settings = $modx->getDatabase()->getFullTableName('web_user_settings');

        $settings = array(
            'login_home',
            'allowed_ip',
            'allowed_days'
        );

        $modx->getDatabase()->delete($tbl_web_user_settings, sprintf("webuser='%d'", (int)$id));

        foreach ($settings as $n) {
            $vl = $_POST[$n];
            if (is_array($vl)) {
                $vl = implode(',', $vl);
            }
            if ($vl != '') {
                $f = array();
                $f['webuser'] = $id;
                $f['setting_name'] = $n;
                $f['setting_value'] = $vl;
                $f = $modx->getDatabase()->escape($f);
                $modx->getDatabase()->insert($f, $tbl_web_user_settings);
            }
        }
    }
}

if (!function_exists('saveManagerUserSettings')) {
    /**
     * Save User Settings
     *
     * @param int $id
     */
    function saveManagerUserSettings($id)
    {
        $modx = evolutionCMS();
        $tbl_user_settings = $modx->getDatabase()->getFullTableName('user_settings');

        $ignore = array(
            'id',
            'oldusername',
            'oldemail',
            'newusername',
            'fullname',
            'newpassword',
            'newpasswordcheck',
            'passwordgenmethod',
            'passwordnotifymethod',
            'specifiedpassword',
            'confirmpassword',
            'email',
            'phone',
            'mobilephone',
            'fax',
            'dob',
            'country',
            'street',
            'city',
            'state',
            'zip',
            'gender',
            'photo',
            'comment',
            'role',
            'failedlogincount',
            'blocked',
            'blockeduntil',
            'blockedafter',
            'user_groups',
            'mode',
            'blockedmode',
            'stay',
            'save',
            'theme_refresher'
        );

        // determine which settings can be saved blank (based on 'default_{settingname}' POST checkbox values)
        $defaults = array(
            'upload_images',
            'upload_media',
            'upload_flash',
            'upload_files'
        );

        // get user setting field names
        $settings = array();
        foreach ($_POST as $n => $v) {
            if (in_array($n, $ignore) || (!in_array($n, $defaults) && is_scalar($v) && trim($v) == '') || (!in_array($n,
                        $defaults) && is_array($v) && empty($v))) {
                continue;
            } // ignore blacklist and empties
            $settings[$n] = $v; // this value should be saved
        }

        foreach ($defaults as $k) {
            if (isset($settings['default_' . $k]) && $settings['default_' . $k] == '1') {
                unset($settings[$k]);
            }
            unset($settings['default_' . $k]);
        }

        $modx->getDatabase()->delete($tbl_user_settings, sprintf("user='%d'", (int)$id));

        foreach ($settings as $n => $vl) {
            if (is_array($vl)) {
                $vl = implode(',', $vl);
            }
            if ($vl != '') {
                $f = array();
                $f['user'] = $id;
                $f['setting_name'] = $n;
                $f['setting_value'] = $vl;
                $f = $modx->getDatabase()->escape($f);
                $modx->getDatabase()->insert($f, $tbl_user_settings);
            }
        }
    }
}

if (!function_exists('webAlertAndQuit')) {
    /**
     * Web alert -  sends an alert to web browser
     *
     * @param $msg
     */
    function webAlertAndQuit($msg, $action)
    {
        global $id, $modx;
        $mode = $_POST['mode'];
        $modx->getManagerApi()->saveFormValues($mode);
        $modx->webAlertAndQuit($msg,
            sprintf(
                "index.php?a=%s%s"
                , $mode
                , $mode === $action ? "&id=" . (int)$id : ''
            )
        );
    }
}