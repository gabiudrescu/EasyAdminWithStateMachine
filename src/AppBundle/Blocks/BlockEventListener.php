<?php
/**
 * Created by PhpStorm.
 * User: gabiudrescu
 * Date: 10.08.2017
 * Time: 22:07
 */

namespace AppBundle\Blocks;


use Sonata\BlockBundle\Event\BlockEvent;
use Sonata\BlockBundle\Model\Block;

/**
 * Class BlockEventListener
 *
 * based on https://github.com/Sylius/Sylius/blob/master/src/Sylius/Bundle/UiBundle/Block/BlockEventListener.php
 *
 * @package AppBundle\Blocks
 */
class BlockEventListener
{
    /**
     * @var string
     */
    private $template;
    /**
     * @param string $template
     */
    public function __construct($template)
    {
        $this->template = $template;
    }
    /**
     * @param BlockEvent $event
     */
    public function onBlockEvent(BlockEvent $event)
    {
        $block = new Block();
        $block->setId(uniqid('', true));
        $block->setSettings(array_replace($event->getSettings(), [
            'template' => $this->template,
        ]));
        $block->setType('sonata.block.service.template');
        $event->addBlock($block);
    }
}