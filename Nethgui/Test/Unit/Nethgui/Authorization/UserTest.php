<?php

namespace Nethgui\Test\Unit\Nethgui\Authorization;

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
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with NethServer. If not, see <http://www.gnu.org/licenses/>.
 */

/**
 * User Unit test case
 *
 * @author Davide Principi <davide.principi@nethesis.it>
 * @since 1.0
 * @covers \Nethgui\Authorization\User
 */
class UserTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var \Nethgui\Authorization\User
     */
    protected $object;

    /**
     *
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $log;

    protected function setUp()
    {
        $this->session = $this->getMock('Nethgui\Utility\Session', array('retrieve'));
        $this->log = $this->getMock('Nethgui\Log\LogInterface');
        $this->object = new \Nethgui\Authorization\User($this->session, $this->log);
    }

    public function testSetAuthenticationProcedure()
    {
        $this->assertSame($this->object, $this->object->setAuthenticationProcedure(function () {
                    return FALSE;
                }));
    }

    public function testGetLanguageCode()
    {
        $this->session->expects($this->once())
                ->method('retrieve')
                ->with(get_class($this->object))
                ->will($this->returnValue(new \ArrayObject(array(
                            'credentials' => array(),
                            'preferences' => array('lang' => 'it'),
                            'authenticated' => array()
        ))));
        $this->assertEquals('it', $this->object->getLanguageCode());
        $this->object->setPreference('lang', 'fr');
        $this->assertEquals('fr', $this->object->getLanguageCode());
    }

    public function testSetLanguageCode()
    {
        $this->assertSame($this->object, $this->object->setLanguageCode('fr'));
    }

    public function testAuthenticate()
    {
        $this->assertNull($this->object->getCredential('undefined'));
        $this->assertFalse($this->object->isAuthenticated());
        $this->assertFalse($this->object->hasCredential('groups'));
        $this->assertEquals(FALSE, $this->object->getAuthorizationAttribute('authenticated'));

        $this->object->setAuthenticationProcedure(function ($uname, $pw, &$credentials) {
            $credentials['groups'] = array('g1', 'g2');
            $credentials['username'] = $uname;
            return TRUE;
        });

        $this->assertTrue($this->object->authenticate('usr1', 'pass'));
        $this->assertTrue($this->object->isAuthenticated());
        $this->assertTrue($this->object->hasCredential('groups'));
        $this->assertEquals('usr1', $this->object->getCredential('username'));


        $this->assertEquals('usr1', $this->object->asAuthorizationString());
        $this->assertEquals(array('g1', 'g2'), $this->object->getAuthorizationAttribute('groups'));
        $this->assertEquals(TRUE, $this->object->getAuthorizationAttribute('authenticated'));
    }

    public function testSerialize()
    {
        $ser = $this->object->serialize();
        $this->assertInternalType('string', $ser);
    }

    public function testUnserialize()
    {
        $ser = $this->object->serialize();

        $state = unserialize($ser);
        $this->assertInternalType('array', $state);

        $object = unserialize(serialize($this->object));
        $this->assertInstanceOf('Nethgui\Authorization\User', $object);
    }

    public function testSetPreference()
    {
        $this->assertSame($this->object, $this->object->setPreference('lang', 'fr'));
    }

    public function testGetPreference()
    {
        $this->assertNull($this->object->getPreference('nopref'));
        $this->object->setPreference('lang', 'fr');
        $this->assertEquals('fr', $this->object->getPreference('lang'));
    }

}
