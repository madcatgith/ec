<!DOCTYPE HTML PUBLIC "-//W3C//DTD XHTML 1.0 Transitional //EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    </head>
    <body style="background: #fff">
        <table border="0" cellpadding="0" cellspacing="0" width="100%">
            <thead>
                <tr>
                    <td style="background: #004bbd;" colspan="3" height="10"></td>
                </tr>
                <tr>
                    <td style="background: #004bbd;"></td>
                    <td width="920" align="center" bgcolor="#004bbd">
                        <table width="920" border="0" cellpadding="0" cellspacing="0">
                            <tbody>
                                <tr>
                                    <td>
                                        <a style="text-decoration: none;" href="http://{$smarty.server.HTTP_HOST}{Url::setUrl(['lang' => $langID])}">
                                            <img src="http://{$smarty.server.HTTP_HOST}{Config::get('logo')}" alt="{Config::get('title')|escape}" />
                                        </a>
                                    </td>
                                    <td style="vertical-align: middle;">
                                        <a style="color: #fff;" href="http://{$smarty.server.HTTP_HOST}{Url::setUrl(['lang' => $langID])}">{Dictionary::GetUniqueWord(28)}</a>
                                        {foreach Menu::getChildrens($langID, 1) as $menuID}
                                            &nbsp;<span style="color: #fff;">|</span>&nbsp;<a style="color: #fff;" href="http://{$smarty.server.HTTP_HOST}{Menu::get($langID, $menuID, 'href')}">{Menu::get($langID, $menuID, 'title')}</a>
                                        {/foreach}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                    <td bgcolor="#004bbd"></td>
                </tr>               
            </thead>
            <tbody>
                <tr>
                    <td colspan="3" height="30"></td>
                </tr>
                <tr>
                    <td></td>
                    <td>
                        <table border="0" cellpadding="0" cellspacing="0" width="100%">
                            <tbody>
                                <tr>
                                    <td>{$body}</td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                    <td></td>
                </tr>
                <tr>
                    <td colspan="3" height="30"></td>
                </tr>                
            </tbody>
            <tfooter>
                <tr>
                    <td bgcolor="#F8BC00" style="url('http://{$smarty.server.HTTP_HOST}/images/footerBg.jpg') repeat scroll 0 0 #F8BC00" colspan="3" height="40"></td>
                </tr>
                <tr>
                    <td bgcolor="#F8BC00" style="url('http://{$smarty.server.HTTP_HOST}/images/footerBg.jpg') repeat scroll 0 0 #F8BC00"></td>
                    <td bgcolor="#F8BC00" style="url('http://{$smarty.server.HTTP_HOST}/images/footerBg.jpg') repeat scroll 0 0 #F8BC00" width="920" align="center">
                        <table width="920" border="0" cellpadding="0" cellspacing="0">
                            <tbody>
                                <tr>
                                    <td width="172" valign="top" rowspan="2">
                                        <img alt="{Config::get('title')|escape}" height="60" src="http://{$smarty.server.HTTP_HOST}{Config::get('logo')}">
                                    </td>
                                    <td valign="top">
                                        <a style="color: #fff;" href="http://{$smarty.server.HTTP_HOST}{Url::setUrl(['lang' => $langID])}">{Dictionary::GetUniqueWord(28)}</a>
                                        {foreach Menu::getChildrens($langID, 1) as $menuID}
                                            &nbsp;<span style="color: #fff;">|</span>&nbsp;<a style="color: #fff;" href="http://{$smarty.server.HTTP_HOST}{Menu::get($langID, $menuID, 'href')}">{Menu::get($langID, $menuID, 'title')}</a>
                                        {/foreach}
                                    </td>
                                </tr>
                                <tr>
                                    <td valign="top">
                                        <p style="font-size: 13px; color: #fff; margin: 13px 0 0; font: 20px/30px Calibri, sans-serif;">{Config::get('above_menu')}</p>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                    <td bgcolor="#F8BC00" style="url('http://{$smarty.server.HTTP_HOST}/images/footerBg.jpg') repeat scroll 0 0 #F8BC00"></td>
                </tr>
                <tr>
                    <td bgcolor="#F8BC00" style="url('http://{$smarty.server.HTTP_HOST}/images/footerBg.jpg') repeat scroll 0 0 #F8BC00" colspan="3" height="40"></td>
                </tr>                
            </tfooter>
        </table>
    </body>
</html>