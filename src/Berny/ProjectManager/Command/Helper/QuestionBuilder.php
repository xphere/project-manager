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

use Symfony\Component\Console\Output\OutputInterface;

class QuestionBuilder
{
    /**
     * @var DialogHelper
     */
    private $dialog;

    /**
     * @var string
     */
    private $question;

    /**
     * @var string
     */
    private $default;

    /**
     * @var callback
     */
    private $validator;

    /**
     * @var integer|boolean
     */
    private $attempts;

    /**
     * @var array
     */
    private $autocomplete;

    /**
     * @var boolean
     */
    private $hidden;

    /**
     * @var boolean
     */
    private $fallback;

    public function __construct(DialogHelper $dialog, $question)
    {
        $this->dialog = $dialog;
        $this->question = $question;
    }

    public function defaultsTo($default)
    {
        $this->default = $default;
        return $this;
    }

    public function validateWith($validator, $attempts = false)
    {
        $this->validator = $validator;
        $this->attempts = $attempts;
        return $this;
    }

    public function autocomplete(array $autocomplete = null)
    {
        $this->autocomplete = $autocomplete;
        return $this;
    }

    public function hide($fallback = true)
    {
        $this->hidden = true;
        $this->fallback = $fallback;
        return $this;
    }

    public function ask(OutputInterface $output)
    {
        $question = '<info>' . $this->question . '</info>';
        if ($this->default !== null) {
            $question .= ' [<comment>' . $this->default . '</comment>] ';
        }
        $question .= ': ';

        if ($this->validator === null) {
            if ($this->hidden) {
                return $this->dialog->askHiddenResponse($output, $question, $this->fallback);
            }
            return $this->dialog->ask($output, $question, $this->default, $this->autocomplete);
        }

        if ($this->hidden) {
            return $this->dialog->askHiddenResponseAndValidate(
                $output,
                $question,
                $this->validator,
                $this->attempts,
                $this->fallback
            );
        }

        return $this->dialog->askAndValidate(
            $output,
            $question,
            $this->validator,
            $this->attempts,
            $this->default,
            $this->autocomplete
        );
    }
}