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
 |   Provide functionality of folders management                         |
 +-----------------------------------------------------------------------+
 | Author: Thomas Bruederli <roundcube@gmail.com>                        |
 | Author: Aleksander Machniak <alec@alec.pl>                            |
 +-----------------------------------------------------------------------+
*/

class rcmail_action_settings_folder_unsubscribe extends rcmail_action
{
    static $mode = self::MODE_AJAX;

    public function run()
    {
        $rcmail  = rcmail::get_instance();
        $storage = $rcmail->get_storage();
        $mbox    = rcube_utils::get_input_value('_mbox', rcube_utils::INPUT_POST, true);

        if (strlen($mbox)) {
            $result = $storage->unsubscribe([$mbox]);

            if ($result) {
                $rcmail->output->show_message('folderunsubscribed', 'confirmation');
            }
            else {
                self::display_server_error('errorsaving');
                $rcmail->output->command('reset_subscription', $mbox, true);
            }
        }

        $rcmail->output->send();
    }
}
