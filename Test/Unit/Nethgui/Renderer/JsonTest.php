<?php
/**
 * @package Tests
 *
 */

/**
 * @package Tests
 *
 */
class Nethgui_Renderer_JsonTest extends PHPUnit_Framework_TestCase
{

    /**
     * @var Nethgui_Renderer_Json
     */
    protected $object;

    protected function setUp()
    {
        $view = $this->getMockBuilder('Nethgui_Core_ViewInterface')
            ->getMock();

        $innerModule = $this->getMockBuilder('Nethgui_Core_ModuleInterface')
            // ->setMethods(array('getParent', 'getIdentifier'))
            ->getMock();


        $module = new Test_Unit_NethguiCoreModuleJsonTest($innerModule, 'ID');

        $innerModule->expects($this->once())
            ->method('getParent')
            ->will($this->returnValue($module));

        $innerModule->expects($this->once())
            ->method('getIdentifier')
            ->will($this->returnValue('Inner'));

        $module->initialize();

        $translator = $this->getMockBuilder('Nethgui_Core_TranslatorInterface')
            ->getMock();

        $translator->expects($this->any())
            ->method('translate')->will($this->returnArgument(0));

        $translator->expects($this->any())
            ->method('getLanguageCode')->will($this->returnValue('en'));

        $view = new Nethgui_Client_View($module, $translator);

        $module->prepareView($view, 0);

        $this->object = new Nethgui_Renderer_Json($view);
    }

    public function testRender()
    {
        $events = json_decode((String) $this->object);

        $this->assertInternalType('array', $events);

        $oTestCommand1 = json_decode(json_encode(array(
                'receiver' => '.ID_CMD1',
                'methodName' => 'testCommand',
                'arguments' => array(0, 'A')
            )));

        $oTestCommand2 = json_decode(json_encode(array(
                'receiver' => '#ID',
                'methodName' => 'testCommand',
                'arguments' => array(1, 'A')
            )));

        $oTestCommand3 = json_decode(json_encode(array(
                'receiver' => '#ID',
                'methodName' => 'testCommand',
                'arguments' => array(2, 'A')
            )));

        $oTestCommand4 = json_decode(json_encode(array(
                'receiver' => '#ID',
                'methodName' => 'testCommand',
                'arguments' => array(3, 'A')
            )));

        $expected = array(
            array(
                'ID_a',
                array('A', 'AA', 'AAA')
            ),
            array(
                'ID_b',
                '10.2'
            ),
            array(
                'ID_c',
                array('C', 'CC', 'CCC', 'CCCC', array('X'))
            ),
            array(
                'ID___invalidParameters',
                array()
            ),
            array(
                'ID_Inner_X',
                5
            ),
            array(
                'ClientCommandHandler',
                array(
                    $oTestCommand1,
                    $oTestCommand2,
                    $oTestCommand3,
                    $oTestCommand4
                )
            )
        );

        $this->assertEquals($expected, $events);
    }

}

/**
 * @ignore
 */
class Test_Unit_NethguiCoreModuleJsonTest extends Nethgui_Core_Module_Standard
{

    /**
     * @var Nethgui_Core_ModuleInterface
     * 
     */
    private $innerModule;

    public function __construct(Nethgui_Core_ModuleInterface $innerModule, $identifier = NULL)
    {
        parent::__construct($identifier);
        $this->innerModule = $innerModule;
    }

    public function initialize()
    {
        parent::initialize();
        $this->parameters['a'] = array('A', 'AA', 'AAA');
        $this->parameters['b'] = '10.2';
        $this->parameters['c'] = new ArrayObject(array('C', 'CC', 'CCC', 'CCCC', new ArrayObject(array('X'))));
    }

    public function prepareView(Nethgui_Core_ViewInterface $view, $mode)
    {
        parent::prepareView($view, $mode);
        $view['VIEW'] = $view->spawnView($this->innerModule);
        $view['VIEW']['X'] = 5;
        $view['CMD1'] = $view->createUiCommand('testCommand', array(0, 'A'));
        $view[] = $view->createUiCommand('testCommand', array(1, 'A'));
        $view[] = $view->createUiCommand('testCommand', array(2, 'A'));
        $view[] = $view->createUiCommand('testCommand', array(3, 'A'));
    }

}