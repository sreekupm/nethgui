<?php
/**
 * @package Tests
 * @subpackage Unit
 */

/**
 * Test class for Nethgui_Core_Validator.
 * Generated by PHPUnit on 2011-04-01 at 16:22:41.
 * @package Tests
 * @subpackage Unit
 */
class Nethgui_Core_ValidatorTest extends PHPUnit_Framework_TestCase
{

    /**
     * @var Nethgui_Core_Validator
     */
    protected $object;

    protected function setUp()
    {
        $this->object = new Nethgui_Core_Validator;
    }

    /**
     * @todo Implement testOrValidator().
     */
    public function testOrValidator()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }

    public function testMemberOf1()
    {
        $this->object->memberOf('a', 'b', 'c');
        $this->assertTrue($this->object->evaluate('a'));
        $this->assertFalse($this->object->evaluate('z'));
    }

    public function testMemberOf2()
    {
        $this->object->memberOf(array('a', 'b', 'c'));
        $this->assertTrue($this->object->evaluate('a'));
        $this->assertFalse($this->object->evaluate('z'));
    }

    public function testRegexpSuccess()
    {
        $this->object->regexp('/[0-9]+/');
        $this->assertTrue($this->object->evaluate('12345'));
    }

    public function testRegexpFail()
    {
        $this->object->regexp('/[0-9]+/');
        $this->assertFalse($this->object->evaluate('aaaaa'));
    }

    /**
     * @todo Implement testNotEmpty().
     */
    public function testNotEmpty()
    {
        $this->object->notEmpty();
        $this->assertFalse($this->object->evaluate(''));
    }

    public function testForceResultTrue()
    {
        $this->object->forceResult(TRUE)->notEmpty();
        $this->assertTrue($this->object->evaluate(''));
    }

    public function testForceResultFalse()
    {
        $this->object->notEmpty()->forceResult(FALSE);
        $this->assertFalse($this->object->evaluate('x'));
    }

    /**
     * @todo Implement testIpV4Address().
     */
    public function testIpV4Address()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }

    /**
     * @todo Implement testIpV6Address().
     */
    public function testIpV6Address()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }

    /**
     * @todo Implement testIpV4Netmask().
     */
    public function testIpV4Netmask()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }

    /**
     * @todo Implement testIpV6Netmask().
     */
    public function testIpV6Netmask()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }

    public function testUsernameValid()
    {
        $this->object->username();
        $this->assertTrue($this->object->evaluate('v123alid-user_name'));
    }

    public function testUsernameInvalid()
    {
        $this->object->username();

        $invalidUsernames = array(
            'invalidUserName', // no uppercase
            '0invalidusername', // start with letter
            'in.valid', // no symbols           
            str_repeat('x', 32), // < 32 characters            
        );

        foreach ($invalidUsernames as $username) {
            $this->assertFalse($this->object->evaluate($username));
        }
    }

    public function testCollectionValidatorNotEmptyMembers()
    {
        $v = new Nethgui_Core_Validator();

        // check members are not empty
        $v->notEmpty();

        $this->object->collectionValidator($v);

        $o = new ArrayObject(array('a', 'b', 'c'));

        $this->assertTrue($this->object->evaluate(array('a', 'b', 'c')));
        $this->assertTrue($this->object->evaluate($o));
        $this->assertTrue($this->object->evaluate(array())); // an empty collection always return TRUE!
        $this->assertTrue($this->object->evaluate($o->getIterator()));
        $this->assertFalse($this->object->evaluate(array('a', '', 'c')));
        $this->assertFalse($this->object->evaluate(new ArrayObject(array('a', 'b', ''))));
        $this->assertFalse($this->object->evaluate(2));
        $this->assertFalse($this->object->evaluate(TRUE));
        $this->assertFalse($this->object->evaluate(1.2));
    }

    /**
     * @todo
     */    
    public function testInteger()
    {
        $this->markTestIncomplete();
    }

    /**
     * @todo
     */
    public function testPositive()
    {
        $this->markTestIncomplete();
    }

    /**
     * @todo
     */
    public function testNegative()
    {
        $this->markTestIncomplete();
    }

    /**
     * @todo
     */
    public function testGreatThan()
    {
        $this->markTestIncomplete();
    }

    /**
     * @todo
     */
    public function testLessThan()
    {
        $this->markTestIncomplete();
    }

    /**
     * @todo
     */
    public function testEqualTo()
    {
        $this->markTestIncomplete();
    }

}

?>