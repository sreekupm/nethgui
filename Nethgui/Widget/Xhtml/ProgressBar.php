<?php
/**
 * @package Widget
 * @subpackage Xhtml
 * @author Davide Principi <davide.principi@nethesis.it>
 * @internal
 * @ignore
 */

/**
 *
 * @package Widget
 * @subpackage Xhtml
 * @internal
 * @ignore
 */
class Nethgui_Widget_Xhtml_ProgressBar extends Nethgui_Widget_Xhtml
{

    public function render()
    {
        $name = $this->getAttribute('name');
        $flags = $this->getAttribute('flags', 0);

        $cssClass = 'Progressbar';

        if ($flags & Nethgui_Renderer_Abstract::STATE_DISABLED) {
            $cssClass .= ' disabled';
        }

        $cssClass .= ' ' . $this->getClientEventTarget();


        $content = $this->selfClosingTag('div', array('class' => $cssClass));
        return $content;
    }

}