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
 |   Spellchecker for TinyMCE                                            |
 +-----------------------------------------------------------------------+
 | Author: Aleksander Machniak <alec@alec.pl>                            |
 +-----------------------------------------------------------------------+
*/

class rcmail_action_utils_spell_html extends rcmail_action
{
    // only process ajax requests
    protected static $mode = self::MODE_AJAX;

    public function run()
    {
        $rcmail = rcmail::get_instance();
        $method = rcube_utils::get_input_value('method', rcube_utils::INPUT_POST);
        $lang   = rcube_utils::get_input_value('lang', rcube_utils::INPUT_POST);
        $result = [];

        $spellchecker = new rcube_spellchecker($lang);

        if ($method == 'addToDictionary') {
            $data = rcube_utils::get_input_value('word', rcube_utils::INPUT_POST);

            $spellchecker->add_word($data);
            $result['result'] = true;
        }
        else {
            $data = rcube_utils::get_input_value('text', rcube_utils::INPUT_POST, true);
            $data = html_entity_decode($data, ENT_QUOTES, RCUBE_CHARSET);

            if ($data && !$spellchecker->check($data)) {
                $result['words']      = $spellchecker->get();
                $result['dictionary'] = (bool) $rcmail->config->get('spellcheck_dictionary');
            }
        }

        if ($error = $spellchecker->error()) {
            rcube::raise_error([
                    'code'    => 500,
                    'file'    => __FILE__,
                    'line'    => __LINE__,
                    'message' => sprintf("Spell check engine error: " . $error)
                ],
                true,
                false
            );

            echo json_encode(['error' => $error]);
            exit;
        }

        // send output
        header("Content-Type: application/json; charset=".RCUBE_CHARSET);
        echo json_encode($result);
        exit;
    }
}
