<?php

/**
 +-----------------------------------------------------------------------+
 | This file is part of the Roundcube Webmail client                     |
 |                                                                       |
 | Copyright (C) The Roundcube Dev Team                                  |
 |                                                                       |
 | Licensed under the GNU General Public License version 3 or            |
 | any later version with exceptions for skins & plugins.                |
 | See the README file for a full license statement.                     |
 |                                                                       |
 | PURPOSE:                                                              |
 |   Convert HTML message to plain text                                  |
 +-----------------------------------------------------------------------+
 | Author: Thomas Bruederli <roundcube@gmail.com>                        |
 +-----------------------------------------------------------------------+
*/

class rcmail_action_utils_html2text extends rcmail_action
{
    // only process ajax requests
    protected static $mode = self::MODE_AJAX;

    function run()
    {
        $html = stream_get_contents(fopen('php://input', 'r'));

        $params['links'] = (bool) rcube_utils::get_input_value('_do_links', rcube_utils::INPUT_GET);
        $params['width'] = (int) rcube_utils::get_input_value('_width', rcube_utils::INPUT_GET);

        $text = rcmail::get_instance()->html2text($html, $params);

        header('Content-Type: text/plain; charset=' . RCUBE_CHARSET);
        print $text;
        exit;
    }
}
