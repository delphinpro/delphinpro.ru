@extends('manager::template.page')
@section('content')
    @push('scripts.top')
        <script language="javascript">
            var actions = {
                cancel: function() {
                    documentDirty = false;
                    document.location.href = 'index.php?a=2';
                }
            };
        </script>
    @endpush

    <h1>
        <i class="fa fa-download"></i>{{ $_lang['export_site_html'] }}
    </h1>

    {!! ManagerTheme::getStyle('actionbuttons.static.cancel') !!}


    <div class="tab-pane" id="exportPane">
        <script type="text/javascript">
            tpExport = new WebFXTabPane(document.getElementById("exportPane"));
        </script>

        <div class="tab-page" id="tabMain">
            <h2 class="tab">{{ $_lang['export_site'] }}</h2>
            <script type="text/javascript">tpExport.addTabPage(document.getElementById("tabMain"));</script>

            <div class="container container-body">
                @if(!empty($output))
                    {!! $output !!}
                @else
                    <form action="index.php" method="post" name="exportFrm">
                        <input type="hidden" name="export" value="export" />
                        <input type="hidden" name="a" value="83" />
                        <style type="text/css">
                            table.settings { width: 100%; }
                            table.settings td.head { white-space: nowrap; vertical-align: top; padding-right: 20px; font-weight: bold; }
                        </style>
                        <table class="settings" cellspacing="0" cellpadding="2">
                            <tr>
                                <td class="head">{{ $_lang['export_site_cacheable'] }}</td>
                                <td><label><input type="radio" name="includenoncache" value="1" checked />{{ $_lang['yes'] }}</label>
                                    <label><input type="radio" name="includenoncache" value="0" />{{ $_lang['no'] }}</label></td>
                            </tr>
                            <tr>
                                <td class="head">{{ $_lang['a83_ignore_ids_title'] }}</td>
                                <td><input type="text" name="ignore_ids" value="" style="width:300px;" /></td>
                            </tr>
                            <tr>
                                <td class="head">{{ $_lang['export_site.static.php4'] }}</td>
                                <td><input type="text" name="repl_before" value="" style="width:300px;" /></td>
                            </tr>
                            <tr>
                                <td class="head">{{ $_lang['export_site.static.php5'] }}</td>
                                <td><input type="text" name="repl_after" value="" style="width:300px;" /></td>
                            </tr>
                            <tr>
                                <td class="head">{{ $_lang['export_site_maxtime'] }}</td>
                                <td><input type="text" name="maxtime" value="60" />
                                    <br />
                                    {{ $_lang['export_site_maxtime_message'] }}
                                </td>
                            </tr>
                        </table>
                        <a href="javascript:;" class="btn btn-primary" onclick="document.exportFrm.submit();jQuery(this).hide();"><i class="{{ $_style["actions_save"] }}"></i> {{ $_lang["export_site_start"] }}</a>
                        <script>
                            jQuery('#exportButton a').click(function() {
                                jQuery(this).parent().html('{{ $_style['ajax_loader'] }}');
                            });
                        </script>
                    </form>
                @endif
            </div>
        </div>

        <div class="tab-page" id="tabHelp">
            <h2 class="tab">{{ $_lang['help'] }}</h2>
            <script type="text/javascript">tpExport.addTabPage(document.getElementById("tabHelp"));</script>

            <div class="container container-body">
                {!! $_lang['export_site_message'] !!}
            </div>
        </div>

    </div>
@endsection
