<?php
namespace Nethgui\Controller;

/*
 * Copyright (C) 2012 Nethesis S.r.l.
 *
 * This script is part of NethServer.
 *
 * NethServer is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * NethServer is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with NethServer.  If not, see <http://www.gnu.org/licenses/>.
 */

/**
 * The default empty request for Standard modules
 *
 * @author Davide Principi <davide.principi@nethesis.it>
 * @since 1.0
 */
class NullRequest implements \Nethgui\Controller\RequestInterface
{

    /**
     * @var \Nethgui\Utility\SessionInterface
     */
    private $session;

    private $log;

    private function __construct()
    {
        $this->session = new \Nethgui\Utility\NullSession();
        $this->log = new \Nethgui\Log\Nullog();
    }

    /**
     *
     * @staticvar self $singleton
     * @return \Nethgui\Controller\RequestInterface
     */
    public static function getInstance()
    {
        static $singleton;

        if ( ! isset($singleton)) {
            $singleton = new self();
        }

        return $singleton;
    }

    public function getPath()
    {
        return array();
    }

    public function getExtension()
    {
        return $this->getFormat();
    }

    public function getParameter($parameterName)
    {
        return NULL;
    }

    public function spawnRequest($parameterName, $arguments = array())
    {
        return $this;
    }

    public function getParameterNames()
    {
        return array();
    }

    public function getUser()
    {
        return new \Nethgui\Authorization\User($this->session, $this->log);
    }

    public function hasParameter($parameterName)
    {
        return FALSE;
    }

    public function isEmpty()
    {
        return TRUE;
    }

    public function isMutation()
    {
        return FALSE;
    }

    public function isValidated()
    {
        return FALSE;
    }

    public function getArgument($argumentName)
    {
        return NULL;
    }

    public function getArgumentNames()
    {
        return array();
    }

    public function hasArgument($argumentName)
    {
        return FALSE;
    }

    public function getSession()
    {
        return $this->session;
    }

    public function getFormat()
    {
        return '';
    }

    public function getLanguageCode()
    {
        return 'en';
    }

    public function getLocale()
    {
        return 'en-US';
    }
    
}
