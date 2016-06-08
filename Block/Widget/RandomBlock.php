<?php

namespace AAllen\RandomBlock\Block\Widget;

use Magento\Cms\Model\Block;
use Magento\Cms\Model\BlockFactory;
use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;

class RandomBlock extends Template
{
    /** @var  array $_blockArray */
    protected $_blockArray = [];

    /** @var BlockFactory $_blockFactory */
    protected $_blockFactory;

    /**
     * RandomBlock constructor.
     * @param Context $context
     * @param BlockFactory $blockFactory
     * @param array $data
     */
    public function __construct(Context $context, BlockFactory $blockFactory, array $data)
    {
        parent::__construct($context, $data);

        $this->_blockFactory = $blockFactory;
        $this->setTemplate('AAllen_RandomBlock::random.phtml');
    }

    /**
     * Populate the _blockArray with blocks from user supplied list of id's
     *
     * @return null
     */
    protected function getRandomBlock()
    {
        //get the array of block IDs
        $ids = $this->getData('ids');
        $ids = preg_split('/\D+/', $ids);
        //generate blocks from the specified IDs
        foreach ($ids as $id) {
            /** @var Block $block */
            $block = $this->_blockFactory->create()->load((int)$id);
            if ($block->getId()) {
                $this->_blockArray[] = $block;
            }
        }
        shuffle($this->_blockArray);

        return null;
    }

    /**
     * Get html for a random block.
     * 
     * @return string
     */
    public function getRandomBlockId()
    {
        if (empty($this->_blockArray)) $this->getRandomBlock();
        return array_pop($this->_blockArray)->getId();
    }

    /**
     * Get the number of blocks to be inserted.
     *
     * @return int
     */
    public function getNumberOfBlocks()
    {
        return (int)$this->getData('numBlocks');
    }
}