<?php
if( ! defined('IN_MANAGER_MODE') || IN_MANAGER_MODE !== true) {
    exit();
}
unset($_SESSION['itemname']); // clear this, because it's only set for logging purposes
// Catch $_REQUEST['searchid'] for compatibility
if (isset($_REQUEST['searchid'])) {
    $_REQUEST['searchfields'] = $_REQUEST['searchid'];
    $_POST['searchfields'] = $_REQUEST['searchid'];
}
?>

    <script language="javascript">
      var actions = {
        cancel: function() {
          documentDirty = false;
          document.location.href = 'index.php?a=2';
        }
      };
    </script>

    <h1>
        <i class="<?= $_style['icon_search'] ?>"></i><?= $_lang['search_criteria'] ?>
    </h1>

<?= ManagerTheme::getStyle('actionbuttons.static.cancel') ?>

    <div class="tab-page">
        <div class="container container-body">
            <form name="searchform" method="post" action="index.php" enctype="multipart/form-data" class="form-group">
                <input type="hidden" name="a" value="71">
                <div class="row form-row">
                    <div class="col-md-3 col-lg-2"><?= $_lang['search_criteria_top'] ?></div>
                    <div class="col-md-9 col-lg-10">
                        <input name="searchfields" type="text" value="<?= entities(get_by_key($_REQUEST, 'searchfields', '', 'is_scalar'), $modx->getConfig('modx_charset')) ?>" />
                        <small class="form-text"><?= $_lang['search_criteria_top_msg'] ?></small>
                    </div>
                </div>
                <div class="row form-row">
                    <div class="col-md-3 col-lg-2"><?= $_lang['search_criteria_template_id'] ?></div>
                    <div class="col-md-9 col-lg-10">
                        <?php
                        $rs = $modx->getDatabase()->select('*', $modx->getDatabase()->getFullTableName('site_templates'));
                        $option[] = '<option value="">' . $_lang['none'] . '</option>';
                        $templateid = (isset($_REQUEST['templateid']) && $_REQUEST['templateid'] !== '') ? (int)$_REQUEST['templateid'] : '';
                        $selected = $templateid === 0 ? ' selected="selected"' : '';
                        $option[] = '<option value="0"' . $selected . '>(blank)</option>';
                        while ($row = $modx->getDatabase()->getRow($rs)) {
                            $templatename = htmlspecialchars($row['templatename'], ENT_QUOTES, $modx->getConfig('modx_charset'));
                            $selected = $row['id'] == $templateid ? ' selected="selected"' : '';
                            $option[] = sprintf('<option value="%s"%s>%s(%s)</option>', $row['id'], $selected, $templatename, $row['id']);
                        }
                        $tpls = sprintf('<select name="templateid">%s</select>', implode("\n", $option));
                        ?>
                        <?= $tpls ?>
                        <small class="form-text"><?= $_lang['search_criteria_template_id_msg'] ?></small>
                    </div>
                </div>
                <div class="row form-row">
                    <div class="col-md-3 col-lg-2">URL</div>
                    <div class="col-md-9 col-lg-10">
                        <input name="url" type="text" value="<?= entities(get_by_key($_REQUEST,'url', '', 'is_scalar'), $modx->getConfig('modx_charset')) ?>" />
                        <small class="form-text"><?= $_lang['search_criteria_url_msg'] ?></small>
                    </div>
                </div>
                <div class="row form-row">
                    <div class="col-md-3 col-lg-2"><?= $_lang['search_criteria_content'] ?></div>
                    <div class="col-md-9 col-lg-10">
                        <input name="content" type="text" value="<?= entities(get_by_key($_REQUEST, 'content', '', 'is_scalar'), $modx->getConfig('modx_charset')) ?>" />
                        <small class="form-text"><?= $_lang['search_criteria_content_msg'] ?></small>
                    </div>
                </div>

                <a class="btn btn-success" href="javascript:;" onClick="document.searchform.submitok.click();"><i class="<?= $_style["icon_search"] ?>"></i> <?= $_lang['search'] ?>
                </a>
                <a class="btn btn-secondary" href="index.php?a=2"><i class="<?= $_style["icon_cancel"] ?>"></i> <?= $_lang['cancel'] ?></a>
                <input type="submit" value="Search" name="submitok" style="display:none" />
            </form>
        </div>
    </div>
<?php
//TODO: сделать поиск по уму пока сделаю что б одно поле было для id,longtitle,pagetitle,alias далее нужно думаю добавить что б и в елементах искало
if (isset($_REQUEST['submitok'])) {
    $tbl_site_content = $modx->getDatabase()->getFullTableName('site_content');
    $tbldg = $modx->getDatabase()->getFullTableName('document_groups');

    $searchfields = trim(get_by_key($_REQUEST, 'searchfields', '', 'is_scalar'));
    $searchlongtitle = $modx->getDatabase()->escape($searchfields);
    $search_alias = $modx->getDatabase()->escape($searchfields);
    $searchfields = htmlentities($searchfields, ENT_QUOTES, ManagerTheme::getCharset());
    $templateid = isset($_REQUEST['templateid']) && $_REQUEST['templateid'] !== '' ? (int)$_REQUEST['templateid'] : '';
    $searchcontent = $modx->getDatabase()->escape(get_by_key($_REQUEST, 'content', '', 'is_scalar'));

    $fields = 'DISTINCT sc.id, contenttype, pagetitle, longtitle, description, introtext, menutitle, deleted, published, isfolder, type';

    $sqladd = "";

    // Handle Input "Search by exact URL"
    $idFromAlias = false;
    if (isset($_REQUEST['url']) && $_REQUEST['url'] !== '') {
        $url = $modx->getDatabase()->escape($_REQUEST['url']);
        $friendly_url_suffix = $modx->getConfig('friendly_url_suffix');
        $base_url = MODX_BASE_URL;
        $site_url = MODX_SITE_URL;
        $url = preg_replace('@' . $friendly_url_suffix . '$@', '', $url);
        if ($url[0] === '/') {
            $url = preg_replace('@^' . $base_url . '@', '', $url);
        }
        if (substr($url, 0, 4) === 'http') {
            $url = preg_replace('@^' . $site_url . '@', '', $url);
        }
        $idFromAlias = $modx->getIdFromAlias($url);
    }

    // Handle Input "Search in main fields"
    if ($searchfields != '') {

		/*start search by TV. Added Rising13*/
		$tbl_site_tmplvar_contentvalues = $modx->getDatabase()->getFullTableName('site_tmplvar_contentvalues');
		$articul_query = "SELECT `contentid` FROM {$tbl_site_tmplvar_contentvalues} WHERE `value` LIKE '%{$searchfields}%'";
		$articul_result = $modx->getDatabase()->query($articul_query);
		$articul_id_array = $modx->getDatabase()->makeArray($articul_result);
		if(count($articul_id_array)>0){
			$articul_id = '';
			$i = 1;
			foreach( $articul_id_array as $articul ) {
				$articul_id.=$articul['contentid'];
				if($i !== count($articul_id_array)){
					$articul_id.=',';
				}
				$i++;
			}
		$articul_id_query = " OR sc.id IN ({$articul_id})";
		}else{
			$articul_id_query = '';
		}
		/*end search by TV*/

        if (ctype_digit($searchfields)) {
            $sqladd .= "sc.id='{$searchfields}'";
            if (strlen($searchfields) > 3) {
				$sqladd .= $articul_id_query;//search by TV
                $sqladd .= " OR sc.pagetitle LIKE '%{$searchfields}%'";
            }
        }
        if ($idFromAlias) {
            $sqladd .= $sqladd != '' ? ' OR ' : '';
            $sqladd .= "sc.id='{$idFromAlias}'";

        }

        $sqladd = $sqladd ? "({$sqladd})" : $sqladd;

        if (!ctype_digit($searchfields)) {
            $sqladd .= $sqladd != '' ? ' AND' : '';
            $sqladd .= " (sc.pagetitle LIKE '%{$searchfields}%'";
            $sqladd .= " OR sc.longtitle LIKE '%{$searchlongtitle}%'";
            $sqladd .= " OR sc.description LIKE '%{$searchlongtitle}%'";
            $sqladd .= " OR sc.introtext LIKE '%{$searchlongtitle}%'";
            $sqladd .= " OR sc.menutitle LIKE '%{$searchlongtitle}%'";
            $sqladd .= " OR sc.alias LIKE '%{$search_alias}%'";
            $sqladd .= $articul_id_query;//search by TV
            $sqladd .= ")";
        }
    } elseif ($idFromAlias) {
        $sqladd .= " sc.id='{$idFromAlias}'";
    }

    // Handle Input "Search by template ID"
    if ($templateid !== '') {
        $sqladd .= $sqladd != '' ? ' AND' : '';
        $sqladd .= " sc.template='{$templateid}'";
    }

    // Handle Input "Search by content"
    if ($searchcontent !== '') {
        $sqladd .= $sqladd != '' ? ' AND' : '';
        $sqladd .= $searchcontent != '' ? " sc.content LIKE '%{$searchcontent}%'" : '';
    }

    // get document groups for current user
    if (!empty($modx->config['use_udperms']) && $sqladd) {
        $docgrp = (isset($_SESSION['mgrDocgroups']) && is_array($_SESSION['mgrDocgroups'])) ? implode(',', $_SESSION['mgrDocgroups']) : '';
        $mgrRole = (isset ($_SESSION['mgrRole']) && $_SESSION['mgrRole'] == 1) ? 1 : 0;
        $docgrp_cond = $docgrp ? " OR dg.document_group IN ({$docgrp})" : '';
        $fields .= ', MAX(IF(1=' . $mgrRole . ' OR sc.privatemgr=0' . $docgrp_cond . ',1,0)) AS hasAccess';
        $sqladd = '(' . $sqladd . ") AND (1={$mgrRole} OR sc.privatemgr=0" . (!$docgrp ? ')' : " OR dg.document_group IN ({$docgrp}))");
    }

    if ($sqladd) {
        $sqladd .= ' GROUP BY sc.id';
    }

    $where = $sqladd;

    if ($where) {
        $rs = $modx->getDatabase()->select($fields, $tbl_site_content . ' AS sc LEFT JOIN ' . $tbldg . ' AS dg ON dg.document=sc.id', $where, 'sc.id');
        $limit = $modx->getDatabase()->getRecordCount($rs);
    } else {
        $limit = 0;
    }

    ?>
    <div class="container navbar">
        <?= $_lang['search_results'] ?>
    </div>

    <div class="tab-page">
        <div class="container container-body">
            <?php
            if ((int)get_by_key($_GET, 'ajax', 0) !== 1) {
                if ($limit < 1) {
                    echo $_lang['search_empty'];
                } else {
                    printf('<p>' . $_lang['search_results_returned_msg'] . '</p>', $limit);
                    ?>
                    <script type="text/javascript" src="media/script/tablesort.js"></script>
                    <table class="grid sortabletable sortable-onload-2 rowstyle-even" id="table-1">
                        <thead>
                        <tr>
                            <th width="40"></th>
                            <th width="40" class="sortable"><b><?= $_lang['search_results_returned_id'] ?></b></th>
                            <th width="40"></th>
                            <th class="sortable"><b><?= $_lang['search_results_returned_title'] ?></b></th>
                            <th class="sortable"><b><?= $_lang['search_results_returned_desc'] ?></b></th>
                        </tr>
                        </thead>
                        <tbody>

                        <?php
                        // icons by content type
                        $icons = array(
                            'text/plain'                 => '<i class="' . $_style['icon_document'] . '"></i>',
                            'text/html'                  => '<i class="' . $_style['icon_document'] . '"></i>',
                            'text/xml'                   => '<i class="' . $_style['icon_code_file'] . '"></i>',
                            'text/css'                   => '<i class="' . $_style['icon_code_file'] . '"></i>',
                            'text/javascript'            => '<i class="' . $_style['icon_code_file'] . '"></i>',
                            'image/gif'                  => '<i class="' . $_style['icon_image'] . '"></i>',
                            'image/jpg'                  => '<i class="' . $_style['icon_image'] . '"></i>',
                            'image/png'                  => '<i class="' . $_style['icon_image'] . '"></i>',
                            'application/pdf'            => '<i class="' . $_style['icon_pdf'] . '"></i>',
                            'application/rss+xml'        => '<i class="' . $_style['icon_code_file'] . '"></i>',
                            'application/vnd.ms-word'    => '<i class="' . $_style['icon_word'] . '"></i>',
                            'application/vnd.ms-excel'   => '<i class="' . $_style['icon_excel'] . '"></i>',
                        );

                        while ($row = $modx->getDatabase()->getRow($rs)) {
                            // figure out the icon for the document...
                            $icon = "";
                            if ($row['type'] == 'reference') {
                                $icon .= $_style["tree_linkgo"];
                            } elseif ($row['isfolder'] == 0) {
                                $icon .= isset($icons[$row['contenttype']]) ? $icons[$row['contenttype']] : '<i class="' . $_style['icon_document'] . '"></i>';
                            } else {
                                $icon .= '<i class="' . $_style['icon_folder'] . '"></i>';
                            }

                            $tdClass = "";
                            if ($row['published'] == 0) {
                                $tdClass .= ' class="unpublishedNode"';
                            }
                            if ($row['deleted'] == 1) {
                                $tdClass .= ' class="deletedNode"';
                            }
                            ?>
                            <tr>
                                <td class="text-center">
                                    <a href="index.php?a=3&id=<?= $row['id'] ?>" title="<?= $_lang['search_view_docdata'] ?>"><i class="<?= $_style['icon_info'] ?>" /></i></a>
                                </td>
                                <td class="text-right"><?= $row['id'] ?></td>
                                <td class="text-center"><?= $icon ?></td>
                                <?php
                                if (function_exists('mb_strlen') && function_exists('mb_substr')) {
                                    ?>
                                    <td<?= $tdClass ?>>
                                        <a href="index.php?a=27&id=<?= $row['id'] ?>"><?= mb_strlen($row['pagetitle'], ManagerTheme::getCharset()) > 70 ? mb_substr($row['pagetitle'], 0, 70, ManagerTheme::getCharset()) . "..." : $row['pagetitle'] ?></a>
                                    </td>
                                    <td<?= $tdClass ?>><?= mb_strlen($row['description'], ManagerTheme::getCharset()) > 70 ? mb_substr($row['description'], 0, 70, ManagerTheme::getCharset()) . "..." : $row['description'] ?></td>
                                    <?php
                                } else {
                                    ?>
                                    <td<?= $tdClass ?>><?= strlen($row['pagetitle']) > 20 ? substr($row['pagetitle'], 0, 20) . '...' : $row['pagetitle'] ?></td>
                                    <td<?= $tdClass ?>><?= strlen($row['description']) > 35 ? substr($row['description'], 0, 35) . '...' : $row['description'] ?></td>
                                    <?php
                                }
                                ?>
                            </tr>
                            <?php
                        }
                        ?>
                        </tbody>
                    </table>
                    <?php

                }
            } else {
                $output = '';

                //docs
                if ($modx->hasPermission('new_document') && $modx->hasPermission('edit_document') && $modx->hasPermission('save_document')) {
                    $docscounts = $modx->getDatabase()->getRecordCount($rs);
                    if ($docscounts > 0) {
                        $output .= '<li><b><i class="' . $_style['icon_sitemap'] . '"></i> ' . $_lang["manage_documents"] . ' (' . $docscounts . ')</b></li>';
                        while ($row = $modx->getDatabase()->getRow($rs)) {
                            $output .= '<li' . addClassForItemList('', !$row['published'], $row['deleted']) . '>
                                <a href="index.php?a=27&id=' . $row['id'] . '" id="content_' . $row['id'] . '" target="main">' .
                                    highlightingCoincidence($row['pagetitle'], $_REQUEST['searchfields']) . ' <small>(' . highlightingCoincidence($row['id'], $_REQUEST['searchfields']) . ')</small>' . '<i class="' . $_style['icon_external_link'] . '"></i>
                                </a>
                            </li>';
                        }
                    }
                }

                //templates
                if ($modx->hasPermission('edit_template')) {
                    $rs = $modx->getDatabase()->select("id,templatename,locked", $modx->getDatabase()->getFullTableName('site_templates'), "`id` like '%" . $searchfields . "%' 
					OR `templatename` like '%" . $searchfields . "%' 
					OR `description` like '%" . $searchfields . "%' 
					OR `content` like '%" . $searchfields . "%'");
                    $templatecounts = $modx->getDatabase()->getRecordCount($rs);
                    if ($templatecounts > 0) {
                        $output .= '<li><b><i class="' . $_style['icon_template'] . '"></i> ' . $_lang["manage_templates"] . ' (' . $templatecounts . ')</b></li>';
                        while ($row = $modx->getDatabase()->getRow($rs)) {
                            $output .= '<li' . addClassForItemList($row['locked']) . '><a href="index.php?a=16&id=' . $row['id'] . '" id="templates_' . $row['id'] . '" target="main">' . highlightingCoincidence($row['templatename'], $_REQUEST['searchfields']) . '<i class="' . $_style['icon_external_link'] . '"></i></a></li>';
                        }
                    }
                }

                //tvs
                if ($modx->hasPermission('edit_template') && $modx->hasPermission('edit_snippet') && $modx->hasPermission('edit_chunk') && $modx->hasPermission('edit_plugin')) {
                    $rs = $modx->getDatabase()->select(
                            "id,name,locked",
                            $modx->getDatabase()->getFullTableName('site_tmplvars'),
                            "`id` like '%" . $searchfields . "%' 
					OR `name` like '%" . $searchfields . "%' 
					OR `description` like '%" . $searchfields . "%' 
					OR `type` like '%" . $searchfields . "%' 
					OR `elements` like '%" . $searchfields . "%' 
					OR `display` like '%" . $searchfields . "%' 
					OR `display_params` like '%" . $searchfields . "%' 
					OR `default_text` like '%" . $searchfields . "%'");
                    $tvscounts = $modx->getDatabase()->getRecordCount($rs);
                    if ($tvscounts > 0) {
                        $output .= '<li><b><i class="' . $_style['icon_tv'] . '"></i> ' . $_lang["settings_templvars"] . ' (' . $tvscounts . ')</b></li>';
                        while ($row = $modx->getDatabase()->getRow($rs)) {
                            $output .= '<li' . addClassForItemList($row['locked']) . '><a href="index.php?a=301&id=' . $row['id'] . '" id="tmplvars_' . $row['id'] . '" target="main">' . highlightingCoincidence($row['name'], $_REQUEST['searchfields']) . '<i class="' . $_style['icon_external_link'] . '"></i></a></li>';
                        }
                    }
                }

                //Chunks
                if ($modx->hasPermission('edit_chunk')) {
                    $rs = $modx->getDatabase()->select(
                            "id,name,locked,disabled",
                            $modx->getDatabase()->getFullTableName('site_htmlsnippets'),
                            "`id` like '%" . $searchfields . "%' 
					OR `name` like '%" . $searchfields . "%' 
					OR `description` like '%" . $searchfields . "%'     
					OR `snippet` like '%" . $searchfields . "%'");
                    $chunkscounts = $modx->getDatabase()->getRecordCount($rs);
                    if ($chunkscounts > 0) {
                        $output .= '<li><b><i class="' . $_style['icon_chunk'] . '"></i> ' . $_lang["manage_htmlsnippets"] . ' (' . $chunkscounts . ')</b></li>';
                        while ($row = $modx->getDatabase()->getRow($rs)) {
                            $output .= '<li' . addClassForItemList($row['locked'], $row['disabled']) . '><a href="index.php?a=78&id=' . $row['id'] . '" id="htmlsnippets_' . $row['id'] . '" target="main">' . highlightingCoincidence($row['name'], $_REQUEST['searchfields']) . '<i class="' . $_style['icon_external_link'] . '"></i></a></li>';
                        }
                    }
                }

                //Snippets
                if ($modx->hasPermission('edit_snippet')) {
                    $rs = $modx->getDatabase()->select(
                            "id,name,locked,disabled",
                            $modx->getDatabase()->getFullTableName('site_snippets'),
                            "`id` like '%" . $searchfields . "%' 
					OR `name` like '%" . $searchfields . "%' 
					OR `description` like '%" . $searchfields . "%' 
					OR `snippet` like '%" . $searchfields . "%'  
					OR `properties` like '%" . $searchfields . "%'      
					OR `moduleguid` like '%" . $searchfields . "%'");
                    $snippetscounts = $modx->getDatabase()->getRecordCount($rs);
                    if ($snippetscounts > 0) {
                        $output .= '<li><b><i class="' . $_style['icon_code'] . '"></i> ' . $_lang["manage_snippets"] . ' (' . $snippetscounts . ')</b></li>';
                        while ($row = $modx->getDatabase()->getRow($rs)) {
                            $output .= '<li' . addClassForItemList($row['locked'], $row['disabled']) . '><a href="index.php?a=22&id=' . $row['id'] . '" id="snippets_' . $row['id'] . '" target="main">' . highlightingCoincidence($row['name'], $_REQUEST['searchfields']) . '<i class="' . $_style['icon_external_link'] . '"></i></a></li>';
                        }
                    }
                }

                //plugins
                if ($modx->hasPermission('edit_plugin')) {
                    $rs = $modx->getDatabase()->select(
                            "id,name,locked,disabled",
                            $modx->getDatabase()->getFullTableName('site_plugins'),
                            "`id` like '%" . $searchfields . "%' 
					OR `name` like '%" . $searchfields . "%' 
					OR `description` like '%" . $searchfields . "%' 
					OR `plugincode` like '%" . $searchfields . "%'  
					OR `properties` like '%" . $searchfields . "%'      
					OR `moduleguid` like '%" . $searchfields . "%'");
                    $pluginscounts = $modx->getDatabase()->getRecordCount($rs);
                    if ($pluginscounts > 0) {
                        $output .= '<li><b><i class="' . $_style['icon_plugin'] . '"></i> ' . $_lang["manage_plugins"] . ' (' . $pluginscounts . ')</b></li>';
                        while ($row = $modx->getDatabase()->getRow($rs)) {
                            $output .= '<li' . addClassForItemList($row['locked'], $row['disabled']) . '><a href="index.php?a=102&id=' . $row['id'] . '" id="plugins_' . $row['id'] . '" target="main">' . highlightingCoincidence($row['name'], $_REQUEST['searchfields']) . '<i class="' . $_style['icon_external_link'] . '"></i></a></li>';
                        }
                    }
                }

                //modules
                if ($modx->hasPermission('edit_module')) {
                    $rs = $modx->getDatabase()->select(
                            "id,name,locked,disabled",
                            $modx->getDatabase()->getFullTableName('site_modules'),
                            "`id` like '%" . $searchfields . "%' 
                    OR `name` like '%" . $searchfields . "%' 
                    OR `description` like '%" . $searchfields . "%' 
                    OR `modulecode` like '%" . $searchfields . "%'  
                    OR `properties` like '%" . $searchfields . "%'  
                    OR `guid` like '%" . $searchfields . "%'      
                    OR `resourcefile` like '%" . $searchfields . "%'");
                    $modulescounts = $modx->getDatabase()->getRecordCount($rs);
                    if ($modulescounts > 0) {
                        $output .= '<li><b><i class="' . $_style['icon_cogs'] . '"></i> ' . $_lang["modules"] . ' (' . $modulescounts . ')</b></li>';
                        while ($row = $modx->getDatabase()->getRow($rs)) {
                            $output .= '<li' . addClassForItemList($row['locked'], $row['disabled']) . '><a href="index.php?a=108&id=' . $row['id'] . '" id="modules_' . $row['id'] . '" target="main">' . highlightingCoincidence($row['name'], $_REQUEST['searchfields']) . '<i class="' . $_style['icon_external_link'] . '"></i></a></li>';
                        }
                    }
                }

                echo $output ? '<div class="ajaxSearchResults"><ul>' . $output . '</ul></div>' : '1';
            }

            ?>
        </div>
    </div>
    <?php
}
