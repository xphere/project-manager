<?php

/*
 * This file is part of the Berny\Project-Manager package
 *
 * (c) Berny Cantos <be@rny.cc>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Berny\ProjectManager\Command\Helper;

use Symfony\Component\Console\Helper\DialogHelper as BaseDialogHelper;

class DialogHelper extends BaseDialogHelper
{
    public function question($question)
    {
        return new QuestionBuilder($this, $question);
    }
}
